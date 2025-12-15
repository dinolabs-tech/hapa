<?php

/**
 * bulk_result_download.php
 *
 * Generates a bulk PDF containing results for all students in a specified class and arm.
 * Each student's result is on a separate page within the PDF.
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start or resume a session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include FPDF library
require('includes/fpdf.php');
include 'db_connection.php';
include 'includes/phpqrcode/qrlib.php'; // For QR code generation

/**
 * Converts an integer into its ordinal representation
 */
function ordinal(int $n): string
{
    $suffixes = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
    $v = $n % 100;
    if ($v >= 11 && $v <= 13) return $n . 'th';
    return $n . $suffixes[$n % 10];
}

/**
 * Custom PDF class extending FPDF
 */
class MyPDF extends FPDF
{
    protected $angle = 0;
    public $studentImage;

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

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(100, 10, date('d/m/Y'), 0, 0, 'L');
        $this->Cell(0, 10, date('H:i:s'), 0, 0, 'R');
    }

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

// Get class and arm from GET parameters
$class = $_GET['class'] ?? '';
$arm = $_GET['arm'] ?? '';

if (empty($class) || empty($arm)) {
    die("Class and Arm are required.");
}

// Sanitize inputs
$class = htmlspecialchars($class);
$arm = htmlspecialchars($arm);

// Fetch students in the specified class and arm
$stmt = $conn->prepare("SELECT id FROM students WHERE class = ? AND arm = ? AND status = 0 ORDER BY name");
$stmt->bind_param("ss", $class, $arm);
$stmt->execute();
$result = $stmt->get_result();
$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row['id'];
}
$stmt->close();

// If no students, create empty PDF with message
if (empty($students)) {
    $pdf = new MyPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'No Students Found', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "No students found for Class: $class, Arm: $arm", 0, 1, 'C');
    $filename = str_replace([' ', '/'], '_', "{$class}_{$arm}_results.pdf");
    $pdf->Output('D', $filename);
    exit();
}

// Fetch current term and session
$term = $conn->query("SELECT cterm FROM currentterm WHERE id=1")->fetch_assoc()['cterm'];
$session = $conn->query("SELECT csession FROM currentsession WHERE id=1")->fetch_assoc()['csession'];

// Instantiate PDF
$pdf = new MyPDF();

// Loop through each student and add their result page
foreach ($students as $student_id) {
    // Fetch student details
    $sd = $conn->query("SELECT * FROM students WHERE id='$student_id'")->fetch_assoc();
    $photo_filename = str_replace('/', '_', $student_id);
    $photo_path = "studentimg/{$photo_filename}.jpg";
    if (!file_exists($photo_path)) $photo_path = "studentimg/default.jpg";

    $pdf->studentImage = $photo_path;
    $pdf->AddPage();

    // Fetch class comments
    $cc = $conn->query("SELECT * FROM classcomments WHERE id='$student_id' AND term='$term' AND csession='$session'")->fetch_assoc() ?: [
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
    $pc = $conn->query("SELECT comment FROM principalcomments WHERE id='$student_id' AND term='$term' AND csession='$session'")->fetch_assoc()['comment'] ?? 'No comment available';

    // Fetch next term
    $next = $conn->query("SELECT Next FROM nextterm WHERE id=1")->fetch_assoc()['Next'] ?? 'N/A';

    // Fetch promote comment
    $promotec = $conn->query("SELECT comment FROM promote WHERE id='$student_id' AND term='$term' AND csession='$session'")->fetch_assoc()['comment'] ?? 'N/A';

    // Add student info to PDF
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(95, 7, "Name: {$sd['name']}", 'B', 0);
    $pdf->Cell(95, 7, "School Opened: {$cc['schlopen']}", 'B', 1);
    $pdf->Cell(95, 7, "Class: {$sd['class']} {$sd['arm']}", 'B', 0);
    $pdf->Cell(95, 7, "Days Absent: {$cc['daysabsent']}", 'B', 1);
    $pdf->Cell(95, 7, "Term: $term", 'B', 0);
    $pdf->Cell(95, 7, "Days Present: {$cc['dayspresent']}", 'B', 1);
    $pdf->Cell(95, 7, "Session: $session", 'B', 0);
    $pdf->Cell(95, 7, "Next Term: $next", 'B', 1);
    $pdf->Ln(5);

    // Results table header
    $pdf->SetFillColor(90, 174, 255);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(80, 25, 'SUBJECT', 1, 0, 'C', true);

    $x_start = $pdf->GetX();
    $y_start = $pdf->GetY();
    $rotated_headers = ['CA1', 'CA2', 'EXAM', 'TOTAL', 'LAST CUM', 'AVERAGE', 'GRADE', 'POSITION'];
    $header_width = 8;

    foreach ($rotated_headers as $index => $header) {
        $x_pos = $x_start + ($index * $header_width);
        $pdf->Cell($header_width, 25, '', 1, 0, 'C', true);
        $pdf->RotatedText($x_pos + 6, $y_start + 23, $header, 90);
    }

    $pdf->Cell(48, 25, 'REMARK', 1, 0, 'C', true);
    $pdf->Ln();

    // Add student results data
    $pdf->SetFont('Arial', '', 8);
    $results_result = $conn->query("SELECT * FROM mastersheet WHERE id = '$student_id' AND term = '$term' AND csession = '$session'");

    $subject_averages_result = $conn->query("
        SELECT subject, AVG(total) AS avg_score 
        FROM mastersheet 
        WHERE class = '{$sd['class']}' AND term = '$term' AND csession = '$session'
        GROUP BY subject
    ");

    $subject_averages = [];
    while ($avg_row = $subject_averages_result->fetch_assoc()) {
        $subject_averages[$avg_row['subject']] = ceil($avg_row['avg_score']);
    }

    $pos_query = $conn->query("
        SELECT *
        FROM (
            SELECT
                id,
                SUM(total) AS overall_total,
                RANK() OVER (ORDER BY SUM(total) DESC) AS position
            FROM mastersheet
            WHERE class = '{$sd['class']}'
            AND arm = '{$sd['arm']}'
              AND term = '$term'
              AND csession = '$session'
            GROUP BY id
        ) AS ranked
        WHERE id = '$student_id'
    ");

    $position_row = $pos_query->fetch_assoc();
    $overall_position = $position_row ? $position_row['position'] : 'N/A';

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
        $pdf->Cell(8, 5, ordinal((int)$row['position']), 1, 0, 'C');
        $pdf->Cell(48, 5, $row['remark'], 1, 1, 'C');
        $total_average += $row['average'];
        $num_subjects++;
    }

    // Calculate overall average
    $overall_average = $num_subjects > 0 ? number_format($total_average / $num_subjects, 1) : '0.0';
    // $overall_average=$total_average;

    // Output overall average
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 10);

    // Left cell (Overall Average)
    $pdf->Cell(95, 7, "Overall Average: {$overall_average}%", 1, 0, 'L');

    // Right cell (Overall Position)
    $overall_position_display = is_numeric($overall_position) ? ordinal((int)$overall_position) : $overall_position;
    $pdf->Cell(95, 7, "Overall Position: " . $overall_position_display, 1, 1, 'R');

    // Add comments
    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, $cc['comment'], 'B', 1, 'C');
    $pdf->Cell(0, 5, "Class Teacher's Comment", 0, 1, 'C');

    // Principal's signature
    $pdf->Ln(3);
    $pdf->SetX(-40);
    if (file_exists('assets/img/signature.jpg')) {
        $pdf->Image('assets/img/signature.jpg', $pdf->GetX(), $pdf->GetY(), 30);
    }
    $pdf->Ln(1);
    $pdf->SetX(-30);
    $pdf->Cell(10, -5, "Principal's Signature", 0, 1, 'C');

    // Grading and Skills tables
    $pdf->Ln(7);
    $pdf->SetFont('Arial', 'B', 10);
    $startX = $pdf->GetX();
    $startY = $pdf->GetY();
    $pdf->SetXY($startX, $startY);
    $pdf->Cell(60, 7, 'Grading Table', 1, 0, 'C', true);
    $secondX = $startX + 65;
    $pdf->SetXY($secondX, $startY);
    $pdf->Cell(90, 7, 'Skills Assessment', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 10);
    $startY += 7;
    if (in_array($sd['class'], ['SSS 1', 'SSS 2', 'SSS 3'])) {
        $grading = [
            ['A1', '75 - 100', 'Excellent'],
            ['B2', '70 - 74', 'Very Good'],
            ['B3', '65 - 69', 'Good'],
            ['C4', '60 - 64', 'Good'],
            ['C5', '55 - 59', 'Average'],
            ['C6', '50 - 54', 'Average'],
            ['D7', '45 - 49', 'Pass'],
            ['E8', '40 - 44', 'Pass'],
            ['F9', '0 - 39', 'Fail']
        ];
    } else {
        $grading = [
            ['A', '70 - 100', 'Excellent'],
            ['B', '60 - 69', 'Good'],
            ['C', '50 - 59', 'Average'],
            ['D', '45 - 49', 'Below Average'],
            ['E', '40 - 44', 'Poor'],
            ['F', '0 - 39', 'Fail']
        ];
    }
    $skills = [
        ['Attentiveness', $cc['attentiveness'] ?? 'N/A', 'Relationship', $cc['relationship'] ?? 'N/A'],
        ['Neatness', $cc['neatness'] ?? 'N/A', 'Handwriting', $cc['handwriting'] ?? 'N/A'],
        ['Politeness', $cc['politeness'] ?? 'N/A', 'Entrepreneurship', $cc['music'] ?? 'N/A'],
        ['Self-Control', $cc['selfcontrol'] ?? 'N/A', 'Club/Society', $cc['club'] ?? 'N/A'],
        ['Punctuality', $cc['punctuality'] ?? 'N/A', 'Sport', $cc['sport'] ?? 'N/A']
    ];
    $rows = max(count($grading), count($skills));
    for ($i = 0; $i < $rows; $i++) {
        $pdf->SetXY($startX, $startY);
        if (isset($grading[$i])) {
            $pdf->Cell(10, 6, $grading[$i][0], 1, 0, 'C');
            $pdf->Cell(20, 6, $grading[$i][1], 1, 0, 'C');
            $pdf->Cell(30, 6, $grading[$i][2], 1, 0, 'C');
        } else {
            $pdf->Cell(10, 6, '', 1, 0);
            $pdf->Cell(20, 6, '', 1, 0);
            $pdf->Cell(30, 6, '', 1, 0);
        }
        if (isset($skills[$i])) {
            $pdf->SetXY($secondX, $startY);
            $pdf->Cell(30, 6, $skills[$i][0], 1, 0, 'C');
            $pdf->Cell(15, 6, $skills[$i][1], 1, 0, 'C');
            $pdf->Cell(30, 6, $skills[$i][2], 1, 0, 'C');
            $pdf->Cell(15, 6, $skills[$i][3], 1, 1, 'C');
        } else {
            $pdf->Ln(6);
        }
        $startY += 6;
    }
    $endY = $startY;

    // QR Code
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $base_url = $protocol . '://' . $_SERVER['HTTP_HOST'];
    $qr_code_text = "This result is an authenticated academic document issued to " . $sd['name'] . ". Its authenticity and legal status can be verified through " . $base_url . "/verify.php?student_id=" . $student_id . "&type=result";
    $qr_file_path = 'temp_qr_' . md5($qr_code_text) . '.png';
    QRcode::png($qr_code_text, $qr_file_path, QR_ECLEVEL_L, 4, 2);
    $qr_w = 25;
    $qr_h = 25;
    $qr_x = $secondX + 95;
    $qr_y = $endY - (count($skills) * 6) - 15 + 15;
    if (file_exists($qr_file_path)) {
        $pdf->Image($qr_file_path, $qr_x, $qr_y, $qr_w, $qr_h, 'PNG');
        unlink($qr_file_path);
    }

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(95, 7, "Promotional Status: {$promotec}", 'B', 0, 'C');
}

// Output the PDF
$filename = str_replace([' ', '/'], '_', "{$class}_{$arm}_results.pdf");
$pdf->Output('D', $filename);
ob_end_flush();
