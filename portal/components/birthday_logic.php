<?php
// Database connection
include 'db_connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current month as a 2-digit string
$currentMonth = date('m');

// Fetch students whose birthdays fall in the current month
$sql = "SELECT * FROM students WHERE MONTH(STR_TO_DATE(dob, '%d/%m/%y')) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentMonth);
$stmt->execute();
$result = $stmt->get_result();
?>

