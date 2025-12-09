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
$classOptions   = fetchDropdownData($conn, 'class', 'class');
$armOptions     = fetchDropdownData($conn, 'arm', 'arm');
$termOptions    = ['1st Term', '2nd Term', '3rd Term'];
$sessionOptions = fetchDropdownData($conn, 'mastersheet', 'csession');

// Get form values (if set)
$class   = isset($_POST['class'])   ? htmlspecialchars($_POST['class'])   : '';
$arm     = isset($_POST['arm'])     ? htmlspecialchars($_POST['arm'])     : '';
$term    = isset($_POST['term'])    ? htmlspecialchars($_POST['term'])    : '';
$session = isset($_POST['session']) ? htmlspecialchars($_POST['session']) : '';

// Initialize arrays to store data
$scores   = [];
$students = [];
$subjects = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare statement to safely query the mastersheet table
    $stmt = $conn->prepare("
        SELECT name, subject, average FROM mastersheet 
        WHERE class = ? AND arm = ? AND term = ? AND csession = ?
    ");
    $stmt->bind_param("ssss", $class, $arm, $term, $session);
    $stmt->execute();
    
    // Bind result variables
    $stmt->store_result();
    $stmt->bind_result($name, $subject, $average);
    
    // Fetch the results using bind_result
    while ($stmt->fetch()) {
        $scores[$name][$subject] = $average;
        $students[$name] = true;
        $subjects[$subject] = true;
    }
    
    // Convert keys to arrays
    $students = array_keys($students);
    $subjects = array_keys($subjects);
    
    generatePDF($class, $arm, $term, $session, $students, $subjects, $scores);
    // generatePDF() will exit after sending the PDF
}

// -----------------------------------------------------------------
// PDF Generation Function
// -----------------------------------------------------------------
function generatePDF($class, $arm, $term, $session, $students, $subjects, $scores)
{
    // Create a new PDF_Rotate object (landscape A4)
    $pdf = new PDF_Rotate('L', 'mm', 'A4');
    $pdf->AddPage();

    // Add logo to top-left
    $pdf->Image('assets/img/logo.png', 10, 10, 30); // x, y, width (height auto-scaled)

    // Set font for header text
    $pdf->SetFont('Arial', 'B', 14);

  // Move to the right for center text
    $pdf->SetXY(0, 10); // Reset Y to top margin
    $pdf->Cell(0, 7, 'HAPA COLLEGE', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 7, 'KM 3, Akure Owo Express Road, Oba Ile, Akure, Ondo State, Nigeria.', 0, 1, 'C');
    $pdf->Cell(0, 7, 'Email: hapacollege2013@yahoo.com | Tel: +234-803-504-2727, +234-803-883-8583', 0, 1, 'C');

    // Space before title
    $pdf->Ln(5);

    // Title
    $pdf->SetFont('Arial', 'B', 12);
    $title = "Master Sheet for $class $arm - $term ($session)";
    $pdf->Cell(0, 10, $title, 0, 1, 'C');
    $pdf->Ln(10);

    // Table column settings
    $cellWidthName    = 60;
    $cellWidthSubject = 10;
    $headerHeight     = 90;
    $rowHeight        = 8;

    // Table Header
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell($cellWidthName, $headerHeight, 'STUDENT NAME', 1, 0, 'C');
    foreach ($subjects as $subject) {
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Rect($x, $y, $cellWidthSubject, $headerHeight);
        $pdf->Rotate(90, $x + $cellWidthSubject / 2, $y + $headerHeight / 2);
        $pdf->Text($x + $cellWidthSubject / 2 - 40, $y + $headerHeight / 2 + 0, $subject);
        $pdf->Rotate(0);
        $pdf->SetXY($x + $cellWidthSubject, $y);
    }
    $pdf->Ln();

    // Table Content
    $pdf->SetFont('Arial', '', 8);
    foreach ($students as $student) {
        $pdf->Cell($cellWidthName, $rowHeight, $student, 1, 0, 'L');
        foreach ($subjects as $subject) {
            $score = isset($scores[$student][$subject]) ? ceil($scores[$student][$subject]) : '-';
            $pdf->Cell($cellWidthSubject, $rowHeight, $score, 1, 0, 'C');
        }
        $pdf->Ln();
    }

    // Output the PDF
    try {
        $pdf->Output('D', "Master_Sheet_{$class}_{$arm}_{$term}_{$session}.pdf");
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
<?php include('head.php');?>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
     <?php include('adminnav.php');?>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <?php include('logo_header.php');?>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
         <?php include('navbar.php');?>
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4 d-none d-lg-block"
            >
              <div>
                <h3 class="fw-bold mb-3">Mastersheet</h3>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active">Home</li>
                  <li class="breadcrumb-item active">Results</li>
                  <li class="breadcrumb-item active">Mastersheet</li>
              </ol>
              </div>
           
            </div>

            <!-- BULK UPLOAD ============================ -->
            <div class="row">
             
             <div class="col-md-12">
               <div class="card card-round">
                 <div class="card-header">
                   <div class="card-head-row">
                     <div class="card-title">Download Mastersheet</div>
                   </div>
                 </div>
                 <div class="card-body pb-0">
                   <div class="mb-4 mt-2">
                      <p> 
                  
                          <!-- Download Mastersheet Form -->
                          <form method="post">

                              <select name="class" required class="form-control form-select">
                                  <option value="">Select Class</option>
                                  <?php foreach ($classOptions as $option) : ?>
                                      <option value="<?= htmlspecialchars($option) ?>" <?= $class === $option ? 'selected' : '' ?>>
                                          <?= htmlspecialchars($option) ?>
                                      </option>
                                  <?php endforeach; ?>
                              </select>
                                    <br>
                              <select name="arm" required class="form-control form-select">
                                  <option value="">Select Arm</option>
                                  <?php foreach ($armOptions as $option) : ?>
                                      <option value="<?= htmlspecialchars($option) ?>" <?= $arm === $option ? 'selected' : '' ?>>
                                          <?= htmlspecialchars($option) ?>
                                      </option>
                                  <?php endforeach; ?>
                              </select>
                                <br>
                              <select name="term" required class="form-control form-select">
                                  <option value="">Select Term</option>
                                  <?php foreach ($termOptions as $option) : ?>
                                      <option value="<?= htmlspecialchars($option) ?>" <?= $term === $option ? 'selected' : '' ?>>
                                          <?= htmlspecialchars($option) ?>
                                      </option>
                                  <?php endforeach; ?>
                              </select>
                              <br>

                              <select name="session" required class="form-control form-select">
                                  <option value="">Select Session</option>
                                  <?php foreach ($sessionOptions as $option) : ?>
                                      <option value="<?= htmlspecialchars($option) ?>" <?= $session === $option ? 'selected' : '' ?>>
                                          <?= htmlspecialchars($option) ?>
                                      </option>
                                  <?php endforeach; ?>
                              </select>
                              <br>
                              <button type="submit" class="btn btn-success">Generate Master Sheet</button>
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
        <?php include('footer.php');?>
      </div>

      <!-- Custom template | don't include it in your project! -->
      <?php include('cust-color.php');?>
      <!-- End Custom template -->
    </div>
   <?php include('scripts.php');?>
  
  </body>
</html>
