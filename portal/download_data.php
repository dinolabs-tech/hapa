<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Include database connection
require 'db_connection.php';
if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}

// Include the rotation library (ensure the path is correct)
require('includes/rotation.php');

// Function to fetch distinct dropdown data safely
function fetchDropdownData($conn, $table, $column)
{
  // Escape table and column names
  $table = $conn->real_escape_string($table);
  $column = $conn->real_escape_string($column);

  $result = $conn->query("SELECT DISTINCT `$column` FROM `$table` ORDER BY `$column` ASC");
  $data = [];
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $data[] = $row[$column];
    }
  }
  return $data;
}

// Get dropdown options
$classOptions = fetchDropdownData($conn, 'class', 'class');
$armOptions   = fetchDropdownData($conn, 'arm', 'arm');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Handle Student List PDF download
  if (isset($_POST['download_student_list_pdf'])) {
    $studentListClass = isset($_POST['student_list_class']) ? htmlspecialchars($_POST['student_list_class']) : '';
    $studentListArm   = isset($_POST['student_list_arm'])   ? htmlspecialchars($_POST['student_list_arm'])   : '';

    if (!empty($studentListClass) && !empty($studentListArm)) {
      $studentData = [];
      // Assuming a 'students' table with 'id', 'name', 'class', 'arm' columns
      $stmt = $conn->prepare("SELECT id, name FROM students WHERE class = ? AND arm = ? AND status = 0 ORDER BY name ASC");
      $stmt->bind_param("ss", $studentListClass, $studentListArm);
      $stmt->execute();
      $result = $stmt->get_result();

      while ($row = $result->fetch_assoc()) {
        $studentData[] = $row;
      }
      $stmt->close();

      generateStudentListPDF($studentListClass, $studentListArm, $studentData);
    }
  }
}

// -----------------------------------------------------------------
// Student List PDF Generation Function
// -----------------------------------------------------------------
function generateStudentListPDF($class, $arm, $studentData)
{
  $pdf = new PDF_Rotate('P', 'mm', 'A4');
  $pdf->AddPage();

  // Add logo to top-left
  $pdf->Image('assets/img/logo.png', 10, 10, 30); // x, y, width (height auto-scaled)

  // Set font for header text
  $pdf->SetFont('Arial', 'B', 14);

  // Move to the right for center text
  $pdf->SetXY(0, 10); // Reset Y to top margin
  $pdf->Cell(0, 7, 'HAPA COLLEGE', 0, 1, 'C');
  $pdf->SetFont('Arial', '', 10);
  $pdf->Cell(0, 7, 'KM 3, Akure Owo Express Road, Oba Ile, Akure, Ondo State, Nigeria. ', 0, 1, 'C');
  $pdf->Cell(0, 7, '+234-803-504-2727, +234-803-883-8583 | hapacollege2013@yahoo.com', 0, 1, 'C');

  // Space before title
  $pdf->Ln(5);

  // Title
  $pdf->SetFont('Arial', 'B', 12);
  $title = "Student Data for $class $arm";
  $pdf->Cell(0, 10, $title, 0, 1, 'C');
  $pdf->Ln(10);

  // Table Header
  $pdf->SetFont('Arial', 'B', 10);
  $pdf->Cell(30, 10, 'Student ID', 1, 0, 'C');
  $pdf->Cell(140, 10, 'Student Name', 1, 1, 'C'); // Changed 0 to 1 to move to next line
  $pdf->SetFont('Arial', '', 10);

  // Table Content
  foreach ($studentData as $student) {
    $pdf->Cell(30, 10, $student['id'], 1, 0, 'C');
    $pdf->Cell(140, 10, $student['name'], 1, 1, 'L');
  }

  // Output the PDF
  try {
    $pdf->Output('D', "Student_List_{$class}_{$arm}.pdf");
  } catch (Exception $e) {
    echo "Error generating PDF: " . $e->getMessage();
  }
  exit;
}

// Fetch the logged-in Staff name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();


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
              <h3 class="fw-bold mb-3">Download Student List</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Students</li>
                <li class="breadcrumb-item active">Download List</li>
              </ol>
            </div>

          </div>

          <!-- Download Student List Form ============================ -->
          <div class="row">

            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Download Student List (PDF)</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">
                    <p>

                    <form method="post">
                      <select name="student_list_class" required class="form-control form-select">
                        <option value="" selected disabled>Select Class</option>
                        <?php foreach ($classOptions as $option) : ?>
                          <option value="<?= htmlspecialchars($option) ?>" <?= (isset($_POST['student_list_class']) && $_POST['student_list_class'] === $option) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($option) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <br>
                      <select name="student_list_arm" required class="form-control form-select">
                        <option value="" selected disabled>Select Arm</option>
                        <?php foreach ($armOptions as $option) : ?>
                          <option value="<?= htmlspecialchars($option) ?>" <?= (isset($_POST['student_list_arm']) && $_POST['student_list_arm'] === $option) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($option) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <br>
                      <div class="col-md-12 text-center">
                        <button type="submit" name="download_student_list_pdf" class="btn btn-info btn-icon btn-round">
                          <span class="fas fa-file-pdf"></span>
                        </button>
                      </div>
                    </form>

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