<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('db_connection.php');

// Fetch alumni students
$sql = "SELECT * FROM students WHERE status = 1";
$result = $conn->query($sql);
$alumni = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alumni[] = $row;
    }
}

// Count alumni for display
$alumni_count = count($alumni);

// Fetch the logged-in Staff name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>
<body>
<div class="wrapper">
    <!-- Sidebar -->
    <?php include('adminnav.php'); ?>

    <div class="main-panel">
        <div class="main-header">
            <div class="main-header-logo">
                <?php include('logo_header.php'); ?>
            </div>
            <?php include('navbar.php'); ?>
        </div>

        <div class="container">
            <div class="page-inner">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                    <div>
                        <h3 class="fw-bold mb-3">Alumni</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="students.php">Home</a></li>
                            <li class="breadcrumb-item active">Alumni</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-round">
                            <div class="card-body">
                                <div class="card-head-row card-tools-still-right">
                                    <div class="card-title">Alumni List</div>
                                    <div class="card-tools">
                                        <span class="badge bg-primary text-white">
                                            Total: <?php echo $alumni_count; ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="table-responsive py-4">
                                    <table id="basic-datatables" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($alumni)): ?>
                                                <?php foreach ($alumni as $student): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                                                        <td><?php echo htmlspecialchars($student['gender']); ?></td>
                                                        <td><?php echo htmlspecialchars($student['studentmobile']); ?></td>
                                                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No alumni records found.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>

    <?php include('cust-color.php'); ?>
</div>

<?php include('scripts.php'); ?>
</body>
</html>
