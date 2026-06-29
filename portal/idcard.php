<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('APP_INIT', true);
require_once 'config/school_config.php';
require('db_connection.php');

// Check if student_id is provided - if yes, generate PDF
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Fetch student data
    $query = "SELECT id, name, class, arm FROM students WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->bind_result($id, $name, $class, $arm);

    if ($stmt->fetch()) {
        $student = [
            'id'    => $id,
            'name'  => $name,
            'class' => $class,
            'arm'   => $arm,
        ];
    } else {
        die("Student not found.");
    }
    $stmt->close();

    require('includes/fpdf.php');
    include 'includes/phpqrcode/qrlib.php'; // QR Code library

    // Extend FPDF class with custom methods
    class PDF extends FPDF {
        function RoundedRect($x, $y, $w, $h, $r, $style = '') {
            $k = $this->k;
            $hp = $this->h;
            if($style=='F')
                $op='f';
            elseif($style=='FD' || $style=='DF')
                $op='B';
            else
                $op='S';
            $MyArc = 4/3 * (sqrt(2) - 1);
            $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
            $xc = $x+$w-$r ;
            $yc = $y+$r;
            $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
            $xc = $x+$w-$r ;
            $yc = $y+$h-$r;
            $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
            $xc = $x+$r ;
            $yc = $y+$h-$r;
            $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-$yc)*$k));
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
            $xc = $x+$r ;
            $yc = $y+$r ;
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
            $this->_out($op);
        }
        
        function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
            $h = $this->h;
            $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
                $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
        }

        // Add Ellipse method
        function Ellipse($x, $y, $rx, $ry, $style = 'D') {
            if($style=='F')
                $op='f';
            elseif($style=='FD' || $style=='DF')
                $op='B';
            else
                $op='S';
            $lx = 4/3 * (sqrt(2) - 1) * $rx;
            $ly = 4/3 * (sqrt(2) - 1) * $ry;
            $k = $this->k;
            $h = $this->h;
            $this->_out(sprintf('%.2F %.2F m %.2F %.2F b', 
                ($x+$rx)*$k, ($h-$y)*$k,
                ($x+$rx)*$k, ($h-($y-$ly))*$k,
                ($x+$lx)*$k, ($h-($y-$ry))*$k,
                $x*$k, ($h-($y-$ry))*$k));
            $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F b', 
                ($x-$lx)*$k, ($h-($y-$ry))*$k,
                ($x-$rx)*$k, ($h-($y-$ly))*$k,
                ($x-$rx)*$k, ($h-$y)*$k,
                ($x-$rx)*$k, ($h-$y)*$k));
            $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F b', 
                ($x-$rx)*$k, ($h-($y+$ly))*$k,
                ($x-$lx)*$k, ($h-($y+$ry))*$k,
                $x*$k, ($h-($y+$ry))*$k,
                $x*$k, ($h-($y+$ry))*$k));
            $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F b', 
                ($x+$lx)*$k, ($h-($y+$ry))*$k,
                ($x+$rx)*$k, ($h-($y+$ly))*$k,
                ($x+$rx)*$k, ($h-$y)*$k,
                ($x+$rx)*$k, ($h-$y)*$k));
            $this->_out($op);
        }
    }

    $pdf = new PDF('L', 'mm', array(85, 54));
    $pdf->SetMargins(0, 0, 0);

    //=================== Front Side ===================//
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(false, 0);

    // Outer border with gold accent
    $pdf->SetDrawColor(184, 134, 11); // Dark goldenrod
    $pdf->SetLineWidth(0.8);
    $pdf->RoundedRect(1.5, 1.5, 82, 51, 2, 'D');

    // Premium gradient header (simulated with layered rectangles)
    // Dark blue base
    $pdf->SetFillColor(25, 25, 112); // Midnight blue
    $pdf->RoundedRect(2.5, 2.5, 80, 16, 1.5, 'F');
    // Lighter blue overlay for gradient effect
    $pdf->SetFillColor(65, 105, 225); // Royal blue
    $pdf->RoundedRect(2.5, 2.5, 80, 10, 1.5, 'F');

    // Gold accent line
    $pdf->SetDrawColor(255, 215, 0); // Gold
    $pdf->SetLineWidth(0.5);
    $pdf->Line(2.5, 12.5, 82.5, 12.5);

    // School Logo
    if (file_exists('assets/img/logo.png')) {
        $pdf->Image('assets/img/logo.png', 4, 4, 10, 8);
    }

    // School Name with better typography
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetXY(16, 4);
    $pdf->MultiCell(65, 5, strtoupper("HAPA COLLEGE"), 0, 'C');

    // "STUDENT ID CARD" subtitle
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(255, 215, 0); // Gold
    $pdf->SetXY(4, 13);
    $pdf->Cell(77, 4, 'OFFICIAL STUDENT IDENTIFICATION', 0, 1, 'C');

    // Student Photo with professional circular frame
    $photo_filename = str_replace('/', '_', $student['id']);
    $photo_path = "studentimg/" . $photo_filename . ".jpg";

    if (!file_exists($photo_path)) {
        $photo_path = "studentimg/default.jpg";
    }

    if (file_exists($photo_path)) {
        // Gold circular frame
        $pdf->SetDrawColor(255, 215, 0); // Gold
        $pdf->SetLineWidth(0.8);
        $pdf->Ellipse(19.5, 30, 13, 15, 'D');
        
        // Inner blue circle
        $pdf->SetDrawColor(65, 105, 225); // Royal blue
        $pdf->SetLineWidth(0.3);
        $pdf->Ellipse(19.5, 30, 12.5, 14.5, 'D');
        
        // Photo
        $pdf->Image($photo_path, 7, 20, 25, 20);
    }

    // Student Information Box with professional styling
    $pdf->SetFillColor(248, 248, 255); // Ghost white
    $pdf->SetLineWidth(0.4);
    $pdf->RoundedRect(35, 19, 46, 24, 2, 'F');

    // Student Details with improved typography
    $pdf->SetTextColor(25, 25, 112); // Midnight blue
    $detailsX = 39;
    $startY = 22;

    // ID Number
    $pdf->SetXY($detailsX, $startY);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetTextColor(128, 128, 128); // Gray for label
    $pdf->Cell(40, 3, 'STUDENT ID', 0, 1, 'L');
    $pdf->SetX($detailsX);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(25, 25, 112);
    $pdf->Cell(40, 5, $student['id'], 0, 1, 'L');

    // Class
    $pdf->SetX($detailsX);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetTextColor(128, 128, 128);
    $pdf->Cell(40, 3, 'CLASS', 0, 1, 'L');
    $pdf->SetX($detailsX);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(25, 25, 112);
    $pdf->Cell(40, 5, $student['class'], 0, 1, 'L');

    // Arm
    $pdf->SetX($detailsX);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetTextColor(128, 128, 128);
    $pdf->Cell(40, 3, 'ARM', 0, 1, 'L');
    $pdf->SetX($detailsX);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(25, 25, 112);
    $pdf->Cell(40, 5, $student['arm'], 0, 1, 'L');

    // Student Name at bottom
    $pdf->SetXY(4, 45);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(25, 25, 112);
    $pdf->Cell(77, 5, $student['name'], 0, 1, 'C');

    // Generate QR Code
    $qr_code_text = $student['id'];
    $qr_file_path = 'temp_qr_' . md5($qr_code_text) . '.png';
    QRcode::png($qr_code_text, $qr_file_path, QR_ECLEVEL_L, 2, 1);

    // QR Code with professional frame
    $qr_size = 14;
    $qr_x = 68;
    $qr_y = 35;
    $pdf->SetDrawColor(255, 215, 0); // Gold frame
    $pdf->SetLineWidth(0.5);
    $pdf->RoundedRect($qr_x - 1, $qr_y - 1, $qr_size + 2, $qr_size + 2, 1, 'D');
    $pdf->Image($qr_file_path, $qr_x, $qr_y, $qr_size, $qr_size, 'PNG');

    // "SCAN ME" label
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->SetTextColor(128, 128, 128);
    $pdf->SetXY($qr_x - 1, $qr_y + $qr_size + 1);
    $pdf->Cell($qr_size + 2, 3, 'SCAN ME', 0, 1, 'C');

    //=================== Back Side ===================//
    $pdf->AddPage();

    // Outer border
    $pdf->SetDrawColor(184, 134, 11); // Dark goldenrod
    $pdf->SetLineWidth(0.8);
    $pdf->RoundedRect(1.5, 1.5, 82, 51, 2, 'D');

    // Header bar with gradient effect
    $pdf->SetFillColor(25, 25, 112); // Midnight blue
    $pdf->RoundedRect(2.5, 2.5, 80, 12, 1.5, 'F');
    $pdf->SetFillColor(65, 105, 225); // Royal blue overlay
    $pdf->RoundedRect(2.5, 2.5, 80, 7, 1.5, 'F');

    // Gold accent line
    $pdf->SetDrawColor(255, 215, 0);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(2.5, 9.5, 82.5, 9.5);

    // Header text
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetXY(5, 3.5);
    $pdf->Cell(75, 6, 'STUDENT ID CARD', 0, 1, 'C');

    // "Back" indicator
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetTextColor(255, 215, 0);
    $pdf->SetXY(5, 10);
    $pdf->Cell(75, 3, 'REVERSE SIDE', 0, 1, 'C');

    // Important Information Box
    $pdf->SetFillColor(248, 248, 255); // Ghost white
    $pdf->SetLineWidth(0.4);
    $pdf->RoundedRect(5, 14, 75, 30, 2, 'F');

    // Important Information Header
    $pdf->SetXY(9, 16);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(25, 25, 112);
    $pdf->Cell(68, 5, 'IMPORTANT INFORMATION', 0, 1, 'L');

    // School details
    $pdf->SetXY(9, 21);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(65, 105, 225);
    $pdf->Cell(68, 4, 'School Address:', 0, 1, 'L');

    $pdf->SetXY(9, 25);
    $pdf->SetFont('Arial', '', 7);
    $pdf->SetTextColor(25, 25, 112);
    $pdf->MultiCell(68, 3.5, "KM 3, Akure Owo Express Road, Oba Ile, Akure, Ondo State, Nigeria.", 0, 'L');

    $pdf->SetXY(9, 31);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(65, 105, 225);
    $pdf->Cell(68, 4, 'Contact:', 0, 1, 'L');

    $pdf->SetXY(9, 35);
    $pdf->SetFont('Arial', '', 6.5);
    $pdf->SetTextColor(25, 25, 112);
    $pdf->Cell(68, 3.5, "Phone: " . "+234-803-504-2727, +234-803-883-8583", 0, 1, 'L');

    $pdf->SetXY(9, 38);
    $pdf->Cell(68, 3.5, "Email: " . "hapacollege2013@yahoo.com", 0, 1, 'L');

    // If found section
    $pdf->SetXY(9, 42);
    $pdf->SetFont('Arial', 'B', 6.5);
    $pdf->SetTextColor(184, 134, 11); // Dark goldenrod
    $pdf->Cell(68, 4, 'IF FOUND, PLEASE RETURN TO:', 0, 1, 'L');

    $pdf->SetXY(9, 46);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(25, 25, 112);
    $pdf->MultiCell(68, 3.5, "HAPA COLLEGE", 0, 'L');

    // Principal signature
    if (file_exists('assets/img/signature.jpg')) {
        $pdf->Image('assets/img/signature.jpg', 55, 40, 25, 8);
    }

    $pdf->Output();

    // Clean up temp QR file AFTER output
    if (file_exists($qr_file_path)) {
        unlink($qr_file_path);
    }
    exit();
}

// Fetch the logged-in Staff name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();

// If no student_id provided, show student list for admin to select
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
            class="d-none d-lg-block d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
              <h3 class="fw-bold mb-3">Print Student ID Card</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Administrator</li>
                <li class="breadcrumb-item active">ID Card</li>
              </ol>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Select Student to Print ID Card</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">
                    <?php
                    require_once 'db_connection.php';
                    
                    // Fetch all active students
                    $result_all = $conn->query("SELECT * FROM students WHERE status = 0 ORDER BY name ASC");
                    if ($result_all) {
                        $students = [];
                        while ($row = $result_all->fetch_assoc()) {
                            $students[] = $row;
                        }
                    } else {
                        die("Error fetching student records: " . $conn->error);
                    }
                    $conn->close();
                    ?>

                    <?php if (!empty($students)): ?>
                      <div class="table-responsive">
                        <table
                          id="multi-filter-select"
                          class="display table table-striped table-hover">
                          <thead>
                            <tr>
                              <th>Photo</th>
                              <th>ID</th>
                              <th>Name</th>
                              <th>Class</th>
                              <th>Arm</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($students as $student):
                              $image_name_base = str_replace("/", "_", $student['id']);
                              $image_extensions = ['jpg', 'jpeg', 'png', 'JPG'];
                              $image_found = false;

                              foreach ($image_extensions as $ext) {
                                $path = "studentimg/{$image_name_base}.{$ext}";
                                if (file_exists($path)) {
                                  $imagePath = $path;
                                  $image_found = true;
                                  break;
                                }
                              }

                              if (!$image_found) {
                                $imagePath = 'studentimg/default.jpg';
                              }
                            ?>
                              <tr>
                                <td>
                                  <img src="<?= $imagePath ?>" width="50" class="img-thumbnail rounded-circle">
                                </td>
                                <td><?= htmlspecialchars($student['id'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($student['name'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($student['class'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($student['arm'], ENT_QUOTES) ?></td>
                                <td>
                                  <a href="?student_id=<?= urlencode($student['id']) ?>" 
                                     class="btn btn-primary btn-sm btn-round"
                                     target="_blank">
                                    <i class="fas fa-print"></i> Print ID Card
                                  </a>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      <?php else: ?>
                        <p class="text-center">No students found.</p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php include('footer.php'); ?>
    </div>

    <!-- Custom template | don't include it in your project! -->
    <?php include('cust-color.php'); ?>
    <!-- End Custom template -->
  </div>
  <?php include('scripts.php'); ?>
</body>

</html>