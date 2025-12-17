<?php
// helpers/exports.php
// Data export for Bursary module (CSV, XLSX, PDF)

include('./db_connection.php');
require_once('pdf.php');

global $conn;
$mysqli = $conn; // Use the existing connection variable

/**
 * Export array data to CSV file
 * @param array $data Array of associative rows
 * @param string $output_path
 * @return bool
 */
function export_csv($data, $output_path) {
    $fp = fopen($output_path, 'w');
    if (!$fp) return false;
    if (count($data) > 0) {
        fputcsv($fp, array_keys($data[0]));
        foreach ($data as $row) {
            fputcsv($fp, $row);
        }
    }
    fclose($fp);
    return true;
}

/**
 * Export array data to XLSX file (simple, using CSV fallback)
 * @param array $data
 * @param string $output_path
 * @return bool
 */
function export_xlsx($data, $output_path) {
    // For simplicity, write as CSV with .xlsx extension
    return export_csv($data, $output_path);
}

/**
 * Export array data to PDF file (tabular)
 * @param array $data
 * @param string $output_path
 * @param string $title
 * @return bool
 */
function export_pdf($data, $output_path, $title = 'Export') {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, $title, 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);

    // Table header
    if (count($data) > 0) {
        foreach (array_keys($data[0]) as $col) {
            $pdf->Cell(40, 8, $col, 1);
        }
        $pdf->Ln();
        foreach ($data as $row) {
            foreach ($row as $cell) {
                $pdf->Cell(40, 8, (string)$cell, 1);
            }
            $pdf->Ln();
        }
    } else {
        $pdf->Cell(0, 10, 'No data.', 0, 1, 'C');
    }
    return $pdf->Output('F', $output_path);
}

/**
 * Stub for async/background export (to be implemented with worker/cron)
 * @param string $type
 * @param array $data
 * @param string $output_path
 * @return bool
 */
function export_async($type, $data, $output_path) {
    // For now, just call sync export
    switch ($type) {
        case 'csv': return export_csv($data, $output_path);
        case 'xlsx': return export_xlsx($data, $output_path);
        case 'pdf': return export_pdf($data, $output_path, 'Export');
        default: return false;
    }
}
?>
