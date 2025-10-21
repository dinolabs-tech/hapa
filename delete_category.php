<?php
session_start();
// Uncomment this when you're ready to protect access
if (!isset($_SESSION["staffname"])) {
    header("Location: portal/login.php");
    exit();
}

include("db_connect.php");

// Get and sanitize ID
$category_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($category_id > 0) {
    $sql = "DELETE FROM categories WHERE id = $category_id";
    if ($conn->query($sql) === TRUE) {
        // Redirect only if no output has been sent
        header("Location: manage_categories.php");
        exit();
    } else {
        // For debugging only — remove this in production
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid ID.";
}

$conn->close();
?>

