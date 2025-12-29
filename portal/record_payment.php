<?php
include('components/admin_logic.php');
require_once('helpers/audit.php');
require_once('helpers/money.php');
require_once('helpers/pdf.php');

$alerts = [];
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

// Fetch outstanding fee items (lock for update)
$fee_items = [];
$total_fee = 0;
$total_paid = 0;
$mysqli->begin_transaction();
$stmt = $mysqli->prepare("SELECT sfi.id, fi.name, sfi.amount, sfi.paid_amount, sfi.carryover_flag, sfi.mandatory FROM student_fee_items sfi JOIN fee_items fi ON sfi.fee_item_id = fi.id JOIN student_fees sf ON sfi.student_fee_id = sf.id WHERE sf.student_id = ? AND sf.status='active' FOR UPDATE");
$stmt->bind_param('s', $student_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
  $row['outstanding'] = $row['amount'] - $row['paid_amount'];
  $fee_items[] = $row;
  $total_fee += $row['amount'];
  $total_paid += $row['paid_amount'];
}
$stmt->close();
$balance = $total_fee - $total_paid;

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'])) {

  $amount = $_POST['amount'];
  $paid_by = trim($_POST['paid_by'] ?? '');
  $payment_date = $_POST['payment_date'] ? date('Y-m-d H:i:s', strtotime($_POST['payment_date'])) : date('Y-m-d H:i:s');
  $method = $_POST['payment_method'] ?? 'cash';
  $bank_from = trim($_POST['bank_from'] ?? '');
  $bank_to = trim($_POST['bank_to'] ?? '');
  $transfer_mode = trim($_POST['transfer_mode'] ?? '');
  $transfer_id = trim($_POST['transfer_id'] ?? '');
  $receipt_no_input = trim($_POST['receipt_no'] ?? '');
  $paid_for = trim($_POST['paid_for'] ?? '');
  $discount = $_POST['discount'] ?? 0;
  $tuckshop_deposit = $_POST['tuckshop_deposit'] ?? 0;
  $reference = trim($_POST['reference'] ?? '');
  $created_by = $_SESSION['user_id'];
  $session = $student['session'];
  $seq = rand(1, 99999); // For demo; use DB sequence in production
  // $receipt_number = $receipt_no_input ?: "SCH/" . date('y') . "/$session/REC/$seq";
  $receipt_number = $receipt_no_input;

  if ($amount <= 0) {
    $alerts[] = ['danger', 'Amount must be positive.'];
  } else {
    try {
      // Allocate payment: mandatory items first, then optional
      $remaining = $amount;
      $allocations = [];
      foreach ([1, 0] as $mand) {
        foreach ($fee_items as &$fi) {
          if ($fi['outstanding'] > 0 && $fi['mandatory'] == $mand && $remaining > 0) {
            $alloc = min($fi['outstanding'], $remaining);
            $allocations[] = [
              'student_fee_item_id' => $fi['id'],
              'allocated_amount' => $alloc,
              'manual_override' => 0
            ];
            $fi['paid_amount'] += $alloc;
            $fi['outstanding'] -= $alloc;
            $remaining -= $alloc;
          }
        }
      }
      // Overpayment: credit/refund
      $overpayment = $remaining > 0 ? $remaining : 0;

      // Calculate totals
      $allocated_amount = $amount - $overpayment;
      $new_total_paid = $total_paid + $allocated_amount;
      $new_balance = $balance - $allocated_amount;

      // Insert payment
      $stmt = $mysqli->prepare("INSERT INTO payments (student_id, amount, payment_method, payment_date, reference, receipt_number, created_by, paid_by, bank_from, bank_to, transfer_mode, transfer_id, paid_for, discount, total_paid_term, balance_term, tuckshop_deposit, term, session) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param('sdssssissssssiiiiss', $student_id, $amount, $method, $payment_date, $reference, $receipt_number, $created_by, $paid_by, $bank_from, $bank_to, $transfer_mode, $transfer_id, $paid_for, $discount, $new_total_paid, $new_balance, $tuckshop_deposit, $current_term, $current_session);
      if (!$stmt->execute()) throw new Exception('Error recording payment.');
      $payment_id = $stmt->insert_id;
      audit_log('record_payment', 'payment', $payment_id, null, [
        'student_id' => $student_id,
        'amount' => $amount,
        'method' => $method,
        'reference' => $reference,
        'receipt_number' => $receipt_number,
        'paid_by' => $paid_by,
        'bank_from' => $bank_from,
        'bank_to' => $bank_to,
        'transfer_mode' => $transfer_mode,
        'transfer_id' => $transfer_id,
        'paid_for' => $paid_for,
        'discount' => $discount,
        'total_paid_term' => $new_total_paid,
        'balance_term' => $new_balance,
        'tuckshop_deposit' => $tuckshop_deposit
      ]);
      $stmt->close();

      // Insert allocations and update fee items
      foreach ($allocations as $alloc) {
        $stmt = $mysqli->prepare("INSERT INTO payment_allocations (payment_id, student_fee_item_id, allocated_amount, manual_override, term, session) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('iiiiss', $payment_id, $alloc['student_fee_item_id'], $alloc['allocated_amount'], $alloc['manual_override'], $current_term, $current_session);
        $stmt->execute();
        $stmt->close();

        // Update paid_amount
        $stmt = $mysqli->prepare("UPDATE student_fee_items SET paid_amount = paid_amount + ? WHERE id = ?");
        $stmt->bind_param('ii', $alloc['allocated_amount'], $alloc['student_fee_item_id']);
        $stmt->execute();
        $stmt->close();
      }

      // Ledger entry
      $stmt = $mysqli->prepare("INSERT INTO transactions (student_id, type, amount, reference, related_id, term, session) VALUES (?, 'payment', ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE amount = amount + VALUES(amount), reference = VALUES(reference), related_id = VALUES(related_id)");
      $stmt->bind_param('sisiss', $student_id, $amount, $receipt_number, $payment_id, $current_term, $current_session);
      $stmt->execute();
      $stmt->close();

      // Overpayment: credit/refund logic (not implemented here, but log)
      if ($overpayment > 0) {
        audit_log('overpayment', 'payment', $payment_id, null, ['student_id' => $student_id, 'overpayment' => $overpayment]);
      }

      $mysqli->commit();
      $alerts[] = ['success', 'Payment recorded successfully.'];
    } catch (Exception $e) {
      $mysqli->rollback();
      $alerts[] = ['danger', 'Error: ' . $e->getMessage()];
    }
  }
}

$mysqli->commit();
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
              <h3 class="fw-bold mb-3">Record Payment</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Record Payment</li>
              </ol>
              <?php foreach ($alerts as [$type, $msg]): ?>
                <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
              <?php endforeach; ?>
            </div>
          </div>


          <div class="col-sm-6 col-md-12">
            <div class="card card-stats card-round">
              <div class="card-body">
                <div class="row">
                  <div class="col-5">
                    <div class="icon-big text-center">
                      <i class="fas fa-user text-warning"></i>
                    </div>
                  </div>
                  <div class="col-7 col-stats">
                    <div class="numbers">
                      <p class="card-category">Student Name</p>
                      <h4 class="card-title"><?= htmlspecialchars($student['name']) ?></h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row">
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="fas fa-home text-secondary"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">Hostel</p>
                        <h4 class="card-title"><?= ucfirst($student['hostel']) ?></h4>
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
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="icon-wallet text-success"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">School Fee</p>
                        <h4 class="card-title"><?= money_format_naira($total_fee) ?></h4>
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
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="icon-wallet text-primary"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">Total Paid (Term)</p>
                        <h4 class="card-title"><?= money_format_naira($total_paid) ?></h4>
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
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="icon-wallet text-danger"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">Balance</p>
                        <h4 class="card-title"><?= money_format_naira($balance) ?></h4>
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
                <h4 class="card-title">Record Payment</h4>
              </div>
              <div class="card-body">
                <form method="post" class="row g-3">
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
                      <option value="refund">Refund</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label>Bank From</label>
                    <input type="text" name="bank_from" class="form-control" placeholder="Sender bank">
                  </div>
                  <div class="col-md-3">
                    <label>Bank To</label>
                    <input type="text" name="bank_to" class="form-control" placeholder="Receiver bank">
                  </div>
                  <div class="col-md-3">
                    <label>Transfer Mode</label>
                    <select name="transfer_mode" class="form-select">
                      <option value="">Select</option>
                      <option value="online">Online Transfer</option>
                      <option value="cheque">Cheque</option>
                      <option value="wire">Wire Transfer</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label>Transfer ID</label>
                    <input type="text" name="transfer_id" class="form-control" placeholder="Transaction ID">
                  </div>
                  <div class="col-md-3">
                    <label>Receipt No</label>
                    <input type="text" name="receipt_no" class="form-control" placeholder="School receipt number">
                  </div>
                  <div class="col-md-3">
                    <label>Paid For</label>
                    <textarea name="paid_for" class="form-control" placeholder="Fee items or description"></textarea>
                  </div>
                  <div class="col-md-3">
                    <label>Discount (₦)</label>
                    <input type="number" name="discount" class="form-control" step="0.01" value="0">
                  </div>
                  <div class="col-md-3">
                    <label>Tuckshop Deposit (₦)</label>
                    <input type="number" name="tuckshop_deposit" class="form-control" step="0.01" value="0">
                  </div>
                  <div class="col-md-12">
                    <label>Reference/Note</label>
                    <input type="text" name="reference" class="form-control">
                  </div>
                  <div class="col-md-12 text-center">
                    <button class="btn btn-icon btn-round btn-primary" type="submit"><i class="fa fa-save"></i>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h5>Outstanding Fee Items</h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="basic-datatables" class="table table-bordered table-striped table-hover bg-white mb-4">
                  <thead class="table-light">
                    <tr>
                      <th>Name</th>
                      <th>Amount</th>
                      <th>Paid</th>
                      <th>Outstanding</th>
                      <th>Mandatory</th>
                      <th>Carryover</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($fee_items as $fi): ?>
                      <tr>
                        <td><?= htmlspecialchars($fi['name']) ?></td>
                        <td><?= money_format_naira($fi['amount']) ?></td>
                        <td><?= money_format_naira($fi['paid_amount']) ?></td>
                        <td><?= money_format_naira($fi['outstanding']) ?></td>
                        <td><?= $fi['mandatory'] ? 'Yes' : 'No' ?></td>
                        <td><?= $fi['carryover_flag'] ? 'Yes' : 'No' ?></td>
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
