<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('db_connection.php');


$success = "";

if (isset($_GET['unassign_id'])) {
    $unassign_id = $_GET['unassign_id'];

    // Delete the record from the parent_student table
    $sql = "DELETE FROM parent_student WHERE id = '$unassign_id'";
    if ($conn->query($sql) === TRUE) {
        $success = "Student unassigned successfully.";
    } else {
        $success = "Error unassigning student: " . $conn->error;
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
                            <h3 class="fw-bold mb-3">Unassign Students</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Unassign Students</li>
                            </ol>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-body">
                                    <div class="card-head-row card-tools-still-right">
                                        <div class="card-title">Unassign Students</div>
                                    </div>

                                    <?php if (!empty($success)): ?>
                                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                                    <?php endif; ?>

                                    <?php
                                    // Fetch assigned students and their parents
                                    $sql = "SELECT ps.id, s.name AS student_name, p.name AS parent_name
                        FROM parent_student ps
                        INNER JOIN students s ON ps.student_id = s.id
                        INNER JOIN parent p ON ps.parent_id = p.id";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        echo "<div class='table-responsive'>";
                                        echo "<table id='basic-datatables' class='table table-striped'>";
                                        echo "<thead><tr><th>Student Name</th><th>Parent Name</th><th>Action</th></tr></thead>";
                                        echo "<tbody>";
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['student_name'] . "</td>";
                                            echo "<td>" . $row['parent_name'] . "</td>";
                                            echo "<td><a href='?unassign_id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Unassign</a></td>";
                                            echo "</tr>";
                                        }
                                        echo "</tbody>";
                                        echo "</table>";
                                        echo "</div>";
                                    } else {
                                        echo "<p>No students are currently assigned to parents.</p>";
                                    }
                                    ?>

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