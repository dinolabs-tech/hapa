<?php
include 'db_connection.php';
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
} elseif ($role === 'Superuser') {
  // Fetch the logged-in Staff name
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $thread_id = $_POST["thread_id"];
    $content = $_POST["content"];
    $author = $student_name;

    try {
        $thread_id = mysqli_real_escape_string($conn, $thread_id);
        $content = mysqli_real_escape_string($conn, $content);
        $author = mysqli_real_escape_string($conn, $author);

        $sql = "INSERT INTO posts (thread_id, content, author, created_at) VALUES ('$thread_id', '$content', '$author', NOW())";

        if ($conn->query($sql) === TRUE) {
            header("Location: view_thread.php?id=" . $thread_id);
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
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
    <title>Add Post</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
    <script>
    <script>
      tinymce.init({
        selector: '#content'
      });
    </script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h2>Add New Post</h2>
        <form action="add_post.php" method="post">
            <input type="hidden" name="thread_id" value="<?php echo $_GET['thread_id']; ?>">
            <div class="mb-3">
                <label for="content" class="form-label">Content:</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" onclick="tinyMCE.triggerSave();">Submit</button>
        </form>
    </div>
</body>
</html>
