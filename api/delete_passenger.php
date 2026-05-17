<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = "localhost";
$db_name = "flight_db";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->passenger_id)) {
        $query = "DELETE FROM passengers WHERE passenger_id = :pid";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':pid', $data->passenger_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo json_encode(["status" => "success", "message" => "Passenger deleted."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Passenger ID not found."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Query execution failed."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No passenger_id provided."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>