<?php
/**
 * EduHive API - Unified Bursary Dashboard Endpoint
 * Returns all dashboard data in a single optimized call
 */

// Set JSON content type first
header('Content-Type: application/json');

// Include CORS handler
require_once __DIR__ . '/../cors.php';

require_once __DIR__ . '/../../db_connection.php';
require_once __DIR__ . '/../../helpers/fee_summary.php';
require_once __DIR__ . '/../../helpers/money.php';

// Only accept GET requests
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

// Get user role from login table
$role = null;
$stmt = $conn->prepare("SELECT role FROM login WHERE id = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $role = $row['role'];
}
$stmt->close();

// Check if bursary or admin
if (!in_array($role, ['Bursary', 'Administrator', 'Superuser', 'Ceo'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

// Get current term and session
$currentTerm = '1st Term';
$currentSession = '2024/2025';

$result = $conn->query("SELECT cterm FROM currentterm LIMIT 1");
if ($row = $result->fetch_assoc()) {
    $currentTerm = $row['cterm'];
}

$result = $conn->query("SELECT csession FROM currentsession LIMIT 1");
if ($row = $result->fetch_assoc()) {
    $currentSession = $row['csession'];
}

// Get filter parameters
$term = $_GET['term'] ?? $currentTerm;
$session = $_GET['session'] ?? $currentSession;
$forceRefresh = isset($_GET['refresh']) && $_GET['refresh'] === 'true';

// Invalidate cache if refresh requested
if ($forceRefresh) {
    invalidateFeeSummary($term, $session);
}

// Get dashboard data using optimized helper
$dashboardData = getBursaryDashboardData($term, $session);

// Get fee collection status by class
$feeCollectionStatus = [];
$stmt = $conn->prepare("SELECT s.class, COUNT(DISTINCT s.id) as student_count, 
                               COALESCE(SUM(sfi.paid_amount), 0) as collected, 
                               COALESCE(SUM(sfi.amount), 0) as total_fee 
                        FROM students s 
                        LEFT JOIN student_fees sf ON s.id = sf.student_id AND sf.status='active' AND sf.term = ? AND sf.session = ? 
                        LEFT JOIN student_fee_items sfi ON sf.id = sfi.student_fee_id 
                        GROUP BY s.class 
                        ORDER BY s.class");
$stmt->bind_param("ss", $term, $session);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $row['collection_rate'] = $row['total_fee'] > 0 ? ($row['collected'] / $row['total_fee']) * 100 : 0;
    $row['collected_display'] = money_format_naira($row['collected']);
    $row['total_fee_display'] = money_format_naira($row['total_fee']);
    $feeCollectionStatus[] = $row;
}
$stmt->close();

// Get payment trends (last 12 months)
$paymentTrends = [];
$stmt = $conn->prepare("SELECT DATE_FORMAT(payment_date, '%Y-%m') as date, SUM(amount) as total 
                        FROM payments 
                        WHERE payment_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH) 
                        AND term = ? AND session = ? 
                        GROUP BY DATE_FORMAT(payment_date, '%Y-%m') 
                        ORDER BY date");
$stmt->bind_param("ss", $term, $session);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $paymentTrends[] = $row;
}
$stmt->close();

// Build response
$response = [
    'status' => 'success',
    'data' => [
        'current_term' => $currentTerm,
        'current_session' => $currentSession,
        'financial_summary' => $dashboardData['financial_summary'],
        'payment_methods' => $dashboardData['payment_methods'],
        'recent_transactions' => $dashboardData['recent_transactions'],
        'fee_collection_status' => $feeCollectionStatus,
        'payment_trends' => $paymentTrends,
        'student_count' => $dashboardData['student_count'],
        'payment_count' => $dashboardData['payment_count'],
        'generated_at' => date('Y-m-d H:i:s')
    ]
];

echo json_encode($response);