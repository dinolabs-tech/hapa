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




$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
//set the appropriate url based on the user role
if ($role === 'Student') {
  // Fetch the logged-in student's name
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT name FROM students WHERE id=?");
  $stmt->bind_param("s", $user_id);
  $stmt->execute();
  $stmt->bind_result($student_name);
  $stmt->fetch();
  $stmt->close();
} elseif ($role === 'Administrator') {
  // Fetch the logged-in Staff name
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
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
} elseif ($role === 'Store') {
  // Fetch the logged-in Staff name
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
  $stmt->bind_param("s", $user_id);
  $stmt->execute();
  $stmt->bind_result($student_name);
  $stmt->fetch();
  $stmt->close();
} elseif ($role === 'Library') {
  // Fetch the logged-in Staff name
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
  $stmt->bind_param("s", $user_id);
  $stmt->execute();
  $stmt->bind_result($student_name);
  $stmt->fetch();
  $stmt->close();
} elseif ($role === 'Tuckshop') {
  // Fetch the logged-in Staff name
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
  $stmt->bind_param("s", $user_id);
  $stmt->execute();
  $stmt->bind_result($student_name);
  $stmt->fetch();
  $stmt->close();
} elseif ($role === 'Teacher') {
  // Fetch the logged-in Staff name
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
  $stmt->bind_param("s", $user_id);
  $stmt->execute();
  $stmt->bind_result($student_name);
  $stmt->fetch();
  $stmt->close();
} elseif ($role === 'Bursary') {
  // Fetch the logged-in Staff name
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
  $stmt->bind_param("s", $user_id);
  $stmt->execute();
  $stmt->bind_result($student_name);
  $stmt->fetch();
  $stmt->close();
} elseif ($role === 'Admission') {
  // Fetch the logged-in Staff name
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
  $stmt->bind_param("s", $user_id);
  $stmt->execute();
  $stmt->bind_result($student_name);
  $stmt->fetch();
  $stmt->close();
} elseif ($role === 'Alumni') {
  // Fetch the logged-in student's name
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT name FROM students WHERE id=?");
  $stmt->bind_param("s", $user_id);
  $stmt->execute();
  $stmt->bind_result($student_name);
  $stmt->fetch();
  $stmt->close();
}



// Create Threads
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST["title"];
  $content = $_POST["content"];
  // $author = $_SESSION["user_id"];
  $author = $student_name;

  try {
    $title = mysqli_real_escape_string($conn, $title);
    $content = mysqli_real_escape_string($conn, $content);
    $author = mysqli_real_escape_string($conn, $author);

    $sql = "INSERT INTO threads (title, content, author, created_at) VALUES ('$title', '$content', '$author', NOW())";

    if ($conn->query($sql) === TRUE) {
      header("Location: threads.php");
      exit();
    } else {
      echo "Error: " . $conn->error;
    }
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
}


$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- TinyMCE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>


<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <?php

    $role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
    //set the appropriate url based on the user role
    if ($role === 'Student') {
      include('studentnav.php');
    } elseif ($role === 'Administrator') {
      include('adminnav.php');
    } elseif ($role === 'Superuser') {
      include('adminnav.php');
    } elseif ($role === 'Teacher') {
      include('adminnav.php');
    } elseif ($role === 'Admission') {
      include('adminnav.php');
    } elseif ($role === 'Bursary') {
      include('adminnav.php');
    } elseif ($role === 'Store') {
      include('adminnav.php');
    } elseif ($role === 'Tuckshop') {
      include('adminnav.php');
    } elseif ($role === 'Library') {
      include('adminnav.php');
    } elseif ($role === 'Alumni') {
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
          <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
              <h3 class="fw-bold mb-3">Discussion Threads</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Create Thread</li>
              </ol>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Create Threads</h4>
              </div>
              <div class="card-body">

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                  <div class="mb-3">
                    <label for="title" class="form-label">Title:</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label for="content" class="form-label">Content:</label>
                    <textarea name="content" id="content" rows="5" class="form-control" required></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary" onclick="tinyMCE.triggerSave();">Post</button>
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