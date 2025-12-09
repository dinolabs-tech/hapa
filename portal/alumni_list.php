<?php

// Start or resume a session. This is crucial for checking user login status.
session_start();
// Check if the user is logged in. If not, redirect them to the login page
// to ensure only authenticated users can access this list.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file to establish a connection to the database.
include('db_connection.php');

// Fetch alumni students: Select all students where their 'status' is 1.
$sql = "SELECT * FROM students WHERE status = 1";
$result = $conn->query($sql);
$alumni = []; // Initialize an empty array to store alumni records.

// Check if the query returned any rows.
if ($result->num_rows > 0) {
    // Loop through each row and add the student data to the $alumni array.
    while ($row = $result->fetch_assoc()) {
        $alumni[] = $row;
    }
}


// Count the number of alumni records fetched for display purposes.
$alumni_count = count($alumni);

// Fetch the name of the logged-in staff member.
// This is typically used for displaying the user's name in the header or sidebar.
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id); // Bind the user ID as a string.
$stmt->execute();
$stmt->bind_result($student_name); // Bind the result to $student_name (though it's a staff name here).
$stmt->fetch();
$stmt->close(); // Close the prepared statement.

?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?> <!-- Includes the head section of the HTML document (meta tags, title, CSS links) -->

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include('adminnav.php'); ?> <!-- Includes the admin specific navigation sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <?php include('logo_header.php'); ?> <!-- Includes the logo and header content -->
                </div>
                <?php include('navbar.php'); ?> <!-- Includes the main navigation bar -->
            </div>

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4 d-none d-lg-block">
                        <div>
                            <h3 class="fw-bold mb-3">Alumni</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Home</li>
                                <li class="breadcrumb-item active">Alumni</li>
                            </ol>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-body">
                                    <div class="card-head-row card-tools-still-right">
                                        <div class="card-title">Alumni List</div>
                                        <div class="card-tools">
                                            <span class="badge bg-primary text-white">
                                                Total: <?php echo $alumni_count; ?> <!-- Display the total count of alumni -->
                                            </span>
                                        </div>
                                    </div>

                                    <div class="table-responsive py-4">
                                        <!-- Table to display alumni records -->
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Gender</th>
                                                    <th>Mobile</th>
                                                    <th>Email</th>
                                                    <th>Session</th>
                                                    <th>Term</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($alumni)): ?>
                                                    <?php
                                                    // Fetch current session and term
                                                    $current_session_query = $conn->query("SELECT csession FROM currentsession ORDER BY id DESC LIMIT 1");
                                                    $current_session = $current_session_query->fetch_assoc()['csession'] ?? 'N/A';

                                                    $current_term_query = $conn->query("SELECT cterm FROM currentterm ORDER BY id DESC LIMIT 1");
                                                    $current_term = $current_term_query->fetch_assoc()['cterm'] ?? 'N/A';
                                                    ?>
                                                    <?php foreach ($alumni as $student): ?>
                                                        <?php
                                                        // Check if student has a testimonial
                                                        $testimonial_check_sql = "SELECT COUNT(*) FROM testimonial WHERE student_id = ?";
                                                        $stmt_testimonial = $conn->prepare($testimonial_check_sql);
                                                        $stmt_testimonial->bind_param("s", $student['id']);
                                                        $stmt_testimonial->execute();
                                                        $stmt_testimonial->bind_result($testimonial_count);
                                                        $stmt_testimonial->fetch();
                                                        $stmt_testimonial->close();

                                                        $testimonial_status = ($testimonial_count > 0) ? '<span class="badge bg-success rounded-5">Testimonial Available</span>' : '<span class="badge bg-danger rounded-5">Testimonial Unavailable</span>';
                                                        ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                                                            <td><?php echo htmlspecialchars($student['gender']); ?></td>
                                                            <td><?php echo htmlspecialchars($student['studentmobile']); ?></td>
                                                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                                                            <td><?php echo htmlspecialchars($current_session); ?></td>
                                                            <td><?php echo htmlspecialchars($current_term); ?></td>
                                                            <td>
                                                                <?php echo $testimonial_status; ?>
                                                            </td>
                                                            <td class="d-flex">
                                                                <button type="button" class="btn btn-primary btn-sm btn-rounded rounded-5 add-testimonial-btn"
                                                                    data-bs-toggle="modal" data-bs-target="#testimonialModal"
                                                                    data-student-id="<?php echo htmlspecialchars($student['id']); ?>"
                                                                    data-student-name="<?php echo htmlspecialchars($student['name']); ?>">
                                                                    Add Testimonial
                                                                </button>
                                                                <?php if ($testimonial_count > 0): ?>
                                                                    <a href="adminchecktestimonial.php?student_id=<?php echo htmlspecialchars($student['id']); ?>" class="btn btn-success btn-icon btn-round mt-2 ms-2">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center text-muted">No alumni records found.</td>
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

            <?php include('footer.php'); ?> <!-- Includes the footer section of the page -->
        </div>

        <?php include('cust-color.php'); ?> <!-- Includes custom color settings or scripts -->
    </div>

    <?php include('scripts.php'); ?> <!-- Includes general JavaScript scripts for the page -->

    <!-- FULL UPGRADED TESTIMONIAL MODAL -->
    <div class="modal fade" id="testimonialModal" tabindex="-1" aria-labelledby="testimonialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Add Testimonial for <span id="data-student-name"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="addTestimonialForm" method="POST" action="save_testimonial.php">
                    <div class="modal-body">
                        <input type="hidden" id="data-student-id" name="student_id">
                        <input type="hidden" name="session" value="<?php echo htmlspecialchars($current_session); ?>">

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Subjects Offered (comma-separated)</label>
                                <textarea name="subjects_offered" class="form-control" rows="2" required></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Academic Ability</label>
                                <textarea name="academic_ability" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prizes Won</label>
                                <textarea name="prizes_won" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Character Assessment</label>
                                <textarea name="character_assessment" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Office Held</label>
                                <input type="text" name="leadership_position" class="form-control">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Co-curricular Activities</label>
                                <textarea name="co_curricular" class="form-control" rows="2"></textarea>
                            </div>

                            <!-- <div class="col-md-12 mb-3">
                                <label class="form-label">General Remarks</label>
                                <textarea name="general_remarks" class="form-control" rows="2"></textarea>
                            </div> -->

                            <div class="col-md-12 mb-3">
                                <label class="form-label">General Remarks</label>
                                <textarea name="principal_comment" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Testimonial</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var testimonialModal = document.getElementById('testimonialModal');
            testimonialModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var studentId = button.getAttribute('data-student-id');
                var studentName = button.getAttribute('data-student-name');

                var modalTitleSpan = testimonialModal.querySelector('#data-student-name');
                var modalStudentIdInput = testimonialModal.querySelector('#data-student-id');

                modalTitleSpan.textContent = studentName;
                modalStudentIdInput.value = studentId;

                // Clear all fields
                testimonialModal.querySelectorAll("textarea, input[type='text']").forEach(el => el.value = "");
            });

            document.getElementById('addTestimonialForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                const form = event.target;
                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // alert('Testimonial saved successfully!');
                            var modal = bootstrap.Modal.getInstance(testimonialModal);
                            window.location.href = 'adminchecktestimonial.php?student_id=' + data.student_id;
                        } else {
                            alert('Error saving testimonial: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while saving the testimonial.');
                    });
            });
        });
    </script>

</body>

</html>
