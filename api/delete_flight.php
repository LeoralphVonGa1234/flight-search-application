<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Handle preflight OPTIONS request for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database configuration
$host = "localhost";
$db_name = "flight_db";
$username = "root";
$password = "";

try {
    // Create PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the posted data from React
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->flight_id)) {
        // Prepare the delete statement
        $query = "DELETE FROM flights WHERE flight_id = :fid";
        $stmt = $conn->prepare($query);
        
        // Bind the ID parameter
        $stmt->bindParam(':fid', $data->flight_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo json_encode([
                    "status" => "success", 
                    "message" => "Flight successfully removed from schedule."
                ]);
            } else {
                echo json_encode([
                    "status" => "error", 
                    "message" => "Flight ID not found in the database."
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error", 
                "message" => "Unable to execute deletion."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Missing flight_id in request."
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error", 
        "message" => "Database connection error: " . $e->getMessage()
    ]);
}
?>