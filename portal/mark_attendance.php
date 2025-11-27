<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session to maintain user state
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Database connection
include 'db_connection.php';

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}



// Fetch classes from the database
$classes = array();
$sql_classes = "SELECT class FROM class";
$result_classes = $conn->query($sql_classes);
if ($result_classes->num_rows > 0) {
  while ($row = $result_classes->fetch_assoc()) {
    $classes[] = $row["class"];
  }
}

// Fetch arms from the database
$arms = array();
$sql_arms = "SELECT arm FROM arm";
$result_arms = $conn->query($sql_arms);
if ($result_arms->num_rows > 0) {
  while ($row = $result_arms->fetch_assoc()) {
    $arms[] = $row["arm"];
  }
}

// Get selected class and arm
$selected_class = isset($_GET['class']) ? $_GET['class'] : '';
$selected_arm = isset($_GET['arm']) ? $_GET['arm'] : '';
$selected_date = isset($_GET['attendance_date']) ? $_GET['attendance_date'] : date('Y-m-d');

// Fetch students based on selected class and arm
$students = array();
$sql_students = "SELECT id, name FROM students WHERE status=0 1=1"; // Start with a neutral condition
if (!empty($selected_class)) {
  $sql_students .= " AND class = '$selected_class'";
}
if (!empty($selected_arm)) {
  $sql_students .= " AND arm = '$selected_arm'";
}
$result_students = $conn->query($sql_students);
if ($result_students->num_rows > 0) {
  while ($row = $result_students->fetch_assoc()) {
    $students[$row["id"]] = $row["name"];
  }
}

// Fetch attendance data for the selected date
$attendance_data = array();
$sql_attendance = "SELECT name, class, arm, status FROM attendance WHERE date = '$selected_date'";
$result_attendance = $conn->query($sql_attendance);
if ($result_attendance->num_rows > 0) {
  while ($row = $result_attendance->fetch_assoc()) {
    $attendance_data[$row["name"] . " " . $row["class"] . " " . $row["arm"]] = $row["status"];
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



// Close database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>

<script>
  $(function () {
    // Set default date from PHP
    var selectedDate = "<?php echo $selected_date; ?>";
    $("#datepicker").datepicker({
      dateFormat: 'yy-mm-dd',
      onSelect: function (dateText) {
        $("#attendance_date").val(dateText);
        $("#attendanceForm").submit();
      }
    });

    // Set the value of the datepicker input
    $("#datepicker").datepicker("setDate", selectedDate);
  });
</script>

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
              <nts class="fw-bold mb-3">Attendance</h3>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">Mark Attendance</li>
                </ol>
            </div>

          </div>

          <!-- BULK UPLOAD ============================ -->

          <div class="row">

            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Filter</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <p>
                    <form id="attendanceForm" method="get">
                      <div class="row g-3 mb-3">
                        <div class="col-md-4">
                          <select class="form-control" id="class" name="class" onchange="this.form.submit()">
                            <option value="">All Classes</option>
                            <?php foreach ($classes as $class_name): ?>
                              <option value="<?php echo $class_name; ?>" <?php if ($selected_class == $class_name)
                                   echo "selected"; ?>>
                                <?php echo $class_name; ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                        <div class="col-md-4">
                          <select class="form-control" id="arm" name="arm" onchange="this.form.submit()">
                            <option value="">All Arms</option>
                            <?php foreach ($arms as $arm_name): ?>
                              <option value="<?php echo $arm_name; ?>" <?php if ($selected_arm == $arm_name)
                                   echo "selected"; ?>>
                                <?php echo $arm_name; ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                        <div class="col-md-4">
                          <input type="date" id="attendance_date" name="attendance_date" class="form-control"
                            value="<?php echo $selected_date; ?>"
                            onchange="document.getElementById('attendanceForm').submit();">
                        </div>
                      </div>


                    </form>
                    </p>

                  </div>
                </div>
              </div>
            </div>
          </div>



          <div class="row">

            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Subject List</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <form method="post" action="mark_attendance_process.php">
                      <input type="hidden" name="attendance_date" value="<?php echo $selected_date; ?>">
                      <input type="hidden" name="class" value="<?php echo $selected_class; ?>">
                      <input type="hidden" name="arm" value="<?php echo $selected_arm; ?>">

                      <?php if (empty($students)): ?>
                        <p>No students found for the selected class and arm.</p>

                        <div class="table-responsive">
                        <?php else: ?>


                          <table class="table table-bordered" id="basic-datatables">
                            <thead>
                              <tr>
                                <th>Student Name</th>
                                <th>Present</th>
                                <th>Absent</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($students as $student_id => $student_name): ?>
                                <?php
                                $student_key = $student_name . " " . $selected_class . " " . $selected_arm;
                                ?>
                                <tr>
                                  <td><?php echo $student_name; ?></td>
                                  <td><input type="radio"
                                      name="attendance[<?php echo $student_id; ?>][<?php echo $student_name; ?>][<?php echo $selected_class; ?>][<?php echo $selected_arm; ?>]"
                                      value="1" <?php if (isset($attendance_data[$student_key]) && $attendance_data[$student_key] == 1)
                                        echo "checked"; ?>></td>
                                  <td><input type="radio"
                                      name="attendance[<?php echo $student_id; ?>][<?php echo $student_name; ?>][<?php echo $selected_class; ?>][<?php echo $selected_arm; ?>]"
                                      value="0" <?php if (isset($attendance_data[$student_key]) && $attendance_data[$student_key] == 0)
                                        echo "checked"; ?>></td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>


                          <button type="button" class="btn btn-primary" onclick="saveAttendance()">Save
                            Attendance</button>
                        <?php endif; ?>

                      </div>
                    </form>

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
  <script>
    function saveAttendance() {
      var formData = new FormData(document.querySelector('form[action="mark_attendance_process.php"]'));

      fetch('mark_attendance_process.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          alert(data.message);
        })
    }
  </script>


</body>

</html>