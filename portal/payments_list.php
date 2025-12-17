<?php
include('components/admin_logic.php');
require_once('helpers/audit.php');
require_once('helpers/money.php');
require_once('helpers/exports.php');

$alerts = [];
$filters = [
  'student' => $_GET['student'] ?? ''
];
$where = [];
$params = [];
$types = '';
if ($filters['student'] !== '') {
  if (is_numeric($filters['student'])) {
    $where[] = "p.student_id = ?";
    $params[] = $filters['student'];
    $types .= 'i';
  } else {
    $where[] = "s.name LIKE ?";
    $params[] = '%' . $filters['student'] . '%';
    $types .= 's';
  }
}
$sql = "SELECT p.id, p.student_id, s.name AS student_name, p.amount, p.payment_method, p.payment_date, p.receipt_number, p.paid_by, p.bank_from, p.bank_to, p.transfer_mode, p.transfer_id, p.paid_for, p.discount, p.total_paid_term, p.balance_term, p.tuckshop_deposit, p.term, p.session FROM payments p JOIN students s ON p.student_id = s.id";
if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY payment_date DESC";
$stmt = $mysqli->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();
$payments = [];
while ($row = $res->fetch_assoc()) {
  $row['amount_display'] = money_format_naira($row['amount']);
  $payments[] = $row;
}
$stmt->close();

// Export
if (isset($_GET['export']) && in_array($_GET['export'], ['csv', 'pdf'])) {
  $type = $_GET['export'];
  $filename = "payments_" . date('Ymd_His') . ".$type";
  switch ($type) {
    case 'csv':
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="' . $filename . '"');
      $fp = fopen('php://output', 'w');
      if (count($payments) > 0) {
        fputcsv($fp, array_keys($payments[0]));
        foreach ($payments as $row) {
          fputcsv($fp, $row);
        }
      }
      fclose($fp);
      exit;
    case 'pdf':
      require_once('helpers/pdf.php');
      $pdf = new FPDF('L'); // Landscape
      $pdf->AddPage();
      $pdf->SetFont('Arial', 'B', 14);
      $pdf->Cell(0, 10, 'Payments List', 0, 1, 'C');
      $pdf->SetFont('Arial', '', 10);
      if (count($payments) > 0) {
        $columns = array_keys($payments[0]);
        // Custom widths for each of the 20 columns (adjust as needed)
        $columnWidths = [15, 20, 40, 25, 30, 35, 25, 30, 35, 35, 30, 30, 35, 25, 30, 30, 30, 20, 25, 25];
        // Headers
        foreach ($columns as $i => $col) {
          $pdf->Cell($columnWidths[$i], 8, $col, 1);
        }
        $pdf->Ln();
        // Data rows
        foreach ($payments as $row) {
          foreach ($columns as $i => $col) {
            $pdf->Cell($columnWidths[$i], 8, (string)$row[$col], 1);
          }
          $pdf->Ln();
        }
      } else {
        $pdf->Cell(0, 10, 'No data.', 0, 1, 'C');
      }
      $pdf->Output('D', $filename);
      exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>
</head>

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
              <h3 class="fw-bold mb-3">Payments List</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Payments List</li>
              </ol>
              <?php foreach ($alerts as [$type, $msg]): ?>
                <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <form method="get" class="row g-2">
                  <div class="col-md-3"><input type="text" name="student" value="<?= htmlspecialchars($filters['student']) ?>" class="form-control" placeholder="Student ID or Name"></div>
                  <div class="col-md-1"><button class="btn btn-primary btn-icon btn-round rounded-5 pt-1" type="submit"><i class="fa fa-filter"></i></button></div>
                  <div class="col-md-8 text-end">
                    <a href="?export=csv" class="btn btn-secondary btn-sm rounded-5"><i class="fas fa-file-excel"></i> Export CSV</a>
                    <a href="?export=pdf" class="btn btn-secondary btn-sm rounded-5 d-none"><i class="fas fa-file-pdf"></i> Export PDF</a>
                  </div>
                </form>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="basic-datatables" class="table table-bordered table-hover bg-white">
                    <thead class="table-light">
                      <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Paid By</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Term</th>
                        <th>Session</th>
                        <th>Bank From</th>
                        <th>Bank To</th>
                        <th>Transfer Mode</th>
                        <th>Transfer ID</th>
                        <th>Receipt</th>
                        <th>Paid For</th>
                        <th>Discount</th>
                        <th>Total Paid Term</th>
                        <th>Balance Term</th>
                        <th>Tuckshop Deposit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($payments as $p): ?>
                        <tr>
                          <td><?= $p['id'] ?></td>
                          <td><?= htmlspecialchars($p['student_name']) ?></td>
                          <td><?= $p['amount_display'] ?></td>
                          <td><?= htmlspecialchars($p['paid_by']) ?></td>
                          <td><?= htmlspecialchars($p['payment_method']) ?></td>
                          <td><?= htmlspecialchars($p['payment_date']) ?></td>
                          <td><?= htmlspecialchars($p['term']) ?></td>
                          <td><?= htmlspecialchars($p['session']) ?></td>
                          <td><?= htmlspecialchars($p['bank_from']) ?></td>
                          <td><?= htmlspecialchars($p['bank_to']) ?></td>
                          <td><?= htmlspecialchars($p['transfer_mode']) ?></td>
                          <td><?= htmlspecialchars($p['transfer_id']) ?></td>
                          <td><?= htmlspecialchars($p['receipt_number']) ?></td>
                          <td><?= htmlspecialchars($p['paid_for']) ?></td>
                          <td><?= money_format_naira($p['discount']) ?></td>
                          <td><?= money_format_naira($p['total_paid_term']) ?></td>
                          <td><?= money_format_naira($p['balance_term']) ?></td>
                          <td><?= money_format_naira($p['tuckshop_deposit']) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
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
