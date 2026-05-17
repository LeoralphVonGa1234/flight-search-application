<?php
// 1. CORS Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

// 2. Database Connection
$conn = new mysqli("localhost", "root", "", "flight_db");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// 3. Get JSON Input
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['name']) && !empty($data['code'])) {
    $name = $conn->real_escape_string($data['name']);
    $code = $conn->real_escape_string($data['code']);

    $sql = "INSERT INTO airlines (name, code) VALUES ('$name', '$code')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Airline registered"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Incomplete data provided"]);
}

$conn->close();
?>