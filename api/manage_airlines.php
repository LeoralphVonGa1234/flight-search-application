<?php
/**
 * API Endpoint: Manage Airlines (GET and POST)
 * Used by: React Admin Panel
 */

// 1. Headers for React Access (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle Preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 2. Include database connection
require_once '../includes/db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    // CASE GET: Retrieve all airlines for the list/dropdowns
    case 'GET':
        $sql = "SELECT * FROM airlines ORDER BY name ASC";
        $result = mysqli_query($conn, $sql);
        
        if($result) {
            $airlines = [];
            while($row = mysqli_fetch_assoc($result)) {
                $airlines[] = $row;
            }
            http_response_code(200);
            echo json_encode(["status" => "success", "data" => $airlines]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
        }
        break;

    // CASE POST: Add a new airline
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->name)) {
            $name = mysqli_real_escape_string($conn, $data->name);
            // Default code or description if not provided
            $code = !empty($data->code) ? mysqli_real_escape_string($conn, $data->code) : strtoupper(substr($name, 0, 3));

            $query = "INSERT INTO airlines (name, code) VALUES ('$name', '$code')";
            
            if(mysqli_query($conn, $query)) {
                http_response_code(201);
                echo json_encode(["status" => "success", "message" => "Airline added successfully!"]);
            } else {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Database error."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Airline name is required."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
        break;
}
?>