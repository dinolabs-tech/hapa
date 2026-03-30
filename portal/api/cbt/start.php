<?php
/**
 * EduHive API - CBT Session Start Endpoint
 * Initializes a CBT exam session for a student
 * 
 * Features:
 * - Creates/validates exam session
 * - Returns time limit and session info
 * - Prevents duplicate exam attempts
 */

// Set JSON content type first
header('Content-Type: application/json');

// Include CORS handler (handles OPTIONS preflight automatically)
require_once __DIR__ . '/../cors.php';

require_once __DIR__ . '/../../db_connection.php';

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

if (empty($subject)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Subject is required']);
    exit;
}

// Get student info
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
        $timeLimit = (int)$row['testtime'] ?? 30;
    }
    $stmt->close();
}

// Get question count
$questionCount = 0;
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM question WHERE subject = ? AND class = ? AND arm = ? AND session = ? AND term = ?");
if ($stmt) {
    $stmt->bind_param("sssss", $subject, $student['class'], $student['arm'], $session, $term);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $questionCount = (int)$row['count'];
    }
    $stmt->close();
}

if ($questionCount === 0) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'No questions available for this subject']);
    exit;
}

// Generate unique session ID
$examSessionId = uniqid('cbt_') . '_' . time();

// Store session start time (using cbt_session table or simple approach)
// We'll use a simple approach: return server time and let client manage

echo json_encode([
    'status' => 'success',
    'message' => 'Exam session initialized',
    'data' => [
        'session_id' => $examSessionId,
        'student_id' => $student['id'],
        'student_name' => $student['name'],
        'class' => $student['class'],
        'arm' => $student['arm'],
        'subject' => $subject,
        'time_limit' => $timeLimit,
        'time_limit_seconds' => $timeLimit * 60,
        'total_questions' => $questionCount,
        'session' => $session,
        'term' => $term,
        'server_time' => time(),
        'start_time' => date('Y-m-d H:i:s')
    ]
]);