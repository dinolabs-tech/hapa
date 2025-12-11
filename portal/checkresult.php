<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if session and term are set
if (isset($_GET['session']) && isset($_GET['term'])) {
    $csession = $_GET['session'];
    $currentterm = $_GET['term'];
    $term = $currentterm; // Set $term to the value of $currentterm
} else {
    header('Location: login.php');
    exit();
}

// Includes FPDF library and database connection
require('includes/fpdf.php');
include 'db_connection.php';
include 'includes/phpqrcode/qrlib.php'; // Corrected path to the QR code library

function ordinal(int $n): string
{
    $suffixes = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
    $v = $n % 100;
    // Special rule for 11,12,13
    if ($v >= 11 && $v <= 13) {
        return $n . 'th';
    }
    return $n . $suffixes[$n % 10];
}

$user_id = $_SESSION['user_id'];

// Extend FPDF with custom header, footer, and a property for the student image
class PDF extends FPDF
{
    public $studentImage; // Path to the student's image
    protected $angle = 0; // Initialize the angle property

    // Header
    function Header()
    {
        // Set font for the header
        $this->SetFont('Arial', 'B', 10);

        // Add school logo on the far left
        $this->Image('assets/img/logo.png', 10, 8, 20);  // Adjust position and size as needed

        // Add student image on the top right if available
        if (isset($this->studentImage)) {
            $x = $this->GetPageWidth() - 10 - 20; // right margin (10) + image width (20)
            $this->Image($this->studentImage, $x, 8, 20);
        }

        // School name (Centered)
        $this->SetFont('Arial', 'B', 18);
        $this->Cell(0, 5, 'HAPA COLLEGE', 0, 1, 'C');

        $this->SetFont('Arial', 'B', 11);
        // School address (Centered)
        $this->Cell(0, 5, 'KM 3, Akure Owo Express Road, Oba Ile,', 0, 1, 'C');
        $this->Cell(0, 5, 'Akure, Ondo State, Nigeria.', 0, 1, 'C');

        // School email (Centered)
        $this->Cell(0, 5, 'hapacollege2013@yahoo.com', 0, 1, 'C');

        // School mobile (Centered)
        $this->Cell(0, 5, '+234-803-504-2727, +234-803-883-8583', 0, 1, 'C');

        $this->Ln(5); // Space after header

        // Draw a horizontal line across the page
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

        // Current date and time
        $date = date('d/m/Y');
        $time = date('H:i:s');

        // Date on the left
        $this->Cell(100, 10, $date, 0, 0, 'L');
        // Time on the right
        $this->Cell(0, 10, $time, 0, 0, 'R');
    }

    // Rotate function and RotatedText for any rotated headers
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

// Query to get the student details from mastersheet
$result = $conn->query("SELECT * FROM mastersheet WHERE id = '$user_id' and term = '$term' and csession = '$csession'");
$student_details = $result->fetch_assoc();

// Query to get student info from the students table
$result = $conn->query("SELECT * FROM students WHERE id = '$user_id'");
$student_photo = $result->fetch_assoc();

// Query for class comments and principal comments
$class_comments_result = $conn->query("SELECT * FROM classcomments WHERE id = '$user_id' and term = '$term' and csession = '$csession'");
$class_comments = $class_comments_result->fetch_assoc();

$principal_comments_result = $conn->query("SELECT comment FROM principalcomments WHERE id = '$user_id' and term = '$term' and csession = '$csession'");
$principal_comment = $principal_comments_result->fetch_assoc();

$next_term_result = $conn->query("SELECT Next FROM nextterm WHERE id = 1");
$next_term = $next_term_result->fetch_assoc()['Next'];

$promotec = $conn->query("SELECT comment FROM promote WHERE id='$student_id' AND term='$term' AND csession='$csession'")->fetch_assoc()['comment'] ?? 'N/A';

// Create the PDF
$pdf = new PDF();

// Get the student image using your filename method
$photo_filename = str_replace('/', '_', $student_photo['id']);  // e.g., wf_1000_24
$photo_path = "studentimg/" . $photo_filename . ".jpg";
if (!file_exists($photo_path)) {
    $photo_path = "studentimg/default.jpg"; // Fallback to default image
}
$pdf->studentImage = $photo_path;

$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// First row (Name / SchlOpen)
$pdf->Cell(95, 7, "Name:      " . $student_photo['name'], 'B', 0);
$pdf->Cell(10, 7, "", 0, 0);  // Add an empty cell to create extra space
$pdf->Cell(85, 7, "School Opened:      " . $class_comments['schlopen'], 'B', 1);

// Second row (Class / Days Absent)
$pdf->Cell(95, 7, "Class:      " . $student_photo['class'] . " " . $student_photo['arm'], 'B', 0);
$pdf->Cell(10, 7, "", 0, 0);
$pdf->Cell(85, 7, "Days Absent:  " . $class_comments['daysabsent'], 'B', 1);

// Third row (Term / Days Present)
$pdf->Cell(95, 7, "Term:      " . $term, 'B', 0);
$pdf->Cell(10, 7, "", 0, 0);
$pdf->Cell(85, 7, "Days Present: " . $class_comments['dayspresent'], 'B', 1);

// Fourth row (Session / Next Term)
$pdf->Cell(95, 7, "Session:  " . $csession, 'B', 0);
$pdf->Cell(10, 7, "", 0, 0);
$pdf->Cell(85, 7, "Next Term:      " . $next_term, 'B', 1);

$pdf->Ln(5);

// Set background color to gray for the results table header
$pdf->SetFillColor(90, 174, 255);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(80, 25, 'SUBJECT', 1, 0, 'C', true);
// $pdf->Cell(10, 25, 'CA1', 1, 0, 'C', true);

// Rotated headers for remaining columns
$x_start = $pdf->GetX();
$y_start = $pdf->GetY();
$rotated_headers = ['CA1', 'CA2', 'EXAM', 'LAST CUM', 'TOTAL', 'AVERAGE', 'GRADE', 'POSITION'];
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
$results_result = $conn->query("SELECT * FROM mastersheet WHERE id = '$user_id' AND term = '$term' AND csession = '$csession'");

// Fetch class average scores for each subject
$subject_averages_result = $conn->query("
    SELECT subject, AVG(total) AS avg_score 
    FROM mastersheet 
    WHERE class = '{$student_details['class']}' AND term = '$term' AND csession = '$csession'
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
        WHERE class = '{$student_photo['class']}'
        AND arm = '{$student_photo['arm']}'
          AND term = '$term'
          AND csession = '$csession'
        GROUP BY id
    ) AS ranked
    WHERE id = '$user_id'
");

$position_row = $pos_query->fetch_assoc();
$overall_position = $position_row['position'];



$total_average = 0;
$num_subjects = 0;

while ($row = $results_result->fetch_assoc()) {
    $subject = $row['subject'];
    $avg_score = isset($subject_averages[$subject]) ? $subject_averages[$subject] : '-';

    $pdf->Cell(80, 5, $subject, 1, 0);
    $pdf->Cell(8, 5, $row['ca1'], 1, 0, 'C');
    $pdf->Cell(8, 5, $row['ca2'], 1, 0, 'C');
    $pdf->Cell(8, 5, $row['exam'], 1, 0, 'C');
    $pdf->Cell(8, 5, ceil($row['lastcum']), 1, 0, 'C');
    $pdf->Cell(8, 5, ceil($row['total']), 1, 0, 'C');
    $pdf->Cell(8, 5, ceil($row['average']), 1, 0, 'C');
    $pdf->Cell(8, 5, $row['grade'], 1, 0, 'C');
    // $pdf->Cell(8, 5, $avg_score, 1, 0, 'C');
    $pdf->Cell(8, 5, ordinal((int)$row['position']), 1, 0, 'C');
    $pdf->Cell(48, 5, $row['remark'], 1, 1, 'C');

    $total_average += $row['average'];
    $num_subjects++;
}

if ($num_subjects > 0) {
    $overall_average = $num_subjects > 0 ? number_format($total_average / $num_subjects, 1) : '0.0';
} else {
    $overall_average = '0.0';
}

// Output overall average
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);

// Left cell (Overall Average)
$pdf->Cell(95, 7, "Overall Average: {$overall_average}%", 1, 0, 'L');

// Right cell (Overall Position)
$pdf->Cell(95, 7, "Overall Position: " . ordinal((int)$overall_position), 1, 1, 'R');

$pdf->Ln(2);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 5, $class_comments['comment'], 'B', 1, 'C');
$pdf->Cell(0, 5, "Class Teacher's Comment", 0, 1, 'C');

// $pdf->Ln(2);
// $pdf->SetFont('Arial', 'I', 10);
// $pdf->Cell(0, 5, $principal_comment['comment'], 'B', 1, 'C');
// $pdf->Cell(0, 5, "Principal's Comment: ", 0, 1, 'C');

$pdf->Ln(3);
$pdf->Cell(10, 2, '', 0, 0);
$pdf->SetX(-40);
$pdf->Image('assets/img/signature.jpg', $pdf->GetX(), $pdf->GetY(), 30);
$pdf->Ln(1);
$pdf->SetX(-30);
$pdf->Cell(10, -5, 'Principal`s Signature', 0, 1, 'C');

$pdf->Ln(7);
$pdf->SetFont('Arial', '', 11);
// Add merged header for Grading Table (Left side)
$startX = $pdf->GetX();
$startY = $pdf->GetY();
$pdf->SetXY($startX, $startY);
$pdf->Cell(60, 7, 'Grading Table', 1, 0, 'C', true);

// Add merged header for Skills Assessment (Right side)
$secondTableX = $startX + 65; // reduced gap from 70 to 65
$pdf->SetXY($secondTableX, $startY);
$pdf->Cell(90, 7, 'Skills Assessment', 1, 1, 'C', true);

// Move down
$startY += 7;
$pdf->SetFont('Arial', '', 10);

// Data for Grading Table based on class
if (in_array($student_photo['class'], ['SSS 1', 'SSS 2', 'SSS 3'])) {
    $grading_data = [
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
    $grading_data = [
        ['A', '70 - 100', 'Excellent'],
        ['B', '60 - 69', 'Good'],
        ['C', '50 - 59', 'Average'],
        ['D', '45 - 49', 'Below Average'],
        ['E', '40 - 44', 'Poor'],
        ['F', '0 - 39', 'Fail']
    ];
}

$second_table_data = [
    ['Attentiveness', $class_comments['attentiveness'], 'Relationship', $class_comments['relationship']],
    ['Neatness', $class_comments['neatness'], 'Handwriting', $class_comments['handwriting']],
    ['Politeness', $class_comments['politeness'], 'Entrepreneurship', $class_comments['music']],
    ['Self-Control', $class_comments['selfcontrol'], 'Club/Society', $class_comments['club']],
    ['Punctuality', $class_comments['punctuality'], 'Sport', $class_comments['sport']],
];

// Calculate max rows but limit to the number of second table rows (so no extra empty row)
$maxRows = count($second_table_data);

for ($i = 0; $i < $maxRows; $i++) {
    // First Table Data (Grading Table)
    $pdf->SetXY($startX, $startY);
    if (isset($grading_data[$i])) {
        $pdf->Cell(10, 6, $grading_data[$i][0], 1, 0, 'C'); // Grade
        $pdf->Cell(20, 6, $grading_data[$i][1], 1, 0, 'C'); // Range
        $pdf->Cell(30, 6, $grading_data[$i][2], 1, 0, 'C'); // Description
    } else {
        // Fill empty cells if grading data finished
        $pdf->Cell(10, 6, '', 1, 0, 'C');
        $pdf->Cell(20, 6, '', 1, 0, 'C');
        $pdf->Cell(30, 6, '', 1, 0, 'C');
    }

    // Second Table Data (Skills Assessment)
    $pdf->SetXY($secondTableX, $startY);
    $pdf->Cell(30, 6, $second_table_data[$i][0], 1, 0, 'C');
    $pdf->Cell(15, 6, $second_table_data[$i][1], 1, 0, 'C');
    $pdf->Cell(30, 6, $second_table_data[$i][2], 1, 0, 'C');
    $pdf->Cell(15, 6, $second_table_data[$i][3], 1, 1, 'C');

    $startY += 6; // Move Y down
}


// --- QR Code ---
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$base_url = $protocol . '://' . $_SERVER['HTTP_HOST'];
$qr_code_text = "This result is an authenticated academic document issued to " . $student_details['name'] . ". Its authenticity and legal status can be verified through " . $base_url . "/eduhive/verify.php?student_id=" . $student_id . "&type=result";
$qr_file_path = 'temp_qr_' . md5($qr_code_text) . '.png';
QRcode::png($qr_code_text, $qr_file_path, QR_ECLEVEL_L, 4, 2);
$qr_w = 25;
$qr_h = 25;
$qr_x = $secondTableX + 95; // Position it to the right of the skills table
$qr_y = $startY - (count($second_table_data) * 6) - 15 + 20;
$pdf->Image($qr_file_path, $qr_x, $qr_y, $qr_w, $qr_h, 'PNG');
if (file_exists($qr_file_path)) {
    unlink($qr_file_path);
}


$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(95, 7, "Promotional Status: {$promotec}", 'B', 0, 'C');


$pdf->Output();
ob_end_flush();
