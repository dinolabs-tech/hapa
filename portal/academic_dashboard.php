<?php
include 'components/admin_logic.php';
error_reporting(E_ALL);
ini_set('display_error', 1);

include('db_connection.php');

// Sanitize filter inputs
$selected_session = isset($_GET['session']) ? mysqli_real_escape_string($conn, $_GET['session']) : '';
$selected_term = isset($_GET['term']) ? mysqli_real_escape_string($conn, $_GET['term']) : '';

// Fetch available sessions
$sessions_query = "SELECT DISTINCT csession FROM mastersheet ORDER BY csession DESC";
$sessions_result = $conn->query($sessions_query);
$sessions = [];
while ($row = $sessions_result->fetch_assoc()) {
    $sessions[] = $row['csession'];
}

// Define terms
$terms = ['1st Term', '2nd Term', '3rd Term'];

// Set defaults
if (empty($selected_session) && !empty($sessions)) {
    $selected_session = $sessions[0]; // Latest session
}
if (empty($selected_term)) {
    $selected_term = 'First'; // Default term
}

// Summary Cards Queries
// Total Students
$total_students_query = "SELECT COUNT(DISTINCT id) as count FROM mastersheet WHERE csession = '$selected_session' AND term = '$selected_term'";
$total_students_result = $conn->query($total_students_query);
$total_students = $total_students_result->fetch_assoc()['count'] ?? 0;

// Average Score
$avg_score_query = "SELECT AVG(average) as avg FROM mastersheet WHERE csession = '$selected_session' AND term = '$selected_term'";
$avg_score_result = $conn->query($avg_score_query);
$avg_score = round($avg_score_result->fetch_assoc()['avg'] ?? 0, 2);

// Pass Rate (assuming pass is 40+)
$pass_rate_query = "SELECT (SUM(CASE WHEN average >= 40 THEN 1 ELSE 0 END) / COUNT(*)) * 100 as rate FROM mastersheet WHERE csession = '$selected_session' AND term = '$selected_term'";
$pass_rate_result = $conn->query($pass_rate_query);
$pass_rate = round($pass_rate_result->fetch_assoc()['rate'] ?? 0, 2);

// Fail Rate
$fail_rate = 100 - $pass_rate;

// At-Risk Students (below 40)
$at_risk_query = "SELECT COUNT(DISTINCT id) as count FROM mastersheet WHERE average < 40 AND csession = '$selected_session' AND term = '$selected_term'";
$at_risk_result = $conn->query($at_risk_query);
$at_risk = $at_risk_result->fetch_assoc()['count'] ?? 0;

// Grade Distribution
$grade_dist_query = "SELECT grade, COUNT(DISTINCT id) AS count FROM mastersheet WHERE csession = '$selected_session' AND term = '$selected_term' GROUP BY grade ORDER BY grade";
$grade_dist_result = $conn->query($grade_dist_query);
$grade_labels = [];
$grade_data = [];
while ($row = $grade_dist_result->fetch_assoc()) {
    $grade_labels[] = $row['grade'];
    $grade_data[] = $row['count'];
}

// Subject Performance
$subject_perf_query = "SELECT subject, AVG(average) as avg FROM mastersheet WHERE csession = '$selected_session' AND term = '$selected_term' GROUP BY subject ORDER BY avg DESC";
$subject_perf_result = $conn->query($subject_perf_query);
$subject_labels = [];
$subject_data = [];
while ($row = $subject_perf_result->fetch_assoc()) {
    $subject_labels[] = $row['subject'];
    $subject_data[] = round($row['avg'], 2);
}

// Performance Trend (across terms for selected session)
$trend_query = "SELECT term, AVG(average) as avg FROM mastersheet WHERE csession = '$selected_session' GROUP BY term ORDER BY FIELD(term, 'First', 'Second', 'Third')";
$trend_result = $conn->query($trend_query);
$trend_labels = [];
$trend_data = [];
while ($row = $trend_result->fetch_assoc()) {
    $trend_labels[] = $row['term'];
    $trend_data[] = round($row['avg'], 2);
}

// Class & Subject Breakdown
$breakdown_query = "SELECT subject, AVG(average) as avg_score, (SUM(CASE WHEN average >= 40 THEN 1 ELSE 0 END) / COUNT(*)) * 100 as pass_rate, MAX(total) as highest, MIN(total) as lowest FROM mastersheet WHERE csession = '$selected_session' AND term = '$selected_term' GROUP BY subject ORDER BY subject";
$breakdown_result = $conn->query($breakdown_query);
$breakdown_data = [];
while ($row = $breakdown_result->fetch_assoc()) {
    $breakdown_data[] = $row;
}

// Attendance Overview
$attendance_query = "SELECT AVG(dayspresent / (dayspresent + daysabsent)) * 100 as rate FROM classcomments WHERE csession = '$selected_session' AND term = '$selected_term'";
$attendance_result = $conn->query($attendance_query);
$attendance_rate = round($attendance_result->fetch_assoc()['rate'] ?? 0, 2);

// Chronic Absenteeism (students with attendance < 70%)
$chronic_query = "SELECT id as student_id, name, class, arm, (dayspresent / (dayspresent + daysabsent)) * 100 as rate FROM classcomments WHERE csession = '$selected_session' AND term = '$selected_term' HAVING rate < 70 ORDER BY rate ASC LIMIT 10";
$chronic_result = $conn->query($chronic_query);
$chronic_data = [];
while ($row = $chronic_result->fetch_assoc()) {
    $chronic_data[] = $row;
}

// Academic Risk Indicators
$risk_query = "SELECT id, name, class, arm, average FROM mastersheet WHERE average < 40 AND csession = '$selected_session' AND term = '$selected_term' ORDER BY average ASC LIMIT 10";
$risk_result = $conn->query($risk_query);
$risk_data = [];
while ($row = $risk_result->fetch_assoc()) {
    $risk_data[] = $row;
}

// Top 10 Performing Students
$top_students_query = "SELECT id, name, class, arm, average FROM mastersheet WHERE csession = '$selected_session' AND term = '$selected_term' ORDER BY average DESC LIMIT 10";
$top_students_result = $conn->query($top_students_query);
$top_students = [];
while ($row = $top_students_result->fetch_assoc()) {
    $top_students[] = $row;
}

// Bottom 10 Students
$bottom_students_query = "SELECT id, name, class, arm, average FROM mastersheet WHERE csession = '$selected_session' AND term = '$selected_term' ORDER BY average ASC LIMIT 10";
$bottom_students_result = $conn->query($bottom_students_query);
$bottom_students = [];
while ($row = $bottom_students_result->fetch_assoc()) {
    $bottom_students[] = $row;
}
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
                    <div class="d-flex d-none d-lg-block align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Academic Dashboard</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Home</li>
                                <li class="breadcrumb-item active">Academic Dashboard</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Filters -->
                    <form method="GET" class="row mb-4">
                        <div class="col-md-3">
                            <label for="session">Academic Session</label>
                            <select name="session" id="session" class="form-select">
                                <?php foreach ($sessions as $session): ?>
                                    <option value="<?php echo $session; ?>" <?php echo $session == $selected_session ? 'selected' : ''; ?>><?php echo $session; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="term">Academic Term</label>
                            <select name="term" id="term" class="form-select">
                                <?php foreach ($terms as $term): ?>
                                    <option value="<?php echo $term; ?>" <?php echo $term == $selected_term ? 'selected' : ''; ?>><?php echo $term; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>

                    <!-- Summary Cards -->
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Total Students</p>
                                                <h4 class="card-title"><?php echo $total_students; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                                <i class="fas fa-chart-line"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Average Score (%)</p>
                                                <h4 class="card-title"><?php echo $avg_score; ?>%</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Pass Rate (%)</p>
                                                <h4 class="card-title"><?php echo $pass_rate; ?>%</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-danger bubble-shadow-small">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">At-Risk Students</p>
                                                <h4 class="card-title"><?php echo $at_risk; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Grade Distribution</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="gradeChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Subject Performance</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="subjectChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Performance Trend</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="trendChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Class & Subject Breakdown -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Class & Subject Breakdown</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Average Score</th>
                                                    <th>Pass Rate (%)</th>
                                                    <th>Highest Score</th>
                                                    <th>Lowest Score</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($breakdown_data as $data): ?>
                                                    <tr class="<?php echo $data['avg_score'] < 40 ? 'table-danger' : ''; ?>">
                                                        <td><?php echo $data['subject']; ?></td>
                                                        <td><?php echo round($data['avg_score'], 2); ?>%</td>
                                                        <td><?php echo round($data['pass_rate'], 2); ?>%</td>
                                                        <td><?php echo $data['highest']; ?></td>
                                                        <td><?php echo $data['lowest']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Overview -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Attendance Overview</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $attendance_rate; ?>%" aria-valuenow="<?php echo $attendance_rate; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $attendance_rate; ?>%</div>
                                    </div>
                                    <p>Overall Attendance Rate: <?php echo $attendance_rate; ?>%</p>
                                    <h5>Chronic Absenteeism</h5>
                                    <ul class="list-group">
                                        <?php foreach ($chronic_data as $student): ?>
                                            <li class="list-group-item"><?php echo $student['name']; ?> (<?php echo $student['class']; ?><?php echo $student['arm']; ?>) - <?php echo round($student['rate'], 2); ?>%</li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Academic Risk Indicators</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning">
                                        <strong>Alert!</strong> <?php echo count($risk_data); ?> students below pass mark.
                                    </div>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Class</th>
                                                <th>Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($risk_data as $student): ?>
                                                <tr>
                                                    <td><?php echo $student['name']; ?></td>
                                                    <td><?php echo $student['class']; ?><?php echo $student['arm']; ?></td>
                                                    <td><?php echo $student['average']; ?>%</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top & Bottom Students -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Top Performing Students</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Rank</th>
                                                <th>Name</th>
                                                <th>Class</th>
                                                <th>Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $rank = 1;
                                            foreach ($top_students as $student): ?>
                                                <tr>
                                                    <td><?php echo $rank++; ?></td>
                                                    <td><?php echo $student['name']; ?></td>
                                                    <td><?php echo $student['class']; ?><?php echo $student['arm']; ?></td>
                                                    <td><?php echo $student['average']; ?>%</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">At-Risk Students</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Rank</th>
                                                <th>Name</th>
                                                <th>Class</th>
                                                <th>Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $rank = 1;
                                            foreach ($bottom_students as $student): ?>
                                                <tr>
                                                    <td><?php echo $rank++; ?></td>
                                                    <td><?php echo $student['name']; ?></td>
                                                    <td><?php echo $student['class']; ?><?php echo $student['arm']; ?></td>
                                                    <td><?php echo $student['average']; ?>%</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('footer.php'); ?>
        </div>

        <!-- Custom template | don't include it in your project! -->
        <?php include('cust-color.php'); ?>
        <!-- End Custom template -->
    </div>
    <?php include('scripts.php'); ?>

    <!-- Chart.js Scripts -->
    <script>
        // Grade Distribution Chart
        var gradeCtx = document.getElementById('gradeChart').getContext('2d');
        var gradeChart = new Chart(gradeCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($grade_labels); ?>,
                datasets: [{
                    label: 'Number of Students',
                    data: <?php echo json_encode($grade_data); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Subject Performance Chart
        var subjectCtx = document.getElementById('subjectChart').getContext('2d');
        var subjectChart = new Chart(subjectCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($subject_labels); ?>,
                datasets: [{
                    label: 'Average Score (%)',
                    data: <?php echo json_encode($subject_data); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Performance Trend Chart
        var trendCtx = document.getElementById('trendChart').getContext('2d');
        var trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($trend_labels); ?>,
                datasets: [{
                    label: 'Average Score (%)',
                    data: <?php echo json_encode($trend_data); ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>
</body>

</html>