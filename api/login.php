<?php
// 1. Set required CORS headers to allow connection from your React Frontend
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS requests gracefully
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 2. Process incoming POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the payload sent by Axios
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->username) && !empty($data->password)) {
        // Trim whitespace from user input
        $admin_username = trim($data->username);
        $admin_password = trim($data->password);
        
        // 3. STRICT HARDCODED VALIDATION (Bypasses MySQL entirely)
        if ($admin_username === 'admin' && $admin_password === 'admin') {
            // Generate a temporary mock session token
            $token = bin2hex(random_bytes(32));
            
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "message" => "Hardcoded authentication successful.",
                "token" => $token,
                "user" => [
                    "id" => 1,
                    "username" => "admin"
                ]
            ]);
        } else {
            // Wrong credentials entered
            http_response_code(200);
            echo json_encode([
                "status" => "error",
                "message" => "Invalid credentials. Hint: Use admin / admin"
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Username and password fields cannot be blank."
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Method Not Allowed."
    ]);
    exit();
}
?>