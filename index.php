<?php
// Start the session to check for user login status
session_start();

// Include the database connection
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if user is logged in for the header display
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['user_name'] : "";

/** * DYNAMIC FILTERING:
 * These queries ensure that the user-side dropdowns ONLY display cities 
 * that have active, scheduled flights created by admins.
 */
try {
    // Get unique Source cities from existing scheduled flights
    $stmt_from = $conn->prepare("SELECT DISTINCT source FROM flights WHERE status = 'Scheduled' ORDER BY source ASC");
    $stmt_from->execute();
    $sources = $stmt_from->fetchAll(PDO::FETCH_ASSOC);

    // Get unique Destination cities from existing scheduled flights
    $stmt_to = $conn->prepare("SELECT DISTINCT destination FROM flights WHERE status = 'Scheduled' ORDER BY destination ASC");
    $stmt_to->execute();
    $destinations = $stmt_to->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $sources = [];
    $destinations = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Flight Booking - Search Flights</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8fafc;
        }
        .hero-section {
            background: linear-gradient(rgba(15, 23, 42, 0.6), rgba(15, 23, 42, 0.7)), 
                        url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            height: 450px;
        }
        .search-glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            margin-top: -80px;
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .form-control:focus, .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.15);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm px-lg-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2 text-uppercase" href="index.php">
                <i class="fas fa-plane text-info"></i> Flight Booking
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="my_flights.php">My Bookings</a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <span class="navbar-text text-white bg-secondary bg-opacity-25 py-1 px-3 rounded-pill text-sm">
                                Welcome, <strong class="text-info"><?php echo htmlspecialchars($user_name); ?></strong>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-danger btn-sm ms-lg-2 px-3 rounded-pill" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link px-3" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm" href="register.php" style="background: #4f46e5; border: 0;">
                                Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section d-flex align-items-center text-center text-white">
        <div class="container py-5">
            <h1 class="display-4 fw-extrabold mb-2 tracking-tight">Discover Your Next Adventure</h1>
            <p class="lead opacity-90">Book domestic and international flights at the best  <strong class="text-warning"></strong></p>
        </div>
    </section>

    <div class="container mb-5">
        <div class="card search-glass-card p-4 p-md-5 rounded-4 shadow-lg">
            <form action="book_flight.php" method="GET">
                <div class="row g-3">
                    
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="from" class="form-label small fw-bold text-secondary text-uppercase tracking-wider">From</label>
                        <select name="from" id="from" class="form-select form-select-lg rounded-3 fs-6" required>
                            <option value="">Select Origin</option>
                            <?php foreach ($sources as $row): ?>
                                <option value="<?php echo htmlspecialchars($row['source']); ?>">
                                    <?php echo htmlspecialchars($row['source']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="to" class="form-label small fw-bold text-secondary text-uppercase tracking-wider">To</label>
                        <select name="to" id="to" class="form-select form-select-lg rounded-3 fs-6" required>
                            <option value="">Select Destination</option>
                            <?php foreach ($destinations as $row): ?>
                                <option value="<?php echo htmlspecialchars($row['destination']); ?>">
                                    <?php echo htmlspecialchars($row['destination']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="date" class="form-label small fw-bold text-secondary text-uppercase tracking-wider">Departure Date</label>
                        <input type="date" name="date" id="date" min="<?php echo date('Y-m-d'); ?>" class="form-control form-control-lg rounded-3 fs-6" required>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="passengers" class="form-label small fw-bold text-secondary text-uppercase tracking-wider">Passengers</label>
                        <input type="number" name="passengers" id="passengers" value="1" min="1" max="10" class="form-control form-control-lg rounded-3 fs-6">
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success btn-lg w-100 py-3 fw-bold shadow-sm rounded-3 d-flex align-items-center justify-content-center gap-2">
                            <i class="fas fa-search"></i> Search Flights
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <footer class="bg-white border-top py-4 mt-auto text-center">
        <div class="container">
            <p class="text-muted small mb-0">&copy; 2026 Online Flight Booking System .</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>