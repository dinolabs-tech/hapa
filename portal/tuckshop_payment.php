<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('components/admin_logic.php');
require_once('helpers/audit.php');
require_once('helpers/money.php');

$alerts = [];

// Fetch all students for dropdown
$students = [];
$result = $mysqli->query("SELECT id, name, class, arm FROM students ORDER BY name ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    $result->close();
}

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'])) {
    $student_id = $_POST['student_id'];
    $amount = (float)$_POST['amount'];
    $paid_by = isset($_POST['paid_by']) ? trim($_POST['paid_by']) : '';
    $payment_date = $_POST['payment_date'] ? date('Y-m-d H:i:s', strtotime($_POST['payment_date'])) : date('Y-m-d H:i:s');
    $method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cash';
    $reference = isset($_POST['reference']) ? trim($_POST['reference']) : '';
    $created_by = $_SESSION['user_id'];

    // Fetch current term and session
    $current_term_result = $mysqli->query("SELECT cterm FROM currentterm LIMIT 1");
    $current_term_row = $current_term_result->fetch_assoc();
    $current_term = $current_term_row ? $current_term_row['cterm'] : '1st Term';
    $current_session_result = $mysqli->query("SELECT csession FROM currentsession LIMIT 1");
    $current_session_row = $current_session_result->fetch_assoc();
    $current_session = $current_session_row ? $current_session_row['csession'] : '2024/2025';

    if ($amount <= 0) {
        $alerts[] = ['danger', 'Amount must be positive.'];
    } elseif (!$student_id) {
        $alerts[] = ['danger', 'Please select a student.'];
    } else {
        // Fetch student details
        $stmt = $mysqli->prepare("SELECT name, gender, class, session, photo FROM students WHERE id = ?");
        $stmt->bind_param('s', $student_id);
        $stmt->execute();
        $stmt->bind_result($student_name, $gender, $class, $session, $photo);
        $student_found = $stmt->fetch();
        $stmt->close();

        if (!$student_found) {
            $alerts[] = ['danger', 'Student not found.'];
        } else {
            $mysqli->begin_transaction();
            try {
                // Check if student exists in tuck table
                $stmt = $mysqli->prepare("SELECT vbalance FROM tuck WHERE regno = ?");
                $stmt->bind_param('s', $student_id);
                $stmt->execute();
                $stmt->bind_result($current_balance);
                $exists = $stmt->fetch();
                $stmt->close();

                if (!$exists) {
                    // Create new tuck record
                    $passcode = rand(1000, 9999); // Random passcode
                    $stmt = $mysqli->prepare("INSERT INTO tuck (regno, studentname, sex, studentclass, csession, vbalance, photo, passcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param('sssssdsi', $student_id, $student_name, $gender, $class, $session, $amount, $photo, $passcode);
                    $stmt->execute();
                    $stmt->close();
                    $new_balance = $amount;
                } else {
                    // Update existing balance
                    $new_balance = $current_balance + $amount;
                    $stmt = $mysqli->prepare("UPDATE tuck SET vbalance = ? WHERE regno = ?");
                    $stmt->bind_param('ds', $new_balance, $student_id);
                    $stmt->execute();
                    $stmt->close();
                }

                // Insert transaction
                $stmt = $mysqli->prepare("INSERT INTO transactions (student_id, type, amount, reference, term, session) VALUES (?, 'tuckshop_deposit', ?, ?, ?, ?)");
                $stmt->bind_param('sdsss', $student_id, $amount, $reference, $current_term, $current_session);
                $stmt->execute();
                $transaction_id = $stmt->insert_id;
                $stmt->close();

                // Audit log
                audit_log('record_tuckshop_payment', 'transaction', $transaction_id, null, [
                    'student_id' => $student_id,
                    'amount' => $amount,
                    'method' => $method,
                    'reference' => $reference,
                    'paid_by' => $paid_by,
                    'new_balance' => $new_balance
                ]);

                $mysqli->commit();
                $alerts[] = ['success', 'Tuckshop deposit recorded successfully. New balance: ' . money_format_naira($new_balance)];
            } catch (Exception $e) {
                $mysqli->rollback();
                $alerts[] = ['danger', 'Error: ' . $e->getMessage()];
            }
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
              <h3 class="fw-bold mb-3">Record Tuckshop Payment</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Record Tuckshop Payment</li>
              </ol>
              <?php foreach ($alerts as $alert): ?>
                <?php $type = $alert[0]; $msg = $alert[1]; ?>
                <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Record Tuckshop Deposit</h4>
              </div>
              <div class="card-body">
                <form method="post" class="row g-3">
                  <div class="col-md-4">
                    <label>Student</label>
                    <select name="student_id" class="form-select" required>
                      <option value="">Select Student</option>
                      <?php foreach ($students as $student): ?>
                        <option value="<?= htmlspecialchars($student['id']) ?>"><?= htmlspecialchars($student['name']) ?> (<?= htmlspecialchars($student['class']) ?> <?= htmlspecialchars($student['arm']) ?>)</option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label>Amount (₦)</label>
                    <input type="number" name="amount" class="form-control" step="0.01" required>
                  </div>
                  <div class="col-md-3">
                    <label>Paid By</label>
                    <input type="text" name="paid_by" class="form-control" placeholder="Name of payer">
                  </div>
                  <div class="col-md-3">
                    <label>Date of Payment</label>
                    <input type="datetime-local" name="payment_date" class="form-control" value="<?= date('Y-m-d\TH:i') ?>" required>
                  </div>
                  <div class="col-md-3">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                      <option value="cash">Cash</option>
                      <option value="bank">Bank Transfer</option>
                      <option value="pos">POS</option>
                    </select>
                  </div>
                  <div class="col-md-12">
                    <label>Reference/Note</label>
                    <input type="text" name="reference" class="form-control">
                  </div>
                  <div class="col-md-12 text-center">
                    <button class="btn btn-icon btn-round btn-primary" type="submit"><i class="fa fa-save"></i></button>
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
</body>

</html>
