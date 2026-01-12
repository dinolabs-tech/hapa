<?php
include('components/admin_logic.php');
require_once('helpers/audit.php');
require_once('helpers/money.php');
require_once('helpers/exports.php');

$alerts = [];

// Fetch dropdown options
$classes = [];
$result = $mysqli->query("SELECT DISTINCT class FROM students ORDER BY class");
while ($row = $result->fetch_assoc()) {
  $classes[] = $row['class'];
}

$arms = [];
$result = $mysqli->query("SELECT DISTINCT arm FROM students ORDER BY arm");
while ($row = $result->fetch_assoc()) {
  $arms[] = $row['arm'];
}

$sessions = [];
$result = $mysqli->query("SELECT DISTINCT session FROM students ORDER BY session");
while ($row = $result->fetch_assoc()) {
  $sessions[] = $row['session'];
}

$hostels = [];
$result = $mysqli->query("SELECT DISTINCT hostel FROM students ORDER BY hostel");
while ($row = $result->fetch_assoc()) {
  $hostels[] = $row['hostel'];
}

$terms = ['1st Term', '2nd Term', '3rd Term'];
$filters = [
  'class' => $_GET['class'] ?? '',
  'arm' => $_GET['arm'] ?? '',
  'term' => $_GET['term'] ?? '',
  'session' => $_GET['session'] ?? ''
];
$where = [];
$params = [];
$types = '';
if ($filters['class'] !== '') {
  $where[] = "s.class = ?";
  $params[] = $filters['class'];
  $types .= 's';
}
if ($filters['arm'] !== '') {
  $where[] = "s.arm = ?";
  $params[] = $filters['arm'];
  $types .= 's';
}
if ($filters['term'] !== '') {
  $where[] = "s.term = ?";
  $params[] = $filters['term'];
  $types .= 's';
}
if ($filters['session'] !== '') {
  $where[] = "s.session = ?";
  $params[] = $filters['session'];
  $types .= 's';
}
$sql = "SELECT s.id, s.name, s.class, s.arm, s.term, s.session,
        SUM(sfi.amount) AS total_fees,
        SUM(sfi.paid_amount) AS total_paid
        FROM students s
        JOIN student_fees sf ON s.id = sf.student_id AND sf.status = 'active'
        JOIN student_fee_items sfi ON sf.id = sfi.student_fee_id
        WHERE sfi.amount = sfi.paid_amount";
if ($where) $sql .= " AND " . implode(' AND ', $where);
$sql .= " GROUP BY s.id ORDER BY s.name ASC";
$stmt = $mysqli->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();
$paid = [];
$total_paid = 0;
while ($row = $res->fetch_assoc()) {
  $row['total_fees_display'] = money_format_naira($row['total_fees']);
  $row['total_paid_display'] = money_format_naira($row['total_paid']);
  $paid[] = $row;
  $total_paid;
}

$stmt->close();

// Export
if (isset($_GET['export']) && in_array($_GET['export'], ['csv', 'pdf'])) {
  $type = $_GET['export'];
  $filename = "paid_report_" . date('Ymd_His') . ".$type";
  switch ($type) {
    case 'csv':
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="' . $filename . '"');
      $fp = fopen('php://output', 'w');
      if (count($paid) > 0) {
        fputcsv($fp, array_keys($paid[0]));
        foreach ($paid as $row) {
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
      $pdf->Cell(0, 10, 'Paid Report', 0, 1, 'C');
      $pdf->SetFont('Arial', '', 10);
      if (count($paid) > 0) {
        foreach (array_keys($paid[0]) as $col) {
          $pdf->Cell(40, 8, $col, 1);
        }
        $pdf->Ln();
        foreach ($paid as $row) {
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
              <h3 class="fw-bold mb-3">Paid Report</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Paid Report</li>
              </ol>
              <?php foreach ($alerts as [$type, $msg]): ?>
                <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <form method="get" class="row g-2 mb-4">
                <div class="col-md-2">
                  <select name="class" class="form-control form-select">
                    <option value="">All Classes</option>
                    <?php foreach ($classes as $c): ?>
                      <option value="<?= htmlspecialchars($c) ?>" <?= $filters['class'] === $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <select name="arm" class="form-control form-select">
                    <option value="">All Arms</option>
                    <?php foreach ($arms as $a): ?>
                      <option value="<?= htmlspecialchars($a) ?>" <?= $filters['arm'] === $a ? 'selected' : '' ?>><?= htmlspecialchars($a) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <select name="term" class="form-control form-select">
                    <option value="">All Terms</option>
                    <?php foreach ($terms as $t): ?>
                      <option value="<?= htmlspecialchars($t) ?>" <?= $filters['term'] === $t ? 'selected' : '' ?>><?= htmlspecialchars($t) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <select name="session" class="form-control form-select">
                    <option value="">All Sessions</option>
                    <?php foreach ($sessions as $s): ?>
                      <option value="<?= htmlspecialchars($s) ?>" <?= $filters['session'] === $s ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-1"><button class="btn btn-primary btn-icon btn-round" type="submit"><i class="fas fa-filter"></i></button></div>
                <div class="col-md-3 d-flex gap-1">
                  <a href="?export=csv" class="btn btn-secondary btn-sm rounded-5"><i class="fas fa-file-excel"></i> Export CSV</a>
                  <a href="?export=pdf" class="btn btn-secondary btn-sm rounded-5"><i class="fas fa-file-pdf"></i> Export PDF</a>
                </div>
              </form>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">
                  <strong>Total Paid: <?= money_format_naira($total_paid) ?></strong>
                </h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="basic-datatables" class="table table-bordered table-hover bg-white">
                    <thead class="table-light">
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Arm</th>
                        <th>Term</th>
                        <th>Session</th>
                        <th>Total Fees</th>
                        <th>Total Paid</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($paid as $p): ?>
                        <tr>
                          <td><?= $p['id'] ?></td>
                          <td><?= htmlspecialchars($p['name']) ?></td>
                          <td><?= htmlspecialchars($p['class']) ?></td>
                          <td><?= htmlspecialchars($p['arm']) ?></td>
                          <td><?= htmlspecialchars($p['term']) ?></td>
                          <td><?= htmlspecialchars($p['session']) ?></td>
                          <td><?= $p['total_fees_display'] ?></td>
                          <td><?= $p['total_paid_display'] ?></td>
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