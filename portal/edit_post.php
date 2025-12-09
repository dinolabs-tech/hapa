<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session to maintain user state
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
include 'db_connection.php';

// Check connection to the database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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



// Edit Posts
// 2) Validate & fetch the post
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
  $post_id = (int)$_GET['id'];

  $stmt = $conn->prepare("SELECT thread_id, author, content FROM posts WHERE id = ? LIMIT 1");
  $stmt->bind_param('i', $post_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $post   = $result->fetch_assoc();
  $stmt->close();

  // Not found or not the author? kick back.
  if (
      !$post ||
      $post['author'] !== $student_name
  ) {
      header('Location: threads.php');
      exit;
  }

  // Keep this for redirect after update:
  $thread_id = (int)$post['thread_id'];
} else {
  header('Location: threads.php');
  exit;
}

$error = '';

// 3) Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Make sure TinyMCE has synced its textarea
  // (you can also do this in JS instead of onclick on the button)
  echo '<script>tinyMCE.triggerSave();</script>';

  // Validate
  if (empty($_POST['content'])) {
      $error = 'Content cannot be empty.';
  } else {
      $content = $_POST['content'];

      $stmt = $conn->prepare("UPDATE posts SET content = ? WHERE id = ?");
      $stmt->bind_param('si', $content, $post_id);

      if ($stmt->execute()) {
          $stmt->close();
          // Success! Back to thread view.
          header("Location: view_thread.php?id={$thread_id}");
          exit;
      } else {
          $error = 'Update failed: ' . htmlspecialchars($stmt->error);
          $stmt->close();
      }
  }
}


$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<?php include('head.php');?>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- TinyMCE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
   
  
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <?php 
      
      $role=isset($_SESSION['role']) ? $_SESSION['role']:'';
      //set the appropriate url based on the user role
      if ($role ==='Student') {
        include('studentnav.php'); 
      } elseif ($role === 'Superuser') {
        include('adminnav.php');
      }elseif ($role ==='Administrator'){
        include('adminnav.php'); 
      }elseif ($role ==='Teacher'){
        include('adminnav.php'); 
      }elseif ($role ==='Admission'){
        include('adminnav.php'); 
      }elseif ($role ==='Bursary'){
        include('adminnav.php'); 
      }elseif ($role ==='Store'){
        include('adminnav.php'); 
      }elseif ($role ==='Tuckshop'){
        include('adminnav.php'); 
      }elseif ($role ==='Library'){
        include('adminnav.php'); 
      }elseif ($role==='Alumni') {
        include('alumninav.php'); 
      }

      ?>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <?php include('logo_header.php'); ?>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <?php include('navbar.php'); ?>
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4 d-none d-lg-block">
              <div>
                <h3 class="fw-bold mb-3">Discussion Threads</h3>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active">Home</li>
                  <li class="breadcrumb-item active">Create Comments</li>
                </ol>
              </div>
            </div>

            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Add New Comment</h4>   
                </div>
                <div class="card-body">
              
                <?php if ($error): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="mb-3">
        <label for="content" class="form-label">Content:</label>
        <textarea
          id="content"
          name="content"
          class="form-control"
          required
        ><?php echo htmlspecialchars($post['content']); ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
    </form>

                </div>
              </div>
            </div>
          </div>
        </div>
     
        <?php include('footer.php'); ?>
      </div>

      <!-- Custom template | don't include it in your project! -->
      <?php include('cust-color.php'); ?>
      <!-- End Custom template -->
    </div>
    <?php include('scripts.php'); ?>

    <script>
    tinymce.init({
      selector: '#content',
      menubar: false,
      toolbar: 'undo redo | formatselect | bold italic underline superscript subscript | alignleft aligncenter alignright | bullist numlist outdent indent | table',
      plugins: 'lists',
      branding: false
    });
  </script>

  </body>
</html>
