<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include 'db_connection.php';

// Check connection to the database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get class and arm from POST or GET
$class = isset($_POST['class']) ? $_POST['class'] : (isset($_GET['class']) ? $_GET['class'] : '');
$arm = isset($_POST['arm']) ? $_POST['arm'] : (isset($_GET['arm']) ? $_GET['arm'] : '');

if (!$class || !$arm) {
    die("Class and Arm are required to download the template.");
}

// Create a safe and readable filename
$filename = ucwords(strtolower($class . ' ' . $arm)) . ' Result Template.csv';
$filename = str_replace(['/', '\\'], '-', $filename); // Remove unsafe characters

// Set headers for download
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=\"$filename\"");

// Open output stream
$output = fopen('php://output', 'w');

// Write the CSV header
$header = ['ID', 'NAME', 'CA1', 'CA2', 'EXAM'];
fputcsv($output, $header);

// Fetch students based on selected class and arm
$sql = "SELECT id, name FROM students WHERE class = ? AND arm = ? and status = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $class, $arm);
$stmt->execute();
$stmt->bind_result($id, $name);

while ($stmt->fetch()) {
    $line = [$id, $name, '', '', ''];
    fputcsv($output, $line);
}


fclose($output);
exit();
?>
