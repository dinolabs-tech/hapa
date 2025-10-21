<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session to maintain user state
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Database connection
include 'db_connection.php';

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$edit_mode = false;
$edit_data = [];

if (isset($_GET['edit_id'])) {
  $edit_id = intval($_GET['edit_id']);
  $edit_mode = true;

  $stmt = $conn->prepare("SELECT staffname, username, password, role FROM login WHERE id = ?");
  $stmt->bind_param("i", $edit_id);
  $stmt->execute();
  $stmt->bind_result($edit_name, $edit_username, $edit_password, $edit_role);
  $stmt->fetch();
  $stmt->close();
}


$message = "";

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  $delete_id = intval($_POST['delete_id']);
  $stmt = $conn->prepare("DELETE FROM login WHERE id = ?");
  $stmt->bind_param("i", $delete_id);

  if ($stmt->execute()) {
    $message = "Record deleted successfully!";
  } else {
    $message = "Error deleting record: " . $stmt->error;
  }

  $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['username'], $_POST['password'], $_POST['role'])) {
  $name = $_POST['name'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
    // Update existing user
    $edit_id = intval($_POST['edit_id']);
    $stmt = $conn->prepare("UPDATE login SET staffname=?, username=?, password=?, role=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $username, $password, $role, $edit_id);

    if ($stmt->execute()) {
      $message = "Record updated successfully!";
    } else {
      $message = "Error updating record: " . $stmt->error;
    }

    $stmt->close();
  } else {
    // Insert new user
    // Check if username already exists
    $check_stmt = $conn->prepare("SELECT id FROM login WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
      $message = "Username already exists. Please choose another.";
    } else {
      $stmt = $conn->prepare("INSERT INTO login (staffname, username, password, role) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $name, $username, $password, $role);

      if ($stmt->execute()) {
        $message = "Record saved successfully!";
      } else {
        $message = "<script>alert('Error saving record: " . addslashes($stmt->error) . "');</script>";
      }

      $stmt->close();
    }

    $check_stmt->close();

  }
}



// Fetch data from the login table
$sql = "SELECT id, staffname, username, role FROM login where role != 'Superuser'";
$result = $conn->query($sql);
// Convert result set into an array so it can be looped over safely later
$students = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $students[] = $row;
  }
}




// Fetch the logged-in Staff name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();



// Close database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>

<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <?php include('adminnav.php'); ?>
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
              <nts class="fw-bold mb-3">User Control</h3>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">User-Control</li>
                </ol>
            </div>

          </div>

          <!-- BULK UPLOAD ============================ -->
          <div class="row">

            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Register</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <?php if (!empty($message)): ?>
                      <div class="alert alert-info"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                      <input type="hidden" name="edit_id"
                        value="<?php echo $edit_mode ? htmlspecialchars($_GET['edit_id']) : ''; ?>">
                      <div class="mb-3">
                        <input type="text" id="name" name="name" class="form-control" placeholder="Staff Name"
                          value="<?php echo $edit_mode ? htmlspecialchars($edit_name) : ''; ?>" required>
                      </div>
                      <div class="mb-3">
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username"
                          value="<?php echo $edit_mode ? htmlspecialchars($edit_username) : ''; ?>" required>
                      </div>
                      <div class="mb-3">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password"
                          value="<?php echo $edit_mode ? htmlspecialchars($edit_password) : ''; ?>" required>
                      </div>
                      <div class="mb-3">
                        <select id="role" name="role" class="form-select" required>
                          <option value="">Select Role</option>
                          <?php
                          $roles = ['Administrator', 'Tuckshop', 'Teacher', 'Bursary', 'Store', 'Library', 'Admission'];
                          foreach ($roles as $r) {
                            $selected = ($edit_mode && $edit_role === $r) ? 'selected' : '';
                            echo "<option value=\"$r\" $selected>$r</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <button type="submit" class="btn btn-success"><span class="btn-label">
                          <i class="fa fa-save"></i> <?php echo $edit_mode ? 'Update' : 'Save'; ?></button>
                      <a href="usercontrol.php" class="btn btn-secondary"><span class="btn-label">
                          <i class="fa fa-undo"></i> Reset</a>
                    </form>


                  </div>
                </div>
              </div>
            </div>
          </div>



          <div class="row">

            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Users</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <div class="table-responsive">
                      <table id="multi-filter-select" class="display table table-striped table-hover">
                        <thead>
                          <tr>
                            <th style="display: none;">ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (!empty($students)): ?>
                            <?php foreach ($students as $student): ?>
                              <tr>
                                <td style="display: none;"><?php echo htmlspecialchars($student['id']); ?></td>
                                <td><?php echo htmlspecialchars($student['staffname']); ?></td>
                                <td><?php echo htmlspecialchars($student['username']); ?></td>
                                <td><?php echo htmlspecialchars($student['role']); ?></td>
                                <td>
                                  <form method="POST" style="display:inline;">
                                    <a href="?edit_id=<?php echo htmlspecialchars($student['id']); ?>"
                                      class="btn btn-primary btn-sm">
                                      <i class="fa fa-edit"></i>
                                    </a>

                                    <input type="hidden" name="delete_id"
                                      value="<?php echo htmlspecialchars($student['id']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"><span class="btn-label">
                                        <i class="fa fa-trash"></i></button>
                                  </form>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <tr>
                              <td colspan="5" class="text-center">No records found</td>
                            </tr>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>



        </div>
      </div>

      </script>
      <?php include('footer.php'); ?>
    </div>

    <!-- Custom template | don't include it in your project! -->
    <?php include('cust-color.php'); ?>
    <!-- End Custom template -->
  </div>
  <?php include('scripts.php'); ?>



</body>

</html>