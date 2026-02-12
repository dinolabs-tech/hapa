<?php

/**
 * classteachercomment.php
 *
 * This file provides an administrative interface for managing class teacher comments.
 * It allows administrators to add, update, and upload comments in bulk via CSV.
 * The comments are associated with specific students, classes, arms, terms, and sessions.
 *
 * Key functionalities include:
 * - User authentication and session management (via admin_logic.php).
 * - Database connection.
 * - Handling POST requests for inserting or updating class teacher comments.
 * - Handling CSV uploads for bulk comment insertion.
 * - Retrieving distinct academic parameters (class, arm, term, session) for dropdowns.
 * - Displaying a list of uploaded comments.
 * - Includes various UI components like head, navigation, header, footer, and scripts.
 */

// Include the administrative logic file, which likely handles session checks and other admin-specific functions.
include('components/admin_logic.php');

// --- Data Retrieval for Form Dropdowns ---
// Fetch distinct classes from the 'class' table for the filter dropdown.
$classes = $conn->query("SELECT DISTINCT class FROM class");
// Fetch distinct arms from the 'arm' table for the filter dropdown.
$arms = $conn->query("SELECT DISTINCT arm FROM arm");
// Fetch distinct terms from the 'currentterm' table for the filter dropdown.
$terms = $conn->query("SELECT DISTINCT cterm FROM currentterm");
// Fetch distinct sessions from the 'currentsession' table for the filter dropdown.
$sessions = $conn->query("SELECT DISTINCT csession FROM currentsession");

/**
 * Executes a SQL query and handles potential errors.
 *
 * @param mysqli $conn The database connection object.
 * @param string $sql The SQL query to execute.
 * @return mixed True on success, or an error message string on failure.
 */
function executeClassQuery($conn, $sql)
{
  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return "Error: " . $sql . "<br>" . $conn->error;
  }
}

// --- Handle CSV Upload ---
// Check if the form has been submitted for CSV upload.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csv_upload'])) {
  // Check if a file has been uploaded and if there are no upload errors.
  if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === 0) {
    // Retrieve the selected class, arm, term, and session from the form.
    $csvClass = $_POST['csv_class'];
    $csvArm = $_POST['csv_arm'];
    $csvTerm = $_POST['csv_term'];
    $csvSession = $_POST['csv_session'];

    // Open the uploaded CSV file for reading.
    $file = fopen($_FILES['csv_file']['tmp_name'], "r");

    // Skip the header row of the CSV file.
    fgetcsv($file);

    // Read the CSV file line by line.
    while (($row = fgetcsv($file)) !== FALSE) {
      // Skip empty lines.
      if (count($row) < 16) {
        continue; // Not enough columns, skip this row
      }

      // Extract data from the CSV row, trimming whitespace from each value.
      $id = trim($row[0]);
      $name = trim($row[1]);
      $comment = trim($row[2]);
      $schlopen = trim($row[3]);
      $dayspresent = trim($row[4]);
      $daysabsent = trim($row[5]);

      $attentiveness = trim($row[6]);
      $neatness = trim($row[7]);
      $politeness = trim($row[8]);
      $selfcontrol = trim($row[9]);
      $punctuality = trim($row[10]);
      $relationship = trim($row[11]);
      $handwriting = trim($row[12]);
      $music = trim($row[13]);
      $club = trim($row[14]);
      $sport = trim($row[15]);

      // SQL query to insert the data into the 'classcomments' table.
      $sql = "INSERT INTO classcomments (id, name, comment, schlopen, dayspresent, daysabsent, attentiveness, neatness, politeness, selfcontrol, punctuality, relationship, handwriting, music, club, sport, class, arm, term, csession)
                VALUES ('$id', '$name', '$comment', '$schlopen', '$dayspresent', '$daysabsent', '$attentiveness', '$neatness', '$politeness', '$selfcontrol', '$punctuality', '$relationship', '$handwriting', '$music', '$club', '$sport', '$csvClass', '$csvArm', '$csvTerm', '$csvSession')";
      // Execute the query using the helper function.
      executeClassQuery($conn, $sql);
    }
    // Close the CSV file.
    fclose($file);
    // Set a success message to be displayed to the user.
    $bulk_upload = 'CSV file uploaded and records saved successfully';
  }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // --- Handle Form Submission for Adding/Updating Records ---
  // Retrieve data from the submitted form.
  $hidden_id = $_POST['hidden_id'];
  $id = $_POST['id'];
  $name = $_POST['name'];
  $comment = $_POST['comment'];
  $schlopen = $_POST['schlopen'];
  $dayspresent = $_POST['dayspresent'];
  $daysabsent = $_POST['daysabsent'];

  $class = $_POST['class'];
  $arm = $_POST['arm'];
  $term = $_POST['term'];
  $session = $_POST['session'];

  $attentiveness = $_POST['attentiveness'];
  $neatness = $_POST['neatness'];
  $politeness = $_POST['politeness'];
  $selfcontrol = $_POST['selfcontrol'];
  $punctuality = $_POST['punctuality'];
  $relationship = $_POST['relationship'];
  $handwriting = $_POST['handwriting'];
  $music = $_POST['music'];
  $club = $_POST['club'];
  $sport = $_POST['sport'];

  // Determine if it's an insert or update operation based on the presence of 'hidden_id'.
  if (empty($hidden_id)) {
    // SQL query to insert a new record into the 'classcomments' table.
    $sql = "INSERT INTO classcomments (id,
      name, comment, schlopen, dayspresent, daysabsent, class, arm, term, csession,
      attentiveness, neatness, politeness, selfcontrol, punctuality,
      relationship, handwriting, music, club, sport
  )
  VALUES ('$id',
      '$name', '$comment', '$schlopen', '$dayspresent', '$daysabsent', '$class', '$arm', '$term', '$session',
      '$attentiveness', '$neatness', '$politeness', '$selfcontrol', '$punctuality',
      '$relationship', '$handwriting', '$music', '$club', '$sport'
  )";
  } else {
    // SQL query to update an existing record in the 'classcomments' table.
    $sql = "UPDATE classcomments SET
      id='$id',
      name='$name',
      comment='$comment',
      schlopen='$schlopen',
      dayspresent='$dayspresent',
      daysabsent='$daysabsent',
      class='$class',
      arm='$arm',
      term='$term',
      csession='$session',
      attentiveness='$attentiveness',
      neatness='$neatness',
      politeness='$politeness',
      selfcontrol='$selfcontrol',
      punctuality='$punctuality',
      relationship='$relationship',
      handwriting='$handwriting',
      music='$music',
      club='$club',
      sport='$sport'
  WHERE id='$hidden_id'";
  }

  // Execute the query using the helper function.
  $result = executeClassQuery($conn, $sql);

  $save_message = 'Record Saved Successfully!'; // Set a success message.
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
  $sql = "DELETE FROM classcomments WHERE class='$deleteClass' AND arm='$deleteArm' AND term='$currentTerm' AND csession='$currentSession'";
  $result = executeClassQuery($conn, $sql);

  if ($result === true) {
    $bulk_delete_message = 'All class comments for ' . htmlspecialchars($deleteClass) . ' ' . htmlspecialchars($deleteArm) . ' have been deleted successfully!';
  } else {
    $bulk_delete_message = 'Error deleting comments: ' . $result;
  }
}

// --- Handle Record Deletion ---
// Check if a delete request has been made via GET parameters.
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  // SQL query to delete a record from the 'classcomments' table.
  $sql = "DELETE FROM classcomments WHERE id='$id'";
  executeClassQuery($conn, $sql); // Execute the query using the helper function.
}

// --- Fetch Current Term and Session ---
// Fetch the current term from the 'currentterm' table.
$currentTermResult = $conn->query("SELECT cterm FROM currentterm WHERE id = 1");
if ($currentTermResult && $row = $currentTermResult->fetch_assoc()) {
  $term = $row['cterm'];
}

// Fetch the current session from the 'currentsession' table.
$currentSessionResult = $conn->query("SELECT csession FROM currentsession WHERE id = 1");
if ($currentSessionResult && $row = $currentSessionResult->fetch_assoc()) {
  $session = $row['csession'];
}

// --- Fetch All Records for Display ---
// Retrieve all records from the 'classcomments' table, ordered by ID in descending order.
$records = $conn->query("SELECT * FROM classcomments where term = '$term' AND csession = '$session' ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?> <!-- Includes the head section of the HTML document (meta tags, title, CSS links) -->

<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <?php include('adminnav.php'); ?> <!-- Includes the admin specific navigation sidebar -->
    <!-- End Sidebar -->

    <div class="main-panel">
      <div class="main-header">
        <div class="main-header-logo">
          <!-- Logo Header -->
          <?php include('logo_header.php'); ?> <!-- Includes the logo and header content -->
          <!-- End Logo Header -->
        </div>
        <!-- Navbar Header -->
        <?php include('navbar.php'); ?> <!-- Includes the main navigation bar -->
        <!-- End Navbar -->
      </div>

      <div class="container">
        <div class="page-inner">
          <div class="d-flex d-none d-lg-block align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
              <h3 class="fw-bold mb-3">Class Teacher's Comments</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Results</li>
                <li class="breadcrumb-item active">Class Teacher's Comments</li>
              </ol>
            </div>

          </div>

          <!-- Display Save and Bulk Upload Messages -->
          <?php if (!empty($save_message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($save_message); ?></div>
          <?php endif; ?>
          <?php if (!empty($bulk_upload)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($bulk_upload); ?></div>
          <?php endif; ?>
          <?php if (!empty($bulk_delete_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($bulk_delete_message); ?></div>
          <?php endif; ?>

          <!-- Download Template and Bulk Upload Section -->
          <div class="row">

            <!-- Download result Template for the selected class ============================ -->
            <div class="col-md-4">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Download Template</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <form method="POST" action="download_classteacher_template.php" id="downloadForm">
                    <div class="mb-4 mt-2">

                      <!-- CLASS Dropdown -->
                      <select name="d_class" id="d_class" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch classes again
                        $classes->data_seek(0);
                        while ($row = $classes->fetch_assoc()): ?>
                          <option value="<?php echo htmlspecialchars($row['class']); ?>"><?php echo htmlspecialchars($row['class']); ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <!-- ARM Dropdown -->
                      <select name="d_arm" id="d_arm" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch arms again
                        $arms->data_seek(0);
                        while ($row = $arms->fetch_assoc()): ?>
                          <option value="<?php echo htmlspecialchars($row['arm']); ?>"><?php echo htmlspecialchars($row['arm']); ?></option>
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

            <!-- Add/Edit Comments Section -->
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

                      <!-- Form for adding or editing class teacher comments -->
                    <form method="post">
                      <input type="hidden" name="hidden_id" id="hidden_id" class="form-control" placeholder="Hidden ID">

                      <input type="text" name="id" id="id" class="form-control" placeholder="ID" required>
                      <br>
                      <input type="text" name="name" id="name" required placeholder="Name" class="form-control">
                      <br>
                      <textarea name="comment" id="comment" required Placeholder="Comments"
                        class="form-control"></textarea>
                      <br>
                      <input type="number" name="schlopen" id="schlopen" required placeholder="Days School Opened" class="form-control">
                      <br>
                      <input type="number" name="dayspresent" id="dayspresent" required placeholder="Days Present" class="form-control">
                      <br>
                      <input type="number" name="daysabsent" id="daysabsent" required placeholder="Days Absent" class="form-control">
                      <br>
                      <input type="text" name="attentiveness" id="attentiveness" placeholder="Attentiveness"
                        class="form-control" required>
                      <br>
                      <input type="text" name="neatness" id="neatness" placeholder="Neatness" class="form-control"
                        required>
                      <br>
                      <input type="text" name="politeness" id="politeness" placeholder="Politeness" class="form-control"
                        required>
                      <br>
                      <input type="text" name="selfcontrol" id="selfcontrol" placeholder="Self Control"
                        class="form-control" required>
                      <br>
                      <input type="text" name="punctuality" id="punctuality" placeholder="Punctuality"
                        class="form-control" required>
                      <br>
                      <input type="text" name="relationship" id="relationship" placeholder="Relationship"
                        class="form-control" required>
                      <br>
                      <input type="text" name="handwriting" id="handwriting" placeholder="Handwriting"
                        class="form-control" required>
                      <br>
                      <input type="text" name="music" id="music" placeholder="Music" class="form-control" required>
                      <br>
                      <input type="text" name="club" id="club" placeholder="Club" class="form-control" required>
                      <br>
                      <input type="text" name="sport" id="sport" placeholder="Sport" class="form-control" required>
                      <br>

                      <select class="form-control form-select" name="class" id="classname" required>
                        <option value="">Select Class</option>
                        <?php
                        $classes->data_seek(0); // rewind
                        while ($row = $classes->fetch_assoc()): ?>
                          <option value="<?php echo htmlspecialchars($row['class']); ?>" <?php if ($class == $row['class'])
                                                                                            echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row['class']); ?>
                          </option>
                        <?php endwhile; ?>
                      </select>

                      <br>
                      <select class="form-control form-select" name="arm" id="arm" required>
                        <option value="">Select Arm</option>
                        <?php
                        $arms->data_seek(0); // rewind
                        while ($row = $arms->fetch_assoc()): ?>
                          <option value="<?php echo htmlspecialchars($row['arm']); ?>" <?php if ($arm == $row['arm'])
                                                                                          echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row['arm']); ?>
                          </option>
                        <?php endwhile; ?>
                      </select>

                      <br>
                      <!-- TERM Dropdown -->
                      <select name="term" id="term" class="form-control form-select" required>
                        <?php
                        // Rewind and fetch terms
                        $terms->data_seek(0);
                        while ($row = $terms->fetch_assoc()): ?>
                          <option value="<?php echo htmlspecialchars($row['cterm']); ?>"><?php echo htmlspecialchars($row['cterm']); ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>

                      <!-- SESSION Dropdown -->
                      <select name="session" id="session" class="form-control form-select" required>
                        <?php
                        // Rewind and fetch sessions
                        $sessions->data_seek(0);
                        while ($row = $sessions->fetch_assoc()): ?>
                          <option value="<?php echo htmlspecialchars($row['csession']); ?>"><?php echo htmlspecialchars($row['csession']); ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>

                      <div class="text-center">
                        <button type="submit" class="btn btn-success btn-icon btn-round ps-1">
                          <span class="btn-label">
                            <i class="fa fa-save"></i></button>
                        <button type="reset" class="btn btn-secondary btn-icon btn-round ps-1">
                          <span class="btn-label">
                            <i class="fa fa-undo"></i></button>
                      </div>
                    </form>

                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Bulk Upload Section -->
            <div class="col-md-4">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Bulk Upload CSV</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">
                    <!-- Form for uploading class teacher comments from a CSV file -->
                    <form method="post" enctype="multipart/form-data" style="margin-top: 30px;">
                      <input type="file" name="csv_file" id="csv_file" accept=".csv" class="form-control" required>
                      <br>
                      <select name="csv_class" id="csv_class" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch classes again
                        $classes->data_seek(0);
                        while ($row = $classes->fetch_assoc()): ?>
                          <option value="<?php echo htmlspecialchars($row['class']); ?>"><?php echo htmlspecialchars($row['class']); ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <select name="csv_arm" id="csv_arm" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch arms again
                        $arms->data_seek(0);
                        while ($row = $arms->fetch_assoc()): ?>
                          <option value="<?php echo htmlspecialchars($row['arm']); ?>"><?php echo htmlspecialchars($row['arm']); ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <select name="csv_term" id="csv_term" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch terms again
                        $terms->data_seek(0);
                        while ($row = $terms->fetch_assoc()): ?>
                          <option value="<?php echo htmlspecialchars($row['cterm']); ?>"><?php echo htmlspecialchars($row['cterm']); ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <select name="csv_session" id="csv_session" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch sessions again
                        $sessions->data_seek(0);
                        while ($row = $sessions->fetch_assoc()): ?>
                          <option value="<?php echo htmlspecialchars($row['csession']); ?>"><?php echo htmlspecialchars($row['csession']); ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-icon btn-round ps-1" name="csv_upload">
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
                    <div class="card-title">Bulk Delete Class Comments</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">
                    <!-- Form for bulk deleting class teacher comments -->
                    <form method="post" onsubmit="return confirm('Are you sure you want to delete all class comments for the selected class and arm? This action cannot be undone.');">
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

        </div>

        <!-- Display Uploaded Comments Section -->
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
                    <!-- Table to display uploaded class teacher comments -->
                    <table id="multi-filter-select" class="display table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Comment</th>
                          <th>Attentiveness</th>
                          <th>Neatness</th>
                          <th>Politeness</th>
                          <th>Selfcontrol</th>
                          <th>Punctuality</th>
                          <th>Relationship</th>
                          <th>Handwriting</th>
                          <th>Music</th>
                          <th>Club</th>
                          <th>Sport</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($row = $records->fetch_assoc()): ?>
                          <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['comment']); ?></td>
                            <td><?php echo htmlspecialchars($row['attentiveness']); ?></td>
                            <td><?php echo htmlspecialchars($row['neatness']); ?></td>
                            <td><?php echo htmlspecialchars($row['politeness']); ?></td>
                            <td><?php echo htmlspecialchars($row['selfcontrol']); ?></td>
                            <td><?php echo htmlspecialchars($row['punctuality']); ?></td>
                            <td><?php echo htmlspecialchars($row['relationship']); ?></td>
                            <td><?php echo htmlspecialchars($row['handwriting']); ?></td>
                            <td><?php echo htmlspecialchars($row['music']); ?></td>
                            <td><?php echo htmlspecialchars($row['club']); ?></td>
                            <td><?php echo htmlspecialchars($row['sport']); ?></td>

                            <td class="d-flex">
                              <!-- Edit button: calls JavaScript function to populate form for editing -->
                              <a href="javascript:void(0);"
                                onclick="editClassCommentRecord('<?php echo htmlspecialchars($row['id']); ?>', '<?php echo htmlspecialchars($row['name']); ?>', '<?php echo htmlspecialchars($row['comment']); ?>', '<?php echo htmlspecialchars($row['schlopen']); ?>', '<?php echo htmlspecialchars($row['dayspresent']); ?>', '<?php echo htmlspecialchars($row['daysabsent']); ?>', '<?php echo htmlspecialchars($row['attentiveness']); ?>', '<?php echo htmlspecialchars($row['neatness']); ?>', '<?php echo htmlspecialchars($row['politeness']); ?>', '<?php echo htmlspecialchars($row['selfcontrol']); ?>', '<?php echo htmlspecialchars($row['punctuality']); ?>', '<?php echo htmlspecialchars($row['relationship']); ?>', '<?php echo htmlspecialchars($row['handwriting']); ?>', '<?php echo htmlspecialchars($row['music']); ?>', '<?php echo htmlspecialchars($row['club']); ?>', '<?php echo htmlspecialchars($row['sport']); ?>', '<?php echo htmlspecialchars($row['class']); ?>', '<?php echo htmlspecialchars($row['arm']); ?>', '<?php echo htmlspecialchars($row['term']); ?>', '<?php echo htmlspecialchars($row['csession']); ?>')"
                                class="btn btn-warning btn-icon btn-round ps-1 me-2"><span class="btn-label">
                                  <i class="fa fa-edit"></i></span></a>
                              <!-- Delete button: links to delete the record -->
                              <a href="?delete=<?php echo htmlspecialchars($row['id']); ?>"
                                class="btn btn-danger btn-icon btn-round ps-1"><span
                                  class="btn-label">
                                  <i class="fa fa-trash"></i></span></a>
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

    <?php include('footer.php'); ?> <!-- Includes the footer section of the page -->
  </div>

  <!-- Custom template | don't include it in your project! -->
  <?php include('cust-color.php'); ?> <!-- Includes custom color settings or scripts -->
  <!-- End Custom template -->
  </div>

  <?php include('scripts.php'); ?> <!-- Includes general JavaScript scripts for the page -->
</body>

</html>