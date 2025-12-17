<?php
include('components/admin_logic.php');
require_once('helpers/audit.php');
require_once('helpers/money.php');
require_once('helpers/exports.php');


$alerts = [];
$filters = [
  'student_id' => $_GET['student_id'] ?? '',
  'type' => $_GET['type'] ?? '',
  'date_from' => $_GET['date_from'] ?? '',
  'date_to' => $_GET['date_to'] ?? ''
];
$where = [];
$params = [];
$types = '';
if ($filters['student_id'] !== '') {
  $where[] = "t.student_id = ?";
  $params[] = $filters['student_id'];
  $types .= 'i';
}
if ($filters['type'] !== '') {
  $where[] = "t.type = ?";
  $params[] = $filters['type'];
  $types .= 's';
}
if ($filters['date_from'] !== '') {
  $where[] = "t.created_at >= ?";
  $params[] = $filters['date_from'] . ' 00:00:00';
  $types .= 's';
}
if ($filters['date_to'] !== '') {
  $where[] = "t.created_at <= ?";
  $params[] = $filters['date_to'] . ' 23:59:59';
  $types .= 's';
}
$sql = "SELECT t.id, t.student_id, s.name AS student_name, t.type, t.amount, t.reference, t.created_at FROM transactions t JOIN students s ON t.student_id = s.id";
if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY t.created_at DESC";
$stmt = $mysqli->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();
$transactions = [];
$total_amount = 0;
while ($row = $res->fetch_assoc()) {
  $row['amount_display'] = money_format_naira($row['amount']);
  $transactions[] = $row;
  $total_amount += $row['amount'];
}
$stmt->close();

// Export
if (isset($_GET['export']) && in_array($_GET['export'], ['csv', 'pdf'])) {
  $type = $_GET['export'];
  $filename = "transactions_report_" . date('Ymd_His') . ".$type";
  switch ($type) {
    case 'csv':
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="' . $filename . '"');
      $fp = fopen('php://output', 'w');
      if (count($transactions) > 0) {
        fputcsv($fp, array_keys($transactions[0]));
        foreach ($transactions as $row) {
          fputcsv($fp, $row);
        }
      }
      fclose($fp);
      exit;
    case 'pdf':
      require_once(__DIR__ . '/helpers/pdf.php');
      $pdf = new FPDF('L'); // Landscape
      $pdf->AddPage();
      $pdf->SetFont('Arial', 'B', 14);
      $pdf->Cell(0, 10, 'Transactions Report', 0, 1, 'C');
      $pdf->SetFont('Arial', '', 10);
      if (count($transactions) > 0) {
        foreach (array_keys($transactions[0]) as $col) {
          $pdf->Cell(40, 8, $col, 1);
        }
        $pdf->Ln();
        foreach ($transactions as $row) {
          foreach ($row as $cell) {
            $pdf->Cell(40, 8, (string)$cell, 1);
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
              <h3 class="fw-bold mb-3">Transactions Report</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Transactions Report</li>
              </ol>
              <?php foreach ($alerts as [$type, $msg]): ?>
                <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <form method="get" class="row g-2 mb-4">
                <div class="col-md-2"><input type="text" name="student_id" value="<?= htmlspecialchars($filters['student_id']) ?>" class="form-control" placeholder="Student ID"></div>
                <div class="col-md-2">
                  <select name="type" class="form-control form-select">
                    <option value="">All Types</option>
                    <option value="payment" <?= $filters['type'] === 'payment' ? 'selected' : '' ?>>Payment</option>
                    <option value="refund" <?= $filters['type'] === 'refund' ? 'selected' : '' ?>>Refund</option>
                    <option value="carryover" <?= $filters['type'] === 'carryover' ? 'selected' : '' ?>>Carryover</option>
                    <option value="adjustment" <?= $filters['type'] === 'adjustment' ? 'selected' : '' ?>>Adjustment</option>
                  </select>
                </div>
                <div class="col-md-2"><input type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from']) ?>" class="form-control" placeholder="From Date"></div>
                <div class="col-md-2"><input type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to']) ?>" class="form-control" placeholder="To Date"></div>
                <div class="col-md-1"><button class="btn btn-primary btn-icon btn-round" type="submit"><i class="fas fa-filter"></i></button></div>
                <div class="col-md-3">
                  <a href="?export=csv" class="btn btn-secondary btn-sm rounded-5"><i class="fas fa-file-excel"></i> Export CSV</a>
                  <a href="?export=pdf" class="btn btn-secondary btn-sm rounded-5 d-none"><i class="fas fa-file-pdf"></i> Export PDF</a>
                </div>
              </form>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">
                  <strong>Total Amount: <?= money_format_naira($total_amount) ?></strong>
                </h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="basic-datatables" class="table table-striped table-bordered table-hover bg-white">
                    <thead class="table-light">
                      <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Reference</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($transactions as $t): ?>
                        <tr>
                          <td><?= $t['id'] ?></td>
                          <td><?= htmlspecialchars($t['student_name']) ?></td>
                          <td><?= htmlspecialchars($t['type']) ?></td>
                          <td><?= $t['amount_display'] ?></td>
                          <td><?= htmlspecialchars($t['reference']) ?></td>
                          <td><?= htmlspecialchars($t['created_at']) ?></td>
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