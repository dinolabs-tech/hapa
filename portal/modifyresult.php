<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
include 'db_connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include helper functions
require_once('helpers/database_locks.php');

// Handle Edit Request for result
if (isset($_POST['edit'])) {
    $id      = $conn->real_escape_string($_POST['id']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $term    = $conn->real_escape_string($_POST['term']);
    $session = $conn->real_escape_string($_POST['session']);

    // Use FOR UPDATE to lock the row while editing
    $stmt = $conn->prepare("SELECT * FROM mastersheet 
              WHERE id=? AND subject=? AND term=? AND csession=? FOR UPDATE");
    $stmt->bind_param("ssss", $id, $subject, $term, $session);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $mrow = $result->fetch_assoc();
        // Store original values hash for optimistic locking
        $_SESSION['edit_hash'] = md5(serialize($mrow));
        $_SESSION['edit_time'] = time();
    } else {
        echo "Record not found.";
    }
    $stmt->close();
}



// Handle Update Request
if (isset($_POST['update'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $term = $conn->real_escape_string($_POST['term']);
    $session = $conn->real_escape_string($_POST['session']);
    $exam = floatval($_POST['exam']);
    $ca1 = floatval($_POST['ca1']);
    $ca2 = floatval($_POST['ca2']);
    $lastcum = floatval($_POST['lastcum']);

    // Calculate total and average
    $total = $ca1 + $ca2 + $exam;

    // Adjust average calculation
    if ($lastcum == 0) {
        $average = $total; // Bring total score forward
    } else {
        $average = ($lastcum + $total) / 2;
    }

    // Start transaction for atomic update
    $conn->begin_transaction();
    
    try {
        // Fetch student's class to determine grading scale - with lock
        $stmt = $conn->prepare("SELECT class FROM students WHERE id = ? FOR UPDATE");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $class_result = $stmt->get_result();
        $selected_class = '';
        
        if ($class_result && $class_result->num_rows > 0) {
            $class_row = $class_result->fetch_assoc();
            $selected_class = $class_row['class'];
        }
        $stmt->close();

        // Determine grade and remark based on class
        if (in_array($selected_class, ['SSS 1', 'SSS 2', 'SSS 3'])) {
            // SSS grading
            if ($average >= 75) {
                $grade = 'A1';
                $remark = 'EXCELLENT';
            } elseif ($average >= 70) {
                $grade = 'B2';
                $remark = 'VERY GOOD';
            } elseif ($average >= 65) {
                $grade = 'B3';
                $remark = 'GOOD';
            } elseif ($average >= 60) {
                $grade = 'C4';
                $remark = 'GOOD';
            } elseif ($average >= 55) {
                $grade = 'C5';
                $remark = 'AVERAGE';
            } elseif ($average >= 50) {
                $grade = 'C6';
                $remark = 'AVERAGE';
            } elseif ($average >= 45) {
                $grade = 'D7';
                $remark = 'PASS';
            } elseif ($average >= 40) {
                $grade = 'E8';
                $remark = 'PASS';
            } else {
                $grade = 'F9';
                $remark = 'FAIL';
            }
        } else {
            // JSS grading
            if ($average >= 70) {
                $grade = 'A';
                $remark = 'EXCELLENT';
            } elseif ($average >= 60) {
                $grade = 'B';
                $remark = 'GOOD';
            } elseif ($average >= 50) {
                $grade = 'C';
                $remark = 'AVERAGE';
            } elseif ($average >= 45) {
                $grade = 'D';
                $remark = 'BELOW AVERAGE';
            } elseif ($average >= 40) {
                $grade = 'E';
                $remark = 'POOR';
            } else {
                $grade = 'F';
                $remark = 'FAIL';
            }
        }

        // Optimistic locking: Check if record was modified by another user
        $stmt = $conn->prepare("SELECT * FROM mastersheet WHERE id=? AND subject=? AND term=? AND csession=?");
        $stmt->bind_param("ssss", $id, $subject, $term, $session);
        $stmt->execute();
        $currentRecord = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        $currentHash = md5(serialize($currentRecord));
        if (isset($_SESSION['edit_hash']) && $_SESSION['edit_hash'] !== $currentHash) {
            // Record was modified by another user
            $conn->rollback();
            echo "<p style='color: red; text-align: center;'>Error: This record was modified by another user. Please refresh and try again.</p>";
        } else {
            // Update the record using prepared statement
            $stmt = $conn->prepare("UPDATE mastersheet SET 
              ca1=?, 
              ca2=?, 
              exam=?, 
              total=?, 
              average=?, 
              grade=?, 
              remark=? 
              WHERE id=? AND subject=? AND term=? AND csession=?");
            $stmt->bind_param("dddtdssssss", $ca1, $ca2, $exam, $total, $average, $grade, $remark, $id, $subject, $term, $session);
            $stmt->execute();
            $stmt->close();
            
            $conn->commit();
            
            // Clear edit session
            unset($_SESSION['edit_hash']);
            unset($_SESSION['edit_time']);
            
            echo "<p style='color: green; text-align: center;'>Record updated successfully!</p>";
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p style='color: red; text-align: center;'>Error updating record: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    // Redirect back to refresh the page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle Search Request
$searchResults = null;
if (isset($_POST['search'])) {
    $searchTerm = $conn->real_escape_string($_POST['search_term']);
    $searchResults = $conn->query("SELECT * FROM mastersheet WHERE id LIKE '%$searchTerm%' OR name LIKE '%$searchTerm%'");
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
                    <div
                        class="d-flex d-none d-lg-block align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Modify</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Home</li>
                                <li class="breadcrumb-item active">Results</li>
                                <li class="breadcrumb-item active">Modify</li>
                            </ol>
                        </div>

                    </div>

                    <!-- SEARCH STUDENTS ============================ -->
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Modify</div>
                                    </div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="mb-4 mt-2">


                                        <!-- Edit Form -->
                                        <?php if (isset($_POST['edit'])) { ?>
                                            <form method="POST">
                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($mrow['id']); ?>">

                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($mrow['name']); ?>" class="form-control" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="ca1">CA</label>
                                                    <input class="form-control" type="text" id="ca1" name="ca1" value="<?php echo htmlspecialchars($mrow['ca1']); ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="ca2">Assignment</label>
                                                    <input class="form-control" type="text" id="ca2" name="ca2" value="<?php echo htmlspecialchars($mrow['ca2']); ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="exam">Exam</label>
                                                    <input class="form-control" type="text" id="exam" name="exam" value="<?php echo htmlspecialchars($mrow['exam']); ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="lastcum">LastCum</label>
                                                    <input class="form-control" type="text" id="lastcum" name="lastcum" value="<?php echo htmlspecialchars($mrow['lastcum']); ?>" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="total">Total</label>
                                                    <input class="form-control" type="text" id="total" name="total" value="<?php echo htmlspecialchars($mrow['total']); ?>" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="average">Average</label>
                                                    <input class="form-control" type="text" id="average" name="average" value="<?php echo htmlspecialchars($mrow['average']); ?>" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="grade">Grade</label>
                                                    <input class="form-control" type="text" id="grade" name="grade" value="<?php echo htmlspecialchars($mrow['grade']); ?>" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="subject">Subject</label>
                                                    <input class="form-control" type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($mrow['subject']); ?>" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="remark">Remark</label>
                                                    <input class="form-control" type="text" id="remark" name="remark" value="<?php echo htmlspecialchars($mrow['remark']); ?>" disabled>
                                                </div>

                                                <!--hidden inputs-->
                                                <input type="hidden" name="subject" value="<?php echo htmlspecialchars($mrow['subject']); ?>">
                                                <input type="hidden" name="term" value="<?php echo htmlspecialchars($mrow['term']); ?>">
                                                <input type="hidden" name="session" value="<?php echo htmlspecialchars($mrow['csession']); ?>">
                                                <!--hidden inputs ends here-->

                                                <button type="submit" name="update" class="btn btn-success btn-icon btn-round">
                                                    <span class="fas fa-save"></span>
                                                </button>
                                            </form>
                                        <?php } ?>

                                        <br />
                                        <!-- Search Form -->
                                        <form method="POST">
                                            <div class="form-group col-md-12">
                                                <input type="text" id="search_term" name="search_term" placeholder="Enter ID or Name" class="form-control" required>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" name="search" class="btn btn-success btn-icon btn-round ps-1">
                                                    <span class="btn-label">
                                                        <i class="fa fa-search"></i>
                                                </button>
                                            </div>

                                        </form>

                                        <br />
                                        <!-- Student List Table -->
                                        <div class="table-responsive">
                                            <table id="multi-filter-select" class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Class</th>
                                                        <th>Arm</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $all_students = $conn->query("SELECT id, name, class, arm FROM students WHERE status = 0 ORDER BY name");
                                                    while ($student = $all_students->fetch_assoc()) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($student['id']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($student['name']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($student['class']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($student['arm']) . "</td>";
                                                        echo "<td>";
                                                        echo "<form style='display: inline;' method='POST'>";
                                                        echo "<input type='hidden' name='search_term' value='" . htmlspecialchars($student['name']) . "'>";
                                                        echo "<button type='submit' name='search' class='btn btn-warning btn-icon btn-round ps-1'>";
                                                        echo "<span class='btn-label'>";
                                                        echo "<i class='fa fa-edit'></i>";
                                                        echo "</button>";
                                                        echo "</form>";
                                                        echo "</td>";
                                                        echo "</tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Search Results -->
                                        <?php if (isset($searchResults)) { ?>
                                            <div class="table-responsive"> <br>
                                                <table
                                                    id="multi-filter-select"
                                                    class="display table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Name</th>
                                                            <th>Subject</th>
                                                            <th>CA</th>
                                                            <th>Assign.</th>
                                                            <th>Exam</th>
                                                            <th>LastCum</th>
                                                            <th>Total</th>
                                                            <th>AVG.</th>
                                                            <th>Grade</th>
                                                            <th>Remark</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($record = $searchResults->fetch_assoc()) { ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($record['id']); ?></td>
                                                                <td><?php echo htmlspecialchars($record['name']); ?></td>
                                                                <td><?php echo htmlspecialchars($record['subject']); ?></td>
                                                                <td><?php echo htmlspecialchars($record['ca1']); ?></td>
                                                                <td><?php echo htmlspecialchars($record['ca2']); ?></td>
                                                                <td><?php echo htmlspecialchars($record['exam']); ?></td>
                                                                <td><?php echo htmlspecialchars($record['lastcum']); ?></td>
                                                                <td><?php echo htmlspecialchars($record['total']); ?></td>
                                                                <td><?php echo htmlspecialchars($record['average']); ?></td>
                                                                <td><?php echo htmlspecialchars($record['grade']); ?></td>
                                                                <td><?php echo htmlspecialchars($record['remark']); ?></td>
                                                                <td>
                                                                    <form style="display: inline;" method="POST">
                                                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id']); ?>">
                                                                        <input type="hidden" name="subject" value="<?php echo htmlspecialchars($record['subject']); ?>">
                                                                        <input type="hidden" name="term" value="<?php echo htmlspecialchars($record['term']); ?>">
                                                                        <input type="hidden" name="session" value="<?php echo htmlspecialchars($record['csession']); ?>">
                                                                        <button type="submit" name="edit" class="btn btn-warning btn-icon btn-round ps-1">
                                                                            <span class="btn-label">
                                                                                <i class="fa fa-edit"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } ?>


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