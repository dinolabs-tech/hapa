<?php
include('components/admin_logic.php');

require_once('db_connection.php');
require_once('helpers/audit.php');
require_once('helpers/money.php');
require_once('helpers/exports.php');

$alerts = [];
$action = $_POST['action'] ?? null;

// Fetch dropdown options
$classes = [];
$result = $mysqli->query("SELECT DISTINCT class FROM class ORDER BY class");
while ($row = $result->fetch_assoc()) {
  $classes[] = $row['class'];
}

$arms = [];
$result = $mysqli->query("SELECT DISTINCT arm FROM arm ORDER BY arm");
while ($row = $result->fetch_assoc()) {
  $arms[] = $row['arm'];
}

$sessions = [];
$result = $mysqli->query("SELECT DISTINCT csession FROM currentsession WHERE id = 1 ORDER BY csession");
while ($row = $result->fetch_assoc()) {
  $sessions[] = $row['csession'];
}

$hostels = [];
$result = $mysqli->query("SELECT DISTINCT hostel FROM students ORDER BY hostel");
while ($row = $result->fetch_assoc()) {
  $hostels[] = $row['hostel'];
}

$terms = ['1st Term', '2nd Term', '3rd Term'];

// Fetch fee items for structure name dropdown
$fee_items = [];
$result = $mysqli->query("SELECT id, name FROM fee_items ORDER BY name");
while ($row = $result->fetch_assoc()) {
  $fee_items[] = $row;
}

// Handle create
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $class = trim($_POST['class'] ?? '');
  $arm = trim($_POST['arm'] ?? '');
  $term = trim($_POST['term'] ?? '');
  $session = trim($_POST['session'] ?? '');
  $hostel_type = trim($_POST['hostel_type'] ?? '');
  if ($name === '' || $class === '' || $arm === '' || $term === '' || $session === '') {
    $alerts[] = ['danger', 'All fields except hostel type are required.'];
  } else {
    $stmt = $mysqli->prepare("INSERT INTO fee_structures (name, class, arm, term, session, hostel_type) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssss', $name, $class, $arm, $term, $session, $hostel_type);
    $before = null;
    $after = compact('name', 'class', 'arm', 'term', 'session', 'hostel_type');
    if ($stmt->execute()) {
      audit_log('create', 'fee_structure', $stmt->insert_id, $before, $after);
      $alerts[] = ['success', 'Fee structure created.'];
    } else {
      $alerts[] = ['danger', 'Error creating fee structure.'];
    }
    $stmt->close();
  }
}

// Handle update
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id'] ?? 0);
  $name = trim($_POST['name'] ?? '');
  $class = trim($_POST['class'] ?? '');
  $arm = trim($_POST['arm'] ?? '');
  $term = trim($_POST['term'] ?? '');
  $session = trim($_POST['session'] ?? '');
  $hostel_type = trim($_POST['hostel_type'] ?? '');
  $stmt = $mysqli->prepare("SELECT * FROM fee_structures WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $before = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if ($before) {
    $stmt = $mysqli->prepare("UPDATE fee_structures SET name=?, class=?, arm=?, term=?, session=?, hostel_type=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param('ssssssi', $name, $class, $arm, $term, $session, $hostel_type, $id);
    $after = compact('name', 'class', 'arm', 'term', 'session', 'hostel_type');
    if ($stmt->execute()) {
      audit_log('update', 'fee_structure', $id, $before, $after);
      $alerts[] = ['success', 'Fee structure updated.'];
    } else {
      $alerts[] = ['danger', 'Error updating fee structure.'];
    }
    $stmt->close();
  } else {
    $alerts[] = ['danger', 'Fee structure not found.'];
  }
}

// Handle delete
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id'] ?? 0);
  $stmt = $mysqli->prepare("SELECT * FROM fee_structures WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $before = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if ($before) {
    // Check if fee structure has been assigned to any students
    $stmt = $mysqli->prepare("SELECT COUNT(*) as assignment_count FROM student_fees WHERE fee_structure_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($assignment_count);
    $stmt->fetch();
    $stmt->close();

    if ($assignment_count > 0) {
      // Fee structure has been assigned to students, prevent deletion
      $alerts[] = ['danger', "Cannot delete fee structure '{$before['name']}' because it has been assigned to {$assignment_count} student(s). Remove all assignments before deleting."];
      // Log the blocked deletion attempt
      audit_log('delete_blocked', 'fee_structure', $id, $before, ['reason' => 'assigned_to_students', 'assignment_count' => $assignment_count]);
    } else {
      // No assignments found, safe to delete
      $stmt = $mysqli->prepare("DELETE FROM fee_structures WHERE id=?");
      $stmt->bind_param('i', $id);
      if ($stmt->execute()) {
        audit_log('delete', 'fee_structure', $id, $before, null);
        $alerts[] = ['success', 'Fee structure deleted successfully.'];
      } else {
        $alerts[] = ['danger', 'Error deleting fee structure.'];
      }
      $stmt->close();
    }
  } else {
    $alerts[] = ['danger', 'Fee structure not found.'];
  }
}

// Export
if (isset($_GET['export']) && in_array($_GET['export'], ['csv', 'pdf'])) {
  $result = $mysqli->query("SELECT id, name, class, arm, term, session, hostel_type, total_amount, created_at FROM fee_structures");
  $data = [];
  while ($row = $result->fetch_assoc()) {
    $row['total_amount'] = money_format_naira($row['total_amount']);
    $data[] = $row;
  }
  $type = $_GET['export'];
  $filename = "fee_structures_" . date('Ymd_His') . ".$type";
  switch ($type) {
    case 'csv':
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="' . $filename . '"');
      $fp = fopen('php://output', 'w');
      if (count($data) > 0) {
        fputcsv($fp, array_keys($data[0]));
        foreach ($data as $row) {
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
      $pdf->Cell(0, 10, 'Fee Structures', 0, 1, 'C');
      $pdf->SetFont('Arial', '', 10);
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
      $pdf->Output('D', $filename);
      exit;
  }
}

// Fetch fee structures with assignment status
$structures = [];
$result = $mysqli->query("
  SELECT fs.id, fs.name, fs.class, fs.arm, fs.term, fs.session, fs.hostel_type, fs.total_amount, fs.created_at,
         (SELECT COUNT(*) FROM student_fees sf WHERE sf.fee_structure_id = fs.id) as assignment_count
  FROM fee_structures fs 
  ORDER BY fs.id DESC
");
while ($row = $result->fetch_assoc()) {
  $row['total_amount_display'] = money_format_naira($row['total_amount']);
  $structures[] = $row;
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
              <h3 class="fw-bold mb-3">Fee Structure</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Fee Structure</li>
              </ol>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Fee Structure</h4>
                <div class="card-subtitle text-muted mb-3">
                  <small>
                    <i class="fas fa-lock text-danger me-2"></i>Locked fee structures cannot be deleted because they have been assigned to students
                  </small>
                </div>
              </div>
              <div class="card-body">

                <?php foreach ($alerts as [$type, $msg]): ?>
                  <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
                <?php endforeach; ?>

                <form method="post" class="row g-2 mb-4">
                  <input type="hidden" name="action" value="create">
                  <div class="col-md-2">
                    <select name="name" class="form-select form-control" required>
                      <option value="" selected disabled>Select Name</option>
                      <?php foreach ($fee_items as $fi): ?>
                        <option value="<?= htmlspecialchars($fi['name']) ?>"><?= htmlspecialchars($fi['name']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <select name="class" class="form-select form-control" required>
                      <option value="" selected disabled>Select Class</option>
                      <?php foreach ($classes as $c): ?>
                        <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <select name="arm" class="form-select form-control" required>
                      <option value="" selected disabled>Select Arm</option>
                      <?php foreach ($arms as $a): ?>
                        <option value="<?= htmlspecialchars($a) ?>"><?= htmlspecialchars($a) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <select name="term" class="form-select form-control" required>
                      <option value="" selected disabled>Select Term</option>
                      <?php foreach ($terms as $t): ?>
                        <option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars($t) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <select name="session" class="form-select form-control" required>
                      <option value="" selected disabled>Select Session</option>
                      <?php foreach ($sessions as $s): ?>
                        <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <select name="hostel_type" class="form-select form-control">
                      <option value="" selected disabled>Select Hostel</option>
                      <?php foreach ($hostels as $h): ?>
                        <option value="<?= htmlspecialchars($h) ?>"><?= htmlspecialchars($h) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-12 text-center mt-3">
                    <button class="btn btn-primary rounded-5" type="submit">Add</button>
                  </div>
                </form>

              </div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Fee Structure</h4>
              </div>
              <div class="card-body">

                <div class="table-responsive">

                  <table class="table table-bordered table-striped table-hover bg-white" id="basic-datatables">
                    <thead class="table-light">
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Arm</th>
                        <th>Term</th>
                        <th>Session</th>
                        <th>Hostel</th>
                        <th>Total</th>
                        <th>Created</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($structures as $s): ?>
                        <tr>
                          <form method="post">
                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
                            <td><?= $s['id'] ?></td>
                            <td style="min-width: 200px;"><input type="text" name="name" value="<?= htmlspecialchars($s['name']) ?>" class="form-control" required></td>
                            <td style="min-width: 100px;"><input type="text" name="class" value="<?= htmlspecialchars($s['class']) ?>" class="form-control" required></td>
                            <td style="min-width:50px;"><input type="text" name="arm" value="<?= htmlspecialchars($s['arm']) ?>" class="form-control" required></td>
                            <td style="min-width: 100px;"><input type="text" name="term" value="<?= htmlspecialchars($s['term']) ?>" class="form-control" required></td>
                            <td style="min-width: 100px;"><input type="text" name="session" value="<?= htmlspecialchars($s['session']) ?>" class="form-control" required></td>
                            <td><input type="text" name="hostel_type" value="<?= htmlspecialchars($s['hostel_type']) ?>" class="form-control"></td>
                            <td><?= $s['total_amount_display'] ?></td>
                            <td><?= htmlspecialchars($s['created_at']) ?></td>
                            <td class="d-flex gap-1">
                              <button name="action" value="update" class="btn btn-sm btn-success rounded-5">Update</button>
                              <?php if ($s['assignment_count'] > 0): ?>
                                <button class="btn btn-sm btn-danger rounded-5 disabled" disabled title="Cannot delete: assigned to <?= $s['assignment_count'] ?> student(s)" style="cursor: not-allowed; opacity: 0.6;">
                                  <i class="fas fa-lock me-1"></i>Delete
                                </button>
                              <?php else: ?>
                                <button name="action" value="delete" class="btn btn-sm btn-danger rounded-5" onclick="return confirm('Delete this fee structure?')">Delete</button>
                              <?php endif; ?>
                              <a href="fee_structure_edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-info rounded-5">Edit Items</a>
                            </td>
                          </form>
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