<?php
/**
 * EduHive Mobile API - CBT Questions Endpoint
 * Returns questions for a specific CBT subject
 */

// Set JSON content type first
header('Content-Type: application/json');

// Include CORS handler (handles OPTIONS preflight automatically)
require_once __DIR__ . '/../cors.php';

require_once __DIR__ . '/../../db_connection.php';

// Only accept GET requests (OPTIONS is already handled by cors.php)
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Get user_id from header
$userId = $_SERVER['HTTP_X_USER_ID'] ?? null;
$subject = $_GET['subject'] ?? '';

if (!$userId) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
    exit;
}

if (empty($subject)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Subject is required']);
    exit;
}

// Get student info (uses 'id' as primary key)
$stmt = $conn->prepare("SELECT id, class, arm FROM students WHERE id = ?");
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

// Check if already taken (from cbt_score table)
$stmt = $conn->prepare("SELECT id FROM cbt_score WHERE login = ? AND subject = ? AND session = ? AND term = ?");
$stmt->bind_param("ssss", $student['id'], $subject, $session, $term);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'You have already taken this exam']);
    exit;
}
$stmt->close();

// Get time limit from cbtadmin table
$timeLimit = 30; // Default 30 minutes
$stmt = $conn->prepare("SELECT testtime FROM cbtadmin WHERE class = ? AND arm = ? AND session = ? AND term = ? LIMIT 1");
if ($stmt) {
    $stmt->bind_param("ssss", $student['class'], $student['arm'], $session, $term);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $timeLimit = $row['testtime'] ?? 30;
    }
    $stmt->close();
}

// Get questions from question table (without correct answers)
// Schema: que_id, subject, que_desc, ans1, ans2, ans3, ans4, true_ans, class, arm, term, session
$questions = [];
$stmt = $conn->prepare("SELECT que_id, que_desc, ans1, ans2, ans3, ans4 
                        FROM question 
                        WHERE subject = ? AND class = ? AND arm = ? AND session = ? AND term = ?
                        ORDER BY RAND()");
if ($stmt) {
    $stmt->bind_param("sssss", $subject, $student['class'], $student['arm'], $session, $term);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $questions[] = [
            'id' => $row['que_id'],
            'question' => $row['que_desc'],
            'options' => [
                'A' => $row['ans1'],
                'B' => $row['ans2'],
                'C' => $row['ans3'],
                'D' => $row['ans4']
            ]
        ];
    }
    $stmt->close();
}

if (empty($questions)) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'No questions found for this subject']);
    exit;
}

echo json_encode([
    'status' => 'success',
    'data' => [
        'subject' => $subject,
        'questions' => $questions,
        'total_questions' => count($questions),
        'time_limit' => $timeLimit,
        'session' => $session,
        'term' => $term
    ]
]);