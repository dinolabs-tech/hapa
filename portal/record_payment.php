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
$stmt = $mysqli->prepare("SELECT sfi.id, fi.name, sfi.amount, sfi.paid_amount, sfi.carryover_flag, sfi.mandatory, sf.id AS student_fee_id, fs.name AS structure_name FROM student_fee_items sfi JOIN fee_items fi ON sfi.fee_item_id = fi.id JOIN student_fees sf ON sfi.student_fee_id = sf.id JOIN fee_structures fs ON sf.fee_structure_id = fs.id WHERE sf.student_id = ? AND sf.status='active' FOR UPDATE");
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

  // ==========================================================================
  // Per-item payment mode: allow paying MULTIPLE fee structures/items at once.
  // Triggered when the form submits an `amounts[student_fee_item_id]` array.
  // One `payments` row, one `payment_allocations` row, one `transactions`
  // ledger row and one audit entry are written PER fee item, all sharing the
  // same receipt number & reference. The legacy single-amount path below is
  // left 100% unchanged and remains the fallback for plain submissions.
  // ==========================================================================
  if (isset($_POST['amounts']) && is_array($_POST['amounts'])) {
    $paid_by = trim($_POST['paid_by'] ?? '');
    $payment_date = $_POST['payment_date'] ? date('Y-m-d H:i:s', strtotime($_POST['payment_date'])) : date('Y-m-d H:i:s');
    $method = $_POST['payment_method'] ?? 'cash';
    $bank_from = trim($_POST['bank_from'] ?? '');
    $bank_to = trim($_POST['bank_to'] ?? '');
    $transfer_mode = trim($_POST['transfer_mode'] ?? '');
    $transfer_id = trim($_POST['transfer_id'] ?? '');
    $receipt_no_input = trim($_POST['receipt_no'] ?? '');
    $paid_for_input = trim($_POST['paid_for'] ?? '');
    $discount = (float)($_POST['discount'] ?? 0);
    $tuckshop_deposit = (float)($_POST['tuckshop_deposit'] ?? 0);
    $reference = trim($_POST['reference'] ?? '');
    $created_by = $_SESSION['user_id'];
    $session = $student['session'];
    $receipt_number = $receipt_no_input;
    // Map submitted fee-item amounts to the locked fee-item rows.
    $selections = [];
    $total_amount = 0;
    $any_positive = false;
    foreach ($_POST['amounts'] as $sfi_id => $val) {
      $sfi_id = (int)$sfi_id;
      if ($sfi_id <= 0) continue;
      foreach ($fee_items as $fi) {
        if ((int)$fi['id'] === $sfi_id) {
          $amt = round((float)($val ?? 0), 2);
          if ($amt > 0) {
            $cap = (float)$fi['outstanding'];
            if ($amt > $cap) $amt = $cap;
            if ($amt <= 0) break;
            $selections[$sfi_id] = [
              'fee_item' => $fi,
              'outstanding' => (float)$fi['outstanding'],
              'amount' => $amt
            ];
            $total_amount += $amt;
            $any_positive = true;
          }
          break;
        }
      }
    }

    if (!$any_positive) {
      $alerts[] = ['danger', 'Enter a payment amount for at least one fee item.'];
    } else {
      try {
        // Preserve the on-screen (display) order of the fee items.
        $ordered = [];
        foreach ($fee_items as $fi) {
          if (isset($selections[(int)$fi['id']])) {
            $ordered[] = $selections[(int)$fi['id']];
          }
        }

        $running_paid = $total_paid;
        $running_balance = $balance;
        $discount_remaining = $discount;
        $total_discount_used = 0;
        $first_payment_id = null;

        foreach ($ordered as $sel) {
          $fi = $sel['fee_item'];
          $sfi_id = (int)$fi['id'];
          $cash = $sel['amount'];
          $item_disc = 0;

          if ($discount > 0 && $total_amount > 0) {
            $item_disc = round($discount * ($cash / $total_amount), 2);
            $max_disc = max(0, $sel['outstanding'] - $cash);
            if ($item_disc > $max_disc) $item_disc = $max_disc;
            if ($item_disc > $discount_remaining) $item_disc = $discount_remaining;
            $discount_remaining -= $item_disc;
            $total_discount_used += $item_disc;
          }

          $applied_total = round($cash + $item_disc, 2);
          $this_paid = $running_paid + $applied_total;
          $this_balance = $total_fee - $this_paid;

          $paid_for_row = $paid_for_input !== '' ? $paid_for_input : $fi['name'];
          // One payments row per fee item (same receipt number & reference).
          $stmt = $mysqli->prepare("INSERT INTO payments (student_id, amount, payment_method, payment_date, reference, receipt_number, created_by, paid_by, bank_from, bank_to, transfer_mode, transfer_id, paid_for, discount, total_paid_term, balance_term, tuckshop_deposit, term, session) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
          $stmt->bind_param('sdssssissssssiiiiss', $student_id, $cash, $method, $payment_date, $reference, $receipt_number, $created_by, $paid_by, $bank_from, $bank_to, $transfer_mode, $transfer_id, $paid_for_row, $item_disc, $this_paid, $this_balance, $tuckshop_deposit, $current_term, $current_session);
          if (!$stmt->execute()) throw new Exception('Error recording payment for ' . $fi['name'] . '.');
          $payment_id = $stmt->insert_id;
          $stmt->close();
          if ($first_payment_id === null) $first_payment_id = $payment_id;

          audit_log('record_payment', 'payment', $payment_id, null, [
            'student_id' => $student_id,
            'fee_item' => $fi['name'],
            'structure' => $fi['structure_name'] ?? '',
            'amount' => $cash,
            'discount' => $item_disc,
            'method' => $method,
            'reference' => $reference,
            'receipt_number' => $receipt_number,
            'paid_by' => $paid_by,
            'bank_from' => $bank_from,
            'bank_to' => $bank_to,
            'transfer_mode' => $transfer_mode,
            'transfer_id' => $transfer_id,
            'total_paid_term' => $this_paid,
            'balance_term' => $this_balance,
            'tuckshop_deposit' => $tuckshop_deposit
          ]);
          // Allocation row linking this payment to this specific fee item.
          $stmt = $mysqli->prepare("INSERT INTO payment_allocations (payment_id, student_fee_item_id, allocated_amount, manual_override, term, session) VALUES (?, ?, ?, 1, ?, ?)");
          $stmt->bind_param('iidss', $payment_id, $sfi_id, $applied_total, $current_term, $current_session);
          $stmt->execute();
          $stmt->close();

          // Update the fee item's paid amount.
          $stmt = $mysqli->prepare("UPDATE student_fee_items SET paid_amount = paid_amount + ? WHERE id = ?");
          $stmt->bind_param('di', $applied_total, $sfi_id);
          $stmt->execute();
          $stmt->close();

          // Keep the in-memory copy in sync so the page re-renders correctly.
          foreach ($fee_items as &$fir) {
            if ((int)$fir['id'] === $sfi_id) {
              $fir['paid_amount'] += $applied_total;
              $fir['outstanding'] = $fir['amount'] - $fir['paid_amount'];
              break;
            }
          }
          unset($fir);

          // One ledger entry per fee item for an accurate audit trail.
          $stmt = $mysqli->prepare("INSERT INTO transactions (student_id, type, amount, reference, related_id, term, session) VALUES (?, 'payment', ?, ?, ?, ?, ?)");
          $stmt->bind_param('sdisss', $student_id, $cash, $receipt_number, $payment_id, $current_term, $current_session);
          if (!$stmt->execute()) throw new Exception('Error recording transaction ledger for ' . $fi['name'] . '.');
          $stmt->close();

          $running_paid = $this_paid;
          $running_balance = $this_balance;
        }

        // Warn (non-fatal) if the discount could not be fully applied because
        // the selected items were already fully covered by the entered amounts.
        if ($discount > 0 && $total_discount_used < $discount) {
          $unused = round($discount - $total_discount_used, 2);
          $alerts[] = ['warning', 'Note: ' . money_format_naira($unused) . ' of the discount could not be applied because the selected fee items were already fully covered by the amounts entered.'];
        }

        // Log one discount transaction + audit entry if a discount was applied.
        if ($total_discount_used > 0) {
          $stmt = $mysqli->prepare("INSERT INTO transactions (student_id, type, amount, reference, related_id, term, session) VALUES (?, 'discount', ?, ?, ?, ?, ?)");
          $stmt->bind_param('sdisss', $student_id, $total_discount_used, $receipt_number, $first_payment_id, $current_term, $current_session);
          $stmt->execute();
          $stmt->close();

          audit_log('apply_discount', 'discount', $first_payment_id, null, [
            'student_id' => $student_id,
            'discount_amount' => $total_discount_used,
            'receipt_number' => $receipt_number,
            'applied_by' => $created_by,
            'payment_id' => $first_payment_id,
            'term' => $current_term,
            'session' => $current_session
          ]);
        }

        $mysqli->commit();

        // Refresh the stat widgets so they reflect the payments just recorded.
        $total_paid = $running_paid;
        $balance    = $total_fee - $total_paid;

        $alerts[] = ['success', 'Payment recorded successfully for ' . count($ordered) . ' fee item(s).'];
      } catch (Exception $e) {
        $mysqli->rollback();
        $alerts[] = ['danger', 'Error: ' . $e->getMessage()];
      }
    }
  } else {
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
        // Allocate payment: handle discount first, then mandatory items, then optional
        $remaining = $amount;
        $allocations = [];

        // Step 1: Apply discount if any
        if ($discount > 0) {
          // Create discount allocation record
          $allocations[] = [
            'student_fee_item_id' => 0, // 0 indicates this is a discount, not a specific fee item
            'allocated_amount' => $discount,
            'manual_override' => 1, // Mark as manual override for discount
            'is_discount' => true
          ];
          $remaining -= $discount;
        }

        // Step 2: Allocate remaining amount to fee items (mandatory first, then optional)
        foreach ([1, 0] as $mand) {
          foreach ($fee_items as &$fi) {
            if ($fi['outstanding'] > 0 && $fi['mandatory'] == $mand && $remaining > 0) {
              $alloc = min($fi['outstanding'], $remaining);
              $allocations[] = [
                'student_fee_item_id' => $fi['id'],
                'allocated_amount' => $alloc,
                'manual_override' => 0,
                'is_discount' => false
              ];
              $fi['paid_amount'] += $alloc;
              $fi['outstanding'] -= $alloc;
              $remaining -= $alloc;
            }
          }
        }
        unset($fi); // break the reference left by the by-reference foreach over $fee_items

        // Step 3: Handle overpayment (credit/refund)
        $overpayment = $remaining > 0 ? $remaining : 0;

        // Calculate totals
        // NOTE: $allocated_amount already includes the discount (the discount is
        // subtracted from $remaining before allocation), so it must only be
        // applied once to the balance.
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

          // Update paid_amount for actual fee items AND apply discount to overall balance
          if ($alloc['student_fee_item_id'] > 0) {
            $stmt = $mysqli->prepare("UPDATE student_fee_items SET paid_amount = paid_amount + ? WHERE id = ?");
            $stmt->bind_param('ii', $alloc['allocated_amount'], $alloc['student_fee_item_id']);
            $stmt->execute();
            $stmt->close();
          } else if ($alloc['is_discount']) {
            // Apply discount proportionally across all outstanding fee items
            $discount_remaining = $discount;
            foreach ($fee_items as &$fi) {
              if ($fi['outstanding'] > 0 && $discount_remaining > 0) {
                $discount_alloc = min($fi['outstanding'], $discount_remaining);
                $stmt = $mysqli->prepare("UPDATE student_fee_items SET paid_amount = paid_amount + ? WHERE id = ?");
                $stmt->bind_param('ii', $discount_alloc, $fi['id']);
                $stmt->execute();
                $stmt->close();
                $fi['paid_amount'] += $discount_alloc;
                $fi['outstanding'] -= $discount_alloc;
                $discount_remaining -= $discount_alloc;
              }
            }
            unset($fi); // break the reference left by the by-reference foreach over $fee_items
          }
        }

        // Create discount transaction entry if discount was applied
        if ($discount > 0) {
          $stmt = $mysqli->prepare("INSERT INTO transactions (student_id, type, amount, reference, related_id, term, session) VALUES (?, 'discount', ?, ?, ?, ?, ?)");
          $stmt->bind_param('sisiss', $student_id, $discount, $receipt_number, $payment_id, $current_term, $current_session);
          $stmt->execute();
          $stmt->close();

          // Log discount application for audit trail
          audit_log('apply_discount', 'discount', $payment_id, null, [
            'student_id' => $student_id,
            'discount_amount' => $discount,
            'receipt_number' => $receipt_number,
            'applied_by' => $created_by,
            'payment_id' => $payment_id,
            'term' => $current_term,
            'session' => $current_session
          ]);
        }

        // Ledger entry
        $stmt = $mysqli->prepare("INSERT INTO transactions (student_id, type, amount, reference, related_id, term, session) VALUES (?, 'payment', ?, ?, ?, ?, ?)");
        $stmt->bind_param('sisiss', $student_id, $amount, $receipt_number, $payment_id, $current_term, $current_session);
        if (!$stmt->execute()) throw new Exception('Error recording transaction ledger.');
        $stmt->close();

        // Overpayment: credit/refund logic (not implemented here, but log)
        if ($overpayment > 0) {
          audit_log('overpayment', 'payment', $payment_id, null, ['student_id' => $student_id, 'overpayment' => $overpayment]);
        }

        $mysqli->commit();

        // Refresh the stat widgets so they reflect the payment just recorded
        $total_paid = $new_total_paid;
        $balance    = $total_fee - $total_paid;

        $alerts[] = ['success', 'Payment recorded successfully.'];
      } catch (Exception $e) {
        $mysqli->rollback();
        $alerts[] = ['danger', 'Error: ' . $e->getMessage()];
      }
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


          <div class="card">
            <div class="card-header">
              <h5>Outstanding Fee Items</h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="basic-datatables" class="table table-bordered table-striped table-hover bg-white mb-4">
                  <thead class="table-light">
                    <tr>
                      <th>Structure</th>
                      <th>Name</th>
                      <th>Amount</th>
                      <th>Paid</th>
                      <th>Outstanding</th>
                      <th>Mandatory</th>
                      <th>Carryover</th>
                      <th>Pay Now (₦)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($fee_items as $fi): ?>
                      <tr>
                        <td><?= htmlspecialchars($fi['structure_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($fi['name']) ?></td>
                        <td><?= money_format_naira($fi['amount']) ?></td>
                        <td><?= money_format_naira($fi['paid_amount']) ?></td>
                        <td><?= money_format_naira($fi['outstanding']) ?></td>
                        <td><?= $fi['mandatory'] ? 'Yes' : 'No' ?></td>
                        <td><?= $fi['carryover_flag'] ? 'Yes' : 'No' ?></td>
                        <td>
                          <input type="number" name="amounts[<?= (int)$fi['id'] ?>]" class="form-control pay-now-input" style="width:150px;" step="0.01" min="0" max="<?= (float)$fi['outstanding'] ?>" placeholder="0.00" data-outstanding="<?= (float)$fi['outstanding'] ?>">
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <script>
            (function() {
              let totalField = document.getElementById('total_amount');
              let inputs = Array.prototype.slice.call(document.querySelectorAll('.pay-now-input'));
              let form = document.getElementById('payment-form');

              function recalc() {
                let total = 0;
                inputs.forEach(function(inp) {
                  let v = parseFloat(inp.value);
                  if (!isNaN(v) && v > 0) total += v;
                });
                if (totalField) totalField.value = total > 0 ? total.toFixed(2) : '';
              }

              // Clip each entry to the item's outstanding and keep the total in sync.
              inputs.forEach(function(inp) {
                inp.addEventListener('input', function() {
                  let cap = parseFloat(inp.getAttribute('data-outstanding'));
                  let v = parseFloat(inp.value);
                  if (!isNaN(v) && !isNaN(cap) && v > cap) inp.value = cap.toFixed(2);
                  recalc();
                });
              });

              // Block submission when no per-item amount was entered (server re-validates).
              if (form) {
                form.addEventListener('submit', function(e) {
                  recalc();
                  let any = inputs.some(function(inp) {
                    let v = parseFloat(inp.value);
                    return !isNaN(v) && v > 0;
                  });
                  if (!any) {
                    e.preventDefault();
                    alert('Enter a payment amount for at least one fee item.');
                    return;
                  }
                  // Safety: an entered amount on a row detached from the form (e.g. by an
                  // active table search) would silently miss the submission.
                  let lost = inputs.filter(function(inp) {
                    return !document.body.contains(inp) && parseFloat(inp.value) > 0;
                  });
                  if (lost.length) {
                    e.preventDefault();
                    alert('Some entered amounts are not visible in the table (a table search/filter is active). Clear the search so every amount is submitted.');
                  }
                });
              }

              recalc();
            })();
          </script>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Record Payment</h4>
              </div>
              <div class="card-body">
                <form method="post" id="payment-form" class="row g-3">
                  <div class="col-md-3">
                    <label>Amount (₦)</label>
                    <input type="number" name="amount" id="total_amount" class="form-control" step="0.01" readonly>
                    <small class="text-muted">Auto-calculated from the fee items below.</small>
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
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i>
                    </button>
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
    // Keep every fee-item row attached to the form: show all rows for THIS
    // table only (no paging) so all per-item amount inputs submit together.
    $(document).ready(function() {
      if (window.jQuery && $.fn.DataTable && $.fn.DataTable.isDataTable('#basic-datatables')) {
        $('#basic-datatables').DataTable().page.len(-1).draw();
      }
    });
  </script>
</body>

</html>