<?php include('components/admin_logic.php');


// Initialize arrays for classes and arms
$classes = [];
$arms = [];

// Fetch classes from the database
try {
    $class_result = $conn->query("SELECT class FROM class");
    if ($class_result) {
        while ($row = $class_result->fetch_assoc()) {
            $classes[] = $row['class'];
        }
    } else {
        throw new Exception("Error fetching classes: " . $conn->error);
    }

    // Fetch arms from the database
    $arm_result = $conn->query("SELECT arm FROM arm");
    if ($arm_result) {
        while ($row = $arm_result->fetch_assoc()) {
            $arms[] = $row['arm'];
        }
    } else {
        throw new Exception("Error fetching arms: " . $conn->error);
    }
} catch (Exception $e) {
    die("Database query failed: " . $e->getMessage());
}

// Initialize variables for search results
$students = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize input, use this for the web portal
    //$selected_class = filter_input(INPUT_POST, 'class', FILTER_SANITIZE_STRING);
    //$selected_arm = filter_input(INPUT_POST, 'arm', FILTER_SANITIZE_STRING);

    //use this for offline
    $selected_class = $_POST['class'] ?? '';
    $selected_arm = $_POST['arm'] ?? '';


    if ($selected_class && $selected_arm) {
        try {
            // Fetch students based on selected class and arm
            $stmt = $conn->prepare("SELECT id, name, class, arm FROM students WHERE class = ? AND arm = ? AND status != 1");
            if ($stmt) {
                $stmt->bind_param("ss", $selected_class, $selected_arm);
                $stmt->execute();

                // Bind result variables
                $stmt->bind_result($id, $name, $class, $arm);
                while ($stmt->fetch()) {
                    $students[] = [
                        'id' => $id,
                        'name' => $name,
                        'class' => $class,
                        'arm' => $arm,
                    ];
                }
                $stmt->close();
            } else {
                throw new Exception("Error preparing statement: " . $conn->error);
            }
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    } else {
        echo "<p>Please select both Class and Arm.</p>";
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
                            <h3 class="fw-bold mb-3">Filter Students</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Students</li>
                                <li class="breadcrumb-item active">Filter Students</li>
                            </ol>
                        </div>

                    </div>

                    <!-- BULK UPLOAD ============================ -->
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Filter Students Registered</div>
                                    </div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="mb-4 mt-2">

                                        <form method="post">

                                            <select name="class" id="class" class="form-control form-select mb-3"
                                                required>
                                                <option value="" disabled selected>-- Select Class --</option>
                                                <?php foreach ($classes as $class): ?>
                                                    <option value="<?= htmlspecialchars($class, ENT_QUOTES) ?>">
                                                        <?= htmlspecialchars($class, ENT_QUOTES) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>


                                            <select name="arm" id="arm" class="form-control form-select mb-3" required>
                                                <option value="" disabled selected>-- Select Arm --</option>
                                                <?php foreach ($arms as $arm): ?>
                                                    <option value="<?= htmlspecialchars($arm, ENT_QUOTES) ?>">
                                                        <?= htmlspecialchars($arm, ENT_QUOTES) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>


                                            <div class="text-start mt-3">
                                                <button type="submit" class="btn btn-success">
                                                    <span class="btn-label">
                                                        <i class="fa fa-filter"></i>
                                                    </span>
                                                    Filter
                                                </button>
                                            </div>
                                        </form>

                                        <p></p>

                                        <?php if (!empty($students)): ?>
                                            <div class="table-responsive">
                                                <table id="multi-filter-select"
                                                    class="display table table-striped table-hover">

                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Name</th>
                                                            <th>Class</th>
                                                            <th>Arm</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($students as $student): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($student['id'], ENT_QUOTES) ?></td>
                                                                <td><?= htmlspecialchars($student['name'], ENT_QUOTES) ?></td>
                                                                <td><?= htmlspecialchars($student['class'], ENT_QUOTES) ?></td>
                                                                <td><?= htmlspecialchars($student['arm'], ENT_QUOTES) ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <p>No records found.</p>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Student Profile Modal -->
                                        <div class="modal fade" id="studentModal" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Student Profile Card</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <img id="studentImage" src="" alt="Student Image"
                                                                class="profile-img">
                                                            <h4 id="studentName"></h4>
                                                        </div>
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td><strong>ID:</strong></td>
                                                                <td id="studentId"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Gender:</strong></td>
                                                                <td id="studentGender"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Date of Birth:</strong></td>
                                                                <td id="studentDob"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Place of Birth:</strong></td>
                                                                <td id="studentPlaceOb"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Religion:</strong></td>
                                                                <td id="studentReligion"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>State:</strong></td>
                                                                <td id="studentState"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>LGA:</strong></td>
                                                                <td id="studentLga"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Class:</strong></td>
                                                                <td id="studentClass"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Arm:</strong></td>
                                                                <td id="studentArm"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Hostel:</strong></td>
                                                                <td id="studentHostel"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Blood Type:</strong></td>
                                                                <td id="studentBloodType"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Blood Group:</strong></td>
                                                                <td id="studentBloodGroup"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Height:</strong></td>
                                                                <td id="studentHeight"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Weight:</strong></td>
                                                                <td id="studentWeight"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Guardian Name:</strong></td>
                                                                <td id="studentGname"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Guardian Occupation:</strong></td>
                                                                <td id="studentGoccupation"></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="200px"><strong>Guardian Mobile:</strong></td>
                                                                <td id="studentMobile"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><strong>Address:</strong> <span
                                                                        id="studentAddress"></span></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>






                </div>
            </div>

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