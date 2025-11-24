<?php
include('components/admin_logic.php');

// MODIFY STUDENTS =============================
// Handle form submission for updating student record and image
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $revoke = $_POST['result'];

    $sql = "UPDATE students SET 
                name='$name', 
                status='$revoke' 
            WHERE id='$id'";

    if ($conn->query($sql)) {
        echo "<script>alert('Operation successful.'); window.location.href='deactivate_student.php';</script>";
    } else {
        echo "<script>alert('Error Deactivating Account: " . $conn->error . "');</script>";
    }
}



// Search query for student based on ID or name
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];
    $searchQuery = "WHERE name LIKE '%$searchTerm%' OR id LIKE '%$searchTerm%'";
}

// Fetch student records
$sql = "SELECT * FROM students WHERE class != 'YEAR 12'";
$result = $conn->query($sql);

// Convert result set into an array so it can be looped over safely later
$students = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Fetch student details for editing if an ID is passed in the URL
$studentDetails = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $studentSql = "SELECT * FROM students WHERE id='$id'";
    $studentResult = $conn->query($studentSql);
    if ($studentResult->num_rows > 0) {
        $studentDetails = $studentResult->fetch_assoc();
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>

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
                            <h3 class="fw-bold mb-3">Deactivate Account</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Student</li>
                                <li class="breadcrumb-item active">Deactivate Account</li>
                            </ol>
                        </div>
                    </div>

                    <!-- MODIFY STUDENTS============================= -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Deactivate Student Account</div>
                                    </div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="mb-4 mt-2">
                                        <?php if ($studentDetails): ?>
                                            <form method="POST" class="row g-3" enctype="multipart/form-data">
                                                <input type="hidden" name="id" value="<?php echo $studentDetails['id']; ?>">
                                                <div class="col-md-6">
                                                    <input class="form-control form-control" type="text" name="name"
                                                        value="<?php echo $studentDetails['name']; ?>" placeholder="Name"
                                                        required>
                                                </div>
                                                <div class="col-md-4">
                                                    <select name="result" class="form-control form-select" required>
                                                        <option value="" selected disabled>Select an Option</option>
                                                        <option value="1" <?php echo $studentDetails['result'] == 1 ? 'selected' : ''; ?>>Deactivate Student Account</option>
                                                        <option value="0" <?php echo $studentDetails['result'] == 0 ? 'selected' : ''; ?>>Activate Student Account</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <button type="submit" name="update" class="btn btn-danger btn-icon btn-round ps-1">
                                                        <span class="btn-label">
                                                            <i class="fa fa-times"></i>
                                                        </span>
                                                    </button>
                                                </div>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STUDENT RECORDS ========================== -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Students Records</div>
                                    </div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="mb-4 mt-2">
                                        <div class="table-responsive">
                                            <table id="multi-filter-select"
                                                class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Class</th>
                                                        <th>Arm</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($students)): ?>
                                                        <?php foreach ($students as $student): ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($student['id']); ?></td>
                                                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                                                <td><?php echo htmlspecialchars($student['class']); ?></td>
                                                                <td><?php echo htmlspecialchars($student['arm']); ?></td>
                                                                <td>
                                                                    <?php if ($student['status'] == '0') { ?>
                                                                        <span class="badge bg-success">Active</span>
                                                                    <?php } else { ?>
                                                                        <span class="badge bg-danger">Inactive</span>
                                                                    <?php } ?>
                                                                </td>

                                                                <td>
                                                                    <a href="?edit=<?php echo $student['id']; ?>"
                                                                        class="btn btn-warning btn-icon btn-round ps-1">
                                                                        <span class="fas fa-edit"></span></a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="5">No data available in table.</td>
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
            </div>
            <script>
                document.querySelector('button[type="reset"]').addEventListener('click', function() {
                    document.getElementById('myForm').reset();
                });
            </script>
            <?php include('footer.php'); ?>
        </div>

        <!-- Custom template | don't include it in your project! -->
        <?php include('cust-color.php'); ?>
        <!-- End Custom template -->
    </div>
    <?php include('scripts.php'); ?>
</body>

</html>