<?php
/**
 * Database Connection
 * Includes security helpers for all portal pages
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$servername = "localhost";
$username = "hapacoll_root";
$password = "foxtrot2november";
$dbname = "hapacoll_portal";

// Create connection with error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // Log error but don't expose details to users
    error_log("Database connection failed: " . $e->getMessage());
    die("A database error occurred. Please try again later.");
}

// Also create $mysqli reference for compatibility with files using $mysqli
$mysqli = $conn;

// Include security helpers
require_once __DIR__ . '/helpers/security.php';
require_once __DIR__ . '/helpers/InputValidator.php';

// Local development configuration (commented out)
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "portal";
// $conn = new mysqli($servername, $username, $password, $dbname);
// $mysqli = $conn;
?>
