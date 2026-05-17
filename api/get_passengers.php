<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "flight_db");

$query = "SELECT passenger_id, first_name, middle_name, last_name, contact, dob FROM passengers";
$result = $conn->query($query);

$passengers = [];
while($row = $result->fetch_assoc()) {
    $passengers[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $passengers
]);
?>