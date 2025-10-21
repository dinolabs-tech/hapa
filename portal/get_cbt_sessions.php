<?php
header('Content-Type: application/json');

// Database connection
include 'db_connection.php';


// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Query to get distinct session values from mastersheet table
// Query to get distinct session values from cbt_score table
$sql_sessions = "SELECT DISTINCT session FROM cbt_score";
$result_sessions = $conn->query($sql_sessions);

$sessions = [];
if ($result_sessions->num_rows > 0) {
    while ($row = $result_sessions->fetch_assoc()) {
        $sessions[] = $row['session'];
    }
}

// Query to get distinct term values from cbt_score table
$sql_terms = "SELECT DISTINCT term FROM cbt_score";
$result_terms = $conn->query($sql_terms);

$terms = [];
if ($result_terms->num_rows > 0) {
    while ($row = $result_terms->fetch_assoc()) {
        $terms[] = $row['term'];
    }
}

$conn->close();

echo json_encode(['sessions' => $sessions, 'terms' => $terms]);
?>
