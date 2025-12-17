<?php
include('components/admin_logic.php');
require_once('helpers/audit.php');
require_once('helpers/money.php');

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

// Fetch fee structures
$structures = [];
$res = $mysqli->query("SELECT id, name, class, arm, term, session, hostel_type FROM fee_structures ORDER BY id DESC");
while ($row = $res->fetch_assoc()) {
  $structures[] = $row;
}

// Fetch students (optionally filter by class/arm/term/session/hostel)
$filters = [
  'class' => $_GET['class'] ?? '',
  'arm' => $_GET['arm'] ?? '',
  'term' => $_GET['term'] ?? '',
  'session' => $_GET['session'] ?? '',
  'hostel' => $_GET['hostel'] ?? ''
];
$where = [];
$params = [];
$types = '';
foreach ($filters as $k => $v) {
  if ($v !== '') {
    $where[] = "$k = ?";
    $params[] = $v;
    $types .= 's';
  }
}
$sql = "SELECT id, name, class, arm, term, session, hostel FROM students";
if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY id DESC";
$stmt = $mysqli->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$students = [];
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
  $students[] = $row;
}
$stmt->close();

// Handle assign (single or bulk)
if ($action === 'assign' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $structure_id = intval($_POST['structure_id'] ?? 0);
  $student_ids = isset($_POST['student_ids']) ? $_POST['student_ids'] : [];
  if ($structure_id > 0 && count($student_ids) > 0) {
    // Get current term and session
    $current_term = $mysqli->query("SELECT cterm FROM currentterm LIMIT 1")->fetch_assoc()['cterm'] ?? '1st Term';
    $current_session = $mysqli->query("SELECT csession FROM currentsession LIMIT 1")->fetch_assoc()['csession'] ?? '2024/2025';
    $mysqli->begin_transaction();
    try {
      foreach ($student_ids as $sid) {
        $sid = $sid;
        // Check if already assigned
        $stmt = $mysqli->prepare("SELECT * FROM student_fees WHERE student_id=? AND fee_structure_id=? AND status='active'");
        $stmt->bind_param('si', $sid, $structure_id);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($exists) continue; // skip already assigned

        $stmt = $mysqli->prepare("INSERT INTO student_fees (student_id, fee_structure_id, term, session) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('siss', $sid, $structure_id, $current_term, $current_session);
        if ($stmt->execute()) {
          $sf_id = $stmt->insert_id;
          audit_log('assign_fee', 'student_fee', $sf_id, null, ['student_id' => $sid, 'fee_structure_id' => $structure_id, 'term' => $current_term, 'session' => $current_session]);
          // Assign fee items
          $items = $mysqli->query("SELECT fee_item_id, amount, mandatory FROM fee_structure_items WHERE fee_structure_id=$structure_id");
          while ($item = $items->fetch_assoc()) {
            $stmt2 = $mysqli->prepare("INSERT INTO student_fee_items (student_fee_id, fee_item_id, amount, paid_amount, carryover_flag) VALUES (?, ?, ?, 0, 0)");
            $stmt2->bind_param('iii', $sf_id, $item['fee_item_id'], $item['amount']);
            $stmt2->execute();
            $stmt2->close();
          }
        }
        $stmt->close();
      }
      $mysqli->commit();
      $alerts[] = ['success', 'Fees assigned to selected students.'];
    } catch (Exception $e) {
      $mysqli->rollback();
      $alerts[] = ['danger', 'Error assigning fees: ' . $e->getMessage()];
    }
  } else {
    $alerts[] = ['danger', 'Select fee structure and students.'];
  }
}

// Handle unassign
if ($action === 'unassign' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $student_ids = isset($_POST['student_ids']) ? $_POST['student_ids'] : [];
  $structure_id = intval($_POST['structure_id'] ?? 0);
  if ($structure_id > 0 && count($student_ids) > 0) {
    $mysqli->begin_transaction();
    try {
      foreach ($student_ids as $sid) {
        $sid = $sid;
        $stmt = $mysqli->prepare("SELECT * FROM student_fees WHERE student_id=? AND fee_structure_id=? AND status='active'");
        $stmt->bind_param('si', $sid, $structure_id);
        $stmt->execute();
        $sf = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($sf) {
          $before = $sf;
          $stmt = $mysqli->prepare("UPDATE student_fees SET status='inactive', unassigned_at=NOW() WHERE id=?");
          $stmt->bind_param('i', $sf['id']);
          $stmt->execute();
          $stmt->close();
          audit_log('unassign_fee', 'student_fee', $sf['id'], $before, ['status' => 'inactive']);
        }
      }
      $mysqli->commit();
      $alerts[] = ['success', 'Fees unassigned from selected students.'];
    } catch (Exception $e) {
      $mysqli->rollback();
      $alerts[] = ['danger', 'Error unassigning fees: ' . $e->getMessage()];
    }
  } else {
    $alerts[] = ['danger', 'Select fee structure and students.'];
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
              <h3 class="fw-bold mb-3">Assign/Unassign Fees</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Assign/unassign Fees</li>
              </ol>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <!-- <div class="card-header">
                <h4 class="card-title">Inbox </h4>
              </div> -->
              <div class="card-body">

                <?php foreach ($alerts as [$type, $msg]): ?>
                  <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
                <?php endforeach; ?>

                <form method="get" class="row g-2">
                  <div class="col-md-2">
                    <select name="class" class="form-select form-control">
                      <option value="" selected disabled>All Classes</option>
                      <?php foreach ($classes as $c): ?>
                        <option value="<?= htmlspecialchars($c) ?>" <?= $filters['class'] === $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <select name="arm" class="form-select form-control">
                      <option value="" selected disabled>All Arms</option>
                      <?php foreach ($arms as $a): ?>
                        <option value="<?= htmlspecialchars($a) ?>" <?= $filters['arm'] === $a ? 'selected' : '' ?>><?= htmlspecialchars($a) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <select name="term" class="form-select form-control">
                      <option value="" selected disabled>All Terms</option>
                      <?php foreach ($terms as $t): ?>
                        <option value="<?= htmlspecialchars($t) ?>" <?= $filters['term'] === $t ? 'selected' : '' ?>><?= htmlspecialchars($t) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <select name="session" class="form-select form-control">
                      <option value="" selected disabled>All Sessions</option>
                      <?php foreach ($sessions as $s): ?>
                        <option value="<?= htmlspecialchars($s) ?>" <?= $filters['session'] === $s ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <select name="hostel" class="form-select form-control">
                      <option value="" selected disabled>All Hostels</option>
                      <?php foreach ($hostels as $h): ?>
                        <option value="<?= htmlspecialchars($h) ?>" <?= $filters['hostel'] === $h ? 'selected' : '' ?>><?= htmlspecialchars($h) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-2 text-sm-center"><button class="btn btn-primary rounded-5" type="submit">Filter</button></div>
                </form>

              </div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <!-- <div class="card-header">
                <h4 class="card-title">Inbox </h4>
              </div> -->
              <div class="card-body">

                <form method="post">
                  <div class="row mb-3 d-flex g-2">
                    <div class="col-md-6">
                      <select name="structure_id" class="form-select form-control" required>
                        <option value="" selected disabled>Select Fee Structure</option>
                        <?php foreach ($structures as $s): ?>
                          <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['class']) ?>/<?= htmlspecialchars($s['arm']) ?>/<?= htmlspecialchars($s['term']) ?>/<?= htmlspecialchars($s['session']) ?>/<?= htmlspecialchars($s['hostel_type']) ?>)</option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-md-6 d-flex gap-3">
                      <button name="action" value="assign" class="btn btn-success rounded-5">Assign to Selected</button>
                      <button name="action" value="unassign" class="btn btn-danger rounded-5">Unassign from Selected</button>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover bg-white" id="basic-datatables">
                      <thead class="table-light">
                        <tr>
                          <th><input type="checkbox" id="select_all" onclick="for(let cb of document.querySelectorAll('.student_cb')) cb.checked=this.checked;"></th>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Class</th>
                          <th>Arm</th>
                          <th>Term</th>
                          <th>Session</th>
                          <th>Hostel</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($students as $st): ?>
                          <tr>
                            <td><input type="checkbox" name="student_ids[]" value="<?= $st['id'] ?>" class="student_cb"></td>
                            <td><?= $st['id'] ?></td>
                            <td><?= htmlspecialchars($st['name']) ?></td>
                            <td><?= htmlspecialchars($st['class']) ?></td>
                            <td><?= htmlspecialchars($st['arm']) ?></td>
                            <td><?= htmlspecialchars($st['term']) ?></td>
                            <td><?= htmlspecialchars($st['session']) ?></td>
                            <td><?= htmlspecialchars($st['hostel']) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </form>

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
  <script>
    document.getElementById('select_all').addEventListener('change', function() {
      for (let cb of document.querySelectorAll('.student_cb')) cb.checked = this.checked;
    });
  </script>
</body>

</html>