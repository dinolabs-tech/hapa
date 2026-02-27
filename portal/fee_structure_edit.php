<?php
include('components/admin_logic.php');
require_once('db_connection.php');
require_once('helpers/audit.php');
require_once('helpers/money.php');

$structure_id = intval($_GET['id'] ?? 0);
if ($structure_id <= 0) {
  header('Location: fee_structures.php');
  exit;
}

// Fetch structure
$stmt = $mysqli->prepare("SELECT * FROM fee_structures WHERE id = ?");
$stmt->bind_param('i', $structure_id);
$stmt->execute();
$structure = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$structure) {
  header('Location: fee_structures.php');
  exit;
}

$alerts = [];
$action = $_POST['action'] ?? null;

// Assign item
if ($action === 'assign' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $item_id = intval($_POST['fee_item_id'] ?? 0);
  $amount = intval(preg_replace('/[^\d]/', '', $_POST['amount'] ?? '0'));
  $mandatory = isset($_POST['mandatory']) ? 1 : 0;
  if ($item_id > 0 && $amount > 0) {
    $mysqli->begin_transaction();
    try {
      $stmt = $mysqli->prepare("INSERT INTO fee_structure_items (fee_structure_id, fee_item_id, amount, mandatory) VALUES (?, ?, ?, ?)");
      $stmt->bind_param('iiii', $structure_id, $item_id, $amount, $mandatory);
      $before = null;
      $after = ['fee_structure_id' => $structure_id, 'fee_item_id' => $item_id, 'amount' => $amount, 'mandatory' => $mandatory];
      if ($stmt->execute()) {
        audit_log('assign_item', 'fee_structure_item', $stmt->insert_id, $before, $after);
        $mysqli->commit();
        // Redirect to fee_structures page after successful insertion
        header('Location: fee_structures.php');
        exit;
      } else {
        throw new Exception('Error assigning item.');
      }
      $stmt->close();

      // Update total
      $mysqli->query("UPDATE fee_structures SET total_amount = (
                SELECT COALESCE(SUM(amount),0) FROM fee_structure_items WHERE fee_structure_id = $structure_id
            ) WHERE id = $structure_id");

    } catch (Exception $e) {
      $mysqli->rollback();
      $alerts[] = ['danger', $e->getMessage()];
    }
  } else {
    $alerts[] = ['danger', 'Valid item and amount required.'];
  }
}

// Unassign item
if ($action === 'unassign' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $fsi_id = intval($_POST['fsi_id'] ?? 0);
  $stmt = $mysqli->prepare("SELECT * FROM fee_structure_items WHERE id = ?");
  $stmt->bind_param('i', $fsi_id);
  $stmt->execute();
  $before = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if ($before) {
    $mysqli->begin_transaction();
    try {
      $stmt = $mysqli->prepare("DELETE FROM fee_structure_items WHERE id=?");
      $stmt->bind_param('i', $fsi_id);
      if ($stmt->execute()) {
        audit_log('unassign_item', 'fee_structure_item', $fsi_id, $before, null);
        $alerts[] = ['success', 'Fee item unassigned.'];
      } else {
        throw new Exception('Error unassigning item.');
      }
      $stmt->close();

      // Update total
      $mysqli->query("UPDATE fee_structures SET total_amount = (
                SELECT COALESCE(SUM(amount),0) FROM fee_structure_items WHERE fee_structure_id = $structure_id
            ) WHERE id = $structure_id");

      $mysqli->commit();
    } catch (Exception $e) {
      $mysqli->rollback();
      $alerts[] = ['danger', $e->getMessage()];
    }
  } else {
    $alerts[] = ['danger', 'Fee structure item not found.'];
  }
}

// Update item amount/mandatory
if ($action === 'update_item' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $fsi_id = intval($_POST['fsi_id'] ?? 0);
  $amount = intval(preg_replace('/[^\d]/', '', $_POST['amount'] ?? '0'));
  $mandatory = isset($_POST['mandatory']) ? 1 : 0;
  $stmt = $mysqli->prepare("SELECT * FROM fee_structure_items WHERE id = ?");
  $stmt->bind_param('i', $fsi_id);
  $stmt->execute();
  $before = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if ($before) {
    $mysqli->begin_transaction();
    try {
      $stmt = $mysqli->prepare("UPDATE fee_structure_items SET amount=?, mandatory=? WHERE id=?");
      $stmt->bind_param('iii', $amount, $mandatory, $fsi_id);
      $after = ['amount' => $amount, 'mandatory' => $mandatory];
      if ($stmt->execute()) {
        audit_log('update_item', 'fee_structure_item', $fsi_id, $before, $after);
        $alerts[] = ['success', 'Fee item updated.'];
      } else {
        throw new Exception('Error updating item.');
      }
      $stmt->close();

      // Update total
      $mysqli->query("UPDATE fee_structures SET total_amount = (
                SELECT COALESCE(SUM(amount),0) FROM fee_structure_items WHERE fee_structure_id = $structure_id
            ) WHERE id = $structure_id");

      $mysqli->commit();
    } catch (Exception $e) {
      $mysqli->rollback();
      $alerts[] = ['danger', $e->getMessage()];
    }
  } else {
    $alerts[] = ['danger', 'Fee structure item not found.'];
  }
}

// Fetch all fee items
$fee_items = [];
$res = $mysqli->query("SELECT id, name FROM fee_items ORDER BY name ASC");
while ($row = $res->fetch_assoc()) {
  $fee_items[] = $row;
}

// Fetch assigned items
$assigned_items = [];
$res = $mysqli->query("SELECT fsi.id, fi.name, fsi.amount, fsi.mandatory FROM fee_structure_items fsi JOIN fee_items fi ON fsi.fee_item_id = fi.id WHERE fsi.fee_structure_id = $structure_id ORDER BY fsi.id ASC");
while ($row = $res->fetch_assoc()) {
  $row['amount_display'] = money_format_naira($row['amount']);
  $assigned_items[] = $row;
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
              <h3 class="fw-bold mb-3">Modify Fee Structure</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Modify Fee Structure</li>
              </ol>
            </div>
          </div>

          <!-- fee structure edit widget  -->
          <!-- Row Card No Padding -->
          <div class="row row-card-no-pd">
            <div class="col-sm-6 col-md-4">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row">
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="icon-pie-chart text-warning"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">Class</p>
                        <h4 class="card-title"><?= htmlspecialchars($structure['class']) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
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
                        <p class="card-category">Arm</p>
                        <h4 class="card-title"><?= htmlspecialchars($structure['arm']) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row">
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="icon-close text-danger"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">Term</p>
                        <h4 class="card-title"><?= htmlspecialchars($structure['term']) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Row Card No Padding -->
          <div class="row row-card-no-pd">
            <div class="col-sm-6 col-md-4">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row">
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="icon-pie-chart text-warning"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">Session</p>
                        <h4 class="card-title"><?= htmlspecialchars($structure['session']) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
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
                        <p class="card-category">Hostel</p>
                        <h4 class="card-title"><?= htmlspecialchars($structure['hostel_type']) ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row">
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="icon-close text-danger"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">Total</p>
                        <h4 class="card-title"><?= number_format($structure['total_amount']) ?></h4>
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
                <h4 class="card-title">
                  <form method="post" class="row g-2">
                    <input type="hidden" name="action" value="assign">
                    <div class="col-md-4">
                      <select name="fee_item_id" class="form-select form-control" required>
                        <option value="">Select Fee Item</option>
                        <?php foreach ($fee_items as $fi): ?>
                          <option value="<?= $fi['id'] ?>"><?= htmlspecialchars($fi['name']) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <input type="text" name="amount" class="form-control" placeholder="Amount (₦)" required>
                    </div>
                    <div class="col-md-3">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mandatory" id="mandatory" checked>
                        <label class="form-check-label" for="mandatory">Mandatory</label>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <button class="btn btn-primary rounded-5" type="submit">Assign Item</button>
                    </div>
                  </form>
                </h4>
              </div>
              <div class="card-body">

                <div class="table-responsive">
                  <table class="table table-bordered table-striped table-hover bg-white" id="basic-datatables">
                    <thead class="table-light">
                      <tr>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Mandatory</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($assigned_items as $ai): ?>
                        <tr>
                          <form method="post">
                            <input type="hidden" name="fsi_id" value="<?= $ai['id'] ?>">
                            <td><?= htmlspecialchars($ai['name']) ?></td>
                            <td style="min-width:200px;">
                              <input type="text" name="amount" value="<?= $ai['amount'] ?>" class="form-control" required>
                            </td>
                            <td class="text-center">
                              <input type="checkbox" name="mandatory" <?= $ai['mandatory'] ? 'checked' : '' ?>>
                            </td>
                            <td class="d-flex gap-2">
                              <button name="action" value="update_item" class="btn btn-sm btn-success rounded-5">Update</button>
                              <button name="action" value="unassign" class="btn btn-sm btn-danger rounded-5" onclick="return confirm('Unassign this item?')">Unassign</button>
                            </td>
                          </form>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>

              </div>
              <div class="card-footer text-center">
                <a href="fee_structures.php" class="btn btn-secondary rounded-5">Back to Structures</a>
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