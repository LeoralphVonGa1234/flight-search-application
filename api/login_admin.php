<?php
/**
 * Authentication Guard
 * Ensures that a user session exists before allowing access to the page.
 */

// 1. Start session only if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * 2. Check if the user_id session variable is set.
 * If not, the user is not logged in.
 */
if (!isset($_SESSION['user_id'])) {
    
    // Get the current page the user was trying to access
    $current_page = basename($_SERVER['PHP_SELF']);
    
    // Redirect them to the login page with a helpful error message
    // and a 'redirect' parameter so you can send them back after they login.
    header("Location: login.php?error=unauthorized&return_to=" . $current_page);
    exit();
}

/**
 * 3. Optional: Prevent Admin-only sections (if applicable)
 * This is where you would add logic if you had different user roles 
 * on the PHP side.
 */
?>