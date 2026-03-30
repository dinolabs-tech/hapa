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
    $result_type_display = "Computer Based Test";
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

        // Calculate average percentage across all subjects
        $num_subjects = count($scores_data);
        if ($num_subjects > 0) {
            $percentage = $total_score / $num_subjects;
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
    $result_type_display = "Regular Exam";
    $mst_result_query = mysqli_query($conn, "SELECT subject, score FROM mst_result WHERE login='$loginid' AND session='$selected_session' AND term='$selected_term'");

    if ($mst_result_query && mysqli_num_rows($mst_result_query) > 0) {
        $num_subjects = 0;
        while ($row = mysqli_fetch_assoc($mst_result_query)) {
            $scores_data[] = ['subject' => $row['subject'], 'score' => $row['score']];
            $total_score += $row['score'];
            $num_subjects++;
        }

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

$percentage = min(round($percentage, 2), 100);

// Determine grade and color based on percentage
function getGradeAndColor($pct) {
    if ($pct >= 70) return ['grade' => 'A', 'color' => '#10B981', 'bg' => '#D1FAE5'];
    if ($pct >= 60) return ['grade' => 'B', 'color' => '#3B82F6', 'bg' => '#DBEAFE'];
    if ($pct >= 50) return ['grade' => 'C', 'color' => '#F59E0B', 'bg' => '#FEF3C7'];
    if ($pct >= 45) return ['grade' => 'D', 'color' => '#F97316', 'bg' => '#FFEDD5'];
    if ($pct >= 40) return ['grade' => 'E', 'color' => '#EF4444', 'bg' => '#FEE2E2'];
    return ['grade' => 'F', 'color' => '#DC2626', 'bg' => '#FEE2E2'];
}

$gradeInfo = getGradeAndColor($percentage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($name); ?> - Result</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --primary: #4F46E5;
      --primary-light: #6366F1;
      --success: #10B981;
      --warning: #F59E0B;
      --danger: #EF4444;
      --info: #3B82F6;
      --gray-50: #F9FAFB;
      --gray-100: #F3F4F6;
      --gray-200: #E5E7EB;
      --gray-300: #D1D5DB;
      --gray-400: #9CA3AF;
      --gray-500: #6B7280;
      --gray-600: #4B5563;
      --gray-700: #374151;
      --gray-800: #1F2937;
      --gray-900: #111827;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding: 20px;
    }

    .result-container {
      max-width: 900px;
      margin: 0 auto;
    }

    .result-card {
      background: white;
      border-radius: 24px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      overflow: hidden;
    }

    .result-header {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
      padding: 30px;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .result-header::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 100%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      pointer-events: none;
    }

    .school-info {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 20px;
    }

    .school-logo {
      width: 80px;
      height: 80px;
      border-radius: 16px;
      background: white;
      padding: 8px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .school-logo img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .school-details h1 {
      font-size: 1.75rem;
      font-weight: 700;
      margin-bottom: 5px;
    }

    .school-details p {
      font-size: 0.875rem;
      opacity: 0.9;
      margin: 0;
    }

    .result-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(255,255,255,0.2);
      backdrop-filter: blur(10px);
      padding: 8px 16px;
      border-radius: 50px;
      font-size: 0.875rem;
      font-weight: 600;
    }

    .student-section {
      padding: 30px;
      background: var(--gray-50);
      border-bottom: 1px solid var(--gray-200);
    }

    .student-info {
      display: flex;
      align-items: center;
      gap: 24px;
      flex-wrap: wrap;
    }

    .student-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 2rem;
      font-weight: 700;
      box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }

    .student-details h2 {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--gray-900);
      margin-bottom: 8px;
    }

    .info-badges {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .badge-item {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .badge-class { background: #EEF2FF; color: var(--primary); }
    .badge-term { background: #D1FAE5; color: #059669; }
    .badge-session { background: #DBEAFE; color: #2563EB; }
    .badge-id { background: #FEF3C7; color: #D97706; }

    .stats-section {
      padding: 30px;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 16px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      border-radius: 16px;
      padding: 20px;
      text-align: center;
      border: 1px solid var(--gray-200);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
    }

    .stat-card.primary::before { background: var(--primary); }
    .stat-card.success::before { background: var(--success); }
    .stat-card.info::before { background: var(--info); }
    .stat-card.warning::before { background: var(--warning); }

    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 12px;
      font-size: 1.25rem;
    }

    .stat-card.primary .stat-icon { background: #EEF2FF; color: var(--primary); }
    .stat-card.success .stat-icon { background: #D1FAE5; color: var(--success); }
    .stat-card.info .stat-icon { background: #DBEAFE; color: var(--info); }
    .stat-card.warning .stat-icon { background: #FEF3C7; color: var(--warning); }

    .stat-value {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--gray-900);
      margin-bottom: 4px;
    }

    .stat-label {
      font-size: 0.75rem;
      color: var(--gray-500);
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .grade-display {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 24px;
      padding: 30px;
      background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
      border-radius: 16px;
      margin-bottom: 30px;
    }

    .grade-circle {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 700;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
    }

    .grade-circle .grade-letter {
      font-size: 2.5rem;
      line-height: 1;
    }

    .grade-circle .grade-label {
      font-size: 0.75rem;
      opacity: 0.9;
    }

    .percentage-display {
      text-align: center;
    }

    .percentage-value {
      font-size: 3rem;
      font-weight: 700;
      color: var(--gray-900);
    }

    .percentage-label {
      font-size: 0.875rem;
      color: var(--gray-500);
    }

    .progress-bar-container {
      width: 100%;
      height: 12px;
      background: var(--gray-200);
      border-radius: 6px;
      overflow: hidden;
      margin-top: 16px;
    }

    .progress-bar-fill {
      height: 100%;
      border-radius: 6px;
      transition: width 0.5s ease;
    }

    .subjects-section {
      padding: 0 30px 30px;
    }

    .section-title {
      font-size: 1.125rem;
      font-weight: 700;
      color: var(--gray-900);
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .section-title i {
      color: var(--primary);
    }

    .subjects-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .subjects-table thead {
      background: var(--gray-800);
    }

    .subjects-table th {
      padding: 14px 16px;
      text-align: left;
      font-size: 0.75rem;
      font-weight: 600;
      color: white;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .subjects-table th:last-child {
      text-align: center;
    }

    .subjects-table tbody tr {
      background: white;
      transition: background 0.2s ease;
    }

    .subjects-table tbody tr:nth-child(even) {
      background: var(--gray-50);
    }

    .subjects-table tbody tr:hover {
      background: #EEF2FF;
    }

    .subjects-table td {
      padding: 14px 16px;
      font-size: 0.875rem;
      color: var(--gray-700);
      border-bottom: 1px solid var(--gray-100);
    }

    .subjects-table td:last-child {
      text-align: center;
    }

    .subject-name {
      font-weight: 600;
      color: var(--gray-900);
    }

    .score-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 50px;
      padding: 6px 12px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 0.875rem;
    }

    .score-high { background: #D1FAE5; color: #059669; }
    .score-medium { background: #DBEAFE; color: #2563EB; }
    .score-low { background: #FEE2E2; color: #DC2626; }

    .total-row {
      background: var(--gray-800) !important;
    }

    .total-row td {
      color: white !important;
      font-weight: 700;
      border-bottom: none;
    }

    .actions-section {
      padding: 30px;
      background: var(--gray-50);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 16px;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 24px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.875rem;
      text-decoration: none;
      transition: all 0.2s ease;
      border: none;
      cursor: pointer;
    }

    .btn-primary {
      background: var(--primary);
      color: white;
    }

    .btn-primary:hover {
      background: var(--primary-light);
      transform: translateY(-2px);
      box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }

    .btn-danger {
      background: var(--danger);
      color: white;
    }

    .btn-danger:hover {
      background: #DC2626;
      transform: translateY(-2px);
      box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.3);
    }

    .result-type-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      border-radius: 50px;
      font-size: 0.875rem;
      font-weight: 600;
    }

    .result-type-cbt {
      background: #FEF3C7;
      color: #D97706;
    }

    .result-type-regular {
      background: #D1FAE5;
      color: #059669;
    }

    @media print {
      body {
        background: white;
        padding: 0;
      }
      .result-card {
        box-shadow: none;
        border-radius: 0;
      }
      .no-print { display: none !important; }
      .actions-section { display: none !important; }
    }

    @media (max-width: 768px) {
      body {
        padding: 10px;
      }
      .result-header {
        padding: 20px;
      }
      .school-info {
        flex-direction: column;
        text-align: center;
      }
      .school-details h1 {
        font-size: 1.25rem;
      }
      .student-section {
        padding: 20px;
      }
      .student-info {
        flex-direction: column;
        text-align: center;
      }
      .stats-section {
        padding: 20px;
      }
      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      .grade-display {
        flex-direction: column;
        padding: 20px;
      }
      .subjects-section {
        padding: 0 20px 20px;
      }
      .actions-section {
        padding: 20px;
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <div class="result-container">
    <div class="result-card">
      <!-- Header -->
      <div class="result-header">
        <div class="school-info">
          <div class="school-logo">
            <img src="assets/img/logo.png" alt="School Logo" />
          </div>
          <div class="school-details">
            <h1>DINOLABS ACADEMY</h1>
            <p>5th Floor, Wing-B TISCO Building, Alagbaka, Akure</p>
            <p>Ondo State, Nigeria</p>
          </div>
        </div>
        <div class="result-badge">
          <i class="fas fa-file-alt"></i>
          <span>STUDENT RESULT SLIP</span>
        </div>
      </div>

      <!-- Student Section -->
      <div class="student-section">
        <div class="student-info">
          <div class="student-avatar">
            <?php echo strtoupper(substr($name, 0, 1)); ?>
          </div>
          <div class="student-details">
            <h2><?php echo htmlspecialchars($name); ?></h2>
            <div class="info-badges">
              <span class="badge-item badge-class">
                <i class="fas fa-graduation-cap"></i>
                <?php echo htmlspecialchars($class . ' ' . $arm); ?>
              </span>
              <span class="badge-item badge-term">
                <i class="fas fa-calendar-alt"></i>
                Term: <?php echo htmlspecialchars($selected_term); ?>
              </span>
              <span class="badge-item badge-session">
                <i class="fas fa-clock"></i>
                <?php echo htmlspecialchars($selected_session); ?>
              </span>
              <span class="badge-item badge-id">
                <i class="fas fa-id-card"></i>
                <?php echo htmlspecialchars($id); ?>
              </span>
            </div>
          </div>
          <div style="margin-left: auto;">
            <span class="result-type-badge <?php echo $is_cbt_result ? 'result-type-cbt' : 'result-type-regular'; ?>">
              <i class="fas <?php echo $is_cbt_result ? 'fa-laptop' : 'fa-file-pen'; ?>"></i>
              <?php echo $result_type_display; ?>
            </span>
          </div>
        </div>
      </div>

      <!-- Stats Section -->
      <div class="stats-section">
        <div class="stats-grid">
          <div class="stat-card primary">
            <div class="stat-icon">
              <i class="fas fa-book"></i>
            </div>
            <div class="stat-value"><?php echo count($scores_data); ?></div>
            <div class="stat-label">Subjects</div>
          </div>
          <div class="stat-card success">
            <div class="stat-icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value"><?php echo $total_score; ?></div>
            <div class="stat-label">Total Score</div>
          </div>
          <div class="stat-card info">
            <div class="stat-icon">
              <i class="fas fa-percent"></i>
            </div>
            <div class="stat-value"><?php echo $percentage; ?>%</div>
            <div class="stat-label">Percentage</div>
          </div>
          <div class="stat-card warning">
            <div class="stat-icon">
              <i class="fas fa-award"></i>
            </div>
            <div class="stat-value"><?php echo $gradeInfo['grade']; ?></div>
            <div class="stat-label">Grade</div>
          </div>
        </div>

        <!-- Grade Display -->
        <div class="grade-display">
          <div class="grade-circle" style="background: linear-gradient(135deg, <?php echo $gradeInfo['color']; ?> 0%, <?php echo $gradeInfo['color']; ?>99 100%);">
            <span class="grade-letter"><?php echo $gradeInfo['grade']; ?></span>
            <span class="grade-label">GRADE</span>
          </div>
          <div class="percentage-display">
            <div class="percentage-value"><?php echo $percentage; ?>%</div>
            <div class="percentage-label">Overall Performance</div>
            <div class="progress-bar-container">
              <div class="progress-bar-fill" style="width: <?php echo min($percentage, 100); ?>%; background: <?php echo $gradeInfo['color']; ?>;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Subjects Section -->
      <div class="subjects-section">
        <h3 class="section-title">
          <i class="fas fa-list-check"></i>
          Subject Breakdown
        </h3>
        <table class="subjects-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Subject</th>
              <th style="text-align: center;">Score</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $index = 1;
            foreach ($scores_data as $data):
              $score = $data['score'];
              $scoreClass = $score >= 70 ? 'score-high' : ($score >= 50 ? 'score-medium' : 'score-low');
            ?>
            <tr>
              <td><?php echo $index++; ?></td>
              <td class="subject-name"><?php echo htmlspecialchars($data['subject']); ?></td>
              <td>
                <span class="score-badge <?php echo $scoreClass; ?>">
                  <?php echo htmlspecialchars($score); ?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
            <tr class="total-row">
              <td colspan="2">TOTAL SCORE</td>
              <td><?php echo $total_score; ?></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Actions -->
      <div class="actions-section no-print">
        <a href="javascript:window.print()" class="btn btn-primary">
          <i class="fas fa-print"></i>
          Print Result
        </a>
        <a href="students.php" class="btn btn-danger">
          <i class="fas fa-times"></i>
          Close Window
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>