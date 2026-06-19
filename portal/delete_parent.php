<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('db_connection.php');


// Handle delete action
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);

    // Fetch parent record before deletion for audit logging
    $parent_before = null;
    $stmt_fetch = $conn->prepare("SELECT * FROM parent WHERE id=?");
    $stmt_fetch->bind_param("i", $delete_id);
    $stmt_fetch->execute();
    $result_fetch = $stmt_fetch->get_result();
    if ($result_fetch && $row = $result_fetch->fetch_assoc()) {
        $parent_before = $row;
    }
    $stmt_fetch->close();

    // Check if the logged-in user is NOT a superuser
    $user_id = $_SESSION['user_id'] ?? 0;
    $is_superuser = false;
    $stmt_role = $conn->prepare("SELECT role FROM login WHERE id=?");
    $stmt_role->bind_param("i", $user_id);
    $stmt_role->execute();
    $stmt_role->bind_result($user_role);
    if ($stmt_role->fetch() && $user_role === 'Superuser') {
        $is_superuser = true;
    }
    $stmt_role->close();

    // Delete the record from the parent_student table
    $stmt_parent = $conn->prepare("DELETE FROM parent_student WHERE parent_id=?");
    $stmt_parent->bind_param("i", $delete_id);
    $stmt_parent->execute();
    $stmt_parent->close();

    // Delete the record from the parent table
    $stmt = $conn->prepare("DELETE FROM parent WHERE id=?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        // Log the deletion in audit_logs if user is not a superuser
        if (!$is_superuser && $parent_before !== null) {
            require_once('helpers/audit.php');
            audit_log('delete', 'parent', $delete_id, $parent_before, null);
        }
        $success = "Parent deleted successfully.";
    } else {
        $success = "Error deleting parent: " . $stmt->error;
    }
    $stmt->close();
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