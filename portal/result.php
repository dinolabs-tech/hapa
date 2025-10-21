<?php
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

// Check if session and term are set
if (isset($_GET['session'], $_GET['term'])) {
    $selected_session = $_GET['session'];
    $selected_term    = $_GET['term'];
} else {
    header('Location: login.php');
    exit();
}

$loginid = $_SESSION['user_id'];
$check = $loginid;

// Retrieve student registration details
$sql_student = mysqli_query($conn, "SELECT id, name, gender, class, arm FROM students WHERE id='$loginid'");
if (!$sql_student || mysqli_num_rows($sql_student) == 0) {
    echo '<script type="text/javascript">
            alert("Student details not found.");
            window.location = "students.php";
          </script>';
    exit();
}
$student_details = mysqli_fetch_assoc($sql_student);
$id          = $student_details['id'];
$name        = $student_details['name'];
$gender      = $student_details['gender'];
$class       = $student_details['class'];
$arm         = $student_details['arm'];

// Determine if it's a CBT result request
$is_cbt_result = isset($_GET['cbt']) && $_GET['cbt'] == 'true';

$scores_data = [];
$total_score = 0;
$percentage = 0;
$result_type_display = "";

if ($is_cbt_result) {
    $result_type_display = "(Computer Based Test)";
    // Fetch individual CBT scores
    $cbt_scores_query = mysqli_query($conn, "SELECT subject, class, arm, score FROM cbt_score WHERE login='$loginid' AND session='$selected_session' AND term='$selected_term'");
    if ($cbt_scores_query && mysqli_num_rows($cbt_scores_query) > 0) {
        while ($row = mysqli_fetch_assoc($cbt_scores_query)) {
            $scores_data[] = ['subject' => $row['subject'], 'class' => $row['class'], 'arm' => $row['arm'], 'score' => $row['score']];
            $total_score += $row['score'];
        }

        // Calculate percentage for CBT
        $subjects_taken = [];
        foreach ($scores_data as $data) {
            $subjects_taken[] = "'" . mysqli_real_escape_string($conn, $data['subject']) . "'";
        }

        $total_questions = 0;
        if (!empty($subjects_taken)) {
            $subject_list = implode(',', $subjects_taken);
            $total_questions_query = mysqli_query($conn, "SELECT COUNT(*) as total_questions FROM question WHERE subject IN ($subject_list) AND class='$class' AND arm='$arm' AND session='$selected_session' AND term='$selected_term'");
            $total_questions_row = mysqli_fetch_assoc($total_questions_query);
            $total_questions = $total_questions_row['total_questions'];
        }

        if ($total_questions > 0) {
            // If cbt_score.score represents the number of correct answers,
            // then total_score is the sum of correct answers.
            // Percentage is (total correct answers / total questions) * 100.
            $percentage = ($total_score / $total_questions) * 100;
        } else {
            $percentage = 0;
        }
    } else {
        echo '<script type="text/javascript">
                alert("No CBT result found for the selected session and term.");
                window.location = "students.php";
              </script>';
        exit();
    }
} else {
    // Logic for regular results
    $result_type_display = "(Regular Exam)";
    $mst_result_query = mysqli_query($conn, "SELECT subject, score FROM mst_result WHERE login='$loginid' AND session='$selected_session' AND term='$selected_term'");

    if ($mst_result_query && mysqli_num_rows($mst_result_query) > 0) {
        $num_subjects = 0;
        while ($row = mysqli_fetch_assoc($mst_result_query)) {
            $scores_data[] = ['subject' => $row['subject'], 'score' => $row['score']];
            $total_score += $row['score'];
            $num_subjects++;
        }

        // Calculate percentage for regular exams
        // Assuming each subject has a max score of 100.
        if ($num_subjects > 0) {
            $max_possible_score_regular = $num_subjects * 100;
            if ($max_possible_score_regular > 0) {
                $percentage = ($total_score / $max_possible_score_regular) * 100;
            } else {
                $percentage = 0;
            }
        } else {
            $percentage = 0;
        }
    } else {
        echo '<script type="text/javascript">
                alert("No regular exam result found for the selected session and term.");
                window.location = "students.php";
              </script>';
        exit();
    }
}

// Optionally, round the percentage to a desired number of decimals.
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
