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
                            <h3 class="fw-bold mb-3">Modify</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Home</li>
                                <li class="breadcrumb-item active">Students</li>
                                <li class="breadcrumb-item active">Modify</li>
                            </ol>
                        </div>
                    </div>

                    <!-- MODIFY STUDENTS============================= -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Modify Records <small>| Fields in red are mandatory</small></div>
                                    </div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="mb-4 mt-2">
                                        <?php if ($studentDetails): ?>
                                            <form method="POST" class="row g-3" enctype="multipart/form-data">
                                                <input type="hidden" name="id" value="<?php echo $studentDetails['id']; ?>">
                                                <div class="col-md-6">
                                                    <input
                                                        style="border-color: red;"
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="name"
                                                        value="<?php echo $studentDetails['name']; ?>"
                                                        placeholder="Name"
                                                        required>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="gender" style="border-color: red;" required>
                                                        <option value="" disabled>Select Gender</option>
                                                        <option value="Male" <?php if ($studentDetails['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                                        <option value="Female" <?php if ($studentDetails['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <input
                                                        style="border-color: red;"
                                                        class="form-control form-control"
                                                        type="date"
                                                        name="dob"
                                                        value="<?php echo !empty($studentDetails['dob']) ? date('Y-m-d', strtotime(str_replace('/', '-', $studentDetails['dob']))) : ''; ?>"
                                                        placeholder="DD/MM/YYYY"
                                                        required>
                                                </div>
                                                <div class="col-md-2">
                                                    <input
                                                        style="border-color: red;"
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="placeob"
                                                        value="<?php echo $studentDetails['placeob']; ?>"
                                                        placeholder="Place of Birth">
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="religion" style="border-color: red;" required>
                                                        <option value="" disabled>Select Religion</option>
                                                        <option value="Christianity" <?php if ($studentDetails['religion'] == 'Christianity') echo 'selected'; ?>>Christianity</option>
                                                        <option value="Islam" <?php if ($studentDetails['religion'] == 'Islam') echo 'selected'; ?>>Islam</option>
                                                        <option value="Traditional" <?php if ($studentDetails['religion'] == 'Traditional') echo 'selected'; ?>>Traditional</option>
                                                        <option value="Other" <?php if ($studentDetails['religion'] == 'Other') echo 'selected'; ?>>Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <input
                                                        style="border-color: red;"
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="address"
                                                        value="<?php echo $studentDetails['address']; ?>"
                                                        placeholder="Address"
                                                        required>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="state" id="state" style="border-color: red;" required>
                                                        <option value="" disabled>Select State</option>
                                                        <?php
                                                        // Normalize the student's state for case-insensitive comparison
                                                        $currentState = !empty($studentDetails['state']) ? strtolower(trim($studentDetails['state'])) : '';

                                                        foreach ($nigerian_states as $state_option) {
                                                            // Normalize state option for comparison
                                                            $state_value = htmlspecialchars($state_option, ENT_QUOTES, 'UTF-8');
                                                            $selected = ($currentState === strtolower($state_option)) ? 'selected' : '';
                                                            echo '<option value="' . $state_value . '" ' . $selected . '>' . $state_value . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="lga" id="lga" style="border-color: red;" required>
                                                        <option value="" disabled>Select LGA</option>
                                                        <?php
                                                        // Fallback: Show the student's LGA if available
                                                        if (!empty($studentDetails['lga'])) {
                                                            $lga_value = htmlspecialchars($studentDetails['lga'], ENT_QUOTES, 'UTF-8');
                                                            echo '<option value="' . $lga_value . '" selected>' . $lga_value . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="class" style="border-color: red;" required>
                                                        <option value="" disabled>Select Class</option>
                                                        <?php
                                                        foreach ($classes as $class) {
                                                            $selected = ($studentDetails['class'] == $class) ? 'selected' : '';
                                                            echo '<option value="' . $class . '" ' . $selected . '>' . $class . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="arm" style="border-color: red;" required>
                                                        <option value="" disabled>Select Arm</option>
                                                        <?php
                                                        foreach ($arms as $arm_option) {
                                                            $selected = ($studentDetails['arm'] == $arm_option) ? 'selected' : '';
                                                            echo '<option value="' . $arm_option . '" ' . $selected . '>' . $arm_option . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">

                                                    <input
                                                        style="border-color: red;"
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="session"
                                                        value="<?php echo $studentDetails['session']; ?>"
                                                        placeholder="Current Session"
                                                        required>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="term" style="border-color: red;" required>
                                                        <option value="" disabled>Select Term</option>
                                                        <option value="1st Term" <?php if ($studentDetails['term'] == '1st Term') echo 'selected'; ?>>1st Term</option>
                                                        <option value="2nd Term" <?php if ($studentDetails['term'] == '2nd Term') echo 'selected'; ?>>2nd Term</option>
                                                        <option value="3rd Term" <?php if ($studentDetails['term'] == '3rd Term') echo 'selected'; ?>>3rd Term</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="hostel" style="display: none;">
                                                        <option value="" disabled>Select Hostel</option>
                                                        <option value="Day" <?php if ($studentDetails['hostel'] == 'Day') echo 'selected'; ?>>Day</option>
                                                        <option value="Boarding" <?php if ($studentDetails['hostel'] == 'Boarding') echo 'selected'; ?>>Boarding</option>
                                                    </select>
                                                </div>
                                                <hr width="100%">
                                                <h5 class="card-title"><span> Parent / Guardian Information </span></h5>
                                                <div class="col-md-2">
                                                    <input
                                                        style="border-color: red;"
                                                        required
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="gname"
                                                        value="<?php echo $studentDetails['gname']; ?>"
                                                        placeholder="Guardian Name">
                                                </div>
                                                <div class="col-md-2">
                                                    <input
                                                        style="border-color: red;"
                                                        required
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="mobile"
                                                        value="<?php echo $studentDetails['mobile']; ?>"
                                                        placeholder="Guardian Mobile">
                                                </div>
                                                <div class="col-md-2">
                                                    <input
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="goccupation"
                                                        value="<?php echo $studentDetails['goccupation']; ?>"
                                                        placeholder="Guardian Occupation">
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="grelationship">
                                                        <option value="" disabled>Select Relationship</option>
                                                        <option value="Father" <?php if ($studentDetails['grelationship'] == 'Father') echo 'selected'; ?>>Father</option>
                                                        <option value="Mother" <?php if ($studentDetails['grelationship'] == 'Mother') echo 'selected'; ?>>Mother</option>
                                                        <option value="Other" <?php if ($studentDetails['grelationship'] == 'Other') echo 'selected'; ?>>Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <input
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="gaddress"
                                                        value="<?php echo $studentDetails['gaddress']; ?>"
                                                        placeholder="Guardian Address">
                                                </div>
                                                <hr width="100%">
                                                <h5 class="card-title"><span> Last School Attended </span></h5>
                                                <div class="col-md-2">
                                                    <input
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="schoolname"
                                                        value="<?php echo $studentDetails['schoolname']; ?>"
                                                        placeholder="Last School Name">
                                                </div>
                                                <div class="col-md-6">
                                                    <input
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="schooladdress"
                                                        value="<?php echo $studentDetails['schooladdress']; ?>"
                                                        placeholder="School Address">
                                                </div>
                                                <div class="col-md-2">
                                                    <input
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="hobbies"
                                                        value="<?php echo $studentDetails['hobbies']; ?>"
                                                        placeholder="Hobbies">
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="lastclass" id="lastclass">
                                                        <option selected value="" disabled>Select Last Class</option>
                                                        <option value="JSS 1"  <?= ($studentDetails['lastclass'] == 'JSS 1') ? 'selected' : '' ?>>JSS 1</option>
                                                        <option value="JSS 2" <?= ($studentDetails['lastclass'] == 'JSS 2') ? 'selected' : '' ?>>JSS 2</option>
                                                        <option value="JSS 3" <?= ($studentDetails['lastclass'] == 'JSS 3') ? 'selected' : '' ?>>JSS 3</option>
                                                        <option value="SSS 1" <?= ($studentDetails['lastclass'] == 'SSS 1') ? 'selected' : '' ?>>SSS 1</option>
                                                        <option value="SSS 2" <?= ($studentDetails['lastclass'] == 'SSS 2') ? 'selected' : '' ?>>SSS 2</option>
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
                                                <div class="col-md-3">
                                                    <select name="bloodtype" id="bloodtype" class="form-control form-select">
                                                        <option value="" disabled selected>Blood Genotype</option>
                                                        <option value="AA" <?= ($studentDetails['bloodtype'] == 'AA') ? 'selected' : '' ?>>AA</option>
                                                        <option value="AS"  <?= ($studentDetails['bloodtype'] == 'AS') ? 'selected' : '' ?>>AS</option>
                                                        <option value="AC"  <?= ($studentDetails['bloodtype'] == 'AC') ? 'selected' : '' ?>>AC</option>
                                                        <option value="SS"  <?= ($studentDetails['bloodtype'] == 'SS') ? 'selected' : '' ?>>SS</option>
                                                        <option value="SC"  <?= ($studentDetails['bloodtype'] == 'SC') ? 'selected' : '' ?>>SC</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control form-select" name="bloodgroup">
                                                        <option value="" disabled <?= empty($studentDetails['bloodgroup']) ? 'selected' : '' ?>>Select Blood Group</option>
                                                        <option value="A+" <?= ($studentDetails['bloodgroup'] == 'A+') ? 'selected' : '' ?>>A+</option>
                                                        <option value="A-" <?= ($studentDetails['bloodgroup'] == 'A-') ? 'selected' : '' ?>>A−</option>
                                                        <option value="B+" <?= ($studentDetails['bloodgroup'] == 'B+') ? 'selected' : '' ?>>B+</option>
                                                        <option value="B-" <?= ($studentDetails['bloodgroup'] == 'B-') ? 'selected' : '' ?>>B−</option>
                                                        <option value="AB+" <?= ($studentDetails['bloodgroup'] == 'AB+') ? 'selected' : '' ?>>AB+</option>
                                                        <option value="AB-" <?= ($studentDetails['bloodgroup'] == 'AB-') ? 'selected' : '' ?>>AB−</option>
                                                        <option value="O+" <?= ($studentDetails['bloodgroup'] == 'O+') ? 'selected' : '' ?>>O+</option>
                                                        <option value="O-" <?= ($studentDetails['bloodgroup'] == 'O-') ? 'selected' : '' ?>>O−</option>
                                                    </select>

                                                </div>
                                                <div class="col-md-3">
                                                    <input
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="height"
                                                        value="<?php echo $studentDetails['height']; ?>"
                                                        placeholder="Height">
                                                </div>
                                                <div class="col-md-3">
                                                    <input
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="weight"
                                                        value="<?php echo $studentDetails['weight']; ?>"
                                                        placeholder="Weight">
                                                </div>
                                                <strong>
                                                    <p>Have you been immunized against any of the following?</p>
                                                </strong>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="polio">
                                                        <option value="" disabled>Polio</option>
                                                        <option value="Yes" <?php if ($studentDetails['polio'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                        <option value="No" <?php if ($studentDetails['polio'] == 'No') echo 'selected'; ?>>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="tuberculosis">
                                                        <option value="" disabled>Tuberculosis</option>
                                                        <option value="Yes" <?php if ($studentDetails['tuberculosis'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                        <option value="No" <?php if ($studentDetails['tuberculosis'] == 'No') echo 'selected'; ?>>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="measles">
                                                        <option value="" disabled>Measles</option>
                                                        <option value="Yes" <?php if ($studentDetails['measles'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                        <option value="No" <?php if ($studentDetails['measles'] == 'No') echo 'selected'; ?>>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="tetanus">
                                                        <option value="" disabled>Tetanus</option>
                                                        <option value="Yes" <?php if ($studentDetails['tetanus'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                        <option value="No" <?php if ($studentDetails['tetanus'] == 'No') echo 'selected'; ?>>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="whooping">
                                                        <option value="" disabled>Whooping</option>
                                                        <option value="Yes" <?php if ($studentDetails['whooping'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                        <option value="No" <?php if ($studentDetails['whooping'] == 'No') echo 'selected'; ?>>No</option>
                                                    </select>
                                                </div>
                                                <strong>
                                                    <p>If "No"</p>
                                                </strong>
                                                <div class="col-md-2">
                                                    <input
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="familydoc"
                                                        value="<?php echo $studentDetails['familydoc']; ?>"
                                                        placeholder="Family Doctor">
                                                </div>
                                                <div class="col-md-2">
                                                    <input
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="docmobile"
                                                        value="<?php echo $studentDetails['docmobile']; ?>"
                                                        placeholder="Doctor's Mobile">
                                                </div>
                                                <div class="col-md-8">
                                                    <input
                                                        class="form-control form-control"
                                                        type="text"
                                                        name="docaddress"
                                                        value="<?php echo $studentDetails['docaddress']; ?>"
                                                        placeholder="Doctor's Address">
                                                </div>
                                                <strong>
                                                    <p>Does your ward have:</p>
                                                </strong>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="sickle">
                                                        <option value="" disabled>Sickle Cell</option>
                                                        <option value="Yes" <?php if ($studentDetails['sickle'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                        <option value="No" <?php if ($studentDetails['sickle'] == 'No') echo 'selected'; ?>>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="challenge" id="challenge" aria-label="Default select example">
                                                        <option value="" disabled <?= empty($studentDetails['challenge']) ? 'selected' : '' ?>>Any of the following challenges</option>
                                                        <option value="None" <?= ($studentDetails['challenge'] == 'None') ? 'selected' : '' ?>>None</option>
                                                        <option value="Polio" <?= ($studentDetails['challenge'] == 'Polio') ? 'selected' : '' ?>>Polio</option>
                                                        <option value="Measles" <?= ($studentDetails['challenge'] == 'Measles') ? 'selected' : '' ?>>Measles</option>
                                                        <option value="Tuberculosis" <?= ($studentDetails['challenge'] == 'Tuberculosis') ? 'selected' : '' ?>>Tuberculosis</option>
                                                        <option value="Tetanus" <?= ($studentDetails['challenge'] == 'Tetanus') ? 'selected' : '' ?>>Tetanus</option>
                                                        <option value="Whooping Cough" <?= ($studentDetails['challenge'] == 'Whooping Cough') ? 'selected' : '' ?>>Whooping Cough</option>
                                                    </select>

                                                </div>
                                                <strong>
                                                    <p>In emergencies, are we permitted to take your ward to the hospital?</p>
                                                </strong>
                                                <div class="col-md-2">
                                                    <select class="form-control form-select" name="emergency">
                                                        <option value="" disabled>Emergency</option>
                                                        <option value="Yes" <?php if ($studentDetails['emergency'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                        <option value="No" <?php if ($studentDetails['emergency'] == 'No') echo 'selected'; ?>>No</option>
                                                    </select>
                                                </div>
                                                <hr width="100%">
                                                <h5 class="card-title"><span> Passport </span></h5>
                                                <div class="col-md-6">
                                                    <div class="col-sm-10">
                                                        <input class="form-control mb-3" type="file" id="formFile" name="formFile" accept=".jpg,.jpeg">
                                                    </div>
                                                </div>
                                                <hr width="100%" />
                                                <h5 class="card-title"><span> Student's Login Password </span></h5>
                                                <div class="col-md-2">
                                                    <input
                                                        style="border-color: red;"
                                                        required
                                                        class="form-control form-control"
                                                        type="password"
                                                        name="password"
                                                        value="<?php echo $studentDetails['password']; ?>"
                                                        placeholder="Password">
                                                </div>
                                                <br>
                                                <button type="submit" name="update" class="btn btn-success btn-block">
                                                    <span class="btn-label">
                                                        <i class="fa fa-check"></i>
                                                    </span> Update
                                                </button>

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
                                            <table id="multi-filter-select" class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Date of Birth</th>
                                                        <th>Class</th>
                                                        <th>Arm</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($students)): ?>
                                                        <?php foreach ($students as $student): ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($student['id']); ?></td>
                                                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                                                <td><?php echo htmlspecialchars($student['dob']); ?></td>
                                                                <td><?php echo htmlspecialchars($student['class']); ?></td>
                                                                <td><?php echo htmlspecialchars($student['arm']); ?></td>
                                                                <td class="d-flex">
                                                                    <a href="?edit=<?php echo $student['id']; ?>" class="btn btn-warning me-3 btn-icon btn-round ps-1"> <i class="fas fa-edit"></i></a>
                                                                    <a href="?delete=<?php echo $student['id']; ?>" class="btn btn-danger btn-icon btn-round" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fas fa-trash"></i></a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="6">No data available in table.</td>
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
                document.addEventListener('DOMContentLoaded', function() {
                    var stateSelect = document.getElementById('state');
                    var lgaSelect = document.getElementById('lga');
                    var initialState = '<?php echo addslashes($studentDetails['state'] ?? ''); ?>';
                    var initialLga = '<?php echo addslashes($studentDetails['lga'] ?? ''); ?>';

                    // Function to fetch and populate LGAs when the state changes
                    function populateLgasOnChange(state) {
                        lgaSelect.innerHTML = ''; // Always clear when state changes
                        var defaultOption = document.createElement('option');
                        defaultOption.value = "";
                        defaultOption.textContent = "Select LGA";
                        defaultOption.disabled = true;
                        defaultOption.selected = true; // Select default when state changes
                        lgaSelect.appendChild(defaultOption);

                        if (!state) {
                            return;
                        }

                        fetch('get_lgas.php?state=' + encodeURIComponent(state))
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Failed to fetch LGAs');
                                }
                                return response.json();
                            })
                            .then(data => {
                                // Remove the "Select LGA" option if LGAs are successfully fetched
                                if (data.length > 0) {
                                    lgaSelect.removeChild(defaultOption);
                                }

                                data.forEach(function(lga) {
                                    var option = document.createElement('option');
                                    option.value = lga;
                                    option.textContent = lga;
                                    lgaSelect.appendChild(option);
                                });
                                // If no LGAs were fetched, ensure "Select LGA" remains selected
                                if (data.length === 0) {
                                    defaultOption.selected = true;
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching LGAs:', error);
                                lgaSelect.innerHTML = '<option value="" disabled selected>Error loading LGAs</option>';
                            });
                    }

                    // Function to handle initial population on page load
                    function populateLgasOnLoad(state, lga) {
                        if (!state) {
                            lgaSelect.innerHTML = '<option value="" disabled selected>Select LGA</option>';
                            return;
                        }

                        fetch('get_lgas.php?state=' + encodeURIComponent(state))
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Failed to fetch LGAs');
                                }
                                return response.json();
                            })
                            .then(data => {
                                // Remove the initial "Select LGA" option if it exists and is not the pre-selected LGA
                                let defaultOption = lgaSelect.querySelector('option[value=""][disabled]');
                                if (defaultOption && !defaultOption.selected) {
                                    lgaSelect.removeChild(defaultOption);
                                }

                                const normalizedInitialLga = lga ? lga.toLowerCase() : '';
                                let initialLgaFoundInFetched = false;

                                data.forEach(function(fetchedLga) {
                                    // Only add if it's not the initialLga (which is already present from PHP)
                                    if (normalizedInitialLga !== fetchedLga.toLowerCase()) {
                                        var option = document.createElement('option');
                                        option.value = fetchedLga;
                                        option.textContent = fetchedLga;
                                        lgaSelect.appendChild(option);
                                    } else {
                                        initialLgaFoundInFetched = true;
                                    }
                                });

                                // If initialLga was provided by PHP but not found in the fetched list, it means it's already there.
                                // If it was found in the fetched list, ensure it's selected.
                                if (initialLga && initialLgaFoundInFetched) {
                                    // Ensure the PHP-rendered option is still selected
                                    let phpRenderedOption = lgaSelect.querySelector(`option[value="${lga}"]`);
                                    if (phpRenderedOption) {
                                        phpRenderedOption.selected = true;
                                    }
                                } else if (!initialLga && data.length === 0) {
                                    // If no initial LGA and no LGAs fetched, ensure "Select LGA" is selected
                                    lgaSelect.innerHTML = '<option value="" disabled selected>Select LGA</option>';
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching LGAs:', error);
                                // If error on initial load, ensure the LGA dropdown is not empty
                                if (lgaSelect.options.length <= 1 && !initialLga) { // Only if no initial LGA was set by PHP
                                    lgaSelect.innerHTML = '<option value="" disabled selected>Error loading LGAs</option>';
                                }
                            });
                    }

                    // Initial population on page load
                    if (initialState) {
                        populateLgasOnLoad(initialState, initialLga);
                    } else {
                        // If no initial state, ensure "Select LGA" is the default
                        lgaSelect.innerHTML = '<option value="" disabled selected>Select LGA</option>';
                    }

                    stateSelect.addEventListener('change', function() {
                        populateLgasOnChange(this.value);
                    });
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