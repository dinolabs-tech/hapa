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


// Load Threads
$threads_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $threads_per_page;

$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
  $search = mysqli_real_escape_string($conn, $search);

  $sql = "SELECT * FROM threads WHERE title LIKE '%$search%' OR author LIKE '%$search%' ORDER BY created_at DESC LIMIT $start, $threads_per_page";
  $result = $conn->query($sql);

  $threads = array();
  while ($row = $result->fetch_assoc()) {
    $threads[] = $row;
  }

  $sql = "SELECT COUNT(*) FROM threads WHERE title LIKE :search OR author LIKE :search";
  $sql = "SELECT COUNT(*) FROM threads WHERE title LIKE '%$search%' OR author LIKE '%$search%'";
  $result = $conn->query($sql);
  $total_threads = $result->fetch_row()[0];
  $total_pages = ceil($total_threads / $threads_per_page);
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
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
                <li class="breadcrumb-item active">Threads</li>
              </ol>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Threads</h4>
              </div>
              <div class="card-body">

                <form method="GET" action="threads.php" class="mb-3">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search threads" name="search">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                  </div>
                </form>

                <?php if ($threads): ?>
                  <div class="row">
                    <?php foreach ($threads as $thread): ?>
                      <div class="col-md-6">
                        <div class="card">
                          <div class="card-body">
                            <h5 class="card-title">
                              <a href="view_thread.php?id=<?php echo $thread["id"]; ?>">
                                <?php echo htmlspecialchars($thread["title"]); ?>
                              </a>
                            </h5>
                            <p class="card-text">
                              Created by <?php echo htmlspecialchars($thread["author"]); ?> on <?php echo date('F j, Y, g:i a', strtotime($thread["created_at"])); ?>
                            </p>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php else: ?>
                  <p>No threads yet.</p>
                <?php endif; ?>

                <nav aria-label="Page navigation">
                  <ul class="pagination justify-content-center">
                    <?php if ($total_pages > 1): ?>
                      <?php if ($page > 1): ?>
                        <li class="page-item">
                          <a class="page-link" href="threads.php?page=<?php echo $page - 1; ?>">Previous</a>
                        </li>
                      <?php endif; ?>

                      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                          <a class="page-link" href="threads.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                      <?php endfor; ?>

                      <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                          <a class="page-link" href="threads.php?page=<?php echo $page + 1; ?>">Next</a>
                        </li>
                      <?php endif; ?>
                    <?php endif; ?>
                  </ul>
                </nav>

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



</body>

</html>