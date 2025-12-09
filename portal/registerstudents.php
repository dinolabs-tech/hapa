<?php include('components/admin_logic.php');

// REGISTER STUDENTS ========================================
// Initialize variables
$students = [];
$student = [];
$search_query = '';
$register_message = '';
$bulk_message = '';

// Utility function for executing prepared statements
function executeQuery($conn, $query, $params, $types = '')
{
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    return false;
  }

  if (!empty($types) && $params) {
    $stmt->bind_param($types, ...$params);
  }
  $stmt->execute();
  return $stmt;
}

// Handle search
if (isset($_POST['search_submit'])) {
  $search_query = $conn->real_escape_string($_POST['search']);
  $query = "SELECT * FROM students WHERE name LIKE ? OR id LIKE ?";
  $search_term = "%$search_query%";
  $stmt = executeQuery($conn, $query, [$search_term, $search_term], 'ss');
  if ($stmt) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $students[] = $row;
    }
    $stmt->close();
  }
}

// Handle registration and updates
if (isset($_POST['register']) || isset($_POST['update'])) {
  $id = $conn->real_escape_string($_POST['id'] ?? '');
  $name = $conn->real_escape_string($_POST['name'] ?? '');
  $gender = $conn->real_escape_string($_POST['gender'] ?? '');
  $dob_formatted = !empty($_POST['dob']) ? date('d/m/Y', strtotime($_POST['dob'])) : '';
  $dob = $conn->real_escape_string($dob_formatted);
  $placeob = $conn->real_escape_string($_POST['placeob'] ?? '');
  $address = $conn->real_escape_string($_POST['address'] ?? '');
  $religion = $conn->real_escape_string($_POST['religion'] ?? '');
  $state = $conn->real_escape_string($_POST['state'] ?? '');
  $lga = $conn->real_escape_string($_POST['lga'] ?? '');
  $class = $conn->real_escape_string($_POST['class'] ?? '');
  $arm = $conn->real_escape_string($_POST['arm'] ?? '');
  $session = $conn->real_escape_string($_POST['session'] ?? '');
  $term = $conn->real_escape_string($_POST['term'] ?? '');
  $schoolname = $conn->real_escape_string($_POST['schoolname'] ?? '');
  $schooladdress = $conn->real_escape_string($_POST['schooladdress'] ?? '');
  $hobbies = $conn->real_escape_string($_POST['hobbies'] ?? '');
  $lastclass = $conn->real_escape_string($_POST['lastclass'] ?? '');
  $sickle = $conn->real_escape_string($_POST['sickle'] ?? '');
  $challenge = $conn->real_escape_string($_POST['challenge'] ?? '');
  $emergency = $conn->real_escape_string($_POST['emergency'] ?? '');
  $familydoc = $conn->real_escape_string($_POST['familydoc'] ?? '');
  $docaddress = $conn->real_escape_string($_POST['docaddress'] ?? '');
  $docmobile = $conn->real_escape_string($_POST['docmobile'] ?? '');
  $polio = $conn->real_escape_string($_POST['polio'] ?? '');
  $tuberculosis = $conn->real_escape_string($_POST['tuberculosis'] ?? '');
  $measles = $conn->real_escape_string($_POST['measles'] ?? '');
  $tetanus = $conn->real_escape_string($_POST['tetanus'] ?? '');
  $whooping = $conn->real_escape_string($_POST['whooping'] ?? '');
  $gname = $conn->real_escape_string($_POST['gname'] ?? '');
  $mobile = $conn->real_escape_string($_POST['mobile'] ?? '');
  $goccupation = $conn->real_escape_string($_POST['goccupation'] ?? '');
  $gaddress = $conn->real_escape_string($_POST['gaddress'] ?? '');
  $grelationship = $conn->real_escape_string($_POST['grelationship'] ?? '');
  $hostel = $conn->real_escape_string($_POST['hostel'] ?? '');
  $bloodtype = $conn->real_escape_string($_POST['bloodtype'] ?? '');
  $bloodgroup = $conn->real_escape_string($_POST['bloodgroup'] ?? '');
  $height = $conn->real_escape_string($_POST['height'] ?? '');
  $weight = $conn->real_escape_string($_POST['weight'] ?? '');
  $password = ($_POST['password'] ?? '');

  if (isset($_POST['update'])) {
    $query = "UPDATE students SET name = ?, dob = ?, class = ?, arm = ?, password = ? WHERE id = ?";
    $stmt = executeQuery($conn, $query, [$name, $dob, $class, $arm, $password, $id], 'ssssss');
    $register_message = $stmt ? 'Student record updated successfully.' : 'Error updating student record.';
  } else {
    $query = "INSERT INTO students (id, name, gender, dob, placeob, address, religion, state, lga, class, arm,session, term,schoolname,schooladdress,hobbies,lastclass,sickle,challenge,emergency,familydoc,docaddress,docmobile,polio,tuberculosis,measles,tetanus,whooping,gname,mobile,goccupation,gaddress,grelationship,hostel,bloodtype,bloodgroup,height,weight, password) VALUES (?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = executeQuery($conn, $query, [$id, $name, $gender, $dob, $placeob, $address, $religion, $state, $lga, $class, $arm, $session, $term, $schoolname, $schooladdress, $hobbies, $lastclass, $sickle, $challenge, $emergency, $familydoc, $docaddress, $docmobile, $polio, $tuberculosis, $measles, $tetanus, $whooping, $gname, $mobile, $goccupation, $gaddress, $grelationship, $hostel, $bloodtype, $bloodgroup, $height, $weight, $password], 'sssssssssssssssssssssssssssssssssssssss');
    $register_message = $stmt ? 'Student registered successfully.' : 'Error registering student.';
  }


  //student picture
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $targetDir = "studentimg/"; // Upload folder
    $studentID = isset($_POST["id"]) ? trim($_POST["id"]) : ''; // Get Student ID

    if (empty($studentID)) {
      $message = "Student ID is required.";
    } else {
      // Replace "/" with "_" in Student ID
      $sanitizedID = str_replace("/", "_", $studentID);

      $fileExtension = strtolower(pathinfo($_FILES["formFile"]["name"], PATHINFO_EXTENSION));
      $fileSize = $_FILES["formFile"]["size"];
      $allowedTypes = ["jpg", "jpeg"];
      $targetFile = $targetDir . $sanitizedID . "." . $fileExtension; // Use sanitized ID as filename

      // Check file size (500KB limit)
      if ($fileSize > 500 * 1024) {
        $message = "File size must be less than 500KB.";
      } elseif (!in_array($fileExtension, $allowedTypes)) {
        $message = "Only JPG/JPEG files are allowed.";
      } else {
        // Create directory if not exists
        if (!is_dir($targetDir)) {
          mkdir($targetDir, 0777, true);
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES["formFile"]["tmp_name"], $targetFile)) {
          $message = "File uploaded successfully as " . htmlspecialchars($sanitizedID) . "." . $fileExtension;
        } else {
          $message = "Error uploading file.";
        }
      }
    }
  }
}

// Handle delete request
if (isset($_POST['delete'])) {
  $id_to_delete = $conn->real_escape_string($_POST['id_to_delete']);
  $query = "DELETE FROM students WHERE id = ?";
  $stmt = executeQuery($conn, $query, [$id_to_delete], 's');
  $register_message = $stmt ? 'Student record deleted successfully.' : 'Error deleting record.';
}

// Handle bulk upload
if (isset($_POST['bulk_upload']) && isset($_FILES['student_file'])) {
  $file = $_FILES['student_file']['tmp_name'];
  $file_ext = pathinfo($_FILES['student_file']['name'], PATHINFO_EXTENSION);

  if ($file_ext === 'csv' && ($handle = fopen($file, 'r')) !== false) {
    fgetcsv($handle); // Skip header
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
      $id = $conn->real_escape_string($data[0]);
      $name = $conn->real_escape_string($data[1]);
      $dob = $conn->real_escape_string($data[2]);
      $class = $conn->real_escape_string($data[3]);
      $arm = $conn->real_escape_string($data[4]);
      $password = $conn->real_escape_string($data[5]);

      $query = "INSERT INTO students (id, name, dob, class, arm, password) VALUES (?, ?, ?, ?, ?, ?)";
      executeQuery($conn, $query, [$id, $name, $dob, $class, $arm, $password], 'ssssss');
    }
    fclose($handle);
    $bulk_message = 'Bulk upload successful.';
  } else {
    $bulk_message = 'Invalid file. Please upload a valid CSV file.';
  }
}


// Fetch classes for dropdown
$sql_class = "SELECT DISTINCT class from class";
$classresult = $conn->query($sql_class);
$classes = [];
if ($classresult && $classresult->num_rows > 0) {
  while ($row = $classresult->fetch_assoc()) {
    $classes[] = $row['class'];
  }
}


// fetch arms for dropdodwn
$sql_arms = "SELECT DISTINCT arm from arm";
$armresult = $conn->query($sql_arms);
$arms = [];
if ($armresult && $armresult->num_rows > 0) {
  while ($row = $armresult->fetch_assoc()) {
    $arms[] = $row['arm'];
  }
}


// fetch current academic session
$sql_session = "SELECT DISTINCT csession FROM currentsession";
$sessionresult = $conn->query($sql_session);
$current_session = '';
if ($sessionresult && $sessionresult->num_rows > 0) {
  $row = $sessionresult->fetch_assoc();
  $current_session = $row['csession'];
}

// Nigerian States
$nigerian_states = [
    "Abia", "Adamawa", "Akwa Ibom", "Anambra", "Bauchi", "Bayelsa", "Benue", "Borno",
    "Cross River", "Delta", "Ebonyi", "Edo", "Ekiti", "Enugu", "FCT - Abuja", "Gombe",
    "Imo", "Jigawa", "Kaduna", "Kano", "Katsina", "Kebbi", "Kogi", "Kwara", "Lagos",
    "Nasarawa", "Niger", "Ogun", "Ondo", "Osun", "Oyo", "Plateau", "Rivers", "Sokoto",
    "Taraba", "Yobe", "Zamfara"
];

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
          <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4 d-none d-lg-block">
            <div>
              <h3 class="fw-bold mb-3">Enroll</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Students</li>
                <li class="breadcrumb-item active">Enroll</li>
              </ol>
            </div>

          </div>

          <!-- BULK UPLOAD ============================ -->
          <div class="row">

            <div class="col-md-12">
              <div class="card card-secondary card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Bulk Upload</div>
                  </div>
                  <a href="download_enroll_template.php"><button style="margin-top:10px;" type="submit"
                      name="bulk_upload" class="btn btn-warning"> <span class="btn-label">
                        <i class="fas fa-cloud-download-alt"></i>
                      </span> Download Enrollment Template</button></a>

                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">
                    <p>

                      <!-- Bulk Upload Form -->
                    <form method="post" enctype="multipart/form-data">
                      <input type="file" id="student_file" style="margin-top:10px;" name="student_file" accept=".csv"
                        class="form-control" required>
                      <br>
                      <button type="submit" name="bulk_upload" class="btn btn-success"> <span class="btn-label">
                          <i class="fas fa-cloud-upload-alt"></i>
                        </span>Upload</button>
                    </form>

                    </p>
                  </div>

                </div>
              </div>

            </div>
          </div>


          <div class="row">

            <div class="col-md-12">
              <div class="card  card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Personal Details <small>| Fields in red are mandatory</small></div>
                  </div>

                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <p>
                      <?php if (!empty($register_message)): ?>
                    <div class="message"><?php echo htmlspecialchars($register_message); ?></div>
                  <?php endif; ?>

                  <?php if (!empty($bulk_message)): ?>
                    <div class="alert alert-info"><?php echo htmlspecialchars($bulk_message); ?></div>
                  <?php endif; ?>

                  <!-- Registration Form -->
                  <form method="post" class="row g-3" enctype="multipart/form-data">

                    <div class="col-md-2">
                      <input class="form-control form-control" type="text" name="id" style="border-color: red;"
                        value="<?php echo isset($student['id']) ? htmlspecialchars($student['id']) : ''; ?>"
                        placeholder="Student's ID" required>
                    </div>

                    <div class="col-md-4">
                      <input class="form-control form-control" type="text" id="name" name="name" style="border-color: red;"
                        value="<?php echo isset($student['name']) ? htmlspecialchars($student['name']) : ''; ?>"
                        placeholder="Name" required>
                    </div>

                    <div class="col-md-2">
                    
                        <select class="form-select form-control form-select" style="border-color: red;" aria-label="Default select example" name="gender" required>
                          <option value="">Select Gender</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                      <input class="form-control form-control" type="date" id="dob" name="dob"
                        value="<?php echo isset($student['dob']) ? htmlspecialchars($student['dob']) : ''; ?>"
                        placeholder="" style="border-color: red;" required>
                    </div>

                    <div class="col-md-2">
                      <input class="form-control form-control" type="text" id="placeob" name="placeob"
                        value="<?php echo isset($student['placeob']) ? htmlspecialchars($student['placeob']) : ''; ?>"
                        placeholder="Place of Birth" style="border-color: red;" required>
                    </div>

                    <div class="col-md-2">
                      <select class="form-select form-control" id="religion" name="religion" style="border-color: red;" required>
                        <option selected disabled value="">Select Religion</option>
                        <option value="Christianity">Christianity</option>
                        <option value="Islam">Islam</option>
                        <option value="Traditional">Traditional</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>

                    <div class="col-md-8">
                      <input class="form-control form-control" type="text" id="address" name="address"
                        value="<?php echo isset($student['address']) ? htmlspecialchars($student['address']) : ''; ?>"
                        placeholder="Address" style="border-color: red;" required>
                    </div>



                    <div class="col-md-2">
                      <select class="form-control form-select" name="state" id="state" style="border-color: red;" required>
                        <option value="" disabled selected>Select State</option>
                        <?php
                        foreach ($nigerian_states as $state_option) {
                          $selected = (isset($student['state']) && $student['state'] == $state_option) ? 'selected' : '';
                          echo '<option value="' . htmlspecialchars($state_option) . '" ' . $selected . '>' . htmlspecialchars($state_option) . '</option>';
                        }
                        ?>
                      </select>
                    </div>

                    <div class="col-md-2">
                      <select class="form-control form-select" name="lga" id="lga" style="border-color: red;" required>
                        <option value="" disabled selected>Select LGA</option>
                      </select>
                    </div>

                    <div class="col-md-2">
                      <select class="form-control form-select" name="class" style="border-color: red;" required>
                        <option value="" disabled selected>Select Class</option>
                        <?php
                        foreach ($classes as $class_option) {
                          $selected = (isset($student['class']) && $student['class'] == $class_option) ? 'selected' : '';
                          echo '<option value="' . htmlspecialchars($class_option) . '" ' . $selected . '>' . htmlspecialchars($class_option) . '</option>';
                        }
                        ?>
                      </select>
                    </div>

                    <div class="col-md-2">
                      <select class="form-control form-select" name="arm" style="border-color: red;" required>
                        <option value="" disabled selected>Select Arm</option>
                        <?php
                        foreach ($arms as $arm_option) {
                          $selected = (isset($student['arm']) && $student['arm'] == $arm_option) ? 'selected' : '';
                          echo '<option value="' . htmlspecialchars($arm_option) . '" ' . $selected . '>' . htmlspecialchars($arm_option) . '</option>';
                        }
                        ?>
                      </select>
                    </div>


                    <div class="col-md-2">
                      <input class="form-control form-control" type="text" id="session" name="session"
                        value="<?php echo htmlspecialchars($current_session); ?>"
                        placeholder="Academic Session" style="border-color: red;" required readonly>
                    </div>


                    <div class="col-md-2">
                      <select class="form-control form-select" name="term" id="term" style="border-color: red;" required>
                        <option value="" disabled>Select Term</option>
                        <option value="1st Term">1st Term</option>
                        <option value="2nd Term">2nd Term</option>
                        <option value="3rd Term">3rd Term</option>
                      </select>
                    </div>

                    <div class="col-md-2">
                      <div class="col-sm-10">
                        <select class="form-select form-control form-select" aria-label="Default select example" name="hostel" style="display: none;">
                          <option value="">Hostel Plan</option>
                          <option selected value="Day">Day</option>
                          <option value="Boarding">Boarding</option>
                        </select>
                      </div>
                    </div>

                    <hr width="100%">
                    <h5 class="card-title"><span> Parent / Guardian Information </span></h5>

                    <div class="col-md-4">
                      <input class="form-control form-control" type="text" id="gname" name="gname"
                        value="<?php echo isset($student['gname']) ? htmlspecialchars($student['gname']) : ''; ?>"
                        placeholder="Guardian Name" style="border-color: red;" required>
                    </div>

                    <div class="col-md-2">
                      <input class="form-control form-control" type="text" id="mobile" name="mobile"
                        value="<?php echo isset($student['Mobile']) ? htmlspecialchars($student['Mobile']) : ''; ?>"
                        placeholder="Guardian Mobile" style="border-color: red;" required>
                    </div>

                    <div class="col-md-4">
                      <input class="form-control form-control" type="text" id="goccupation" name="goccupation"
                        value="<?php echo isset($student['goccupation']) ? htmlspecialchars($student['goccupation']) : ''; ?>"
                        placeholder="Occupation" >
                    </div>

                    <div class="col-md-2">
                      <input class="form-control form-control" type="text" id="grelationship" name="grelationship"
                        value="<?php echo isset($student['grelationship']) ? htmlspecialchars($student['grelationship']) : ''; ?>"
                        placeholder="Relationship" >
                    </div>

                    <div class="col-md-6">
                      <input class="form-control form-control" type="text" id="gaddress" name="gaddress"
                        value="<?php echo isset($student['gaddress']) ? htmlspecialchars($student['gaddress']) : ''; ?>"
                        placeholder="Address" >
                    </div>



                    <hr width="100%">
                    <h5 class="card-title"><span> Last School Attended </span></h5>

                    <div class="col-md-4">
                      <input class="form-control form-control" type="text" id="schoolname" name="schoolname"
                        value="<?php echo isset($student['schoolname']) ? htmlspecialchars($student['schoolname']) : ''; ?>"
                        placeholder="Last School Name" >
                    </div>

                    <div class="col-md-4">
                      <input class="form-control form-control" type="text" id="schooladdress" name="schooladdress"
                        value="<?php echo isset($student['schooladdress']) ? htmlspecialchars($student['schooladdress']) : ''; ?>"
                        placeholder="Last School Address" >
                    </div>

                    <div class="col-md-2">
                      <input class="form-control form-control" type="text" id="hobbies" name="hobbies"
                        value="<?php echo isset($student['hobbies']) ? htmlspecialchars($student['hobbies']) : ''; ?>"
                        placeholder="Hobbies" >
                    </div>

                    <div class="col-md-2">
                      <!-- <input class="form-control form-control" type="text" id="lastclass" name="lastclass"
                          value="<?php echo isset($student['lastclass']) ? htmlspecialchars($student['lastclass']) : ''; ?>"
                          placeholder="Last Class Attended" required> -->

                      <select class="form-control form-select" name="lastclass" id="lastclass" >
                        <option selected value="" disabled>Select Last Class</option>
                        <option value="JSS 1">JSS 1</option>
                        <option value="JSS 2">JSS 2</option>
                        <option value="JSS 3">JSS 3</option>
                        <option value="SSS 1">SSS 1</option>
                        <option value="SSS 2">SSS 2</option>
                        <?php
                        for ($i = 1; $i <= 6; $i++) {
                          $class = 'BASIC ' . $i;
                          $selected = ($studentDetails['lastclass'] == $class) ? 'selected' : '';
                          echo '<option value="' . $class . '" ' . $selected . '>' . $class . '</option>';
                        }
                        ?>
                      </select>
                    </div>


                    <hr width="100%">
                    <h5 class="card-title"><span> Medical Information </span></h5>

                    <div class="col-md-2">
                      <!-- <input class="form-control form-control" type="text" id="bloodtype" name="bloodtype"
                          value="<?php echo isset($student['bloodtype']) ? htmlspecialchars($student['bloodtype']) : ''; ?>"
                          placeholder="Blood Type" required> -->

                      <select name="bloodtype" id="bloodtype" class="form-control form-select">
                       <option value="">Blood Genotype</option>
                        <option value="AA">AA</option>
                        <option value="AS">AS</option>
                        <option value="AC">AC</option>
                        <option value="SS">SS</option>
                        <option value="SC">SC</option>
                      </select>
                    </div>

                    <div class="col-md-2">
                      <!-- <input class="form-control form-control" type="text" id="bloodgroup" name="bloodgroup"
                        value="<?php echo isset($student['bloodgroup']) ? htmlspecialchars($student['bloodgroup']) : ''; ?>"
                        placeholder="Blood Group" required> -->

                      <select name="bloodgroup" id="bloodgroup" class="form-control form-select">
                        <option value="" disabled selected>Blood Type</option>
                        <option value="A+">A+</option>
                        <option value="A-">A−</option>
                        <option value="B+">B+</option>
                        <option value="B-">B−</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB−</option>
                        <option value="O+">O+</option>
                        <option value="O-">O−</option>
                      </select>

                    </div>

                    <div class="col-md-2">
                      <input class="form-control form-control" type="text" id="height" name="height"
                        value="<?php echo isset($student['height']) ? htmlspecialchars($student['height']) : ''; ?>"
                        placeholder="height" >
                    </div>

                    <div class="col-md-2">
                      <input class="form-control form-control" type="text" id="weight" name="weight"
                        value="<?php echo isset($student['weight']) ? htmlspecialchars($student['weight']) : ''; ?>"
                        placeholder="Weight" >
                    </div>

                    <!--<strong>-->
                    <!--  <p>Have you been immunized against any of the following?</p>-->
                    <!--</strong>-->
                    <div class="col-md-2" style="display:none;">
                      <div class="col-sm-10">
                        <select class="form-select form-control form-select" aria-label="Default select example" name="polio"
                          id="polio">
                          <option value="">Polio</option>
                          <option value="Yes">Yes</option>
                          <option value="Yes">No</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-2" style="display:none;">
                      <div class="col-sm-10">
                        <select class="form-select form-control form-select" aria-label="Default select example"
                          name="tuberculosis" id="tuberculosis">
                          <option value="">Tuberculosis</option>
                          <option value="Yes">Yes</option>
                          <option value="Yes">No</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-2" style="display:none;">
                      <div class="col-sm-10">
                        <select class="form-select form-control form-select" aria-label="Default select example" name="measles"
                          id="measles">
                          <option value="">Measles</option>
                          <option value="Yes">Yes</option>
                          <option value="Yes">No</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-2" style="display:none;">
                      <div class="col-sm-10">
                        <select class="form-select form-control form-select" aria-label="Default select example" name="tetanus"
                          id="tetanus">
                          <option value="">Tetanus</option>
                          <option value="Yes">Yes</option>
                          <option value="Yes">No</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-4" style="display:none;">
                      <div class="col-sm-10">
                        <select class="form-select form-control form-select" aria-label="Default select example" name="whooping"
                          id="whooping">
                          <option value="">Whooping Cough</option>
                          <option value="Yes">Yes</option>
                          <option value="Yes">No</option>
                        </select>
                      </div>
                    </div>

                    <!--<strong>-->
                    <!--  <p>If "No"</p>-->
                    <!--</strong>-->
                    <div class="col-md-4" style="display:none;">
                      <input class="form-control" type="text" id="familydoc" name="familydoc"
                        value="<?php echo isset($student['familydoc']) ? htmlspecialchars($student['familydoc']) : ''; ?>"
                        placeholder="Family Doctor" >
                    </div>

                    <div class="col-md-2" style="display:none;">
                      <input class="form-control form-control" type="text" id="docmobile" name="docmobile"
                        value="<?php echo isset($student['docmobile']) ? htmlspecialchars($student['docmobile']) : ''; ?>"
                        placeholder="Doctor's Mobile" >
                    </div>

                    <div class="col-md-6" style="display:none;">
                      <input class="form-control form-control" type="text" id="docaddress" name="docaddress"
                        value="<?php echo isset($student['docaddress']) ? htmlspecialchars($student['docaddress']) : ''; ?>"
                        placeholder="Doctor's Address" >
                    </div>

                    <!--<strong>-->
                    <!--  <p>Does your ward have:</p>-->
                    <!--</strong>-->
                    <div class="col-md-6" style="display:none;">
                      <div class="col-sm-10">
                        <select class="form-select form-control form-select" aria-label="Default select example" name="sickle"
                          id="sickle">
                          <option value="">Sickle Cell Anaemia</option>
                          <option value="Yes">Yes</option>
                          <option value="Yes">No</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-6" style="display:none;">
                      <div class="col-sm-10">
                        <select class="form-select form-control form-select" aria-label="Default select example" name="challenge"
                          id="challenge">
                          <option value="">Any of the following challenges</option>
                          <option value="Yes">None</option>
                          <option value="Yes">Polio</option>
                          <option value="Yes">Measles</option>
                          <option value="Yes">Tuberculosis</option>
                          <option value="Yes">Tetanus</option>
                          <option value="Yes">Whooping Cough</option>
                        </select>
                      </div>
                    </div>

                    <!--<strong>-->
                    <!--  <p>in emergencies are we permitted to take your ward to the hospital?</p>-->
                    <!--</strong>-->
                    <div class="col-md-6" style="display:none;">
                      <div class="col-sm-10">
                        <select class="form-select form-control form-select" aria-label="Default select example" name="emergency"
                          id="emergency">
                          <option value="Yes">Yes</option>
                          <option value="Yes">No</option>
                        </select>
                      </div>
                    </div>
                    <hr width="100%">

                    <div class="col-md-2">
                      <label>Upload Passport</label>
                    </div>
                    <div class="col-md-6">
                      <div class="col-sm-10">
                        <input class="form-control mb-3" type="file" id="formFile" name="formFile" accept=".jpg,.jpeg"
                          >
                      </div>
                    </div>

                    <hr width="100%">
                    <h5 class="card-title"><span> Authentication </span></h5>

                    <div class="col-md-4">
                      <input class="form-control form-control" type="password" id="password" name="password"
                        placeholder="Password" style="border-color: red;" required>
                    </div>
                    <br />
                    <div class="card-action">
                      <button type="submit" name="<?php echo isset($edit_id) && $edit_id ? 'update' : 'register'; ?>"
                        class="btn btn-success">
                        <span class="btn-label">
                          <i class="fa fa-check"></i>
                        </span>
                        <?php echo isset($edit_id) && $edit_id ? 'Update' : 'Register'; ?>
                      </button>

                      <button type="reset" class="btn btn-black"><span class="btn-label">
                          <i class="fa fa-archive"></i>
                        </span> Reset</button>
                    </div>
                  </form>


                  </p>

                  </div>

                </div>
              </div>

            </div>
          </div>






        </div>
      </div>
      <script>
        document.getElementById('state').addEventListener('change', function() {
            var state = this.value;
            var lgaSelect = document.getElementById('lga');
            lgaSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';

            if (state) {
                fetch('get_lgas.php?state=' + state)
                    .then(response => response.json())
                    .then(data => {
                        lgaSelect.innerHTML = '<option value="" disabled selected>Select LGA</option>';
                        data.forEach(function(lga) {
                            var option = document.createElement('option');
                            option.value = lga;
                            option.textContent = lga;
                            lgaSelect.appendChild(option);
                        });
                    });
            } else {
                lgaSelect.innerHTML = '<option value="" disabled selected>Select LGA</option>';
            }
        });

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
