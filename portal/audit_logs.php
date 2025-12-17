<?php
include('components/admin_logic.php');
require_once('helpers/audit.php');
require_once('helpers/exports.php');

$alerts = [];

// Fetch dropdown options
$usernames = [];
$result = $mysqli->query("SELECT DISTINCT username FROM login WHERE role != 'Superuser' ORDER BY username");
while ($row = $result->fetch_assoc()) {
  $usernames[] = $row['username'];
}

$actions = [];
$result = $mysqli->query("SELECT DISTINCT action FROM audit_logs ORDER BY action");
while ($row = $result->fetch_assoc()) {
  $actions[] = $row['action'];
}

$filters = [
  'username' => $_GET['username'] ?? '',
  'action' => $_GET['action'] ?? '',
  'date_from' => $_GET['date_from'] ?? '',
  'date_to' => $_GET['date_to'] ?? ''
];
$where = [];
$params = [];
$types = '';
if ($filters['username'] !== '') {
  $where[] = "u.username = ?";
  $params[] = $filters['username'];
  $types .= 's';
}
if ($filters['action'] !== '') {
  $where[] = "a.action = ?";
  $params[] = $filters['action'];
  $types .= 's';
}
if ($filters['date_from'] !== '') {
  $where[] = "a.timestamp >= ?";
  $params[] = $filters['date_from'] . ' 00:00:00';
  $types .= 's';
}
if ($filters['date_to'] !== '') {
  $where[] = "a.timestamp <= ?";
  $params[] = $filters['date_to'] . ' 23:59:59';
  $types .= 's';
}
$sql = "SELECT a.id, a.user_id, u.username, a.action, a.object_type, a.object_id, a.timestamp, a.ip, s.name AS object_name FROM audit_logs a LEFT JOIN login u ON a.user_id = u.id LEFT JOIN students s ON a.object_type = 'student' AND a.object_id = s.id";
if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY a.timestamp DESC LIMIT 1000";
$stmt = $mysqli->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();
$logs = [];
while ($row = $res->fetch_assoc()) {
  $logs[] = $row;
}
$stmt->close();

// Export
if (isset($_GET['export']) && in_array($_GET['export'], ['csv', 'pdf'])) {
  $type = $_GET['export'];
  $filename = "audit_logs_" . date('Ymd_His') . ".$type";
  switch ($type) {
    case 'csv':
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="' . $filename . '"');
      $fp = fopen('php://output', 'w');
      if (count($logs) > 0) {
        fputcsv($fp, array_keys($logs[0]));
        foreach ($logs as $row) {
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
      $pdf->Cell(0, 10, 'Audit Logs', 0, 1, 'C');
      $pdf->SetFont('Arial', '', 10);
      if (count($logs) > 0) {
        foreach (array_keys($logs[0]) as $col) {
          $pdf->Cell(40, 8, $col, 1);
        }
        $pdf->Ln();
        foreach ($logs as $row) {
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
              <h3 class="fw-bold mb-3">Audit Logs</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Audit Logs</li>
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
                  <select name="username" class="form-control form-select">
                    <option value="">All Users</option>
                    <?php foreach ($usernames as $u): ?>
                      <option value="<?= htmlspecialchars($u) ?>" <?= $filters['username'] === $u ? 'selected' : '' ?>><?= htmlspecialchars($u) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <select name="action" class="form-control form-select">
                    <option value="">All Actions</option>
                    <?php foreach ($actions as $a): ?>
                      <option value="<?= htmlspecialchars($a) ?>" <?= $filters['action'] === $a ? 'selected' : '' ?>><?= htmlspecialchars($a) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-2"><input type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from']) ?>" class="form-control"></div>
                <div class="col-md-2"><input type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to']) ?>" class="form-control"></div>
                <div class="col-md-4 gx-3">
                  <button class="btn btn-primary btn-icon btn-round" type="submit"><i class="fas fa-filter"></i></button>
                  <a href="?export=csv" class="btn btn-secondary btn-sm rounded-5"><i class="fas fa-file-excel"></i> Export CSV</a>
                  <a href="?export=pdf" class="btn btn-secondary btn-sm rounded-5 d-none"><i class="fas fa-file-pdf"></i> Export PDF</a>
                </div>
              </form>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="table-reponsive">
                  <table id="basic-datatables" class="table table-striped table-bordered table-hover bg-white">
                    <thead class="table-light">
                      <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Object Type</th>
                        <th>Timestamp</th>
                        <th>IP</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($logs as $log): ?>
                        <tr>
                          <td><?= $log['id'] ?></td>
                          <td><?= htmlspecialchars($log['username'] ?? 'System') ?></td>
                          <td><?= htmlspecialchars($log['action']) ?></td>
                          <td><?= htmlspecialchars($log['object_type']) ?></td>
                          <td><?= htmlspecialchars($log['timestamp']) ?></td>
                          <td><?= htmlspecialchars($log['ip']) ?></td>
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