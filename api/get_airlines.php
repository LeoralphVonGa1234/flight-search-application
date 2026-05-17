<?php
// 1. CORS Headers - Must be at the very top
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

// 2. Database Connection (Matching your SQL dump)
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "flight_db"; // As seen in your SQL dump

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit;
}

// 3. Query
$sql = "SELECT * FROM airlines ORDER BY name ASC";
$result = $conn->query($sql);

$airlines = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $airlines[] = $row;
    }
}

echo json_encode(["status" => "success", "data" => $airlines]);

$conn->close();
?>