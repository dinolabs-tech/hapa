<?php
/**
 * EduHive Mobile API - CBT Subjects Endpoint
 * Returns available CBT subjects for the student
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

if (!$userId) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
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

// Get CBT subjects from question table (distinct subjects for student's class)
$subjects = [];

$stmt = $conn->prepare("SELECT DISTINCT subject FROM question WHERE class = ? AND arm = ? AND session = ? AND term = ?");
if ($stmt) {
    $stmt->bind_param("ssss", $student['class'], $student['arm'], $session, $term);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $subject = $row['subject'];
        
        // Check if student has already taken this exam (from cbt_score table)
        $stmt2 = $conn->prepare("SELECT id FROM cbt_score WHERE login = ? AND subject = ? AND session = ? AND term = ?");
        $stmt2->bind_param("ssss", $student['id'], $subject, $session, $term);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $taken = $result2->num_rows > 0;
        $stmt2->close();
        
        $subjects[] = [
            'subject' => $subject,
            'taken' => $taken,
            'session' => $session,
            'term' => $term
        ];
    }
    $stmt->close();
}

echo json_encode([
    'status' => 'success',
    'data' => [
        'subjects' => $subjects,
        'session' => $session,
        'term' => $term
    ]
]);