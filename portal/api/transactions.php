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
?>