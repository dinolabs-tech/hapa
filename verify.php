<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'portal/db_connection.php';

$verification_status = '';
$student_name = 'N/A';
$testimonial_details = [];
$error_message = '';

if (isset($_GET['student_id']) && !empty($_GET['student_id'])) {
  $student_id = trim($_GET['student_id']);

  // Fetch student info
  $stmt = $conn->prepare("SELECT name, gender, dob, state, lga, session FROM students WHERE id = ?");
  $stmt->bind_param("s", $student_id);
  $stmt->execute();
  $student = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if ($student) {
    $student_name = $student['name'];

    $session_year = $student['session'] ?? date('Y'); // Default to current year if session is missing

    // Fetch testimonial info for the student and their associated session
    $stmt = $conn->prepare("
            SELECT *
            FROM testimonial
            WHERE student_id = ?
            LIMIT 1
        ");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $testimonial = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($testimonial) {
      $verification_status = 'verified';
      $testimonial_details = [
        'Student Name' => $student['name'],
        'Gender' => $student['gender'],
        'Date of Birth' => $student['dob'],
        'State' => $student['state'],
        'LGA' => $student['lga'],
        'Academic Session' => $student['session'],
        'Subjects Offered' => $testimonial['subjects_offered'],
        'Academic Ability' => (!empty($testimonial['academic_ability']) ? $testimonial['academic_ability'] : 'The student displayed good academic ability.'),
        'Prizes Won' => (!empty($testimonial['prizes_won']) ? $testimonial['prizes_won'] : 'No significant prizes or awards were won.'),
        'Character Assessment' => (!empty($testimonial['character_assessment']) ? $testimonial['character_assessment'] : 'The student exhibited good character and conduct.'),
        'Leadership Position' => (!empty($testimonial['leadership_position']) ? $testimonial['leadership_position'] : 'No leadership position was held.'),
        'Co-curricular Activities' => (!empty($testimonial['co_curricular']) ? $testimonial['co_curricular'] : 'No specific co-curricular activities were noted.'),
        'Principal\'s Comment' => (!empty($testimonial['principal_comment']) ? $testimonial['principal_comment'] : 'The student has been a model student throughout their time at the school.')
      ];
    } else {
      $verification_status = 'not_found';
      $error_message = "No testimonial found for student ID: " . htmlspecialchars($student_id) . " for the academic session " . htmlspecialchars($session_year) . ".";
    }
  } else {
    $verification_status = 'invalid_id';
    $error_message = "Student ID " . htmlspecialchars($student_id) . " not found.";
  }
} else {
  $verification_status = 'no_id';
  $error_message = "No student ID provided for verification.";
}

?>


<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php');?>
<title>Testimonial Verification</title>
<style>
  .status-verified {
    color: green;
    font-weight: bold;
  }

  .status-error {
    color: red;
    font-weight: bold;
  }

  .details-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  .details-table th,
  .details-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
  }

  .details-table th {
    background-color: #f2f2f2;
  }

  .error-message {
    background-color: #ffe6e6;
    border: 1px solid #ffb3b3;
    color: #cc0000;
    padding: 15px;
    border-radius: 5px;
    text-align: center;
    margin-top: 20px;
  }

  .success-message {
    background-color: #e6ffe6;
    border: 1px solid #b3ffb3;
    color: #009900;
    padding: 15px;
    border-radius: 5px;
    text-align: center;
    margin-top: 20px;
  }
</style>

<body class="starter-page-page">

<?php include('components/header.php');?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background position-relative" style="background-image: url(assets/img/pg-header.jpg);">
      <div class="container position-relative">
        <h1>Testimonial Verification</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Testimmonial Verification</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

  <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">

      <div class="container" data-aos="fade-up">
        <?php if ($verification_status === 'verified'): ?>
          <div class="success-message mb-3">
            <p>✅ Testimonial for <strong><?php echo htmlspecialchars($student_name); ?></strong> is successfully verified!</p>
          </div>
          <h2>Testimonial Details</h2>
          <table class="details-table">
            <?php foreach ($testimonial_details as $label => $value): ?>
              <tr>
                <th><?php echo htmlspecialchars($label); ?></th>
                <td><?php echo nl2br(htmlspecialchars($value)); ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php else: ?>
          <div class="error-message">
            <p>❌ Verification Failed: <?php echo htmlspecialchars($error_message); ?></p>
            <?php if ($verification_status === 'no_id'): ?>
              <p>Please use the QR code scanner or provide a student ID to verify a testimonial.</p>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

    </section><!-- /Starter Section Section -->

  </main>

  <?php include ('components/footer.php');?>
  

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <?php include ('components/scripts.php');?>

</body>

</html>