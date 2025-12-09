<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('db_connection.php');


// Handle delete action
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Delete the record from the parent_student table
    $sql = "DELETE FROM parent_student WHERE parent_id = '$delete_id'";
    if ($conn->query($sql) === TRUE) {
        // Delete the record from the parent table
        $sql = "DELETE FROM parent WHERE id = '$delete_id'";
        if ($conn->query($sql) === TRUE) {
            $success = "Parent deleted successfully.";
        } else {
            $success = "Error deleting parent: " . $conn->error;
        }
    } else {
        $success = "Error deleting parent: " . $conn->error;
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
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4 d-none d-lg-block">
                        <div>
                            <h3 class="fw-bold mb-3">Delete Parents</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Home</li>
                                <li class="breadcrumb-item active">Delete Parents</li>
                            </ol>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-body">
                                    <div class="card-head-row card-tools-still-right">
                                        <div class="card-title">Parent Accounts</div>
                                    </div>

                                    <div class="table-responsive">
                                        <?php if (!empty($success)): ?>
                                            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                                        <?php endif; ?>
                                        <table id="basic-datatables" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Username</th>
                                                    <th>Password</th>
                                                    <th>Mobile</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT id, name, username, password, mobile FROM parent";
                                                $result = $conn->query($sql);

                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["password"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["mobile"]) . "</td>";
                                                        echo "<td>
                                <a href='?delete=" . $row["id"] . "' class='btn btn-sm btn-danger'>Delete</a>
                              </td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='4'>No Parents found.</td></tr>";
                                                }
                                                ?>
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