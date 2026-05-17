<?php
/**
 * Global Utility Functions
 * Project: Online Flight Booking System
 */

/**
 * Formats a number into Philippine Peso (₱)
 * Example: 5000 -> ₱ 5,000.00
 * 
 * @param float $amount
 * @return string
 */
function format_peso($amount) {
    return '₱ ' . number_format((float)$amount, 2, '.', ',');
}

/**
 * Returns a CSS class name based on flight status
 * Matches the logic in your React Admin Panel
 * 
 * @param string $status
 * @return string
 */
function get_status_class($status) {
    switch (strtolower($status)) {
        case 'scheduled':
            return 'status-scheduled';
        case 'departed':
            return 'status-departed';
        case 'arrived':
            return 'status-arrived';
        case 'issue':
            return 'status-issue';
        default:
            return 'status-default';
    }
}

/**
 * Sanitizes input to prevent XSS (Cross-Site Scripting)
 * Use this when echoing user-submitted data
 * 
 * @param string $data
 * @return string
 */
function clean_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Calculates time difference/duration in a readable format
 * 
 * @param string $start (DateTime string)
 * @param string $end (DateTime string)
 * @return string
 */
function calculate_duration($start, $end) {
    $time1 = strtotime($start);
    $time2 = strtotime($end);
    $diff = abs($time2 - $time1);
    
    $hours = floor($diff / 3600);
    $minutes = floor(($diff % 3600) / 60);
    
    if ($hours > 0) {
        return $hours . "h " . $minutes . "m";
    }
    return $minutes . "m";
}

/**
 * Checks if a flight departure date is in the past
 * 
 * @param string $departure_time
 * @return bool
 */
function is_expired($departure_time) {
    return strtotime($departure_time) < time();
}
?>