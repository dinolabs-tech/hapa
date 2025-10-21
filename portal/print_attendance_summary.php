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

require('includes/fpdf.php');

// 1) Fetch current term
$current_term_id = null;
$sqlTerm = "SELECT cterm FROM currentterm LIMIT 1";
if ($res = $conn->query($sqlTerm)) {
  if ($row = $res->fetch_assoc()) {
    $current_term_id = $row['cterm'];
  }
  $res->free();
}
if ($current_term_id === null) {
  die("Error: Could not retrieve current term. Please configure it in the database.");
}

// 2) Fetch current session
$current_session_id = null;
$sqlSess = "SELECT csession FROM currentsession LIMIT 1";
if ($res = $conn->query($sqlSess)) {
  if ($row = $res->fetch_assoc()) {
    $current_session_id = $row['csession'];
  }
  $res->free();
}
if ($current_session_id === null) {
  die("Error: Could not retrieve current session. Please configure it in the database.");
}

// 3) Fetch classes and arms for filter dropdowns
$classes = [];
if ($res = $conn->query("SELECT class FROM class ORDER BY class")) {
  while ($r = $res->fetch_assoc()) {
    $classes[] = $r['class'];
  }
  $res->free();
}

$arms = [];
if ($res = $conn->query("SELECT arm FROM arm ORDER BY arm")) {
  while ($r = $res->fetch_assoc()) {
    $arms[] = $r['arm'];
  }
  $res->free();
}

// Get filters
$selected_class = $_GET['class'] ?? '';
$selected_arm = $_GET['arm'] ?? '';

// Fetch attendance records
function getAttendanceRecords($conn, $class, $arm, $term_id, $session_id)
{
  $sql = "SELECT s.name, a.date, a.status
            FROM students s
            LEFT JOIN attendance a ON s.name=a.name
                AND s.class=a.class AND s.arm=a.arm
                AND a.term_id='$term_id' AND a.session_id='$session_id'
            WHERE s.class='$class' AND s.arm='$arm'
            ORDER BY s.name, a.date";
  $res = $conn->query($sql);
  $records = [];
  while ($row = $res->fetch_assoc()) {
    $records[] = $row;
  }
  return $records;
}

$attendance_records = [];
if ($selected_class && $selected_arm) {
  $attendance_records = getAttendanceRecords(
    $conn,
    $selected_class,
    $selected_arm,
    $current_term_id,
    $current_session_id
  );
}

// Generate PDF showing only totals
function generateAttendancePDF($records, $class, $arm)
{
  $pdf = new FPDF('L', 'mm', 'A4');
  $pdf->AddPage();
  $pdf->SetFont('Arial', 'B', 12);
 $pdf->Image('assets/img/logo.png', 10, 8, 20);
  // Header
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->Cell(0, 10, "HAPA COLLEGE", 0, 1, 'C');
  $pdf->SetFont('Arial', '', 12);
  $pdf->Cell(0, 6, "KM 3, Akure Owo Express Road, Oba Ile, Akure, Ondo State, Nigeria.", 0, 1, 'C');
  $pdf->Cell(0, 6, "hapacollege2013@yahoo.com", 0, 1, 'C');
  $pdf->Cell(0, 6, "+234-803-504-2727, +234-803-883-8583", 0, 1, 'C');
  $pdf->Ln(5);
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(0, 10, "Attendance Sheet for $class - $arm", 0, 1, 'C');
  $pdf->Ln(3);
  
  // Calculate total school days from attendance records
  $school_days = [];
  foreach ($records as $r) {
    if ($r['date']) {
      $school_days[$r['date']] = 1; // Use date as key to ensure uniqueness
    }
  }
  $total_days = count($school_days);

  // Pre-calculate present counts per student
  $presentCounts = [];
  foreach ($records as $r) {
    $name = $r['name'];
    if ($r['status'] == 1) {
      if (!isset($presentCounts[$name])) {
        $presentCounts[$name] = 0;
      }
      $presentCounts[$name]++;
    }
  }

  // Column widths
  $name_w = 120;
  $stat_w = 40;
  $row_h = 8;

  // Header row
  $pdf->SetFont('Arial', 'B', 10);
  $pdf->Cell($name_w, $row_h, 'Student Name', 1, 0, 'C');
  $pdf->Cell($stat_w, $row_h, 'Days School Opened', 1, 0, 'C');
  $pdf->Cell($stat_w, $row_h, 'Days Present', 1, 0, 'C');
  $pdf->Cell($stat_w, $row_h, 'Days Absent', 1, 1, 'C');

  // Data rows
  $pdf->SetFont('Arial', '', 10);
  $lastStudent = '';
  foreach ($records as $r) {
    $name = $r['name'];
    if ($name !== $lastStudent) {
      $lastStudent = $name;
      $present = $presentCounts[$name] ?? 0;
      $absent = $total_days - $present;

      $pdf->Cell($name_w, $row_h, $name, 1);
      $pdf->Cell($stat_w, $row_h, $total_days, 1, 0, 'C');
      $pdf->Cell($stat_w, $row_h, $present, 1, 0, 'C');
      $pdf->Cell($stat_w, $row_h, $absent, 1, 1, 'C');
    }
  }

  // Output the PDF
  $pdf->Output('D', 'attendance_summary.pdf');
}

// Handle print
if (isset($_GET['print'])) {
  if ($selected_class && $selected_arm) {
    generateAttendancePDF(
      $attendance_records,
      $selected_class,
      $selected_arm
    );
    exit;
  } else {
    echo "<p>Please select both Class and Arm.</p>";
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
              <nts class="fw-bold mb-3">Attendance</h3>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">Print Attendance Summary</li>
                </ol>
            </div>

          </div>

          <!-- BULK UPLOAD ============================ -->

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

                    <form method="get" class="form-inline mb-3">
                    <div class="row g-3 mb-3">
                      <div class="col-md-5">
                        <select name="class" class="form-select" onchange="this.form.submit()">
                          <option value="">Select Class</option>
                          <?php foreach ($classes as $c): ?>
                            <option value="<?= $c ?>" <?= $c == $selected_class ? 'selected' : '' ?>><?= $c ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <div class="col-md-5">
                        <select name="arm" class="form-select" onchange="this.form.submit()">
                          <option value="">Select Arm</option>
                          <?php foreach ($arms as $a): ?>
                            <option value="<?= $a ?>" <?= $a == $selected_arm ? 'selected' : '' ?>><?= $a ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <div class="col-md-2">
                      <button name="print" class="btn btn-primary">Print</button>
                      </div>
                    </div>
                      
                    </form>

                    <?php if ($selected_class && $selected_arm && count($attendance_records)): ?>

                      <?php
                      // Calculate total distinct school days
                      $school_days = [];
                      foreach ($attendance_records as $r) {
                        if ($r['date']) {
                          $school_days[$r['date']] = true;
                        }
                      }
                      $total_days = count($school_days);

                      // Count presents per student
                      $presentCounts = [];
                      foreach ($attendance_records as $r) {
                        if ($r['status'] == 1) {
                          $presentCounts[$r['name']] = ($presentCounts[$r['name']] ?? 0) + 1;
                        }
                      }

                      // Build unique student list in order
                      $students = [];
                      foreach ($attendance_records as $r) {
                        if (!isset($students[$r['name']])) {
                          $students[$r['name']] = true;
                        }
                      }
                      ?>

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
                      <div class="card-title">Attendance Summary for <?= htmlspecialchars($selected_class) ?> —
                        <?= htmlspecialchars($selected_arm) ?>
                      </div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">

                    <div id="table-responsive">
                      <table class="table table-bordered table-striped" id="basic-datatables">
                        <thead class="thead-dark">
                          <tr>
                            <th>Student Name</th>
                            <th class="text-center">Days School Opened</th>
                            <th class="text-center">Days Present</th>
                            <th class="text-center">Days Absent</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach (array_keys($students) as $name):
                            $present = $presentCounts[$name] ?? 0;
                            $absent = $total_days - $present;
                            ?>
                            <tr>
                              <td><?= htmlspecialchars($name) ?></td>
                              <td class="text-center"><?= $total_days ?></td>
                              <td class="text-center"><?= $present ?></td>
                              <td class="text-center"><?= $absent ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                      </div>
                      
                    <?php elseif ($selected_class && $selected_arm): ?>

                      <p class="alert alert-info">No attendance records found for
                        <?=
                          htmlspecialchars("$selected_class — $selected_arm") ?>.
                      </p>

                    <?php endif; ?>

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