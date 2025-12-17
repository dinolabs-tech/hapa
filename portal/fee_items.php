<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('components/admin_logic.php');

require_once('helpers/audit.php');
require_once('helpers/money.php');
require_once('helpers/exports.php');


$alerts = [];
$action = $_POST['action'] ?? null;

// Handle create
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $desc = trim($_POST['description'] ?? '');
  $mandatory = isset($_POST['mandatory']) ? 1 : 0;
  if ($name === '') {
    $alerts[] = ['danger', 'Fee item name is required.'];
  } else {
    $stmt = $mysqli->prepare("INSERT INTO fee_items (name, description, mandatory) VALUES (?, ?, ?)");
    $stmt->bind_param('ssi', $name, $desc, $mandatory);
    $before = null;
    $after = ['name' => $name, 'description' => $desc, 'mandatory' => $mandatory];
    if ($stmt->execute()) {
      audit_log('create', 'fee_item', $stmt->insert_id, $before, $after);
      $alerts[] = ['success', 'Fee item created.'];
    } else {
      $alerts[] = ['danger', 'Error creating fee item.'];
    }
    $stmt->close();
  }
}

// Handle update
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id'] ?? 0);
  $name = trim($_POST['name'] ?? '');
  $desc = trim($_POST['description'] ?? '');
  $mandatory = isset($_POST['mandatory']) ? 1 : 0;
  $stmt = $mysqli->prepare("SELECT * FROM fee_items WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $before = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if ($before) {
    $stmt = $mysqli->prepare("UPDATE fee_items SET name=?, description=?, mandatory=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param('ssii', $name, $desc, $mandatory, $id);
    $after = ['name' => $name, 'description' => $desc, 'mandatory' => $mandatory];
    if ($stmt->execute()) {
      audit_log('update', 'fee_item', $id, $before, $after);
      $alerts[] = ['success', 'Fee item updated.'];
    } else {
      $alerts[] = ['danger', 'Error updating fee item.'];
    }
    $stmt->close();
  } else {
    $alerts[] = ['danger', 'Fee item not found.'];
  }
}

// Handle delete
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id'] ?? 0);
  $stmt = $mysqli->prepare("SELECT * FROM fee_items WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $before = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if ($before) {
    $stmt = $mysqli->prepare("DELETE FROM fee_items WHERE id=?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
      audit_log('delete', 'fee_item', $id, $before, null);
      $alerts[] = ['success', 'Fee item deleted.'];
    } else {
      $alerts[] = ['danger', 'Error deleting fee item.'];
    }
    $stmt->close();
  } else {
    $alerts[] = ['danger', 'Fee item not found.'];
  }
}

// Export
if (isset($_GET['export']) && in_array($_GET['export'], ['csv', 'pdf'])) {
  $result = $mysqli->query("SELECT id, name, description, mandatory, created_at FROM fee_items");
  $data = [];
  while ($row = $result->fetch_assoc()) {
    $row['mandatory'] = $row['mandatory'] ? 'Yes' : 'No';
    $data[] = $row;
  }
  $type = $_GET['export'];
  $filename = "fee_items_" . date('Ymd_His') . ".$type";
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
      require_once('helpers/pdf.php');
      $pdf = new FPDF('L'); // Landscape
      $pdf->AddPage();
      $pdf->SetFont('Arial', 'B', 14);
      $pdf->Cell(0, 10, 'Fee Items', 0, 1, 'C');
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

// Fetch fee items
$items = [];
$result = $mysqli->query("SELECT id, name, description, mandatory, created_at FROM fee_items ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
  $items[] = $row;
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
              <h3 class="fw-bold mb-3">Fee Items</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Fee Items</li>
              </ol>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <!-- <h4 class="card-title">Inbox </h4> -->

                <?php foreach ($alerts as [$type, $msg]): ?>
                  <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
                <?php endforeach; ?>
              </div>

                <div class="card-body">

                  <form method="post" class="row g-2 mb-4">
                    <input type="hidden" name="action" value="create">
                    <div class="col-md-4">
                      <input type="text" name="name" class="form-control" placeholder="Fee Item Name" required>
                    </div>
                    <div class="col-md-4">
                      <input type="text" name="description" class="form-control" placeholder="Description">
                    </div>
                    <div class="col-md-2">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mandatory" id="mandatory" checked>
                        <label class="form-check-label" for="mandatory">Mandatory</label>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <button class="btn btn-primary rounded-5" type="submit">Add Fee Item</button>
                    </div>
                  </form>

                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <!-- <h4 class="card-title">Inbox </h4> -->
                  <div class="text-center">
                    <a href="?export=csv" class="btn btn-outline-secondary btn-sm">Export CSV</a>
                    <a href="?export=pdf" class="btn btn-outline-secondary btn-sm">Export PDF</a>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover bg-white" id="basic-datatables">
                      <thead class="table-light">
                        <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Description</th>
                          <th>Mandatory</th>
                          <th>Created</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($items as $item): ?>
                          <tr>
                            <form method="post">
                              <input type="hidden" name="id" value="<?= $item['id'] ?>">
                              <td><?= $item['id'] ?></td>
                              <td>
                                <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" class="form-control form-control-sm" required>
                              </td>
                              <td>
                                <input type="text" name="description" value="<?= htmlspecialchars($item['description']) ?>" class="form-control form-control-sm">
                              </td>
                              <td>
                                <input type="checkbox" name="mandatory" <?= $item['mandatory'] ? 'checked' : '' ?>>
                              </td>
                              <td><?= htmlspecialchars($item['created_at']) ?></td>
                              <td>
                                <button name="action" value="update" class="btn btn-sm btn-success rounded-5">Update</button>
                                <button name="action" value="delete" class="btn btn-sm btn-danger rounded-5" onclick="return confirm('Delete this fee item?')">Delete</button>
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