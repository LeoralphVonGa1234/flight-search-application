<?php
// Start the session
session_start();

// Include database connection
require_once 'includes/db_connect.php';

$error = "";

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle Login Logic using PDO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        try {
            // Using safe PDO prepared statements instead of mysqli
            $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verify the hashed password securely
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'] ?? $user['user_id']; // Handles either schema setup
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['user_email'] = $user['email'];

                    // Redirect to user home layout panel
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Invalid email or password.";
                }
            } else {
                $error = "No account found with that email.";
            }
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Online Flight Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .form-control::placeholder { 
            color: rgba(255, 255, 255, 0.5) !important; 
        }
        .form-control:focus { 
            background-color: rgba(255, 255, 255, 0.3) !important; 
            border-color: rgba(255, 255, 255, 0.5) !important;
            color: #fff !important;
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1) !important;
        }
    </style>
</head>
<body>

    <div 
        style="
            background-image: linear-gradient(rgba(15, 23, 42, 0.6), rgba(15, 23, 42, 0.8)), url('https://images.unsplash.com/photo-1540962351504-03099e0a754b?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: fixed;
            width: 100vw;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        "
    >
        <div class="flex-grow-1 d-flex align-items-center justify-content-center py-5 px-3">
            <div class="w-100" style="max-width: 400px;">
                
                <div 
                    class="card border border-white border-opacity-25 shadow-lg p-3 p-sm-4 rounded-4 bg-white bg-opacity-25 text-white"
                    style="backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);"
                >
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img 
                                src="https://img.icons8.com/fluency/96/airplane-take-off.png" 
                                alt="Airline Logo" 
                                class="img-fluid mb-2"
                                style="width: 56px; height: 56px;"
                            />
                            <h2 class="fw-bold mb-1 tracking-wide">User Login</h2>
                            <p class="text-white-50 small text-uppercase">Access Your Flight Dashboard</p>
                        </div>

                        <?php if($error): ?>
                            <div class="alert alert-danger text-start py-2 px-3 small border-0 rounded-3 mb-3 bg-danger bg-opacity-75 text-white shadow-sm" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <form action="login.php" method="POST" class="text-start">
                            <div class="mb-3">
                                <label class="form-label text-white-50 small fw-semibold">Email Address</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    required 
                                    class="form-control bg-white bg-opacity-20 border-white border-opacity-25 text-white py-2 px-3"
                                    placeholder="Enter your email"
                                    style="border-radius: 8px; color: #fff;"
                                />
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-white-50 small fw-semibold">Password</label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    required 
                                    class="form-control bg-white bg-opacity-20 border-white border-opacity-25 text-white py-2 px-3"
                                    placeholder="Enter your password"
                                    style="border-radius: 8px; color: #fff;"
                                />
                            </div>

                            <button 
                                type="submit" 
                                class="btn btn-success w-100 py-2.5 fw-bold shadow border-0 d-flex justify-content-center align-items-center gap-2 mb-3"
                                style="border-radius: 8px; background: #10b981;"
                            >
                                <i class="fas fa-sign-in-alt"></i> Sign In
                            </button>
                        </form>

                        <div class="text-center small mt-3 border-top border-white border-opacity-10 pt-3">
                            <span class="text-white-50">Don't have an account?</span> 
                            <a href="register.php" class="text-info fw-semibold text-decoration-none ms-1">Sign up here</a>
                        </div>
                        <div class="text-center small mt-2">
                            <a href="index.php" class="text-white-50 text-decoration-none small">
                                <i class="fas fa-arrow-left me-1"></i> Back to Home
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <footer class="w-100 text-center py-3 bg-dark bg-opacity-50 border-top border-white border-opacity-10">
            <div class="container">
                <p class="text-white-50 small mb-0">
                    &copy; 2026 Airline Management System. All Rights Reserved.
                </p>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>