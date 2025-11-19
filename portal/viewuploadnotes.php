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
  customErrorHandler(E_ERROR, "Connection failed: " . $conn->connect_error, __FILE__, __LINE__);
}

// Fetch classes from the database
$class_sql = "SELECT DISTINCT Class FROM class"; // Adjust this query based on your class table structure
$class_stmt = $conn->prepare($class_sql);
if ($class_stmt === false) {
  customErrorHandler(E_ERROR, "Error preparing statement: " . $conn->error, __FILE__, __LINE__);
}
$class_stmt->execute();
$class_stmt->bind_result($class_name); // Bind result to a variable
$classes = [];
while ($class_stmt->fetch()) { // Fetch results
  $classes[] = $class_name;
}

// Fetch subjects from the database based on selected class
$subjects = [];
if (isset($_POST['Class'])) {
  $selected_class = $_POST['Class'];
  $subject_sql = "SELECT subject FROM subject WHERE Class = ?"; // Adjust this query based on your subject table structure
  $subject_stmt = $conn->prepare($subject_sql);
  if ($subject_stmt === false) {
    customErrorHandler(E_ERROR, "Error preparing statement: " . $conn->error, __FILE__, __LINE__);
  }
  $subject_stmt->bind_param("s", $selected_class);
  $subject_stmt->execute();
  $subject_stmt->bind_result($subject_name); // Bind result to a variable
  while ($subject_stmt->fetch()) { // Fetch results
    $subjects[] = $subject_name;
  }
  $subject_stmt->close();
}

// Handle form submission for upload
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload'])) {
  $subject_name = $_POST['subject'];
  $class_name = $_POST['class'];

  if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
    $document = $_FILES['document'];
    $file_ext = pathinfo($document['name'], PATHINFO_EXTENSION);
    $session_id = $_SESSION['session_id']; // Replace with your session variable
    $file_name = $subject_name . '_' . $class_name . '_' . $session_id . '.' . $file_ext;
    $target_dir = 'notes/';
    $target_file = $target_dir . $file_name;

    // Create the notes directory if it doesn't exist
    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0755, true);
    }

    // Insert assignment details into the database
    $insert_sql = "INSERT INTO notes (subject_name, class_name, session_id, file_name) VALUES (?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ssss", $subject_name, $class_name, $session_id, $file_name);
    if ($insert_stmt->execute()) {
      $message = 'File uploaded and record saved successfully!';
    } else {
      $message = 'File uploaded but record not saved: ' . $conn->error;
    }
    $insert_stmt->close();
  } else {
    $message = 'Please select a valid file.';
  }
}

// Handle form submission for delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
  $note_id = $_POST['note_id'];
  $file_name = $_POST['file_name'];

  // Delete the file from the file system
  $target_file = 'notes/' . $file_name;
  if (file_exists($target_file)) {
    unlink($target_file);
  }

  // Delete the record from the database
  $delete_sql = "DELETE FROM notes WHERE id = ?";
  $delete_stmt = $conn->prepare($delete_sql);
  $delete_stmt->bind_param("i", $note_id);
  if ($delete_stmt->execute()) {
    $message = 'Notes deleted successfully!';
  } else {
    $message = 'Error deleting Note: ' . $conn->error;
  }
  $delete_stmt->close();
}

// Fetch uploaded assignments
$notes_sql = "SELECT * FROM notes";
$notes_stmt = $conn->prepare($notes_sql);
$notes_stmt->execute();
$notes_row = [];
$notes_result_metadata = $notes_stmt->result_metadata();
while ($field = $notes_result_metadata->fetch_field()) {
  $notes_columns[] = &$notes_row[$field->name];
}
call_user_func_array([$notes_stmt, 'bind_result'], $notes_columns);

$uploaded_notes = [];
while ($notes_stmt->fetch()) {
  $row_copy = [];
  foreach ($notes_row as $key => $value) {
    $row_copy[$key] = $value;
  }
  $uploaded_notes[] = $row_copy;
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
          <div
            class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
              <h3 class="fw-bold mb-3">Notes</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">E-Learning Resources</li>
                <li class="breadcrumb-item active">Notes</li>
                <li class="breadcrumb-item active">View</li>
              </ol>
            </div>

          </div>

          <!-- BULK UPLOAD ============================ -->
          <div class="row">

            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Notes</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <p>
                    <div class="table-responsive">
                      <table
                        id="multi-filter-select"
                        class="display table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>Subject</th>
                            <th>Class</th>
                            <th>File Name</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($uploaded_notes as $assignment) { ?>
                            <tr>
                              <td><?php echo $assignment['subject_name']; ?></td>
                              <td><?php echo $assignment['class_name']; ?></td>
                              <td><?php echo $assignment['file_name']; ?></td>
                              <td>
                                <form action="viewuploadnotes.php" method="post" style="display:inline;">
                                  <input type="hidden" name="note_id" value="<?php echo $assignment['id']; ?>">
                                  <input type="hidden" name="file_name" value="<?php echo $assignment['file_name']; ?>">
                                  <button type="submit" name="delete" class="btn btn-danger btn-icon btn-round ps-1"><span class="btn-label">
                                      <i class="fa fa-trash"></i></button>
                                </form>
                              </td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                    </p>


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