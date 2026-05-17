<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$db_name = "flight_db";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    
    // 1. Get Passenger Count
    $passengers = $conn->query("SELECT COUNT(*) FROM passengers")->fetchColumn();
    
    // 2. Get Total Revenue (Sum of booking amounts)
    $revenue = $conn->query("SELECT SUM(amount) FROM bookings")->fetchColumn() ?: 0;
    
    // 3. Get Active Flights
    $flights = $conn->query("SELECT COUNT(*) FROM flights WHERE status != 'Arrived'")->fetchColumn();
    
    // 4. Get Airline Count
    $airlines = $conn->query("SELECT COUNT(*) FROM airlines")->fetchColumn();

    echo json_encode([
        "status" => "success",
        "data" => [
            "total_passengers" => (int)$passengers,
            "total_amount" => (float)$revenue,
            "total_flights" => (int)$flights,
            "total_airlines" => (int)$airlines
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>