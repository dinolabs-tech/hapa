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

if (isset($_GET["id"])) {
  $thread_id = $_GET["id"];

  try {
    $thread_id = mysqli_real_escape_string($conn, $thread_id);

    $sql = "SELECT * FROM threads WHERE id = '$thread_id'";
    $result = $conn->query($sql);
    $thread = $result->fetch_assoc();

    $sql = "SELECT * FROM posts WHERE thread_id = '$thread_id' ORDER BY created_at ASC";
    $result = $conn->query($sql);

    $posts = array();
    while ($row = $result->fetch_assoc()) {
      $posts[] = $row;
    }
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
} else {
  header("Location: threads.php");
  exit();
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


$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- TinyMCE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
<style>
  .list-group-item {
    margin-bottom: 15px;
    border: none;
    border-radius: 8px;
    padding: 15px;
    background-color: #f9f9f9;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    transition: background-color 0.3s ease;
  }

  .list-group-item:hover {
    background-color: #eee;
  }
</style>



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
          <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4 d-none d-lg-block">
            <div>
              <h3 class="fw-bold mb-3">Discussion Threads</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">View Threads</li>
              </ol>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">View Threads</h4>

              </div>
              <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">
                  <h2><?php echo htmlspecialchars($thread["title"]); ?></h2>
                  <div class="card-tools">
                    <div class="dropdown">
                      <?php if ($student_name == $thread["author"] || $_SESSION['role'] == 'Administrator' || $_SESSION['role'] == 'Superuser'): ?>
                        <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton"
                          data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a href="edit_thread.php?id=<?php echo $thread["id"]; ?>" class="dropdown-item">Edit</a>
                          <a href="delete_thread.php?id=<?php echo $thread["id"]; ?>" class="dropdown-item">Delete</a>

                        </div>
                      <?php endif; ?>
                    </div>
                  </div>

                </div>
                <p><i>Created by <?php echo htmlspecialchars($thread["author"]); ?> on <?php echo $thread["created_at"]; ?></i>
                </p>
                <p><?php echo nl2br($thread["content"]); ?></p>
                <hr>
                <h3>Comments</h3>
                <?php if ($posts): ?>
                  <ul class="list-group">
                    <?php foreach ($posts as $post): ?>
                      <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                          <div>
                            <?php echo nl2br($post["content"]); ?>
                            <!-- nested “posted by” under the content -->
                            <div class="mt-2 text-muted small">
                              <i>- Posted by <?php echo htmlspecialchars($post["author"]); ?> on</i>
                              <?php echo $post["created_at"]; ?>
                            </div>
                          </div>

                          <div class="card-tools">
                            <div class="dropdown">
                              <?php if ($student_name == $post["author"] || $_SESSION['role'] == 'Administrator' || $_SESSION['role'] == 'Superuser'): ?>
                                <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton"
                                  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  <a href="edit_post.php?id=<?php echo $post["id"]; ?>" class="dropdown-item">Edit</a>
                                  <a href="delete_post.php?id=<?php echo $post["id"]; ?>" class="dropdown-item">Delete</a>

                                </div>
                              <?php endif; ?>
                            </div>
                          </div>
                        </div>

                      </li>

                    <?php endforeach; ?>
                  </ul>
                <?php else: ?>
                  <p>No Comments yet.</p>
                <?php endif; ?>
                <form method="post" action="add_post.php">
                  <input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>">
                  <div class="form-group">
                    <label for="content">Add a Comment:</label>
                    <textarea name="content" id="content" rows="3" class="form-control" required></textarea>
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