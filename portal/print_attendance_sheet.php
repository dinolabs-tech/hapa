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

// Extend FPDF to add rotation and expose protected properties via getters
class PDF_Rotate extends FPDF
{
  protected $angle = 0;

  // Rotate around a point (x,y)
  function Rotate($angle, $x = -1, $y = -1)
  {
    if ($this->angle !== 0) {
      $this->_out('Q');
    }
    $this->angle = $angle;
    if ($angle !== 0) {
      if ($x === -1)
        $x = $this->x;
      if ($y === -1)
        $y = $this->y;
      $angleRad = $angle * M_PI / 180.0;
      $c = cos($angleRad);
      $s = sin($angleRad);
      $cx = $x * $this->k;
      $cy = ($this->h - $y) * $this->k;
      $this->_out(sprintf(
        'q %.5F %.5F %.5F %.5F %.5F %.5F cm 1 0 0 1 %.5F %.5F cm',
        $c,
        $s,
        -$s,
        $c,
        $cx,
        $cy,
        -$cx,
        -$cy
      ));
    }
  }

  // Helper to write rotated text at (x,y)
  function RotatedText($x, $y, $txt, $angle)
  {
    $this->Rotate($angle, $x, $y);
    $this->Text($x, $y, $txt);
    $this->Rotate(0);
  }

  // Getters for protected properties
  public function getPageHeight()
  {
    return $this->h;
  }
  public function getBottomMargin()
  {
    return $this->bMargin;
  }
  public function getPageWidth()
  {
    return $this->w;
  }
  public function getLeftMargin()
  {
    return $this->lMargin;
  }
  public function getRightMargin()
  {
    return $this->rMargin;
  }
}

// Database connection
include 'db_connection.php';

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

// Fetch attendance records function
function getAttendanceRecords($conn, $class, $arm, $term_id, $session_id)
{
  $sql = "SELECT s.name, a.date, a.status
            FROM students s
            LEFT JOIN attendance a
              ON s.name = a.name
             AND s.class      = a.class
             AND s.arm        = a.arm
             AND a.term_id    = ?
             AND a.session_id = ?
            WHERE s.class = ?
              AND s.arm   = ?
            ORDER BY a.date, s.name";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssss", $term_id, $session_id, $class, $arm);
  $stmt->execute();
  $res = $stmt->get_result();
  $records = [];
  while ($row = $res->fetch_assoc()) {
    $records[] = $row;
  }
  $stmt->close();
  return $records;
}

$attendance_records = [];
if ($selected_class && $selected_arm) {
  $attendance_records = getAttendanceRecords(
    $conn,
    htmlspecialchars($selected_class),
    htmlspecialchars($selected_arm),
    $current_term_id,
    $current_session_id
  );
}

// Detailed sheet: dates as rows, names as rotated column headers
function generateDetailedAttendancePDF($records, $class, $arm)
{
  $pdf = new PDF_Rotate('L', 'mm', 'A4');
  $pdf->SetTopMargin(10);
  $pdf->SetAutoPageBreak(false);
  $pdf->AddPage();

  // Header
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

  // Collect unique names and dates
  $names = $dates = [];
  foreach ($records as $r) {
    $names[$r['name']] = true;
    if ($r['date']) {
      $dates[$r['date']] = true;
    }
  }
  $names = array_keys($names);
  $dates = array_keys($dates);

  // Layout sizes
  $date_w = 20;
  $col_w = 6;
  $row_h = 6;
  $header_h = 70;  // height of header cells

  // Draw the "Date" header
  $pdf->SetFont('Arial', 'B', 8);
  $pdf->Cell($date_w, $header_h, 'Date', 1, 0, 'C');

  $startX = $pdf->GetX();
  $startY = $pdf->GetY();

  // Draw rotated (upside-down) name headers
  foreach ($names as $i => $name) {
    $x = $startX + $i * $col_w;
    $y = $startY;
    $pdf->Rect($x, $y, $col_w, $header_h);
    $pdf->SetFont('Arial', 'B', 8);
    // Rotate 180 degrees around the center of each header cell
    $pdf->RotatedText(
      $x + ($col_w / 2),             // center x within cell
      // $y + ($header_h/2) + 2,       // center y, adjust +2 for vertical alignment
      $y + 3,
      $name,
      -90                            // rotate 180 degrees
    );
  }
  $pdf->Ln($header_h);

  // Data rows
  $pdf->SetFont('Arial', '', 8);
  foreach ($dates as $date) {
    if ($pdf->GetY() + $row_h > $pdf->getPageHeight() - $pdf->getBottomMargin()) {
      $pdf->AddPage();
      // Repeat header
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Cell($date_w, $header_h, 'Date', 1, 0, 'C');
      $startX = $pdf->GetX();
      $startY = $pdf->GetY();
      foreach ($names as $i => $name) {
        $x = $startX + $i * $col_w;
        $y = $startY;
        $pdf->Rect($x, $y, $col_w, $header_h);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->RotatedText(
          $x + ($col_w / 2),
          $y + $header_h - 2,
          $name,
          90
        );
      }
      $pdf->Ln($header_h);
      $pdf->SetFont('Arial', '', 8);
    }

    $pdf->Cell($date_w, $row_h, $date, 1);
    foreach ($names as $name) {
      $status = '';
      foreach ($records as $r) {
        if (
          ($r['name']) === $name
          && $r['date'] === $date
        ) {
          $status = $r['status'] == 1 ? 'P' : 'A';
          break;
        }
      }
      $pdf->Cell($col_w, $row_h, $status, 1, 0, 'C');
    }
    $pdf->Ln($row_h);
  }

  // --- SUMMARY ROWS ---

  // Compute summary statistics
  $totalDays = count($dates);
  $presentCounts = array_fill_keys($names, 0);
  foreach ($records as $r) {
    $full = $r['name'];
    if ($r['status'] == 1) {
      $presentCounts[$full]++;
    }
  }
  $absentCounts = [];
  foreach ($names as $name) {
    $absentCounts[$name] = $totalDays - $presentCounts[$name];
  }

  // Days Opened row
  $pdf->SetFont('Arial', 'B', 8);
  $pdf->Cell($date_w, $row_h, 'Days Opened', 1);
  foreach ($names as $name) {
    $pdf->Cell($col_w, $row_h, $totalDays, 1, 0, 'C');
  }
  $pdf->Ln($row_h);

  // Present row
  $pdf->Cell($date_w, $row_h, 'Present', 1);
  foreach ($names as $name) {
    $pdf->Cell($col_w, $row_h, $presentCounts[$name], 1, 0, 'C');
  }
  $pdf->Ln($row_h);

  // Absent row
  $pdf->Cell($date_w, $row_h, 'Absent', 1);
  foreach ($names as $name) {
    $pdf->Cell($col_w, $row_h, $absentCounts[$name], 1, 0, 'C');
  }
  $pdf->Ln($row_h);
  $pdf->Output('D', 'attendance_sheet.pdf');
}

// Handle download
if (isset($_GET['download'])) {
  if ($attendance_records) {
    generateDetailedAttendancePDF($attendance_records, $selected_class, $selected_arm);
    exit;
  }
  echo '<p>Please select both Class and Arm.</p>';
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
                  <li class="breadcrumb-item active">Print Attendance Sheet</li>
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

                    
                   
                    </div>

                    </div>
                      
                    </form>
                    
                    <button class="btn btn-secondary"
                      onclick="window.location.href='?class=<?= $selected_class ?>&arm=<?= $selected_arm ?>&download=1'">
                      Print Detailed Sheet
                    </button>
                    <p></p>
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
                    <div class="card-title">Attendance Sheet for <?= htmlspecialchars($selected_class) ?> —
                      <?= htmlspecialchars($selected_arm) ?>
                    </div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <?php if ($selected_class && $selected_arm): ?>
                      <div id="table-responsive"> 
                      <table class="table table-bordered" id="basic-datatables">
                        <thead>
                          <tr>
                            <th>Student Name</th>
                            <th>Date</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (empty($attendance_records)): ?>
                            <tr>
                              <td colspan="3">No attendance records found for the selected class and arm.</td>
                            </tr>
                          <?php else: ?>
                            <?php foreach ($attendance_records as $record): ?>
                              <tr>
                                <td><?php echo $record['name']; ?></td>
                                <td><?php echo $record['date']; ?></td>
                                <td><?php echo $record['status'] == 1 ? 'Present' : 'Absent'; ?></td>
                              </tr>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </tbody>
                      </table>
                      </div>
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