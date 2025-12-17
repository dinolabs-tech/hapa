<?php
include('components/admin_logic.php');
require_once('helpers/audit.php');
require_once('helpers/money.php');


// Fetch current session from database
$current_session_query = "SELECT csession FROM currentsession WHERE id = 1 LIMIT 1";
$current_session_result = $mysqli->query($current_session_query);
$current_session = $current_session_result ? $current_session_result->fetch_assoc()['csession'] : '';

$alerts = [];
$action = $_GET['action'] ?? null;
$session = $_GET['session'] ?? $current_session;
$current_term = $_GET['current_term'] ?? '';
$new_term = $_GET['new_term'] ?? '';

// Preview rollover
if ($action === 'preview' && $_SERVER['REQUEST_METHOD'] === 'GET') {
  if ($session === '' || $current_term === '' || $new_term === '') {
    $alerts[] = ['danger', 'Session, current term, and new term required.'];
  } else {
    $sql = "SELECT s.id, s.name, s.class, s.arm, s.term, s.session,
                SUM(sfi.amount - sfi.paid_amount) AS outstanding
                FROM students s
                JOIN student_fees sf ON s.id = sf.student_id AND sf.status = 'active'
                JOIN student_fee_items sfi ON sf.id = sfi.student_fee_id
                WHERE s.session = ? AND s.term = ? AND sfi.amount > sfi.paid_amount
                GROUP BY s.id ORDER BY s.name ASC";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ss', $session, $current_term);
    $stmt->execute();
    $res = $stmt->get_result();
    $preview = [];
    $total_carryover = 0;
    while ($row = $res->fetch_assoc()) {
      $row['outstanding_display'] = money_format_naira($row['outstanding']);
      $preview[] = $row;
      $total_carryover += $row['outstanding'];
    }
    $stmt->close();
  }
}

// Execute rollover
if ($action === 'execute' && $_SERVER['REQUEST_METHOD'] === 'GET') {
  if ($session === '' || $current_term === '' || $new_term === '') {
    $alerts[] = ['danger', 'Session, current term, and new term required.'];
  } else {
    $mysqli->begin_transaction();
    try {
      // Get students with outstanding fees
      $sql = "SELECT s.id, s.name, SUM(sfi.amount - sfi.paid_amount) AS outstanding
                    FROM students s
                    JOIN student_fees sf ON s.id = sf.student_id AND sf.status = 'active'
                    JOIN student_fee_items sfi ON sf.id = sfi.student_fee_id
                    WHERE s.session = ? AND s.term = ? AND sfi.amount > sfi.paid_amount
                    GROUP BY s.id";
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param('ss', $session, $current_term);
      $stmt->execute();
      $res = $stmt->get_result();
      $students = [];
      while ($row = $res->fetch_assoc()) {
        $students[] = $row;
      }
      $stmt->close();

      // Ensure CARRYOVER fee item exists
      $carryover_check = $mysqli->query("SELECT id FROM fee_items WHERE name = 'CARRYOVER' LIMIT 1");
      if ($carryover_check->num_rows == 0) {
        $mysqli->query("INSERT INTO fee_items (name, description, mandatory) VALUES ('CARRYOVER', 'Previous term balance', 1)");
      }

      foreach ($students as $student) {
        $student_id = $student['id'];
        $outstanding = $student['outstanding'];

        // Create carryover record
        $stmt = $mysqli->prepare("INSERT INTO carryovers (student_id, session, amount) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $student_id, $session, $outstanding);
        $stmt->execute();
        $stmt->close();

        // Mark existing student_fee_items as rolled over
        $stmt = $mysqli->prepare("UPDATE student_fee_items SET rolled_over = 1 WHERE student_fee_id IN (
                    SELECT id FROM student_fees WHERE student_id = ? AND status = 'active'
                )");
        $stmt->bind_param('s', $student_id);
        $stmt->execute();
        $stmt->close();

        // Create CARRYOVER student_fee_items for new term
        $stmt = $mysqli->prepare("INSERT INTO student_fee_items (student_fee_id, fee_item_id, amount, paid_amount, carryover_flag) VALUES (
                    (SELECT id FROM student_fees WHERE student_id = ? AND status = 'active' LIMIT 1),
                    (SELECT id FROM fee_items WHERE name = 'CARRYOVER' LIMIT 1),
                    ?, 0, 1
                )");
        $stmt->bind_param('si', $student_id, $outstanding);
        $stmt->execute();
        $stmt->close();

        // Update student term
        $stmt = $mysqli->prepare("UPDATE students SET term = ? WHERE id = ?");
        $stmt->bind_param('ss', $new_term, $student_id);
        $stmt->execute();
        $stmt->close();

        // Log rollover
        audit_log('term_rollover', 'student', $student_id, ['term' => $current_term], ['term' => $new_term, 'carryover' => $outstanding]);

        // Optional: assign new fee structure (not implemented here)
      }

      $mysqli->commit();
      $alerts[] = ['success', 'Term rollover executed successfully.'];
    } catch (Exception $e) {
      $mysqli->rollback();
      $alerts[] = ['danger', 'Error executing term rollover: ' . $e->getMessage()];
    }
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
              <h3 class="fw-bold mb-3">Term Rollover</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Term Rollover</li>
              </ol>
              <?php foreach ($alerts as [$type, $msg]): ?>
                <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <form method="get" class="row g-2">
                <div class="col-md-2">
                  <input type="text" id="session" name="session" value="<?= htmlspecialchars($session) ?>" class="form-control" readonly>
                </div>
                <div class="col-md-3">
                  <select id="current_term" name="current_term" class="form-control form-select" required>
                    <option value="" selected disabled>Select Current Term</option>
                    <option value="1st Term" <?= $current_term === '1st Term' ? 'selected' : '' ?>>1st Term</option>
                    <option value="2nd Term" <?= $current_term === '2nd Term' ? 'selected' : '' ?>>2nd Term</option>
                    <option value="3rd Term" <?= $current_term === '3rd Term' ? 'selected' : '' ?>>3rd Term</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <select id="new_term" name="new_term" class="form-control form-select" required>
                    <option value="" selected disabled>Select New Term</option>
                    <option value="1st Term" <?= $new_term === '1st Term' ? 'selected' : '' ?>>1st Term</option>
                    <option value="2nd Term" <?= $new_term === '2nd Term' ? 'selected' : '' ?>>2nd Term</option>
                    <option value="3rd Term" <?= $new_term === '3rd Term' ? 'selected' : '' ?>>3rd Term</option>
                  </select>
                </div>

                <div class="col-md-5 d-flex gap-2">
                  <button name="action" value="preview" class="btn btn-primary rounded-5" type="submit">Preview Rollover</button>
                  <button name="action" value="execute" class="btn btn-danger rounded-5" type="submit">Execute Rollover</button>
                </div>
              </form>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4>Preview: Students with Outstanding Fees</h4>
                <div class="mb-3">
                  <strong>Total Carryover: <?= money_format_naira($total_carryover ?? 0) ?></strong>
                </div>
              </div>
              <div class="card-body">
                <?php if (isset($preview)): ?>
                 <table id="basic-datatables" class="table table-striped table-bordered table-hover bg-white">
                    <thead class="table-light">
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Arm</th>
                        <th>Term</th>
                        <th>Session</th>
                        <th>Outstanding</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($preview as $p): ?>
                        <tr>
                          <td><?= $p['id'] ?></td>
                          <td><?= htmlspecialchars($p['name']) ?></td>
                          <td><?= htmlspecialchars($p['class']) ?></td>
                          <td><?= htmlspecialchars($p['arm']) ?></td>
                          <td><?= htmlspecialchars($p['term']) ?></td>
                          <td><?= htmlspecialchars($p['session']) ?></td>
                          <td><?= $p['outstanding_display'] ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                <?php endif; ?>
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