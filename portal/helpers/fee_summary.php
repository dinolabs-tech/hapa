<?php
/**
 * Fee Summary Helper
 * Manages cached fee summaries for performance optimization
 */

require_once __DIR__ . '/../db_connection.php';

/**
 * Get or create fee summary for a specific date, term, and session
 */
function getFeeSummary($term, $session, $date = null) {
    global $conn;
    
    $date = $date ?? date('Y-m-d');
    
    // Try to get cached summary
    $stmt = $conn->prepare("SELECT * FROM fee_summaries WHERE summary_date = ? AND term = ? AND session = ?");
    $stmt->bind_param("sss", $date, $term, $session);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $stmt->close();
        return $row;
    }
    $stmt->close();
    
    // Calculate and cache summary
    return calculateAndCacheSummary($term, $session, $date);
}

/**
 * Calculate and cache fee summary
 */
function calculateAndCacheSummary($term, $session, $date = null) {
    global $conn;
    
    $date = $date ?? date('Y-m-d');
    
    // Calculate total collected
    $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE term = ? AND session = ? AND DATE(payment_date) <= ?");
    $stmt->bind_param("sss", $term, $session, $date);
    $stmt->execute();
    $totalCollected = $stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();
    
    // Calculate total outstanding
    $stmt = $conn->prepare("SELECT COALESCE(SUM(sfi.amount - sfi.paid_amount), 0) as total 
                            FROM student_fee_items sfi 
                            JOIN student_fees sf ON sfi.student_fee_id = sf.id 
                            WHERE sf.status = 'active' AND sf.term = ? AND sf.session = ?");
    $stmt->bind_param("ss", $term, $session);
    $stmt->execute();
    $totalOutstanding = $stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();
    
    // Count students with active fees
    $stmt = $conn->prepare("SELECT COUNT(DISTINCT student_id) as count FROM student_fees WHERE status = 'active' AND term = ? AND session = ?");
    $stmt->bind_param("ss", $term, $session);
    $stmt->execute();
    $studentCount = $stmt->get_result()->fetch_assoc()['count'];
    $stmt->close();
    
    // Count payments
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM payments WHERE term = ? AND session = ? AND DATE(payment_date) <= ?");
    $stmt->bind_param("sss", $term, $session, $date);
    $stmt->execute();
    $paymentCount = $stmt->get_result()->fetch_assoc()['count'];
    $stmt->close();
    
    // Insert or update summary
    $stmt = $conn->prepare("INSERT INTO fee_summaries (summary_date, term, session, total_collected, total_outstanding, student_count, payment_count) 
                            VALUES (?, ?, ?, ?, ?, ?, ?) 
                            ON DUPLICATE KEY UPDATE 
                            total_collected = VALUES(total_collected),
                            total_outstanding = VALUES(total_outstanding),
                            student_count = VALUES(student_count),
                            payment_count = VALUES(payment_count),
                            updated_at = CURRENT_TIMESTAMP");
    $stmt->bind_param("sssiiii", $date, $term, $session, $totalCollected, $totalOutstanding, $studentCount, $paymentCount);
    $stmt->execute();
    $stmt->close();
    
    return [
        'summary_date' => $date,
        'term' => $term,
        'session' => $session,
        'total_collected' => $totalCollected,
        'total_outstanding' => $totalOutstanding,
        'student_count' => $studentCount,
        'payment_count' => $paymentCount
    ];
}

/**
 * Invalidate summary cache (call after payment)
 */
function invalidateFeeSummary($term, $session) {
    global $conn;
    
    $today = date('Y-m-d');
    
    // Delete today's cached summary to force recalculation
    $stmt = $conn->prepare("DELETE FROM fee_summaries WHERE summary_date = ? AND term = ? AND session = ?");
    $stmt->bind_param("sss", $today, $term, $session);
    $stmt->execute();
    $stmt->close();
    
    // Recalculate immediately
    return calculateAndCacheSummary($term, $session, $today);
}

/**
 * Get dashboard data with single query optimization
 */
function getBursaryDashboardData($term, $session) {
    global $conn;
    
    $today = date('Y-m-d');
    
    // Get cached summary
    $summary = getFeeSummary($term, $session, $today);
    
    // Get today's payments
    $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE DATE(payment_date) = ? AND term = ? AND session = ?");
    $stmt->bind_param("sss", $today, $term, $session);
    $stmt->execute();
    $todaysPayments = $stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();
    
    // Get payment methods distribution
    $methods = [];
    $stmt = $conn->prepare("SELECT payment_method, COUNT(*) as count, SUM(amount) as total FROM payments WHERE term = ? AND session = ? GROUP BY payment_method ORDER BY total DESC");
    $stmt->bind_param("ss", $term, $session);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $methods[] = $row;
    }
    $stmt->close();
    
    // Get recent transactions (last 10)
    $transactions = [];
    $stmt = $conn->prepare("SELECT p.id, p.student_id, s.name, p.amount, p.payment_method, p.payment_date, p.receipt_number 
                            FROM payments p 
                            JOIN students s ON p.student_id = s.id 
                            WHERE p.term = ? AND p.session = ? 
                            ORDER BY p.payment_date DESC LIMIT 10");
    $stmt->bind_param("ss", $term, $session);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }
    $stmt->close();
    
    return [
        'financial_summary' => [
            'total_revenue' => $summary['total_collected'],
            'outstanding_balance' => $summary['total_outstanding'],
            'todays_payments' => $todaysPayments,
            'payment_methods_count' => count($methods)
        ],
        'payment_methods' => $methods,
        'recent_transactions' => $transactions,
        'student_count' => $summary['student_count'],
        'payment_count' => $summary['payment_count']
    ];
}