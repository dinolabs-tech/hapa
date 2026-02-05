<?php
/**
 * API endpoint for bursary dashboard real-time updates
 * Handles AJAX requests for transaction data, search, and export
 */

// Enable CORS for API access
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include necessary files
require_once('../components/admin_logic.php');
require_once('../helpers/money.php');

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Authentication required']);
    exit();
}

// Check if user has bursary access
$allowed_roles = ['Superuser', 'Administrator', 'Bursary', 'Ceo'];
if (!in_array($_SESSION['role'], $allowed_roles)) {
    echo json_encode(['success' => false, 'message' => 'Insufficient permissions']);
    exit();
}

// Get current term and session
$current_term = $mysqli->query("SELECT cterm FROM currentterm LIMIT 1")->fetch_assoc()['cterm'] ?? '1st Term';
$current_session = $mysqli->query("SELECT csession FROM currentsession LIMIT 1")->fetch_assoc()['csession'] ?? '2024/2025';

// Get request data
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

// Set default term and session if not provided
$term = $input['term'] ?? $current_term;
$session = $input['session'] ?? $current_session;

switch ($action) {
    case 'get_recent_transactions':
        getRecentTransactions($term, $session, $input['limit'] ?? 10);
        break;
    
    case 'search_transactions':
        searchTransactions($term, $session, $input);
        break;
    
    case 'export_transactions':
        exportTransactions($term, $session, $input);
        break;
    
    case 'get_dashboard_data':
        getDashboardData($term, $session, $input['filters'] ?? []);
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

function getRecentTransactions($term, $session, $limit) {
    global $mysqli;
    
    $sql = "SELECT p.id, p.student_id, s.name, p.amount, p.payment_method, p.payment_date, p.receipt_number 
            FROM payments p 
            JOIN students s ON p.student_id = s.id 
            WHERE p.term = ? AND p.session = ? 
            ORDER BY p.payment_date DESC 
            LIMIT ?";
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ssi', $term, $session, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $row['amount_display'] = money_format_naira($row['amount']);
        $transactions[] = $row;
    }
    
    echo json_encode(['success' => true, 'transactions' => $transactions]);
}

function searchTransactions($term, $session, $params) {
    global $mysqli;
    
    $student = $params['student'] ?? '';
    $method = $params['method'] ?? '';
    $date_from = $params['date_from'] ?? '';
    $date_to = $params['date_to'] ?? '';
    
    $sql = "SELECT p.id, p.student_id, s.name, p.amount, p.payment_method, p.payment_date, p.receipt_number 
            FROM payments p 
            JOIN students s ON p.student_id = s.id 
            WHERE p.term = ? AND p.session = ?";
    
    $params = [$term, $session];
    $types = 'ss';
    
    if (!empty($student)) {
        if (is_numeric($student)) {
            $sql .= " AND p.student_id = ?";
            $params[] = $student;
            $types .= 'i';
        } else {
            $sql .= " AND s.name LIKE ?";
            $params[] = '%' . $student . '%';
            $types .= 's';
        }
    }
    
    if (!empty($method)) {
        $sql .= " AND p.payment_method = ?";
        $params[] = $method;
        $types .= 's';
    }
    
    if (!empty($date_from)) {
        $sql .= " AND DATE(p.payment_date) >= ?";
        $params[] = $date_from;
        $types .= 's';
    }
    
    if (!empty($date_to)) {
        $sql .= " AND DATE(p.payment_date) <= ?";
        $params[] = $date_to;
        $types .= 's';
    }
    
    $sql .= " ORDER BY p.payment_date DESC";
    
    $stmt = $mysqli->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $row['amount_display'] = money_format_naira($row['amount']);
        $transactions[] = $row;
    }
    
    echo json_encode(['success' => true, 'transactions' => $transactions]);
}

function exportTransactions($term, $session, $params) {
    global $mysqli;
    
    // Get filtered transactions
    $searchParams = [
        'student' => $params['student'] ?? '',
        'method' => $params['method'] ?? '',
        'date_from' => $params['date_from'] ?? '',
        'date_to' => $params['date_to'] ?? ''
    ];
    
    $sql = "SELECT p.id, p.student_id, s.name, p.amount, p.payment_method, p.payment_date, p.receipt_number 
            FROM payments p 
            JOIN students s ON p.student_id = s.id 
            WHERE p.term = ? AND p.session = ?";
    
    $params = [$term, $session];
    $types = 'ss';
    
    if (!empty($searchParams['student'])) {
        if (is_numeric($searchParams['student'])) {
            $sql .= " AND p.student_id = ?";
            $params[] = $searchParams['student'];
            $types .= 'i';
        } else {
            $sql .= " AND s.name LIKE ?";
            $params[] = '%' . $searchParams['student'] . '%';
            $types .= 's';
        }
    }
    
    if (!empty($searchParams['method'])) {
        $sql .= " AND p.payment_method = ?";
        $params[] = $searchParams['method'];
        $types .= 's';
    }
    
    if (!empty($searchParams['date_from'])) {
        $sql .= " AND DATE(p.payment_date) >= ?";
        $params[] = $searchParams['date_from'];
        $types .= 's';
    }
    
    if (!empty($searchParams['date_to'])) {
        $sql .= " AND DATE(p.payment_date) <= ?";
        $params[] = $searchParams['date_to'];
        $types .= 's';
    }
    
    $sql .= " ORDER BY p.payment_date DESC";
    
    $stmt = $mysqli->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $format = $params['format'] ?? 'csv';
    
    if ($format === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="transactions_export_' . date('Y-m-d_H-i-s') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Write headers
        fputcsv($output, ['ID', 'Student ID', 'Student Name', 'Amount', 'Payment Method', 'Payment Date', 'Receipt Number']);
        
        // Write data
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['id'],
                $row['student_id'],
                $row['name'],
                $row['amount'],
                $row['payment_method'],
                $row['payment_date'],
                $row['receipt_number']
            ]);
        }
        
        fclose($output);
    } else {
        echo json_encode(['success' => false, 'message' => 'Unsupported format']);
    }
}

function getDashboardData($term, $session, $filters) {
    global $mysqli;
    
    // Build WHERE clause for filters
    $where = [];
    $params = [$term, $session];
    $types = 'ss';
    
    if (!empty($filters['student'])) {
        if (is_numeric($filters['student'])) {
            $where[] = "p.student_id = ?";
            $params[] = $filters['student'];
            $types .= 'i';
        } else {
            $where[] = "s.name LIKE ?";
            $params[] = '%' . $filters['student'] . '%';
            $types .= 's';
        }
    }
    
    if (!empty($filters['method'])) {
        $where[] = "p.payment_method = ?";
        $params[] = $filters['method'];
        $types .= 's';
    }
    
    if (!empty($filters['date_from'])) {
        $where[] = "DATE(p.payment_date) >= ?";
        $params[] = $filters['date_from'];
        $types .= 's';
    }
    
    if (!empty($filters['date_to'])) {
        $where[] = "DATE(p.payment_date) <= ?";
        $params[] = $filters['date_to'];
        $types .= 's';
    }
    
    $whereClause = !empty($where) ? ' AND ' . implode(' AND ', $where) : '';
    
    // Get financial summaries
    $total_revenue_sql = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE term = ? AND session = ?" . $whereClause;
    $stmt = $mysqli->prepare($total_revenue_sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $total_revenue = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt->close();
    
    // Get outstanding balance
    $outstanding_sql = "SELECT COALESCE(SUM(sfi.amount - sfi.paid_amount), 0) as total FROM student_fee_items sfi 
                      JOIN student_fees sf ON sfi.student_fee_id = sf.id 
                      WHERE sf.status='active' AND sf.term = ? AND sf.session = ?" . $whereClause;
    $outstanding_params = [$term, $session];
    $outstanding_types = 'ss';
    if (!empty($filters['student'])) {
        if (is_numeric($filters['student'])) {
            $outstanding_sql .= " AND sf.student_id = ?";
            $outstanding_params[] = $filters['student'];
            $outstanding_types .= 'i';
        } else {
            $outstanding_sql .= " AND EXISTS (SELECT 1 FROM students s WHERE s.id = sf.student_id AND s.name LIKE ?)";
            $outstanding_params[] = '%' . $filters['student'] . '%';
            $outstanding_types .= 's';
        }
    }
    $stmt = $mysqli->prepare($outstanding_sql);
    $stmt->bind_param($outstanding_types, ...$outstanding_params);
    $stmt->execute();
    $outstanding_balance = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt->close();
    
    // Get today's payments
    $today = date('Y-m-d');
    $todays_payments_sql = "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE DATE(payment_date) = ? AND term = ? AND session = ?" . $whereClause;
    $todays_params = [$today, $term, $session];
    $todays_types = 'sss';
    if (!empty($filters['student'])) {
        if (is_numeric($filters['student'])) {
            $todays_payments_sql .= " AND student_id = ?";
            $todays_params[] = $filters['student'];
            $todays_types .= 'i';
        } else {
            $todays_payments_sql .= " AND student_id IN (SELECT id FROM students WHERE name LIKE ?)";
            $todays_params[] = '%' . $filters['student'] . '%';
            $todays_types .= 's';
        }
    }
    $stmt = $mysqli->prepare($todays_payments_sql);
    $stmt->bind_param($todays_types, ...$todays_params);
    $stmt->execute();
    $todays_payments = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt->close();
    
    // Get payment methods distribution
    $payment_methods_sql = "SELECT payment_method, COUNT(*) as count, SUM(amount) as total FROM payments WHERE term = ? AND session = ?" . $whereClause . " GROUP BY payment_method ORDER BY total DESC";
    $stmt = $mysqli->prepare($payment_methods_sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $payment_methods_result = $stmt->get_result();
    $payment_methods = [];
    while ($row = $payment_methods_result->fetch_assoc()) {
        $payment_methods[] = $row;
    }
    $stmt->close();
    
    // Get recent transactions
    $recent_transactions_sql = "SELECT p.id, p.student_id, s.name, p.amount, p.payment_method, p.payment_date, p.receipt_number FROM payments p JOIN students s ON p.student_id = s.id WHERE p.term = ? AND p.session = ?" . $whereClause . " ORDER BY p.payment_date DESC LIMIT 10";
    $stmt = $mysqli->prepare($recent_transactions_sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $recent_transactions_result = $stmt->get_result();
    $recent_transactions = [];
    while ($row = $recent_transactions_result->fetch_assoc()) {
        $row['amount_display'] = money_format_naira($row['amount']);
        $recent_transactions[] = $row;
    }
    $stmt->close();
    
    // Get payment trends (last 7 days)
    $payment_trends_sql = "SELECT DATE(payment_date) as date, SUM(amount) as total FROM payments WHERE payment_date >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND term = ? AND session = ?" . $whereClause . " GROUP BY DATE(payment_date) ORDER BY date";
    $stmt = $mysqli->prepare($payment_trends_sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $payment_trends_result = $stmt->get_result();
    $payment_trends = [];
    while ($row = $payment_trends_result->fetch_assoc()) {
        $payment_trends[] = $row;
    }
    $stmt->close();
    
    // Get fee collection status by class
    $fee_collection_sql = "SELECT s.class, COUNT(DISTINCT s.id) as student_count, COALESCE(SUM(sfi.paid_amount), 0) as collected, COALESCE(SUM(sfi.amount), 0) as total_fee FROM students s LEFT JOIN student_fees sf ON s.id = sf.student_id AND sf.status='active' AND sf.term = ? AND sf.session = ? LEFT JOIN student_fee_items sfi ON sf.id = sfi.student_fee_id WHERE 1=1";
    
    $fee_collection_params = [$term, $session];
    $fee_collection_types = 'ss';
    
    if (!empty($filters['student'])) {
        if (is_numeric($filters['student'])) {
            $fee_collection_sql .= " AND s.id = ?";
            $fee_collection_params[] = $filters['student'];
            $fee_collection_types .= 'i';
        } else {
            $fee_collection_sql .= " AND s.name LIKE ?";
            $fee_collection_params[] = '%' . $filters['student'] . '%';
            $fee_collection_types .= 's';
        }
    }
    
    $fee_collection_sql .= " GROUP BY s.class ORDER BY s.class";
    
    $stmt = $mysqli->prepare($fee_collection_sql);
    $stmt->bind_param($fee_collection_types, ...$fee_collection_params);
    $stmt->execute();
    $fee_collection_result = $stmt->get_result();
    $fee_collection_status = [];
    while ($row = $fee_collection_result->fetch_assoc()) {
        $row['collection_rate'] = $row['total_fee'] > 0 ? ($row['collected'] / $row['total_fee']) * 100 : 0;
        $row['collected_display'] = money_format_naira($row['collected']);
        $row['total_fee_display'] = money_format_naira($row['total_fee']);
        $fee_collection_status[] = $row;
    }
    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'financial_summary' => [
            'total_revenue' => $total_revenue,
            'outstanding_balance' => $outstanding_balance,
            'todays_payments' => $todays_payments
        ],
        'payment_methods' => $payment_methods,
        'payment_methods_count' => count($payment_methods),
        'recent_transactions' => $recent_transactions,
        'payment_trends' => $payment_trends,
        'fee_collection_status' => $fee_collection_status
    ]);
}
?>