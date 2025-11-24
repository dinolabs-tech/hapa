<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('db_connection.php');


$success = "";
$student_id = "";
$gname = "";
$mobile = "";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $student_id = $_POST['student_id'];

    // Get gname and mobile from students table
    $sql = "SELECT gname, mobile FROM students WHERE id = '$student_id'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $gname = $student['gname'];
        $mobile = $student['mobile'];

        // Check if the parent name already exists
        $sql = "SELECT id FROM parent WHERE name = '$gname'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $success = "Parent name already exists.";
        } else {
            // Check if the username already exists
            $sql = "SELECT id FROM parent WHERE username = '$username'";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $success = "Username already exists.";
            } else {
                // Insert the new parent into the database
                $sql = "INSERT INTO parent (username, password, name, mobile) VALUES ('$username', '$password', '$gname', '$mobile')";
                if ($conn->query($sql) === TRUE) {
                    $success = "Parent registered successfully.";
                } else {
                    $success = "Error registering parent: " . $conn->error;
                }
            }
        }
    } else {
        $success = "Student not found.";
    }
}

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Fetch student details
    $sql = "SELECT gname, mobile FROM students WHERE id = '$student_id'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $gname = $student['gname'];
        $mobile = $student['mobile'];
    } else {
        $success = "Student not found.";
    }
}


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
                            <h3 class="fw-bold mb-3">Register Parents</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Register Parents</li>
                            </ol>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-body">
                                    <div class="card-head-row card-tools-still-right">
                                        <div class="card-title">Parent Data</div>
                                    </div>

                                    <?php if (!empty($success)): ?>
                                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                                    <?php endif; ?>

                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Student Name</th>
                                                    <th>Guardian Name</th>
                                                    <th>Mobile</th>
                                                    <th>Occupation</th>
                                                    <th>Address</th>
                                                    <th>Relationship</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Fetch parent details from the students table
                                                $sql = "SELECT id, name, gname, mobile, goccupation, gaddress, grelationship FROM students";
                                                $result = $conn->query($sql);

                                                if ($result && $result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) { ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['name']) ?></td>
                                                            <td><?= htmlspecialchars($row['gname']) ?></td>
                                                            <td><?= htmlspecialchars($row['mobile']) ?></td>
                                                            <td><?= htmlspecialchars($row['goccupation']) ?></td>
                                                            <td><?= htmlspecialchars($row['gaddress']) ?></td>
                                                            <td><?= htmlspecialchars($row['grelationship']) ?></td>
                                                            <td><a href='?id=<?= htmlspecialchars($row['id']) ?>' class='btn btn-warning btn-icon btn-round'><i class="fas fa-edit"></i></a></td>
                                                        </tr>
                                                    <?php  }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan='6' class='text-center'>No parent details found in the students table.</td>
                                                    </tr>
                                                <?php  }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($_GET['id'])): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-round">
                                    <div class="card-body">
                                        <div class="card-head-row card-tools-still-right">
                                            <div class="card-title">Register Parent</div>
                                        </div>

                                        <form method="POST">
                                            <input type="hidden" name="student_id"
                                                value="<?= htmlspecialchars($student_id) ?>">
                                            <div class="mb-3">
                                                <label for="gname" class="form-label">Name:</label>
                                                <input type="text" id="gname" name="gname" class="form-control"
                                                    value="<?= htmlspecialchars($gname) ?>" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="mobile" class="form-label">Mobile:</label>
                                                <input type="text" id="mobile" name="mobile" class="form-control"
                                                    value="<?= htmlspecialchars($mobile) ?>" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username:</label>
                                                <input type="text" id="username" name="username" class="form-control"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password:</label>
                                                <input type="password" id="password" name="password" class="form-control"
                                                    required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Register</button>
                                        </form>


                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <?php include('footer.php'); ?>
        </div>

        <?php include('cust-color.php'); ?>
    </div>

    <?php include('scripts.php'); ?>
</body>

</html>