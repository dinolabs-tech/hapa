<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('db_connection.php');



if (isset($_POST['parent_id']) && isset($_POST['student_ids'])) {
    $parent_id = $_POST['parent_id'];
    $student_ids = $_POST['student_ids'];

   // Delete existing relationships for the parent using prepared statement
    $stmt = $conn->prepare("DELETE FROM parent_student WHERE parent_id = ?");
    $stmt->bind_param("i", $parent_id);
    $stmt->execute();
    $stmt->close();

    // Insert new relationships
    foreach ($student_ids as $student_id) {
       $stmt = $conn->prepare("INSERT INTO parent_student (parent_id, student_id) VALUES (?, ?)");
        $stmt->bind_param("is", $parent_id, $student_id);
        $stmt->execute();
        $stmt->close();
    }

    $success = "Students assigned successfully.";
}

// Fetch all parents
$sql = "SELECT id, name FROM parent";
$parents = $conn->query($sql);

// Fetch all students
$sql = "SELECT id, name FROM students";
$students = $conn->query($sql);


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
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4 d-none d-lg-block">
                        <div>
                            <h3 class="fw-bold mb-3">Assign Students</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Home</li>
                                <li class="breadcrumb-item active">Assign Students</li>
                            </ol>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-body">
                                    <div class="card-head-row card-tools-still-right">
                                        <div class="card-title">Assign Students</div>
                                    </div>

                                    <?php if (!empty($success)): ?>
                                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                                    <?php endif; ?>

                                    <form method="POST">
                                        <div class="mb-3">
                                            <label for="parent_id" class="form-label">Select Parent:</label>
                                            <select name="parent_id" id="parent_id" class="form-select">
                                                <?php
                                                if ($parents && $parents->num_rows > 0) {
                                                    while ($parent = $parents->fetch_assoc()) {
                                                        echo "<option value='" . htmlspecialchars($parent['id']) . "'>" . htmlspecialchars($parent['name']) . "</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>No parents found</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Select Students:</label>
                                            <?php if ($students && $students->num_rows > 0): ?>
                                                <div class="table-responsive">

                                                    <table id="basic-datatables" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Select</th>
                                                                <th>Student ID</th>
                                                                <th>Name</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php while ($student = $students->fetch_assoc()): ?>
                                                                <tr>
                                                                    <td>
                                                                        <input type="checkbox" name="student_ids[]"
                                                                            value="<?= htmlspecialchars($student['id']) ?>">
                                                                    </td>
                                                                    <td><?= htmlspecialchars($student['id']) ?></td>
                                                                    <td><?= htmlspecialchars($student['name']) ?></td>
                                                                </tr>
                                                            <?php endwhile; ?>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            <?php else: ?>
                                                <p>No students found.</p>
                                            <?php endif; ?>
                                        </div>


                                        <button type="submit" class="btn btn-primary">Assign</button>
                                    </form>

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
