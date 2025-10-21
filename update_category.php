<?php
session_start();
if (!isset($_SESSION["staffname"])) {
    header("Location: portal/login.php");
    exit();
}

include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST["id"];
    $name = $_POST["name"];
    $description = $_POST["description"];

    $sql = "UPDATE categories SET name = '$name', description = '$description' WHERE id = $category_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_categories.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
