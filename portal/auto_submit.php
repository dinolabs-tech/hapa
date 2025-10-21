<?php
session_start();

// Check if the user is logged in, if not return an error
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit();
}

// Database connection
include 'db_connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 $student_class = $_SESSION['user_class'];
 $student_arm = $_SESSION['user_arm'];
 $student_term = $_SESSION['term'];
 $student_session = $_SESSION['student_session'];


$loginid = $_SESSION['user_id'];
$subject = $_SESSION['subject'];
$tdate = date("h:i:s") . '  ' . date("l, F j, Y");

// Escape the subject value for queries.
$subjectEsc = mysqli_real_escape_string($conn, $subject);

// Check if the student already took the exam.
$scoli = mysqli_query($conn, "SELECT * FROM mst_result WHERE login = '$loginid' and subject='$subjectEsc' ");
if (mysqli_num_rows($scoli) > 0) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Exam already submitted.']);
    exit();
}

// Calculate the score from mst_useranswer
$trueans = 0;
$result = mysqli_query($conn, "SELECT true_ans, your_ans FROM mst_useranswer WHERE sess_id='" . session_id() . "'");
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['your_ans'] == $row['true_ans']) {
        $trueans++;
    }
}

// Get total number of questions answered
$total_questions = mysqli_num_rows($result);

// Store results in the database.
mysqli_query($conn, "INSERT INTO mst_result(login, subject, test_date, score, class, arm, term, session) 
    VALUES('$loginid', '$subjectEsc', '$tdate', '$trueans', '$student_class', '$student_arm', '$student_term', '$student_session')");

// Clear session variables after submission.
unset($_SESSION['qn']);
unset($_SESSION['subject']);
unset($_SESSION['trueans']);

// Return a success response
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'message' => 'Exam submitted successfully.',
    'total_questions' => $total_questions,
    'correct_answers' => $trueans
]);

$conn->close();
?>
