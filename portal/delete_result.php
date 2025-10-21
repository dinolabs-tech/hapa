<?php include('components/admin_logic.php');

$delete_result_message = "";


// Delete block
if (isset($_POST['delete_subject'])) {
  $class   = $_POST['class'];
  $arm     = $_POST['arm'];
  $subject = $_POST['subject'];
  $term    = $_POST['term'] ?? '';
  $session = $_POST['session'] ?? '';

  $stmt = $conn->prepare("DELETE FROM mastersheet WHERE class = ? AND arm = ? AND subject = ? AND term = ? AND csession = ?");
  if ($stmt === false) {
    $delete_result_message = "<div class='alert alert-danger'>Error preparing statement: " . htmlspecialchars($conn->error) . "</div>";
  } else {
    $stmt->bind_param("sssss", $class, $arm, $subject, $term, $session);
    if ($stmt->execute()) {
      $delete_result_message = "<div class='alert alert-success'>Deleted subject <b>" . htmlspecialchars($subject) . "</b> from <b>$class $arm</b> ($term - $session)</div>";
    } else {
      $delete_result_message = "<div class='alert alert-danger'>Error deleting subject: " . htmlspecialchars($stmt->error) . "</div>";
    }
    $stmt->close();
  }
}


// Fetch dropdown values (all options)
$class_options = $arm_options = $term_options = $session_options = "";

// Classes
$result = $conn->query("SELECT DISTINCT class FROM class");
while ($row = $result->fetch_assoc()) {
  $class_options .= "<option value='" . htmlspecialchars($row['class']) . "'>" . htmlspecialchars($row['class']) . "</option>";
}

// Arms
$result = $conn->query("SELECT DISTINCT arm FROM arm");
while ($row = $result->fetch_assoc()) {
  $arm_options .= "<option value='" . htmlspecialchars($row['arm']) . "'>" . htmlspecialchars($row['arm']) . "</option>";
}

// Terms
$result = $conn->query("SELECT DISTINCT term FROM mastersheet ORDER BY term");
while ($row = $result->fetch_assoc()) {
  $term_options .= "<option value='" . htmlspecialchars($row['term']) . "'>" . htmlspecialchars($row['term']) . "</option>";
}

// Sessions
$result = $conn->query("SELECT DISTINCT csession FROM mastersheet ORDER BY csession DESC");
while ($row = $result->fetch_assoc()) {
  $session_options .= "<option value='" . htmlspecialchars($row['csession']) . "'>" . htmlspecialchars($row['csession']) . "</option>";
}

// Filter logic
$filtered_results = [];
if (isset($_POST['filter'])) {
  $class   = $_POST['class'] ?? '';
  $arm     = $_POST['arm'] ?? '';
  $term    = $_POST['term'] ?? '';
  $session = $_POST['session'] ?? '';

  if ($class && $arm && $term && $session) {
    $stmt = $conn->prepare("SELECT DISTINCT class, arm, subject, term, csession FROM mastersheet WHERE class=? AND arm=? AND term=? AND csession=? ORDER BY subject");
    $stmt->bind_param("ssss", $class, $arm, $term, $session);
    $stmt->execute();
    $stmt->bind_result($resClass, $resArm, $resSubject, $resTerm, $resSession);
    while ($stmt->fetch()) {
      $filtered_results[] = [
        'class' => $resClass,
        'arm' => $resArm,
        'subject' => $resSubject,
        'term' => $resTerm,
        'csession' => $resSession
      ];
    }
    $stmt->close();
  } else {
    echo "<p style='color:red;'>Please select all fields to filter.</p>";
  }
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
              <h3 class="fw-bold mb-3">Delete Result</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Result</li>
                <li class="breadcrumb-item active">Delete Result</li>
              </ol>
            </div>

          </div>

          <div class="row">

            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Filter Result</div>
                  </div>
                </div>
                <div class="card-body pb-0">

                  <div class="mb-4 mt-2">

                    <form action="" method="post">


                      <!-- Dropdowns & Subject Input -->
                      <div class="row align-items-end g-2 mt-3">
                        <div class="col-md-2">
                          <select class="form-control form-select" id="class" name="class" required>
                            <option value="">Select Class</option>
                            <?= $class_options ?>
                          </select>
                        </div>

                        <div class="col-md-2">
                          <select class="form-control form-select" id="arm" name="arm" required>
                            <option value="">Select Arm</option>
                            <?= $arm_options ?>
                          </select>
                        </div>

                        <div class="col-md-2">
                          <select class="form-control form-select" id="term" name="term" required>
                            <?= $term_options ?>
                          </select>
                        </div>

                        <div class="col-md-2">
                          <select class="form-control form-select" id="session" name="session" required>
                            <?= $session_options ?>
                          </select>
                        </div>

                        <div class="col-md-2">
                          <button class="btn btn-success" name="filter">Filter</button>
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
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Delete Result</div>
                  </div>
                  <?php if (!empty($delete_result_message)) echo $delete_result_message; ?>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <p>
                      <?php
                      if (!empty($filtered_results)) {
                        $current_class = '';
                        foreach ($filtered_results as $row) {
                          if ($current_class != $row['class']) {
                            if ($current_class != '') echo '</div>';
                            $current_class = $row['class'];
                            echo '<div class="mb-3 rounded-3" style="border: 1px solid blue; padding: 10px;">';
                            echo '<h6 class="border-bottom pb-1">' . htmlspecialchars($row['class']) . ' - ' . htmlspecialchars($row['arm']) . '</h6>';
                          }

                          echo '<p class="ml-3" style="display: flex; justify-content: space-between; align-items: center;">';
                          echo '<span>' . htmlspecialchars($row['subject']) . '</span>';
                          echo '<form method="post" action="" style="margin: 0;">';
                          echo '<input type="hidden" name="class" value="' . htmlspecialchars($row['class']) . '">';
                          echo '<input type="hidden" name="arm" value="' . htmlspecialchars($row['arm']) . '">';
                          echo '<input type="hidden" name="subject" value="' . htmlspecialchars($row['subject']) . '">';
                          echo '<input type="hidden" name="term" value="' . htmlspecialchars($row['term']) . '">';
                          echo '<input type="hidden" name="session" value="' . htmlspecialchars($row['csession']) . '">';
                          echo '<button type="submit" name="delete_subject" class="btn btn-danger"><span class="btn-label"><i class="fa fa-trash"></i></span></button>';
                          echo '</form>';
                          echo '</p>';
                        }
                        if ($current_class != '') echo '</div>';
                      } else {
                        echo "<p>No results found. Please filter to display subjects.</p>";
                      }
                      ?>

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