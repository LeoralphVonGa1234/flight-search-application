<?php
// Start the session
session_start();

// Include the database connection (Now using PDO)
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Get search parameters from index.php
// With PDO Prepared Statements, we don't need mysqli_real_escape_string
$from = isset($_GET['from']) ? $_GET['from'] : '';
$to = isset($_GET['to']) ? $_GET['to'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

/**
 * FIX: Use PDO Prepared Statements
 * This replaces mysqli_query and mysqli_real_escape_string
 */
try {
    $sql = "SELECT f.*, a.name as airline_name 
            FROM flights f 
            JOIN airlines a ON f.airline_id = a.airline_id 
            WHERE f.source = :from 
            AND f.destination = :to 
            AND DATE(f.departure_time) = :date
            AND f.status = 'Scheduled'";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'from' => $from,
        'to' => $to,
        'date' => $date
    ]);
    
    // Fetch all results into an array
    $flights = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Flights - PHP (₱)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; }
        .search-info { background: #343a40; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .flight-card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eee; }
        .airline-info h3 { margin: 0; color: #007bff; }
        .time-info { text-align: center; }
        .price-tag { font-size: 24px; font-weight: bold; color: #28a745; }
        .btn-select { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; }
        .no-results { text-align: center; padding: 50px; background: white; border-radius: 8px; }
        .back-btn { display: inline-block; margin-bottom: 15px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Modify Search</a>

    <div class="search-info">
        <h3><i class="fas fa-plane-departure"></i> Flights from <?php echo htmlspecialchars($from); ?> to <?php echo htmlspecialchars($to); ?></h3>
        <p>Departure Date: <?php echo !empty($date) ? date('F d, Y', strtotime($date)) : 'N/A'; ?></p>
    </div>

    <?php if (count($flights) > 0): ?>
        <?php foreach($flights as $row): ?>
            <div class="flight-card">
                <div class="airline-info">
                    <h3><?php echo htmlspecialchars($row['airline_name']); ?></h3>
                    <small>Flight ID: #<?php echo $row['flight_id']; ?></small>
                </div>

                <div class="time-info">
                    <div><strong>Departure</strong></div>
                    <div><?php echo date('h:i A', strtotime($row['departure_time'])); ?></div>
                </div>

                <div class="time-info">
                    <i class="fas fa-long-arrow-alt-right" style="font-size: 24px; color: #ccc;"></i>
                    <div style="font-size: 12px; color: #888;"><?php echo $row['duration']; ?> mins</div>
                </div>

                <div class="time-info">
                    <div><strong>Arrival</strong></div>
                    <div><?php echo date('h:i A', strtotime($row['arrival_time'])); ?></div>
                </div>

                <div class="price-section">
                    <div class="price-tag">₱ <?php echo number_format($row['price'], 2); ?></div>
                    <form action="pass_form.php" method="POST">
                        <input type="hidden" name="flight_id" value="<?php echo $row['flight_id']; ?>">
                        <button type="submit" class="btn-select">Select Flight</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-results">
            <i class="fas fa-search-minus" style="font-size: 48px; color: #ccc;"></i>
            <h2>No Flights Found</h2>
            <p>Sorry, there are no scheduled flights for this route on the selected date.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>