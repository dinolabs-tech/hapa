<?php include('components/admin_logic.php');


// PRINCIPAL'S COMMENTS =====================================

// Fetching data for dropdowns
$classes = $conn->query("SELECT DISTINCT class FROM class");
$arms = $conn->query("SELECT DISTINCT arm FROM arm");
$terms = $conn->query("SELECT DISTINCT cterm FROM currentterm");
$sessions = $conn->query("SELECT DISTINCT csession FROM currentsession");

// Function to generate a unique ID
function generateUniqueId()
{
  return 'CMT-' . uniqid();
}

// Initialize variables for the update form
$successMessage = '';
$errorMessage = '';
$id = $name = $comment = $class = $arm = $term = $session = '';

// Handle form submission for adding/updating records
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (isset($_POST['csv_upload'])) {
    // 1. CSV UPLOAD
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
      $csvClass = $_POST['csv_class'];
      $csvArm = $_POST['csv_arm'];
      $csvTerm = $_POST['csv_term'];
      $csvSession = $_POST['csv_session'];

      $file = fopen($_FILES['csv_file']['tmp_name'], "r");
      fgetcsv($file); // Skip header
      while (($row = fgetcsv($file)) !== FALSE) {
        $id = !empty($row[0]) ? $row[0] : generateUniqueId();
        $name = $row[1];
        $comment = $row[2];

        if (!empty($name) && !empty($comment)) { // Check that important fields are not empty
          $sql = "INSERT INTO principalcomments (id, name, comment, class, arm, term, csession)
                          VALUES ('$id', '$name', '$comment', '$csvClass', '$csvArm', '$csvTerm', '$csvSession')";
          if (!$conn->query($sql)) {
            $errorMessage = "Error: " . $conn->error;
          }
        }
      }
      fclose($file);
      $successMessage = "CSV file uploaded and records saved successfully!";
    }
  } else {
    // 2. FORM SAVE OR UPDATE
    $hidden_id = $_POST['hidden_id'];
    $id = $_POST['id'];
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    $class = $_POST['class'];
    $arm = $_POST['arm'];
    $term = $_POST['term'];
    $session = $_POST['session'];

    if (empty($hidden_id)) {
      // Insert New Record
      $stmt = $conn->prepare("INSERT INTO principalcomments (id, name, comment, class, arm, term, csession) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sssssss", $id, $name, $comment, $class, $arm, $term, $session);
      if ($stmt->execute()) {
        $successMessage = "New record inserted successfully!";
      } else {
        $errorMessage = "Insert Error: " . $stmt->error;
      }
      $stmt->close();
    } else {
      // Update Existing Record
      $stmt = $conn->prepare("UPDATE principalcomments 
                                  SET id=?, name=?, comment=?, class=?, arm=?, term=?, csession=?
                                  WHERE id=?");
      $stmt->bind_param("ssssssss", $id, $name, $comment, $class, $arm, $term, $session, $hidden_id);
      if ($stmt->execute()) {
        $successMessage = "Record updated successfully!";
      } else {
        $errorMessage = "Update Error: " . $stmt->error;
      }
      $stmt->close();
    }
  }
}

// --- Handle Bulk Delete ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_delete'])) {
  $deleteClass = $_POST['delete_class'];
  $deleteArm = $_POST['delete_arm'];

  // Get current term and session
  $currentTermResult = $conn->query("SELECT cterm FROM currentterm WHERE id = 1");
  $currentTerm = '';
  if ($currentTermResult && $row = $currentTermResult->fetch_assoc()) {
    $currentTerm = $row['cterm'];
  }

  $currentSessionResult = $conn->query("SELECT csession FROM currentsession WHERE id = 1");
  $currentSession = '';
  if ($currentSessionResult && $row = $currentSessionResult->fetch_assoc()) {
    $currentSession = $row['csession'];
  }

  // SQL query to delete all records for the selected class and arm in current term/session
  $sql = "DELETE FROM principalcomments WHERE class='$deleteClass' AND arm='$deleteArm' AND term='$currentTerm' AND csession='$currentSession'";
  if ($conn->query($sql) === TRUE) {
    $bulkDeleteMessage = 'All principal comments for ' . htmlspecialchars($deleteClass) . ' ' . htmlspecialchars($deleteArm) . ' have been deleted successfully!';
  } else {
    $bulkDeleteMessage = 'Error deleting comments: ' . $conn->error;
  }
}


// Handle record deletion
if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  $conn->query("DELETE FROM principalcomments WHERE id='$delete_id'");
}

// Fetch the record to edit if an ID is passed
$editRecord = null;
if (isset($_GET['edit'])) {
  $edit_id = $_GET['edit'];
  $result = $conn->query("SELECT * FROM principalcomments WHERE id='$edit_id'");
  if ($result->num_rows > 0) {
    $editRecord = $result->fetch_assoc();
    $id = $editRecord['id'];
    $name = $editRecord['name'];
    $comment = $editRecord['comment'];
    $class = $editRecord['class'];
    $arm = $editRecord['arm'];
    $term = $editRecord['term'];
    $session = $editRecord['csession'];
  }
}



// Fetch current term
$currentTermResult = $conn->query("SELECT cterm FROM currentterm WHERE id = 1");
if ($currentTermResult && $row = $currentTermResult->fetch_assoc()) {
  $term = $row['cterm'];
}

// Fetch current session
$currentSessionResult = $conn->query("SELECT csession FROM currentsession WHERE id = 1");
if ($currentSessionResult && $row = $currentSessionResult->fetch_assoc()) {
  $session = $row['csession'];
}

// Fetch all records
$principalrecords = $conn->query("SELECT * FROM principalcomments where term = '$term' AND csession = '$session' ORDER BY id DESC");



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
            class="d-flex d-none d-lg-block align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
              <h3 class="fw-bold mb-3">Principal's Comments</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Results</li>
                <li class="breadcrumb-item active">Principal's Comments</li>
              </ol>
            </div>

          </div>

          <!-- BULK UPLOAD ============================ -->
          <div class="row">

            <!-- Download result Template for the selected clas ============================ -->
            <div class="col-md-4">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Download Template</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <form method="POST" action="download_principal_template.php" id="downloadForm">
                    <div class="mb-4 mt-2">

                      <!-- CLASS Dropdown -->
                      <select name="d_class" id="d_class" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch classes again
                        $classes->data_seek(0);
                        while ($row = $classes->fetch_assoc()): ?>
                          <option value="<?php echo $row['class']; ?>"><?php echo $row['class']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <!-- ARM Dropdown -->
                      <select name="d_arm" id="d_arm" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch arms again
                        $arms->data_seek(0);
                        while ($row = $arms->fetch_assoc()): ?>
                          <option value="<?php echo $row['arm']; ?>"><?php echo $row['arm']; ?></option>
                        <?php endwhile; ?>
                      </select>

                      <!-- DOWNLOAD Button -->
                      <div class="text-center mt-3">
                        <button type="submit" name="bulk_upload" class="btn btn-primary btn-icon btn-round ps-1">
                          <span class="btn-label">
                            <i class="fa fa-cloud-download-alt"></i>
                          </span>
                        </button>
                      </div>

                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Comments</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">
                    <p>

                      <?php if ($successMessage) {
                        echo "<div class='alert alert-success'>$successMessage</div>";
                      } ?>
                      <?php if (!empty($bulkDeleteMessage)) {
                        echo "<div class='alert alert-success'>$bulkDeleteMessage</div>";
                      } ?>

                    <form method="POST" class="form-container">
                      <input class="form-control" type="hidden" name="hidden_id" id="hidden_id" value="<?php echo $id; ?>">
                      <div class="form-group">
                        <input class="form-control" type="text" name="id" id="id" value="<?php echo $id; ?>" required placeholder="ID">
                      </div>
                      <div class="form-group">
                        <input class="form-control" type="text" name="name" id="name" value="<?php echo $name; ?>" required placeholder="Name">
                      </div>
                      <div class="form-group">
                        <input class="form-control" type="text" name="comment" id="comment" value="<?php echo $comment; ?>" required placeholder="Comments">
                      </div>
                      <div class="form-group">
                        <select class="form-control form-select" name="class" id="class" required>
                          <option value="">Select Class</option>
                          <?php
                          $classes->data_seek(0); // rewind
                          while ($row = $classes->fetch_assoc()): ?>
                            <option value="<?php echo $row['class']; ?>" <?php if ($class == $row['class']) echo 'selected'; ?>>
                              <?php echo $row['class']; ?>
                            </option>
                          <?php endwhile; ?>
                        </select>
                      </div>

                      <div class="form-group">
                        <select class="form-control form-select" name="arm" id="arm" required>
                          <option value="">Select Arm</option>
                          <?php
                          $arms->data_seek(0); // rewind
                          while ($row = $arms->fetch_assoc()): ?>
                            <option value="<?php echo $row['arm']; ?>" <?php if ($arm == $row['arm']) echo 'selected'; ?>>
                              <?php echo $row['arm']; ?>
                            </option>
                          <?php endwhile; ?>
                        </select>
                      </div>

                      <div class="form-group">
                        <select class="form-control form-select" name="term" id="term" required>
                          <option value="">Select Term</option>
                          <?php
                          $terms->data_seek(0); // rewind
                          while ($row = $terms->fetch_assoc()): ?>
                            <option value="<?php echo $row['cterm']; ?>" <?php if ($term == $row['cterm']) echo 'selected'; ?>>
                              <?php echo $row['cterm']; ?>
                            </option>
                          <?php endwhile; ?>
                        </select>
                      </div>

                      <div class="form-group">
                        <select class="form-control form-select" name="session" id="session" required>
                          <option value="">Select Session</option>
                          <?php
                          $sessions->data_seek(0); // rewind
                          while ($row = $sessions->fetch_assoc()): ?>
                            <option value="<?php echo $row['csession']; ?>" <?php if ($session == $row['csession']) echo 'selected'; ?>>
                              <?php echo $row['csession']; ?>
                            </option>
                          <?php endwhile; ?>
                        </select>
                      </div>

                      <div class="text-center">
                        <button type="submit" class="btn btn-success btn-icon btn-round ps-1">
                          <span class="btn-label">
                            <i class="fa fa-save"></i></button>
                        <button type="button" class="btn btn-secondary btn-icon btn-round ps-1" onclick="window.location.href='principalcomment.php';">
                          <span class="btn-label">
                            <i class="fa fa-undo"></i></button>
                      </div>
                    </form>

                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- FILTER UPLOADED ============================ -->
            <div class="col-md-4">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Bulk Upload CSV</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <form method="post" enctype="multipart/form-data" style="margin-top: 30px;">
                      <input type="file" name="csv_file" class="form-control" id="csv_file" accept=".csv" required>
                      <br>
                      <select name="csv_class" id="csv_class" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch classes again
                        $classes->data_seek(0);
                        while ($row = $classes->fetch_assoc()): ?>
                          <option value="<?php echo $row['class']; ?>"><?php echo $row['class']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <select name="csv_arm" id="csv_arm" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch arms again
                        $arms->data_seek(0);
                        while ($row = $arms->fetch_assoc()): ?>
                          <option value="<?php echo $row['arm']; ?>"><?php echo $row['arm']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <select name="csv_term" id="csv_term" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch terms again
                        $terms->data_seek(0);
                        while ($row = $terms->fetch_assoc()): ?>
                          <option value="<?php echo $row['cterm']; ?>"><?php echo $row['cterm']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <select name="csv_session" id="csv_session" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch sessions again
                        $sessions->data_seek(0);
                        while ($row = $sessions->fetch_assoc()): ?>
                          <option value="<?php echo $row['csession']; ?>"><?php echo $row['csession']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <div class="text-center">
                        <button type="submit" class="btn btn-warning btn-icon btn-round ps-1" name="csv_upload">
                          <span class="btn-label">
                            <i class="fa fa-cloud-upload-alt"></i></button>
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>



          </div>

          <!-- Bulk Delete Section -->
          <div class="row">
            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Bulk Delete Principal Comments</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">
                    <!-- Form for bulk deleting principal comments -->
                    <form method="post" onsubmit="return confirm('Are you sure you want to delete all principal comments for the selected class and arm? This action cannot be undone.');">
                      <div class="row">
                        <div class="col-md-4">
                          <select name="delete_class" id="delete_class" class="form-control form-select" required>
                            <option value="">Select Class</option>
                            <?php
                            $classes->data_seek(0);
                            while ($row = $classes->fetch_assoc()): ?>
                              <option value="<?php echo htmlspecialchars($row['class']); ?>"><?php echo htmlspecialchars($row['class']); ?></option>
                            <?php endwhile; ?>
                          </select>
                        </div>
                        <div class="col-md-4">
                          <select name="delete_arm" id="delete_arm" class="form-control form-select" required>
                            <option value="">Select Arm</option>
                            <?php
                            $arms->data_seek(0);
                            while ($row = $arms->fetch_assoc()): ?>
                              <option value="<?php echo htmlspecialchars($row['arm']); ?>"><?php echo htmlspecialchars($row['arm']); ?></option>
                            <?php endwhile; ?>
                          </select>
                        </div>
                        <div class="col-md-4">
                          <button type="submit" name="bulk_delete" class="btn btn-danger btn-round ps-3">
                            <span class="btn-label">
                              <i class="fa fa-trash"></i>
                            </span>
                            Bulk Delete
                          </button>
                        </div>
                      </div>
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
                    <div class="card-title">Uploaded Comments</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <div class="table-responsive">
                      <!-- Display subjects -->
                      <table
                        id="multi-filter-select"
                        class="display table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Comment</th>
                            <th>Class</th>
                            <th>Arm</th>
                            <th>Term</th>
                            <th>Session</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php while ($row = $principalrecords->fetch_assoc()): ?>
                            <tr>
                              <td><?php echo $row['id']; ?></td>
                              <td><?php echo $row['name']; ?></td>
                              <td><?php echo $row['comment']; ?></td>
                              <td><?php echo $row['class']; ?></td>
                              <td><?php echo $row['arm']; ?></td>
                              <td><?php echo $row['term']; ?></td>
                              <td><?php echo $row['csession']; ?></td>
                              <td class="d-flex">
                                <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-icon btn-round me-2 ps-1"><span class="btn-label">
                                    <i class="fa fa-edit"></i></a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-icon btn-round ps-1"><span class="btn-label">
                                    <i class="fa fa-trash"></i></a>
                              </td>
                            </tr>
                          <?php endwhile; ?>
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
  <script>

  </script>
  <?php include('scripts.php'); ?>
</body>

</html>
