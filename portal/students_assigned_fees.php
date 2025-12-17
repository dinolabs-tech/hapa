<?php
session_start();
include('components/admin_logic.php');
require_once('db_connection.php');
require_once('helpers/audit.php');
require_once('helpers/money.php');

$alerts = [];

// Fetch dropdown options
$classes = [];
$result = $mysqli->query("SELECT DISTINCT class FROM students ORDER BY class");
while ($row = $result->fetch_assoc()) {
  $classes[] = $row['class'];
}

$arms = [];
$result = $mysqli->query("SELECT DISTINCT arm FROM students ORDER BY arm");
while ($row = $result->fetch_assoc()) {
  $arms[] = $row['arm'];
}

$sessions = [];
$result = $mysqli->query("SELECT DISTINCT session FROM students ORDER BY session");
while ($row = $result->fetch_assoc()) {
  $sessions[] = $row['session'];
}

$hostels = [];
$result = $mysqli->query("SELECT DISTINCT hostel FROM students ORDER BY hostel");
while ($row = $result->fetch_assoc()) {
  $hostels[] = $row['hostel'];
}

$terms = ['1st Term', '2nd Term', '3rd Term'];
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
    $where[] = "s.$k = ?";
    $params[] = $v;
    $types .= 's';
  }
}
$sql = "SELECT DISTINCT s.id, s.name, s.class, s.arm, s.term, s.session, s.hostel FROM students s JOIN student_fees sf ON s.id = sf.student_id WHERE sf.status='active'";
if ($where) $sql .= " AND " . implode(' AND ', $where);
$sql .= " ORDER BY s.id DESC";
$stmt = $mysqli->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$students = [];
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
  $students[] = $row;
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
              <h3 class="fw-bold mb-3">Students with Assigned Fees</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Students with Assigned Fees</li>
              </ol>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
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
                  <div class="col-md-2"><button class="btn btn-primary rounded-5" type="submit">Filter</button></div>
                </form>
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
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($students as $st): ?>
                        <tr>
                          <td><?= $st['id'] ?></td>
                          <td><a href="student_bursary_profile.php?id=<?= $st['id'] ?>"><?= htmlspecialchars($st['name']) ?></a></td>
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