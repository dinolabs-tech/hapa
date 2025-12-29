<?php
include('components/admin_logic.php');
require_once('helpers/audit.php');
require_once('helpers/money.php');

$student_id = $_GET['id'];
if ($student_id <= 0) {
  echo "<div class='alert alert-danger'>Invalid student ID.</div>";
  exit;
}

// Fetch current term and session
$current_term = $mysqli->query("SELECT cterm FROM currentterm LIMIT 1")->fetch_assoc()['cterm'] ?? '1st Term';
$current_session = $mysqli->query("SELECT csession FROM currentsession LIMIT 1")->fetch_assoc()['csession'] ?? '2024/2025';

// Fetch student
$stmt = $mysqli->prepare("SELECT id, name, class, arm, term, session, hostel FROM students WHERE id = ?");
$stmt->bind_param('s', $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$student) {
  echo "<div class='alert alert-danger'>Student not found.</div>";
  exit;
}

// Fetch assigned fees
$fees = [];
$stmt = $mysqli->prepare("SELECT sf.id, fs.name, fs.class, fs.arm, fs.term, fs.session, fs.hostel_type, fs.total_amount FROM student_fees sf 
JOIN fee_structures fs ON sf.fee_structure_id = fs.id WHERE sf.student_id = ? AND sf.status='active' AND sf.term = ? AND sf.session = ?");
$stmt->bind_param('sss', $student_id, $current_term, $current_session);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
  $row['total_amount_display'] = money_format_naira($row['total_amount']);
  $fees[] = $row;
}
$stmt->close();

// Fetch fee items
$fee_items = [];
foreach ($fees as $fee) {
  $sfid = $fee['id'];
  $stmt = $mysqli->prepare("SELECT sfi.id, fi.name, sfi.amount, sfi.paid_amount, sfi.carryover_flag FROM student_fee_items sfi JOIN fee_items fi ON sfi.fee_item_id = fi.id WHERE sfi.student_fee_id = ?");
  $stmt->bind_param('i', $sfid);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($row = $res->fetch_assoc()) {
    $row['amount_display'] = money_format_naira($row['amount']);
    $row['paid_display'] = money_format_naira($row['paid_amount']);
    $fee_items[] = $row;
  }
  $stmt->close();
}

// Fetch payments
$payments = [];
$stmt = $mysqli->prepare("SELECT id, amount, payment_method, payment_date, receipt_number FROM payments WHERE student_id = ? AND term = ? AND session = ? ORDER BY payment_date DESC");
$stmt->bind_param('sss', $student_id, $current_term, $current_session);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
  $row['amount_display'] = money_format_naira($row['amount']);
  $payments[] = $row;
}
$stmt->close();

// Calculate total paid and outstanding for current term
$total_paid = 0;
$outstanding = 0;
foreach ($fee_items as $fi) {
  $total_paid += $fi['paid_amount'];
  $outstanding += ($fi['amount'] - $fi['paid_amount']);
}

// Calculate overall totals for current session (across all terms)
$session_total_paid = 0;
$session_outstanding = 0;

// Get all fee items for the current session
$session_fee_items = [];
$stmt = $mysqli->prepare("SELECT sfi.amount, sfi.paid_amount FROM student_fee_items sfi
JOIN student_fees sf ON sfi.student_fee_id = sf.id
WHERE sf.student_id = ? AND sf.status='active' AND sf.session = ?");
$stmt->bind_param('ss', $student_id, $current_session);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
  $session_total_paid += $row['paid_amount'];
  $session_outstanding += ($row['amount'] - $row['paid_amount']);
}
$stmt->close();
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
              <h3 class="fw-bold mb-3">Student Bursary Profile</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Student Bursary Profile</li>
              </ol>
            </div>
          </div>

          <!-- Card With Icon States Color -->
          <div class="row justify-content-center">
            <div class="col-sm-6 col-md-12">
              <div class="card card-stats card-round bg-primary">
                <div class="card-body">
                  <div class="row text-center">
                    <div class="col-12 col-stats justify-content-center">
                      <div class="numbers">
                        <p class="card-category text-white">Name</p>
                        <h4 class="card-title text-center text-white"><?= htmlspecialchars($student['name']) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Card With Icon States Color -->
          <div class="row">
            <div class="col-sm-6 col-md-2">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-stats justify-content-center">
                      <div class="numbers">
                        <p class="card-category text-center">Class</p>
                        <h4 class="card-title"></strong> <?= htmlspecialchars($student['class']) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-2">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-stats justify-content-center">
                      <div class="numbers">
                        <p class="card-category text-center">Arm</p>
                        <h4 class="card-title"></strong> <?= htmlspecialchars($student['arm']) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-stats justify-content-center">
                      <div class="numbers">
                        <p class="card-category text-center">Term</p>
                        <h4 class="card-title"></strong> <?= htmlspecialchars($student['term']) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-stats justify-content-center">
                      <div class="numbers">
                        <p class="card-category text-center">Session</p>
                        <h4 class="card-title"></strong> <?= htmlspecialchars($student['session']) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-2">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-stats justify-content-center">
                      <div class="numbers">
                        <p class="card-category text-center">Hostel</p>
                        <h4 class="card-title"></strong> <?= htmlspecialchars($student['hostel']) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6 col-md-6">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-stats justify-content-center">
                      <div class="numbers">
                        <p class="card-category text-center">Total Fee Paid at End of Term</p>
                        <h4 class="card-title"></strong> <?= money_format_naira($total_paid) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-6">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-stats justify-content-center">
                      <div class="numbers">
                        <p class="card-category text-center">Outstanding Balance at End of Term</p>
                        <h4 class="card-title"></strong> <?= money_format_naira($outstanding) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6 col-md-6">
              <div class="card card-stats card-round bg-success">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-stats justify-content-center">
                      <div class="numbers">
                        <p class="card-category text-center text-white">Overall Fee Paid in Current Session</p>
                        <h4 class="card-title text-white"></strong> <?= money_format_naira($session_total_paid) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-6">
              <div class="card card-stats card-round bg-warning">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-stats justify-content-center">
                      <div class="numbers">
                        <p class="card-category text-center text-white">Overall Outstanding Balance in Current Session</p>
                        <h4 class="card-title text-white"></strong> <?= money_format_naira($session_outstanding) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Assigned Fees </h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover table-striped bg-white mb-4">
                    <thead class="table-light">
                      <tr>
                        <th>Structure</th>
                        <th>Class</th>
                        <th>Arm</th>
                        <th>Term</th>
                        <th>Session</th>
                        <th>Hostel</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($fees as $fee): ?>
                        <tr>
                          <td><?= htmlspecialchars($fee['name']) ?></td>
                          <td><?= htmlspecialchars($fee['class']) ?></td>
                          <td><?= htmlspecialchars($fee['arm']) ?></td>
                          <td><?= htmlspecialchars($fee['term']) ?></td>
                          <td><?= htmlspecialchars($fee['session']) ?></td>
                          <td><?= htmlspecialchars($fee['hostel_type']) ?></td>
                          <td><?= $fee['total_amount_display'] ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Assigned Fees </h4>
              </div>
              <div class="card-body">

                <table class="table table-bordered table-striped table-hover bg-white mb-4">
                  <thead class="table-light">
                    <tr>
                      <th>Name</th>
                      <th>Amount</th>
                      <th>Paid</th>
                      <th>Outstanding</th>
                      <th>Carryover</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($fee_items as $fi): ?>
                      <tr>
                        <td><?= htmlspecialchars($fi['name']) ?></td>
                        <td><?= $fi['amount_display'] ?></td>
                        <td><?= $fi['paid_display'] ?></td>
                        <td><?= money_format_naira($fi['amount'] - $fi['paid_amount']) ?></td>
                        <td><?= $fi['carryover_flag'] ? 'Yes' : 'No' ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>

              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Payments </h4>
              </div>
              <div class="card-body">

                <table class="table table-bordered table-striped table-hover bg-white mb-4" id="basic-datatables">
                  <thead class="table-light">
                    <tr>
                      <th>Date</th>
                      <th>Method</th>
                      <th>Amount</th>
                      <th>Receipt</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($payments as $p): ?>
                      <tr>
                        <td><?= htmlspecialchars($p['payment_date']) ?></td>
                        <td><?= htmlspecialchars($p['payment_method']) ?></td>
                        <td><?= $p['amount_display'] ?></td>
                        <td><?= htmlspecialchars($p['receipt_number']) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>

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
