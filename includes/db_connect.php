<?php
/**
 * Database Connection for flight_db
 * Using PDO for compatibility with fetchAll() and better security
 */

$host = "localhost";
$db_name = "flight_db"; // Your database name
$username = "root";     // Default XAMPP username
$password = "";         // Default XAMPP password

try {
    // Create the PDO connection string
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    
    // Set PDO error mode to Exception so you can see SQL errors clearly
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to Associative Array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Optional: Set character set to utf8mb4
    $conn->exec("set names utf8mb4");

} catch(PDOException $e) {
    // If connection fails, stop the script and show the error
    die("Database Connection Error: " . $e->getMessage());
}
?>