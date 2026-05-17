<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../includes/db_connect.php'; // The PDO file we fixed earlier

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['flight_id'])) {
    try {
        $sql = "UPDATE flights SET 
                source = :source, 
                destination = :destination, 
                price = :price, 
                departure_time = :departure_time 
                WHERE flight_id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'source' => $data['source'],
            'destination' => $data['destination'],
            'price' => $data['price'],
            'departure_time' => $data['departure_time'],
            'id' => $data['flight_id']
        ]);

        echo json_encode(["status" => "success", "message" => "Flight updated"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>