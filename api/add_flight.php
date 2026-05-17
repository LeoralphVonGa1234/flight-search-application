<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once '../includes/db_connect.php'; // Ensure this uses the PDO connection we built

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "No data provided"]);
    exit;
}

try {
    $sql = "INSERT INTO flights (airline_id, source, destination, departure_time, arrival_time, duration, price, status) 
            VALUES (:airline_id, :source, :destination, :departure_time, :arrival_time, :duration, :price, :status)";

    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        ':airline_id'     => $data['airline_id'],
        ':source'         => $data['source'],
        ':destination'    => $data['destination'],
        ':departure_time' => $data['departure_time'],
        ':arrival_time'   => $data['arrival_time'],
        ':duration'       => $data['duration'],
        ':price'          => $data['price'],
        ':status'         => $data['status'] ?? 'Scheduled'
    ]);

    if($result) {
        echo json_encode(["status" => "success", "message" => "Flight scheduled successfully"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>