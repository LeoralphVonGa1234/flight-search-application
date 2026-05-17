<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "flight_db");

$sql = "SELECT f.*, a.name as airline_name 
        FROM flights f 
        JOIN airlines a ON f.airline_id = a.airline_id 
        ORDER BY f.departure_time DESC";

$result = $conn->query($sql);
$data = [];
while($row = $result->fetch_assoc()) { $data[] = $row; }

echo json_encode(["status" => "success", "data" => $data]);
?>