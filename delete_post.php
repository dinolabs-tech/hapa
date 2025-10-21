<?php
session_start();

if (!isset($_SESSION["staffname"])) {
    header("Location: portal/login.php");
    exit();
}

include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $post_id = $_GET["id"];

 // Delete comments associated with the post
    $sql_comments = "DELETE FROM comments WHERE post_id = $post_id";
    $conn->query($sql_comments);
    
    // Fetch image filename
    $sql = "SELECT image_path FROM blog_posts WHERE id = $post_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_filename = $row["image_path"];

        // Delete the post
        $sql = "DELETE FROM blog_posts WHERE id = $post_id";

        if ($conn->query($sql) === TRUE) {
            // Delete the image file
            if (!empty($image_filename) && file_exists("assets/img/" . $image_filename)) {
                unlink("assets/img/" . $image_filename);
            }

            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Post not found";
    }
}

$conn->close();
?>
