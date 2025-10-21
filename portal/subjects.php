<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Database connection
include 'db_connection.php';
// Start the session to maintain user state
session_start();


// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}


if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch classes and arms from the database
$classes = $conn->query("SELECT DISTINCT class FROM class");
$arms = $conn->query("SELECT DISTINCT arm FROM arm");

$filter_class = isset($_POST['filter_class']) ? $_POST['filter_class'] : '';
$filter_arm = isset($_POST['filter_arm']) ? $_POST['filter_arm'] : '';

// Handle bulk upload CSV
if (isset($_POST['bulk_submit'])) {
  $class = $_POST['bulk_class'];
  $arm = $_POST['bulk_arm'];

  if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
    // Open the uploaded CSV
    $csvFile = fopen($_FILES['csv_file']['tmp_name'], 'r');

    // Remove/skip header row:
    fgetcsv($csvFile);

    // Process remaining rows
    while (($row = fgetcsv($csvFile)) !== FALSE) {
      $subject = $row[0];
      // $class   = $row[1];
      // $arm     = $row[2];

      // Be sure to escape or use prepared statements in production!
      $conn->query("
              INSERT INTO subject (subject, class, arm)
              VALUES ('$subject', '$class', '$arm')
          ");
    }
    fclose($csvFile);
    echo "<script>alert('Bulk upload successful!');</script>";
  } else {
    echo "<script>alert('Please upload a valid CSV file.');</script>";
  }
}


// Handle individual subject entry
if (isset($_POST['individual_submit'])) {
  $subject = $_POST['subject'];
  $class = $_POST['class'];
  $arm = $_POST['arm'];
  $conn->query("INSERT INTO subject (subject, class, arm) VALUES ('$subject', '$class', '$arm')");
  echo "<script>alert('Subject added successfully!');</script>";
}

// Handle deleting a subject
if (isset($_POST['delete_subject'])) {
  $id = $_POST['id'];
  $conn->query("DELETE FROM subject WHERE id = '$id'");
  echo "<script>alert('Subject deleted successfully!');</script>";
}

// Handle updating a subject
if (isset($_POST['update_subject'])) {
  $id = $_POST['id'];
  $new_subject = $_POST['subject'];
  $new_class = $_POST['class'];
  $new_arm = $_POST['arm'];

  // Fetch the old subject name before updating
  $stmt_old_subject = $conn->prepare("SELECT subject FROM subject WHERE id = ?");
  $stmt_old_subject->bind_param("i", $id);
  $stmt_old_subject->execute();
  $stmt_old_subject->bind_result($old_subject);
  $stmt_old_subject->fetch();
  $stmt_old_subject->close();

  // Update the subject in the subject table
  $stmt_update_subject = $conn->prepare("UPDATE subject SET subject = ?, class = ?, arm = ? WHERE id = ?");
  $stmt_update_subject->bind_param("sssi", $new_subject, $new_class, $new_arm, $id);
  $stmt_update_subject->execute();
  $stmt_update_subject->close();

  // Update the mastersheet table if the subject name has changed
  if ($old_subject !== $new_subject) {
    $stmt_update_mastersheet = $conn->prepare("UPDATE mastersheet SET subject = ? WHERE subject = ? and class = ? and arm = ?");
    $stmt_update_mastersheet->bind_param("ssss", $new_subject, $old_subject, $new_class, $new_arm);
    $stmt_update_mastersheet->execute();
    $stmt_update_mastersheet->close();
  }

  echo "<script>alert('Subject updated successfully!');</script>";
}

// Fetch subjects based on the filters
$query = "SELECT * FROM subject";
if ($filter_class) {
  $query .= " WHERE class = '$filter_class'";
  if ($filter_arm) {
    $query .= " AND arm = '$filter_arm'";
  }
} elseif ($filter_arm) {
  $query .= " WHERE arm = '$filter_arm'";
}
$subjects = $conn->query($query);



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
              <nts class="fw-bold mb-3">Subjects</h3>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">Subjects</li>
                </ol>
            </div>

          </div>

          <!-- BULK UPLOAD ============================ -->
          <div class="row">

            <div class="col-md-6">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Bulk Upload (CSV)</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <p>
                    <form action="" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        <select class="form-control form-select" id="class" name="bulk_class" required>
                          <option value="">Select Class</option>
                          <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['class']; ?>"><?php echo $class['class']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <select class="form-control form-select" id="arm" name="bulk_arm" required>
                          <option value="">Select Arm</option>
                          <?php foreach ($arms as $arm): ?>
                            <option value="<?php echo $arm['arm']; ?>"><?php echo $arm['arm']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <input type="file" class="form-control" id="csv_file" name="csv_file" required>
                      </div>
                      <button type="submit" name="bulk_submit" class="btn btn-success"><span class="btn-label">
                          <i class="fa fa-cloud-upload-alt"></i>Upload</button>

                      <!-- Download Template Link -->
                      <div class="mt-2">
                        <a href="download_subject_template.php" class="btn btn-warning"><span class="btn-label">
                            <i class="fa fa-cloud-download-alt"></i>Download Subject Template</a>
                      </div>
                    </form>
                    </p>

                  </div>
                </div>
              </div>
            </div>


            <div class="col-md-6">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Single Entry</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <p>

                    <form action="" method="post">
                      <div class="form-group">
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter Subject"
                          required>
                      </div>
                      <div class="form-group">
                        <select class="form-control" id="class" name="class" required>
                          <option value="">Select Class</option>
                          <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['class']; ?>"><?php echo $class['class']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <select class="form-control" id="arm" name="arm" required>
                          <option value="">Select Arm</option>
                          <?php foreach ($arms as $arm): ?>
                            <option value="<?php echo $arm['arm']; ?>"><?php echo $arm['arm']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <button type="submit" name="individual_submit" class="btn btn-success"> <span class="btn-label">
                          <i class="fa fa-save"></i> Add Subject</button>
                    </form>

                    </p>

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
                    <div class="card-title">Filter</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <form action="" method="post" class="form-inline mb-4">
                      <div class="form-group mr-2">
                        <select class="form-control" id="filter_class" name="filter_class">
                          <option value="">Select Class</option>
                          <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['class']; ?>" <?php echo ($filter_class == $class['class']) ? 'selected' : ''; ?>><?php echo $class['class']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="form-group mr-2">
                        <select class="form-control" id="filter_arm" name="filter_arm">
                          <option value="">Select Arms</option>
                          <?php foreach ($arms as $arm): ?>
                            <option value="<?php echo $arm['arm']; ?>" <?php echo ($filter_arm == $arm['arm']) ? 'selected' : ''; ?>><?php echo $arm['arm']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <button type="submit" class="btn btn-success"><span class="btn-label">
                          <i class="fa fa-filter"></i> Filter</button>
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
                    <div class="card-title">Subject List</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <div class="table-responsive">
                      <table id="multi-filter-select" class="display table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>Subject</th>
                            <th>Class</th>
                            <th>Arm</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if ($subjects->num_rows == 0): ?>
                            <tr>
                              <td colspan="4" class="text-center">No subjects found.</td>
                            </tr>
                          <?php else: ?>
                            <?php while ($subject = $subjects->fetch_assoc()): ?>
                              <tr>
                                <td><?php echo $subject['subject']; ?></td>
                                <td><?php echo $subject['class']; ?></td>
                                <td><?php echo $subject['arm']; ?></td>
                                <td>
                                  <button type="button" class="btn btn-primary btn-sm edit-subject-btn mb-3" data-id="<?php echo $subject['id']; ?>" data-subject="<?php echo $subject['subject']; ?>" data-class="<?php echo $subject['class']; ?>" data-arm="<?php echo $subject['arm']; ?>" data-bs-toggle="modal" data-bs-target="#editSubjectModal"><span class="btn-label">
                                      <i class="fa fa-edit"></i></button>
                                  <form action="" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $subject['id']; ?>">
                                    <button type="submit" name="delete_subject" class="btn btn-danger btn-sm mb-3"><span
                                        class="btn-label">
                                        <i class="fa fa-trash"></i></button>
                                  </form>
                                </td>

                              </tr>
                            <?php endwhile; ?>
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

  <!-- Edit Subject Modal -->
  <div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editSubjectModalLabel">Edit Subject</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post">
          <div class="modal-body">
            <input type="hidden" id="edit_subject_id" name="id">
            <div class="mb-3">
              <label for="edit_subject_name" class="form-label">Subject Name</label>
              <input type="text" class="form-control" id="edit_subject_name" name="subject" required>
            </div>
            <div class="mb-3">
              <label for="edit_subject_class" class="form-label">Class</label>
              <select class="form-control" id="edit_subject_class" name="class" required>
                <option value="">Select Class</option>
                <?php
                // Re-fetch classes as the connection was closed
                include 'db_connection.php';
                $classes_modal = $conn->query("SELECT DISTINCT class FROM class");
                foreach ($classes_modal as $class): ?>
                  <option value="<?php echo $class['class']; ?>"><?php echo $class['class']; ?></option>
                <?php endforeach;
                $conn->close();
                ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_subject_arm" class="form-label">Arm</label>
              <select class="form-control" id="edit_subject_arm" name="arm" required>
                <option value="">Select Arm</option>
                <?php
                // Re-fetch arms as the connection was closed
                include 'db_connection.php';
                $arms_modal = $conn->query("SELECT DISTINCT arm FROM arm");
                foreach ($arms_modal as $arm): ?>
                  <option value="<?php echo $arm['arm']; ?>"><?php echo $arm['arm']; ?></option>
                <?php endforeach;
                $conn->close();
                ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="update_subject" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var editSubjectModal = document.getElementById('editSubjectModal');
      editSubjectModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var subject = button.getAttribute('data-subject');
        var className = button.getAttribute('data-class');
        var arm = button.getAttribute('data-arm');

        var modalIdInput = editSubjectModal.querySelector('#edit_subject_id');
        var modalSubjectInput = editSubjectModal.querySelector('#edit_subject_name');
        var modalClassSelect = editSubjectModal.querySelector('#edit_subject_class');
        var modalArmSelect = editSubjectModal.querySelector('#edit_subject_arm');

        modalIdInput.value = id;
        modalSubjectInput.value = subject;
        modalClassSelect.value = className;
        modalArmSelect.value = arm;
      });
    });
  </script>

</body>

</html>