<?php
include('db_connection.php');
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $post_id = $_GET["id"];

    $post_id = mysqli_real_escape_string($conn, $post_id);
    $sql = "SELECT * FROM posts WHERE id = '$post_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        if (!$post || $student_name != $thread["author"]) {
            header("Location: threads.php");
            exit();
        }
    } else {
        header("Location: threads.php");
        exit();
    }


    $sql = "DELETE FROM posts WHERE id = '$post_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: threads.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    header("Location: threads.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Thread</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
        <h2>Delete Post</h2>
        <p>Are you sure you want to delete this post?</p>
        <a href="delete_post.php?id=<?php echo $_GET['id']; ?>" class="btn btn-danger">Yes, Delete</a>
        <a href="view_thread.php?id=<?php echo isset($post['thread_id']) ? $post['thread_id'] : ''; ?>" class="btn btn-secondary">No, Cancel</a>
    </div>
</body>
</html>
