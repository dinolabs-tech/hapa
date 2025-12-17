<?php
// helpers/pdf.php
// PDF generation for Bursary module receipts and exports

include('./db_connection.php');
require_once('./includes/fpdf.php');

global $conn;
$mysqli = $conn; // Use the existing connection variable

/**
 * Generate PDF receipt for payment
 * @param array $payment Payment details (student, amount, method, date, receipt_number, items)
 * @param string $session Academic session
 * @param int $seq Sequence number for receipt
 * @param string $output_path Where to save PDF
 * @return bool Success
 */
function pdf_generate_receipt($payment, $session, $seq, $output_path) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Header
    $pdf->Cell(0, 10, 'School Payment Receipt', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 8, 'Receipt No: SCH/' . date('y') . "/$session/REC/$seq", 0, 1, 'C');
    $pdf->Ln(4);

    // Student/payment info
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 8, 'Student: ' . htmlspecialchars($payment['student_name']), 0, 1);
    $pdf->Cell(0, 8, 'Class: ' . htmlspecialchars($payment['class']), 0, 1);
    $pdf->Cell(0, 8, 'Date: ' . htmlspecialchars($payment['payment_date']), 0, 1);
    $pdf->Cell(0, 8, 'Method: ' . htmlspecialchars($payment['payment_method']), 0, 1);
    $pdf->Cell(0, 8, 'Amount: ' . htmlspecialchars($payment['amount_display']), 0, 1);
    $pdf->Ln(4);

    // Fee items
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 8, 'Allocated Items:', 0, 1);
    $pdf->SetFont('Arial', '', 10);
    foreach ($payment['items'] as $item) {
        $pdf->Cell(0, 7, $item['name'] . ' - ' . $item['amount_display'], 0, 1);
    }

    // Footer
    $pdf->Ln(8);
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Cell(0, 8, 'Thank you for your payment.', 0, 1, 'C');
    $pdf->Cell(0, 8, 'Generated: ' . date('Y-m-d H:i:s'), 0, 1, 'C');

    // Output
    return $pdf->Output('F', $output_path);
}
?>
