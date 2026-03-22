<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection (includes security helpers)
include 'db_connection.php';

// Validate session and term parameters
if (isset($_GET['session'], $_GET['term'])) {
    $selected_session = validate_string($_GET['session'], 1, 50);
    $selected_term = validate_enum($_GET['term'], ['1st Term', '2nd Term', '3rd Term']);
    
    if ($selected_session === false || $selected_term === false) {
        die('<script type="text/javascript">
            alert("Invalid session or term selected.");
            window.location = "dashboard.php";
          </script>');
    }
} else {
    header('Location: dashboard.php');
    exit();
}

$loginid = $_SESSION['user_id'];

// Retrieve student registration details using prepared statement
$stmt = $conn->prepare("SELECT id, name, gender, class, arm FROM students WHERE id=?");
$stmt->bind_param("s", $loginid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $stmt->close();
    echo '<script type="text/javascript">
            alert("Student details not found.");
            window.location = "dashboard.php";
          </script>';
    exit();
}

$student_details = $result->fetch_assoc();
$stmt->close();

$id          = $student_details['id'];
$name        = $student_details['name'];
$gender      = $student_details['gender'];
$class       = $student_details['class'];
$arm         = $student_details['arm'];

// Determine if it's a CBT result request
$is_cbt_result = isset($_GET['cbt']) && $_GET['cbt'] === 'true';

$scores_data = [];
$total_score = 0;
$percentage = 0;
$result_type_display = "";

if ($is_cbt_result) {
    $result_type_display = "(Computer Based Test)";
    // Fetch individual CBT scores using prepared statement
    $stmt = $conn->prepare("SELECT subject, class, arm, score FROM cbt_score WHERE login=? AND session=? AND term=?");
    $stmt->bind_param("sss", $loginid, $selected_session, $selected_term);
    $stmt->execute();
    $cbt_scores_query = $stmt->get_result();
    
    if ($cbt_scores_query->num_rows > 0) {
        while ($row = $cbt_scores_query->fetch_assoc()) {
            $scores_data[] = ['subject' => $row['subject'], 'class' => $row['class'], 'arm' => $row['arm'], 'score' => $row['score']];
            $total_score += $row['score'];
        }

        // Calculate percentage for CBT using prepared statement
        $total_questions = 0;
        if (!empty($scores_data)) {
            // Build placeholders for IN clause
            $subjects = array_column($scores_data, 'subject');
            $placeholders = implode(',', array_fill(0, count($subjects), '?'));
            $types = str_repeat('s', count($subjects)) . 'sss'; // subjects + class + arm + session + term
            
            $stmt = $conn->prepare("SELECT COUNT(*) as total_questions FROM question WHERE subject IN ($placeholders) AND class=? AND arm=? AND session=? AND term=?");
            $params = array_merge($subjects, [$class, $arm, $selected_session, $selected_term]);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $total_questions_row = $stmt->get_result()->fetch_assoc();
            $total_questions = $total_questions_row['total_questions'];
            $stmt->close();
        }

        $percentage = $total_questions > 0 ? ($total_score / $total_questions) * 100 : 0;
    } else {
        $stmt->close();
        echo '<script type="text/javascript">
                alert("No CBT result found for the selected session and term.");
                window.location = "dashboard.php";
              </script>';
        exit();
    }
} else {
    // Logic for regular results using prepared statement
    $result_type_display = "(Regular Exam)";
    $stmt = $conn->prepare("SELECT subject, score FROM mst_result WHERE login=? AND session=? AND term=?");
    $stmt->bind_param("sss", $loginid, $selected_session, $selected_term);
    $stmt->execute();
    $mst_result_query = $stmt->get_result();

    if ($mst_result_query->num_rows > 0) {
        $num_subjects = 0;
        while ($row = $mst_result_query->fetch_assoc()) {
            $scores_data[] = ['subject' => $row['subject'], 'score' => $row['score']];
            $total_score += $row['score'];
            $num_subjects++;
        }

        // Calculate percentage for regular exams (max score per subject = 100)
        $percentage = $num_subjects > 0 ? ($total_score / ($num_subjects * 100)) * 100 : 0;
    } else {
        $stmt->close();
        echo '<script type="text/javascript">
                alert("No regular exam result found for the selected session and term.");
                window.location = "dashboard.php";
              </script>';
        exit();
    }
    $stmt->close();
}

// Round the percentage to 2 decimal places
$percentage = round($percentage, 2);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $name; ?> - Result</title>
  <!-- Latest Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    @media print {
      .no-print { display: none; }
    }
  </style>
</head>
<body class="bg-light">
  <div class="container my-4">
   

    <!-- Second table (kept unchanged) -->
    <table border="0" width="100%">
      <tr>
        <td width="100">
          <img style="border-radius: 10px;" height="110" width="95" src="logo.jpg" alt="Logo" />
        </td>
        <td valign="top" style="padding:10px; font-size:14px; text-align:center">
          <b style="font-size:17px">HAPA College</b><br>
          <address>KM 3, Akure Owo Express Road, Oba Ile, Akure, Ondo State, Nigeria.</address>
          <span style="font-size:15px;"><?php echo $result_type_display; ?></span><br />
          <b style="font-family:'Times New Roman', Times, serif;">Result Slip</b><br />
          Class: <b><?php echo $class; ?></b> | Arm: <b><?php echo $arm; ?></b>
        </td>
        <td width="100">
         
        </td>
      </tr>
    </table>
    <hr />

     <!-- Name Details -->
     <div class="row my-3">
      <div class="col-md-12">
        <div style="text-align: center"><strong> <h3><?php echo $name; ?></h3></strong> </div>
      </div>
    </div>

    <!-- Student Registration Details -->
    <div class="row my-3">
      <div class="col-md-4">
        <div><strong>REG. NO.:</strong> <?php echo $id; ?></div>
      </div>
      <div class="col-md-4">
        <div><strong>CLASS:</strong> <?= htmlspecialchars($data['class']); ?></div>
      </div>
      <div class="col-md-4">
        <div><strong>ARM:</strong> <?= htmlspecialchars($data['arm']); ?></div>
      </div>
      <div class="col-md-4">
        <div><strong>SESSION:</strong> <?php echo $selected_session; ?></div>
      </div>
      <div class="col-md-4">
        <div><strong>TERM:</strong> <?php echo $selected_term; ?></div>
      </div>
      <div class="col-md-4">
        <div><strong>GENDER:</strong> <?php echo $gender; ?></div>
      </div>
    </div>



    <!-- Subjects and Scores -->
    <div class="row my-3">
      <div class="col-12">
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>SUBJECTS</th>
              <th>SCORES</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (!empty($scores_data)) {
                foreach ($scores_data as $data) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($data['subject']) . "</td>";
                    echo "<td>" . htmlspecialchars($data['score']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No results found for this session and term.</td></tr>";
            }
            ?>
            <tr class="table-secondary">
              <td><strong>TOTAL</strong></td>
              <td><strong><?php echo $total_score; ?></strong></td>
            </tr>
            <tr class="table-success">
              <td><strong>PERCENTAGE</strong></td>
              <td><strong><?php echo $percentage; ?>%</strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

   
    <hr />

    <!-- Action Buttons -->
    <div class="row my-3">
      <div class="col-md-6">
        <a href="javascript:window.print()" class="btn btn-primary no-print">Print Result</a>
      </div>
      <div class="col-md-6 text-end">
        <a href="students.php" class="btn btn-danger no-print">Close Window</a>
      </div>
    </div>
  </div>

  <!-- Latest Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
