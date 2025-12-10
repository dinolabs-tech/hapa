<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include FPDF library
require('includes/fpdf.php');
// Database connection
include 'db_connection.php';
include 'includes/phpqrcode/qrlib.php'; // Corrected path to the QR code library

/**
 * Convert an integer into its ordinal representation.
 * E.g. 1→"1st", 2→"2nd", 3→"3rd", 11→"11th", etc.
 */
function ordinal(int $n): string
{
    $suffixes = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
    $v = $n % 100;
    if ($v >= 11 && $v <= 13) {
        return $n . 'th';
    }
    return $n . $suffixes[$n % 10];
}

// Get student ID from URL (e.g., WF/944/24)
$student_id = $_GET['student_id'];

// Construct the photo filename (e.g., WF_944_24.jpg)
$photo_filename = str_replace('/', '_', $student_id);
$photo_path = "studentimg/" . $photo_filename . ".jpg";

// Use default image if the student photo doesn’t exist
if (!file_exists($photo_path)) {
    $photo_path = "studentimg/default.jpg";
}

// Custom PDF class
class MyPDF extends FPDF
{
    protected $angle = 0;
    public $studentImage; // Property to hold the photo path

    // Header
    function Header()
    {
        $this->SetFont('Arial', 'B', 10);
        $this->Image('assets/img/logo.png', 10, 8, 20);

        // Student photo in the top-right corner
        if (!empty($this->studentImage) && file_exists($this->studentImage)) {
            $x = $this->GetPageWidth() - 10 - 20; // Right margin (10mm) + image width (20mm)
            $this->Image($this->studentImage, $x, 8, 20);
        }

        $this->SetFont('Arial', 'B', 18);
        $this->Cell(0, 5, 'HAPA COLLEGE', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 5, 'KM 3, Akure Owo Express Road, Oba Ile,', 0, 1, 'C');
        $this->Cell(0, 5, 'Akure, Ondo State, Nigeria.', 0, 1, 'C');
        $this->Cell(0, 5, 'hapacollege2013@yahoo.com', 0, 1, 'C');
        $this->Cell(0, 5, '+234-803-504-2727, +234-803-883-8583', 0, 1, 'C');
        $this->Ln(5);

        $x1 = 10;
        $x2 = $this->GetPageWidth() - 10;
        $y = $this->GetY();
        $this->Line($x1, $y, $x2, $y);
        $this->Ln(5);
    }

    // Footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $date = date('d/m/Y');
        $time = date('H:i:s');
        $this->Cell(100, 10, $date, 0, 0, 'L');
        $this->Cell(0, 10, $time, 0, 0, 'R');
    }

    // Rotate function
    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1) $x = $this->x;
        if ($y == -1) $y = $this->y;
        if ($this->angle != 0) $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.2F %.2F %.2F %.2F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    function RotatedText($x, $y, $txt, $angle)
    {
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }
}

// Start output buffering
ob_start();

// Create PDF instance and set photo path
$pdf = new MyPDF();
$pdf->studentImage = $photo_path;
$pdf->AddPage();

// Fetch student details
$student_details_result = $conn->query("SELECT * FROM students WHERE id = '$student_id'");
$student_details = $student_details_result->fetch_assoc();

// Fetch current term and session
$current_term_result = $conn->query("SELECT * FROM currentterm WHERE id = 1");
$current_term = $current_term_result->fetch_assoc();
$term = $current_term['cterm'];

$current_session_result = $conn->query("SELECT * FROM currentsession WHERE id = 1");
$current_session = $current_session_result->fetch_assoc();
$curr_session = $current_session['csession'];

// Fetch class comments
$class_comments_result = $conn->query("SELECT * FROM classcomments WHERE id = '$student_id' AND term = '$term' AND csession = '$curr_session'");
$class_comments = $class_comments_result->num_rows > 0 ? $class_comments_result->fetch_assoc() : [
    'schlopen' => 'N/A',
    'daysabsent' => 'N/A',
    'dayspresent' => 'N/A',
    'comment' => 'No comment available',
    'attentiveness' => 'N/A',
    'neatness' => 'N/A',
    'politeness' => 'N/A',
    'selfcontrol' => 'N/A',
    'punctuality' => 'N/A',
    'relationship' => 'N/A',
    'handwriting' => 'N/A',
    'music' => 'N/A',
    'club' => 'N/A',
    'sport' => 'N/A'
];

// Fetch principal comment
$principal_comments_result = $conn->query("SELECT comment FROM principalcomments WHERE id = '$student_id' AND term = '$term' AND csession = '$curr_session'");
$principal_comment = $principal_comments_result->num_rows > 0 ? $principal_comments_result->fetch_assoc() : ['comment' => 'No comment available'];

// Fetch next term
$next_term_result = $conn->query("SELECT Next FROM nextterm WHERE id = 1");
$next_term = $next_term_result->fetch_assoc()['Next'];

$promotec = $conn->query("SELECT comment FROM promote WHERE id='$student_id' AND term='$term' AND csession='$curr_session'")->fetch_assoc()['comment'] ?? 'N/A';

// Add student info to PDF
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(95, 7, "Name: " . $student_details['name'], 'B', 0);
$pdf->Cell(95, 7, "School Opened: " . $class_comments['schlopen'], 'B', 1);
$pdf->Cell(95, 7, "Class: " . $student_details['class'] . " " . $student_details['arm'], 'B', 0);
$pdf->Cell(95, 7, "Days Absent: " . $class_comments['daysabsent'], 'B', 1);
$pdf->Cell(95, 7, "Term: " . $term, 'B', 0);
$pdf->Cell(95, 7, "Days Present: " . $class_comments['dayspresent'], 'B', 1);
$pdf->Cell(95, 7, "Session: " . $curr_session, 'B', 0);
$pdf->Cell(95, 7, "Next Term: " . $next_term, 'B', 1);
$pdf->Ln(5);

// Results table header
$pdf->SetFillColor(90, 174, 255);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(80, 25, 'SUBJECT', 1, 0, 'C', true);

$x_start = $pdf->GetX();
$y_start = $pdf->GetY();
$rotated_headers = ['CA1', 'CA2', 'EXAM', 'TOTAL', 'LAST CUM',  'AVERAGE', 'GRADE', 'CLASS AVG.', 'POSITION'];
$header_width = 8;

foreach ($rotated_headers as $index => $header) {
    $x_pos = $x_start + ($index * $header_width);
    $pdf->Cell($header_width, 25, '', 1, 0, 'C', true);
    $pdf->RotatedText($x_pos + 6, $y_start + 23, $header, 90);
}

$pdf->Cell(40, 25, 'REMARK', 1, 0, 'C', true);
$pdf->Ln();

// Add student results data
$pdf->SetFont('Arial', '', 8);
$results_result = $conn->query("SELECT * FROM mastersheet WHERE id = '$student_id' AND term = '$term' AND csession = '$curr_session'");

$subject_averages_result = $conn->query("
    SELECT subject, AVG(total) AS avg_score 
    FROM mastersheet 
    WHERE class = '{$student_details['class']}' AND term = '$term' AND csession = '$curr_session'
    GROUP BY subject
");

$subject_averages = [];
while ($avg_row = $subject_averages_result->fetch_assoc()) {
    $subject_averages[$avg_row['subject']] = ceil($avg_row['avg_score']);
}

$total_average = 0;
$num_subjects = 0;

while ($row = $results_result->fetch_assoc()) {
    $subject = $row['subject'];
    $avg_score = isset($subject_averages[$subject]) ? $subject_averages[$subject] : '-';
    $pdf->Cell(80, 5, $subject, 1, 0);
    $pdf->Cell(8, 5, $row['ca1'], 1, 0, 'C');
    $pdf->Cell(8, 5, $row['ca2'], 1, 0, 'C');
    $pdf->Cell(8, 5, $row['exam'], 1, 0, 'C');
    $pdf->Cell(8, 5, ceil($row['total']), 1, 0, 'C');
    $pdf->Cell(8, 5, ceil($row['lastcum']), 1, 0, 'C');
    $pdf->Cell(8, 5, ceil($row['average']), 1, 0, 'C');
    $pdf->Cell(8, 5, $row['grade'], 1, 0, 'C');
    $pdf->Cell(8, 5, $avg_score, 1, 0, 'C');
    $pdf->Cell(8, 5, ordinal((int)$row['position']), 1, 0, 'C');
    $pdf->Cell(40, 5, $row['remark'], 1, 1, 'C');
    $total_average += $row['average'];
    $num_subjects++;
}

// Calculate overall average
$overall_average = $num_subjects > 0 ? number_format($total_average / $num_subjects, 1) : '0.0';

// Output overall average
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 7, "Overall Average: {$overall_average}%", 1, 1, 'C');

// Add comments
$pdf->Ln(2);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 5, $class_comments['comment'], 'B', 1, 'C');
$pdf->Cell(0, 5, "Class Teacher's Comment", 0, 1, 'C');

// $pdf->Ln(2);
// $pdf->Cell(0, 5, $principal_comment['comment'], 'B', 1, 'C');
// $pdf->Cell(0, 5, "Principal's Comment: ", 0, 1, 'C');

// Add principal's signature
$pdf->Ln(3);
$pdf->SetX(-40);
$pdf->Image('assets/img/signature.jpg', $pdf->GetX(), $pdf->GetY(), 30);
$pdf->Ln(1);
$pdf->SetX(-30);
$pdf->Cell(10, -5, "Principal's Signature", 0, 1, 'C');

// Add some space before the tables
$pdf->Ln(7);

// Set font for tables
$pdf->SetFont('Arial', 'B', 10);

// Add merged header for Grading Table (Left side)
$startX = $pdf->GetX();
$startY = $pdf->GetY();
$pdf->SetXY($startX, $startY);
$pdf->Cell(60, 7, 'Grading Table', 1, 0, 'C', true);

// Add merged header for Skills Assessment (Right side)
$secondTableX = $startX + 65;
$pdf->SetXY($secondTableX, $startY);
$pdf->Cell(90, 7, 'Skills Assessment', 1, 1, 'C', true);

// Move down
$startY += 7;
$pdf->SetFont('Arial', '', 10);

// Data for Grading Table
$grading_data = [
    ['A', '75 - 100', 'Excellent'],
    ['B', '65 - 74', 'Very Good'],
    ['C', '50 - 64', 'Good'],
    ['D', '45 - 49', 'Fair'],
    ['E', '40 - 44', 'Poor'],
    ['F', '0 - 39', 'Very Poor']
];

// Data for Skills Assessment
$second_table_data = [
    ['Attentiveness', $class_comments['attentiveness'], 'Relationship', $class_comments['relationship']],
    ['Neatness', $class_comments['neatness'], 'Handwriting', $class_comments['handwriting']],
    ['Politeness', $class_comments['politeness'], 'Music', $class_comments['music']],
    ['Self-Control', $class_comments['selfcontrol'], 'Club/Society', $class_comments['club']],
    ['Punctuality', $class_comments['punctuality'], 'Sport', $class_comments['sport']],
];

// Loop through rows for both tables
$maxRows = max(count($grading_data), count($second_table_data));

for ($i = 0; $i < $maxRows; $i++) {
    // Grading Table
    $pdf->SetXY($startX, $startY);
    if (isset($grading_data[$i])) {
        $pdf->Cell(10, 6, $grading_data[$i][0], 1, 0, 'C');
        $pdf->Cell(20, 6, $grading_data[$i][1], 1, 0, 'C');
        $pdf->Cell(30, 6, $grading_data[$i][2], 1, 0, 'C');
    } else {
        $pdf->Cell(10, 6, '', 1, 0, 'C');
        $pdf->Cell(20, 6, '', 1, 0, 'C');
        $pdf->Cell(30, 6, '', 1, 0, 'C');
    }

    // Skills Assessment Table
       // Skills Assessment Table
       if (isset($second_table_data[$i])) {
        $pdf->SetXY($secondTableX, $startY);
        $pdf->Cell(30, 6, $second_table_data[$i][0], 1, 0, 'C');
        $pdf->Cell(15, 6, $second_table_data[$i][1], 1, 0, 'C');
        $pdf->Cell(30, 6, $second_table_data[$i][2], 1, 0, 'C');
        $pdf->Cell(15, 6, $second_table_data[$i][3], 1, 1, 'C');
    }


    $startY += 6; // Move Y down
}

$endY = $startY;

// --- QR Code ---
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$base_url = $protocol . '://' . $_SERVER['HTTP_HOST'];
$qr_code_text = "This result is an authenticated academic document issued to " . $student_details['name'] . ". Its authenticity and legal status can be verified through " . $base_url . "/verify.php?student_id=" . $student_id . "&type=result";
$qr_file_path = 'temp_qr_' . md5($qr_code_text) . '.png';
QRcode::png($qr_code_text, $qr_file_path, QR_ECLEVEL_L, 4, 2);
$qr_w = 25;
$qr_h = 25;
$qr_x = $secondTableX + 95; // Position it to the right of the skills table
$qr_y = $endY - (count($second_table_data) * 6) - 15 + 15; // Adjust Y position to align with the tables
$pdf->Image($qr_file_path, $qr_x, $qr_y, $qr_w, $qr_h, 'PNG');
if (file_exists($qr_file_path)) {
    unlink($qr_file_path);
}


$pdf->SetFont('Arial','B',10);
$pdf->Cell(95,7,"Promotional Status: {$promotec}", 'B',0, 'C');

// Output the PDF
$pdf->Output();
ob_end_flush();
?>