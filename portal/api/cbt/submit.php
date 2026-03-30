<?php
/**
 * EduHive Mobile API - CBT Submit Endpoint
 * Submits CBT answers and calculates score
 * 
 * Race Condition Prevention:
 * - Uses database transactions
 * - Uses INSERT ... ON DUPLICATE KEY UPDATE for atomic operations
 * - Relies on unique constraint (login, subject, term, session) to prevent duplicates
 */

// Set JSON content type first
header('Content-Type: application/json');

// Include CORS handler (handles OPTIONS preflight automatically)
require_once __DIR__ . '/../cors.php';

require_once __DIR__ . '/../../db_connection.php';
require_once __DIR__ . '/../../helpers/database.php';

// Only accept POST requests (OPTIONS is already handled by cors.php)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Get user_id from header
$userId = $_SERVER['HTTP_X_USER_ID'] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

$subject = $input['subject'] ?? '';
$answers = $input['answers'] ?? []; // Array of {question_id, answer}

if (empty($subject) || empty($answers)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Subject and answers are required']);
    exit;
}

// Get student info (uses 'id' as primary key)
$stmt = $conn->prepare("SELECT id, name, class, arm FROM students WHERE id = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Student not found']);
    exit;
}

// Get current session and term
$session = '';
$term = '';

$result = $conn->query("SELECT csession FROM currentsession LIMIT 1");
if ($result && $row = $result->fetch_assoc()) {
    $session = $row['csession'];
}

$result = $conn->query("SELECT cterm FROM currentterm LIMIT 1");
if ($result && $row = $result->fetch_assoc()) {
    $term = $row['cterm'];
}

try {
    $cbtResult = withTransaction(function($conn) use ($student, $subject, $answers, $session, $term) {
        // Check if already taken using FOR UPDATE to lock the check
        // This prevents race condition where two requests both pass the check
        $stmt = $conn->prepare("SELECT id FROM cbt_score 
                                WHERE login = ? AND subject = ? AND session = ? AND term = ? 
                                FOR UPDATE");
        $stmt->bind_param("ssss", $student['id'], $subject, $session, $term);
        $stmt->execute();
        $result = $stmt->get_result();
        $existingSubmission = $result->num_rows > 0;
        $stmt->close();
        
        if ($existingSubmission) {
            throw new Exception('You have already taken this exam');
        }

        // Calculate score
        $correct = 0;
        $total = count($answers);

        foreach ($answers as $answer) {
            $questionId = intval($answer['question_id']);
            $studentAnswer = strtoupper($answer['answer']);
            
            // Get correct answer from question table (true_ans column)
            $stmt = $conn->prepare("SELECT true_ans FROM question WHERE que_id = ?");
            $stmt->bind_param("i", $questionId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $correctAnswer = strtoupper($row['true_ans']);
                // true_ans is stored as '1', '2', '3', '4' or 'A', 'B', 'C', 'D'
                // Convert numeric to letter if needed
                if (in_array($correctAnswer, ['1', '2', '3', '4'])) {
                    $correctAnswer = chr(64 + intval($correctAnswer)); // 1=A, 2=B, etc.
                }
                if ($studentAnswer === $correctAnswer) {
                    $correct++;
                }
            }
            $stmt->close();
        }

        // Calculate percentage
        $score = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

        // Generate unique ID for cbt_score
        $scoreId = uniqid() . time();

        // Save result to cbt_score table
        // Uses INSERT with unique constraint to prevent duplicates at database level
        $testDate = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO cbt_score (id, login, subject, class, arm, term, session, test_date, score) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", 
            $scoreId,
            $student['id'], 
            $subject, 
            $student['class'], 
            $student['arm'], 
            $term, 
            $session,
            $testDate,
            $score
        );
        
        if (!$stmt->execute()) {
            // Check if it's a duplicate entry error (unique constraint violation)
            if ($conn->errno == 1062) {
                throw new Exception('You have already taken this exam');
            }
            throw new Exception('Failed to save exam result: ' . $stmt->error);
        }
        $stmt->close();

        return [
            'subject' => $subject,
            'score' => $score,
            'total_questions' => $total,
            'correct_answers' => $correct,
            'session' => $session,
            'term' => $term
        ];
    });

    echo json_encode([
        'status' => 'success',
        'message' => 'Exam submitted successfully',
        'data' => $cbtResult
    ]);

} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    
    // Check if it's the "already taken" error
    if (strpos($errorMessage, 'already taken') !== false) {
        http_response_code(400);
    } else {
        http_response_code(500);
    }
    
    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
}