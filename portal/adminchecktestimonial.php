<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 * testimonial_premium.php (Final Integrated Version)
 */

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require('includes/fpdf.php');
include 'db_connection.php';
include 'includes/phpqrcode/qrlib.php'; // Corrected path to the QR code library

// Check student_id
if (isset($_REQUEST['student_id']) && !empty($_REQUEST['student_id'])) {

    $student_id = trim($_REQUEST['student_id']);

    // Fetch student info
    $student = $conn->query("SELECT * FROM students WHERE id='$student_id'")->fetch_assoc();
    if (!$student) {
        echo "<div style='text-align:center;margin-top:50px;font-family:sans-serif;'>
              <h1>Student Not Found</h1>
              <p>The student ID provided does not exist.</p>
              <a href='alumni_list.php' style='padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;'>Go Back</a>
              </div>";
        exit();
    }

    $session = $conn->query("SELECT csession FROM currentsession WHERE id=1")->fetch_assoc()['csession'] ?? date('Y');

    // Fetch all testimonial info for the student and session
    $testimonial = $conn->query("
        SELECT *
        FROM testimonial
        WHERE student_id='$student_id' AND session='$session'
        LIMIT 1
    ")->fetch_assoc();

    // Assign testimonial fields, with defaults
    $subjects_offered     = (!empty($testimonial['subjects_offered'])) ? $testimonial['subjects_offered'] : "No subjects listed.";
    $academic_ability     = (!empty($testimonial['academic_ability'])) ? $testimonial['academic_ability'] : "The student displayed good academic ability.";
    $prizes_won           = (!empty($testimonial['prizes_won'])) ? $testimonial['prizes_won'] : "No significant prizes or awards were won.";
    $character_assessment = (!empty($testimonial['character_assessment'])) ? $testimonial['character_assessment'] : "The student exhibited good character and conduct.";
    $leadership_position  = (!empty($testimonial['leadership_position'])) ? $testimonial['leadership_position'] : "No leadership position was held.";
    $co_curricular        = (!empty($testimonial['co_curricular'])) ? $testimonial['co_curricular'] : "No specific co-curricular activities were noted.";
    $principal_comment    = (!empty($testimonial['principal_comment'])) ? $testimonial['principal_comment'] : "The student has been a model student throughout their time at the school.";


    // Pronouns
    $gender     = strtolower($student['gender'] ?? 'male');
    $pronoun    = ($gender === 'female') ? 'She' : 'He';
    $possessive = ($gender === 'female') ? 'her' : 'his';

    // Photo
    $photo = "studentimg/" . str_replace('/', '_', $student_id) . ".jpg";
    if (!file_exists($photo)) {
        $photo = "studentimg/default.jpg";
    }

    // Define $student_name_bold and assign to PDF object here, before PDF creation
    $student_name_bold = $student['name']; // Changed to use original case from $student['name']
    $current_student_year = 0;
    if (isset($student['session']) && preg_match('/^(\d{4})/', $student['session'], $matches)) {
        $current_student_year = (int)$matches[1];
    }
    $start_session = $current_student_year - 6;
    $end_session = $current_student_year - 5;

    // -----------------------------
    // PDF Generator
    // -----------------------------
    class MyPDF extends FPDF
    {
        public $studentImage;
        public $studentNameBold;
        public $studentId; // Add this line

        function Header()
        {

            // Decorative gold border
            for ($i = 0; $i < 4; $i++) {
                $this->SetDrawColor(90 - ($i * 40), 174 - ($i * 30), 255);
                $this->SetLineWidth(0.5 + $i * 0.2);
                $this->Rect(5 + $i, 8 + $i, $this->GetPageWidth() - 10 - ($i * 2), $this->GetPageHeight() - 16 - ($i * 2));
            }

            // Logo
            $this->Image('assets/img/logo.png', 10, 12, 35);

            // School details centered
            $this->SetFont('Arial', 'B', 22);
            $this->SetXY(60, 15);
            $this->MultiCell($this->GetPageWidth() - 120, 15, "HAPA COLLEGE", 0, 'C');

            $this->SetFont('Arial', '', 12);
            $this->SetXY(60, 26);
            $this->MultiCell(
                $this->GetPageWidth() - 120,
                6,
                "KM 3, Akure Owo Express Road, Oba Ile,\nAkure, Ondo State, Nigeria. \n +234-803-504-2727, +234-803-883-8583\nhapacollege2013@yahoo.com",
                0,
                'C'
            );

            // Student Photo
            if (!empty($this->studentImage) && file_exists($this->studentImage)) {
                $this->Image($this->studentImage, $this->GetPageWidth() - 40, 15, 25);
            }

            $this->Ln(15);
        }

        function Footer()
        {

            // Seal at center bottom
            if (file_exists('assets/img/seal.jpg')) {
                $w = 40;
                $h = 40;
                $x = ($this->GetPageWidth() / 2) - ($w / 2);
                $y = $this->GetPageHeight() - 65;
                $this->Image('assets/img/seal.jpg', $x, $y, $w, $h);
            }

            // QR Code at bottom left, on the same line as the seal
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $base_url = $protocol . '://' . $_SERVER['HTTP_HOST'];
            $qr_code_text = "This testimonial is an authenticated academic document issued to " . $this->studentNameBold . ". Its authenticity and legal status can be verified through " . $base_url . "/verify.php?student_id=" . $this->studentId . "&type=testimonial";

            $qr_file_path = 'temp_qr_' . md5($qr_code_text) . '.png'; // Temporary file name

            // Generate QR code and save it to a temporary file
            QRcode::png($qr_code_text, $qr_file_path, QR_ECLEVEL_L, 4, 2);

            $qr_w = 25; // QR code width
            $qr_h = 25; // QR code height
            $qr_x = 10; // Left margin
            $qr_y = $this->GetPageHeight() - 65 + (($h - $qr_h) / 2); // Align vertically with the seal's center

            $this->Image($qr_file_path, $qr_x, $qr_y, $qr_w, $qr_h, 'PNG');

            // Delete the temporary QR code file after use
            if (file_exists($qr_file_path)) {
                unlink($qr_file_path);
            }

            // Footer text
            $this->SetY(-25);
            $this->SetFont('Arial', 'I', 9);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 5, "God First", 0, 1, 'C');
            // $this->Cell(0, 5, "+234-813-772-6887 • enquiries@dinolabstech.com • www.dinolabstech.com", 0, 0, 'C');
        }
    }

    // Start PDF
    $pdf = new MyPDF();
    $pdf->studentImage = $photo;
    $pdf->studentNameBold = $student_name_bold; // Assign student name
    $pdf->studentId = $student_id; // Assign student ID
    $pdf->AddPage();

    // Title
    $pdf->SetFont('Arial', 'B', 22);
    $pdf->SetTextColor(204, 153, 0);
    $pdf->Cell(0, 12, 'Testimonial / Completion Certificate', 0, 1, 'C');
    $pdf->Ln(5);

    // -----------------------------
    // ⭐ PRESTIGE TESTIMONIAL BLOCK
    // -----------------------------
    $pdf->SetFont('Arial', '', 13);
    $pdf->SetTextColor(40, 40, 40);


    // main narrative
    $statement  = "$student_name_bold, {$student['gender']}, born on {$student['dob']}, ";
    $statement .= "and a native of {$student['state']}, {$student['lga']}, was a student of HAPA COLLEGE ";
    $statement .= "from {$start_session}/{$end_session} to {$student['session']} academic session. During this period, {$pronoun} maintained an excellent academic and moral record.\n\n";
    $pdf->MultiCell(0, 9, $statement);
    $pdf->Ln(2);

    // Subjects Offered
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 7, "Subjects Offered:", 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 7, $subjects_offered);
    $pdf->Ln(2);

    // Academic Ability & Prizes Won
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(95, 7, "Academic Ability:", 0, 0, 'L');
    $pdf->Cell(95, 7, "Prizes Won:", 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $y_start_block1 = $pdf->GetY();
    $pdf->MultiCell(95, 7, $academic_ability, 0, 'L');
    $y_after_academic = $pdf->GetY();

    $pdf->SetXY(10 + 95, $y_start_block1); // 10 is the left margin
    $pdf->MultiCell(95, 7, $prizes_won, 0, 'L');
    $y_after_prizes = $pdf->GetY();
    $pdf->SetY(max($y_after_academic, $y_after_prizes));
    $pdf->Ln(2);

    // Character Assessment & Leadership / Office Held
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(95, 7, "Character Assessment:", 0, 0, 'L');
    $pdf->Cell(95, 7, "Leadership / Office Held:", 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $y_start_block2 = $pdf->GetY();
    $pdf->MultiCell(95, 7, $character_assessment, 0, 'L');
    $y_after_char = $pdf->GetY();

    $pdf->SetXY(10 + 95, $y_start_block2); // 10 is the left margin
    $pdf->MultiCell(95, 7, $leadership_position, 0, 'L');
    $y_after_leadership = $pdf->GetY();
    $pdf->SetY(max($y_after_char, $y_after_leadership));
    $pdf->Ln(2);

    // Co-curricular Activities
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 7, "Co-curricular Activities:", 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 7, $co_curricular);
    $pdf->Ln(2);

    // Principal’s Comment
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 7, "Principal's Comment:", 0, 1);
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->MultiCell(0, 8, $principal_comment);
    $pdf->Ln(15);

    // Signature
    $pdf->SetFont('Arial', 'B', 12);
    if (file_exists('assets/img/signature.jpg')) {
        $pdf->Image('assets/img/signature.jpg', $pdf->GetPageWidth() - 60, $pdf->GetY(), 40);
    }
    $pdf->Ln(15);
    $pdf->Cell(0, 6, "Principal's Signature", 0, 1, 'R');

    // Date
    $pdf->Ln(1);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 6, "Date: " . date('d/m/Y'), 0, 1, 'R');

    $pdf->Output();
    exit();
} else {
    echo "<div style='text-align:center;margin-top:50px;font-family:sans-serif;'>
          <h1>No Student ID Provided</h1>
          <p>Please select a student to generate the testimonial.</p>
          <a href='alumni_list.php' style='padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;'>Go Back</a>
          </div>";
    exit();
}
