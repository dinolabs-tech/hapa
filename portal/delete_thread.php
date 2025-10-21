<?php
include('db_connection.php');
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}


$role=isset($_SESSION['role']) ? $_SESSION['role']:'';
//set the appropriate url based on the user role
if ($role ==='Student') {
  // Fetch the logged-in student's name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name FROM students WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();

}elseif ($role ==='Administrator'){
  // Fetch the logged-in Staff name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();
}elseif ($role ==='Store'){
  // Fetch the logged-in Staff name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();
}elseif ($role ==='Library'){
  // Fetch the logged-in Staff name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();
}elseif ($role ==='Tuckshop'){
  // Fetch the logged-in Staff name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();
}elseif ($role ==='Teacher'){
  // Fetch the logged-in Staff name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();
}elseif ($role ==='Bursary'){
  // Fetch the logged-in Staff name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();
}elseif ($role ==='Admission'){
  // Fetch the logged-in Staff name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();
}elseif ($role==='Alumni') {
    // Fetch the logged-in student's name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name FROM students WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();
}



if (isset($_GET["id"])) {
    $thread_id = mysqli_real_escape_string($conn, $_GET["id"]);

    // Fetch the thread to verify the author
    $sql = "SELECT * FROM threads WHERE id = '$thread_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $thread = $result->fetch_assoc();

        // Only allow deletion by author or admin or Superuser
        if ($student_name === $thread["author"] || $role === 'Administrator' || $role === 'Superuser') {
            $sql = "DELETE FROM threads WHERE id = '$thread_id'";
            $sql2 = "DELETE FROM posts WHERE thread_id = '$thread_id'";
            if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
                header("Location: threads.php");
                exit();
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        } else {
            // Not authorized to delete
            header("Location: threads.php");
            exit();
        }
    } else {
        // Thread not found
        header("Location: threads.php");
        exit();
    }
} else {
    // No ID provided
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
        <h2>Delete Thread</h2>
        <p>Are you sure you want to delete this thread?</p>
        <a href="delete_thread.php?id=<?php echo $_GET['id']; ?>" class="btn btn-danger">Yes, Delete</a>
        <a href="index.php" class="btn btn-secondary">No, Cancel</a>
    </div>
</body>
</html>
