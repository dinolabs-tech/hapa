<?php include('components/admin_logic.php');


if (isset($_POST['delete'])) {
  // Step 1: Make sure there's a unique key in cbt_score to identify duplicates
  // You should ensure in your database that:
  // UNIQUE KEY unique_exam (login, subject, class, arm, term, session)
  // exists in cbt_score table.

  // Step 2: Insert or update from mst_result into cbt_score
  $insert_sql = "
    INSERT INTO cbt_score (login, subject, class, arm, term, session, test_date, score)
    SELECT login, subject, class, arm, term, session, test_date, score
    FROM mst_result
    ON DUPLICATE KEY UPDATE
      test_date = VALUES(test_date),
      score = VALUES(score)
  ";

  if ($conn->query($insert_sql) === TRUE) {
    // Step 3: If insert/update successful, delete records from related tables
    $sql = "DELETE FROM mst_result";
    $sql0 = "DELETE FROM mst_useranswer";
    $sql1 = "DELETE FROM timer";

    if (
      $conn->query($sql) === TRUE &&
      $conn->query($sql0) === TRUE &&
      $conn->query($sql1) === TRUE
    ) {
      $delete_message = "Exam Initiated successfully!\nStudents can take their exams";
    } else {
      echo "Error Initiating Exams: " . $conn->error;
    }
  } else {
    echo "Error inserting/updating cbt_score: " . $conn->error;
  }
}






// Process deletion if a delete button was clicked
if (isset($_POST['delete_subject'])) {
  // Retrieve the class, arm, and subject values directly from POST
  $class   = $_POST['class'];
  $arm     = $_POST['arm'];
  $subject = $_POST['subject'];

  // Prepare the DELETE statement including the subject in the WHERE clause
  $stmt = $conn->prepare("DELETE FROM question WHERE class = ? AND arm = ? AND subject = ?");
  if ($stmt === false) {
    echo "<p style='color: red;'>Error preparing statement: " . htmlspecialchars($conn->error) . "</p>";
  } else {
    // Bind the parameters as strings ("sss")
    $stmt->bind_param("sss", $class, $arm, $subject);
    if ($stmt->execute()) {
      $del_message = "Subjects deleted for Class: " . htmlspecialchars($class) . ", Arm: " . htmlspecialchars($arm) . ", Subject: " . htmlspecialchars($subject) . "";
    } else {
      $del_message = "Error deleting subjects: " . htmlspecialchars($stmt->error) . "";
    }
    $stmt->close();
  }
}


// Fetch available subjects
$subject_options = "";
$subject_result = $conn->query("SELECT subject FROM subject group by subject");
if ($subject_result) {
  while ($row = $subject_result->fetch_assoc()) {
    $subject_options .= "<option value='" . htmlspecialchars($row['subject']) . "'>" . htmlspecialchars($row['subject']) . "</option>";
  }
} else {
  die("Error fetching subjects: " . $conn->error);
}

// Fetch classes
$class_options = "";
$class_result = $conn->query("SELECT class FROM class");
if ($class_result) {
  while ($row = $class_result->fetch_assoc()) {
    $class_options .= "<option value='" . htmlspecialchars($row['class']) . "'>" . htmlspecialchars($row['class']) . "</option>";
  }
} else {
  die("Error fetching class: " . $conn->error);
}

// Fetch arms
$arm_options = "";
$arm_result = $conn->query("SELECT arm FROM arm");
if ($arm_result) {
  while ($row = $arm_result->fetch_assoc()) {
    $arm_options .= "<option value='" . htmlspecialchars($row['arm']) . "'>" . htmlspecialchars($row['arm']) . "</option>";
  }
} else {
  die("Error fetching arm: " . $conn->error);
}

// Fetch current term
$term_options = "";
$term_result = $conn->query("SELECT cterm FROM currentterm WHERE id=1");
if ($term_result) {
  $row = $term_result->fetch_assoc();
  $term_options = "<option value='" . htmlspecialchars($row['cterm']) . "'>" . htmlspecialchars($row['cterm']) . "</option>";
} else {
  die("Error fetching current term: " . $conn->error);
}

// Fetch current session
$session_options = "";
$session_result = $conn->query("SELECT csession FROM currentsession WHERE id=1");
if ($session_result) {
  $row = $session_result->fetch_assoc();
  $session_options = "<option value='" . htmlspecialchars($row['csession']) . "'>" . htmlspecialchars($row['csession']) . "</option>";
} else {
  die("Error fetching current session: " . $conn->error);
}



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
              <h3 class="fw-bold mb-3">Add Question</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">CBT</li>
                <li class="breadcrumb-item active">Add Question</li>
              </ol>
            </div>

          </div>

          <!-- BULK UPLOAD ============================ -->
          <div class="row">

            <div class="col-md-12">

              <?php if (!empty($delete_message)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($delete_message); ?></div>
              <?php endif; ?>

              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Upload Questions</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <form method="post" action="">
                    <div style="float: right;"><button type="submit" name="delete" class="btn btn-secondary"><span class="btn-label">
                          <i class="fa fa-play-circle"></i>Initiate Exam</button></div>
                  </form>
                  <div class="mb-4 mt-2">

                    <h5>Bulk Upload Questions via CSV</h5>
                    <form id="csvUploadForm" action="upload_csv.php" method="post" enctype="multipart/form-data">

                      <!-- CSV File Upload -->
                      <div class="row">
                        <div class="col-md-12">
                          <label for="csvFile" class="form-label">Select CSV File</label>
                          <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv">
                          <div class="form-text">
                            CSV file should include: <strong>ID, Questions, Option 1, Option 2, Option 3, Option 4, Correct Answer</strong>
                          </div>
                        </div>
                      </div>

                      <!-- Dropdowns & Subject Input -->
                      <div class="row align-items-end g-2 mt-3">
                        <div class="col-md-2">
                          <select class="form-control form-select" id="class" name="class">
                            <option value="" selected disabled>Select Class</option>
                            <?= $class_options ?>
                          </select>
                        </div>

                        <div class="col-md-2">
                          <select class="form-control form-select" id="arm" name="arm">
                            <option value="" selected disabled>Select Arm</option>
                            <?= $arm_options ?>
                          </select>
                        </div>

                        <div class="col-md-2">
                          <select class="form-control form-select" id="term" name="term">
                            <option value="" selected disabled>Select Term</option>
                            <?= $term_options ?>
                          </select>
                        </div>

                        <div class="col-md-2">
                          <select class="form-control form-select" id="session" name="session">
                            <option value="" selected disabled>Select Session</option>
                            <?= $session_options ?>
                          </select>
                        </div>

                        <div class="col-md-4">
                          <select class="form-control form-select" id="subject" name="subject">
                            <option value="" selected disabled>Select Subject</option>
                            <?= $subject_options ?>
                          </select>
                        </div>
                      </div>

                      <div class="row mt-3">
                        <div class="col-md-12 text-center">
                          <!-- Submit Button -->
                          <button type="submit" class="btn btn-success btn-icon btn-round ps-1 mx-3"><span class="btn-label">
                              <i class="fa fa-cloud-upload-alt"></i></button>

                          <!-- Download Template Link -->
                          <a href="download_template.php" class="btn btn-warning btn-icon btn-round ps-1"><span class="btn-label">
                              <i class="fa fa-cloud-download-alt"></i></a>
                        </div>
                      </div>

                    </form>
                    <div id="errorMsg" class="alert alert-danger d-none"></div>
                  </div>
                </div>
              </div>
            </div>


          </div>

          <div class="row">

            <div class="col-md-12">

              <?php if (!empty($del_message)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($del_message); ?></div>
              <?php endif; ?>

              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Uploaded Questions</div>
                  </div>
                </div>

                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">
                    <?php
                    // Query to get distinct class, arm, and subject values
                    $query  = "SELECT DISTINCT class, arm, subject FROM question ORDER BY class, arm, subject";
                    $result = mysqli_query($conn, $query);

                    $current_class = '';
                    $section_open = false;

                    while ($row = mysqli_fetch_assoc($result)) {
                      // Start a new section when class changes
                      if ($current_class != $row['class']) {
                        // Close previous section if open
                        if ($section_open) {
                          echo '</div></div>';
                        }

                        $current_class = $row['class'];
                        $section_open = true; ?>

                        <!-- Start new class section -->
                        <div class="mb-4">
                          <h5 class="border-bottom pb-2 text-primary">
                            <?= htmlspecialchars($current_class) . ' - ' . htmlspecialchars($row['arm']) ?>
                          </h5>
                          <div class="row g-3">
                          <?php
                        }
                          ?>
                          <!-- Each subject card -->
                          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="card shadow-sm border-0 h-100">
                              <div class="card-body d-flex justify-content-between align-items-center">
                                <span class="fw-semibold text-secondary"><?= htmlspecialchars($row['subject']) ?></span>
                                <form method="post" action="" class="m-0">
                                  <input type="hidden" name="class" value="<?= htmlspecialchars($row['class']) ?>">
                                  <input type="hidden" name="arm" value="<?= htmlspecialchars($row['arm']) ?>">
                                  <input type="hidden" name="subject" value="<?= htmlspecialchars($row['subject']) ?>">
                                  <button type="submit" name="delete_subject" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                  </button>
                                </form>
                              </div>
                            </div>
                          </div>

                        <?php
                      }

                      // Close the last section
                      if ($section_open) {
                        echo '</div></div>';
                      }

                      $conn->close();
                        ?>
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