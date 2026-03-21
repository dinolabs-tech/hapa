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

// Include helper functions
require_once('helpers/database_locks.php');

$student_class = $_SESSION['user_class'];
$student_arm = $_SESSION['user_arm'];
$student_term = $_SESSION['term'];
$student_session = $_SESSION['student_session'];

$loginid = $_SESSION['user_id'];
$subject = $_SESSION['subject'];
$tdate = date("h:i:s") . '  ' . date("l, F j, Y");

// Escape the subject value for queries.
$subjectEsc = mysqli_real_escape_string($conn, $subject);

// Use advisory lock to prevent concurrent exam submission for the same student/subject
$lockName = "exam_submit_{$loginid}_" . preg_replace('/[^a-zA-Z0-9]/', '_', $subject);

try {
    // Acquire lock with 5 second timeout
    if (!acquireLock($conn, $lockName, 5)) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Another submission is in progress. Please try again.']);
        exit();
    }
    
    // Start transaction for atomic operations
    $conn->begin_transaction();
    
    try {
        // Check if the student already took the exam - use FOR UPDATE to lock the row
        $stmt = $conn->prepare("SELECT id FROM mst_result WHERE login = ? AND subject = ? FOR UPDATE");
        $stmt->bind_param("ss", $loginid, $subjectEsc);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $stmt->close();
            $conn->rollback();
            releaseLock($conn, $lockName);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Exam already submitted.']);
            exit();
        }
        $stmt->close();
        
        // Calculate the score from mst_useranswer
        $trueans = 0;
        $stmt = $conn->prepare("SELECT true_ans, your_ans FROM mst_useranswer WHERE sess_id = ?");
        $sessId = session_id();
        $stmt->bind_param("s", $sessId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            if ($row['your_ans'] == $row['true_ans']) {
                $trueans++;
            }
        }
        $total_questions = $result->num_rows;
        $stmt->close();
        
        // Store results in the database using prepared statement
        $stmt = $conn->prepare("INSERT INTO mst_result(login, subject, test_date, score, class, arm, term, session) 
            VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssissss", $loginid, $subjectEsc, $tdate, $trueans, $student_class, $student_arm, $student_term, $student_session);
        $stmt->execute();
        $stmt->close();
        
        // Delete user answers after successful submission (cleanup)
        $stmt = $conn->prepare("DELETE FROM mst_useranswer WHERE sess_id = ?");
        $stmt->bind_param("s", $sessId);
        $stmt->execute();
        $stmt->close();
        
        // Commit transaction
        $conn->commit();
        
        // Clear session variables after submission.
        unset($_SESSION['qn']);
        unset($_SESSION['subject']);
        unset($_SESSION['trueans']);
        
        // Release lock
        releaseLock($conn, $lockName);
        
        // Return a success response
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Exam submitted successfully.',
            'total_questions' => $total_questions,
            'correct_answers' => $trueans
        ]);
        
    } catch (Exception $e) {
        $conn->rollback();
        releaseLock($conn, $lockName);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Error submitting exam: ' . $e->getMessage()]);
    }
    
} catch (Exception $e) {
    releaseLock($conn, $lockName);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'An error occurred. Please try again.']);
}

$conn->close();
?>