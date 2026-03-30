<?php
/**
 * Server-Sent Events (SSE) Handler
 * Provides real-time updates for bursary dashboard
 */

require_once __DIR__ . '/../db_connection.php';
require_once __DIR__ . '/fee_summary.php';

/**
 * Send SSE headers
 */
function sseHeaders() {
    // Disable buffering and caching
    ini_set('output_buffering', 'off');
    ini_set('zlib.output_compression', false);
    ini_set('implicit_flush', true);
    
    // Set SSE headers
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('Connection: keep-alive');
    header('X-Accel-Buffering: no'); // Disable nginx buffering
    
    // Disable execution time limit
    set_time_limit(0);
    
    // Flush any existing output
    if (ob_get_level() > 0) {
        ob_end_flush();
    }
}

/**
 * Send SSE event
 */
function sendSSEEvent($event, $data) {
    echo "event: {$event}\n";
    echo "data: " . json_encode($data) . "\n\n";
    
    // Flush output
    if (ob_get_level() > 0) {
        ob_flush();
    }
    flush();
}

/**
 * Send SSE heartbeat
 */
function sendSSEHeartbeat() {
    echo ": heartbeat\n\n";
    
    if (ob_get_level() > 0) {
        ob_flush();
    }
    flush();
}

/**
 * Check for new payments since last check
 */
function checkForNewPayments($conn, $term, $session, $lastCheckTime) {
    $stmt = $conn->prepare("SELECT p.id, p.student_id, s.name, p.amount, p.payment_method, p.payment_date, p.receipt_number 
                            FROM payments p 
                            JOIN students s ON p.student_id = s.id 
                            WHERE p.term = ? AND p.session = ? 
                            AND p.created_at > ? 
                            ORDER BY p.payment_date DESC");
    $stmt->bind_param("sss", $term, $session, $lastCheckTime);
    $stmt->execute();
    $result = $stmt->get_result();
    $payments = [];
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
    $stmt->close();
    
    return $payments;
}

/**
 * Get updated dashboard stats
 */
function getUpdatedStats($term, $session) {
    return getBursaryDashboardData($term, $session);
}

/**
 * Run SSE loop for bursary updates
 */
function runBursarySSE($term, $session, $intervalSeconds = 5) {
    global $conn;
    
    sseHeaders();
    
    $lastCheckTime = date('Y-m-d H:i:s');
    $heartbeatCounter = 0;
    
    // Send initial connection message
    sendSSEEvent('connected', [
        'message' => 'SSE connection established',
        'timestamp' => time()
    ]);
    
    // Send initial data
    $initialData = getUpdatedStats($term, $session);
    sendSSEEvent('initial_data', $initialData);
    
    // Main SSE loop
    while (true) {
        // Check if connection is still alive
        if (connection_aborted()) {
            break;
        }
        
        // Check for new payments
        $newPayments = checkForNewPayments($conn, $term, $session, $lastCheckTime);
        
        if (!empty($newPayments)) {
            // Update last check time
            $lastCheckTime = date('Y-m-d H:i:s');
            
            // Invalidate cache and get fresh data
            invalidateFeeSummary($term, $session);
            $updatedStats = getUpdatedStats($term, $session);
            
            // Send new payments event
            sendSSEEvent('new_payments', [
                'payments' => $newPayments,
                'stats' => $updatedStats['financial_summary'],
                'timestamp' => time()
            ]);
        }
        
        // Send heartbeat every 30 seconds (6 intervals of 5 seconds)
        $heartbeatCounter++;
        if ($heartbeatCounter >= 6) {
            sendSSEHeartbeat();
            $heartbeatCounter = 0;
        }
        
        // Sleep for the interval
        sleep($intervalSeconds);
    }
}

/**
 * Handle SSE connection with timeout
 */
function handleSSEConnection($term, $session, $maxDuration = 300) {
    global $conn;
    
    sseHeaders();
    
    $startTime = time();
    $lastCheckTime = date('Y-m-d H:i:s');
    $heartbeatCounter = 0;
    $intervalSeconds = 5;
    
    // Send initial connection message
    sendSSEEvent('connected', [
        'message' => 'SSE connection established',
        'timestamp' => time(),
        'max_duration' => $maxDuration
    ]);
    
    // Send initial data
    $initialData = getUpdatedStats($term, $session);
    sendSSEEvent('initial_data', $initialData);
    
    // Main SSE loop with timeout
    while (true) {
        // Check timeout
        if ((time() - $startTime) >= $maxDuration) {
            sendSSEEvent('timeout', [
                'message' => 'Connection timeout, please reconnect',
                'timestamp' => time()
            ]);
            break;
        }
        
        // Check if connection is still alive
        if (connection_aborted()) {
            break;
        }
        
        // Check for new payments
        $newPayments = checkForNewPayments($conn, $term, $session, $lastCheckTime);
        
        if (!empty($newPayments)) {
            // Update last check time
            $lastCheckTime = date('Y-m-d H:i:s');
            
            // Invalidate cache and get fresh data
            invalidateFeeSummary($term, $session);
            $updatedStats = getUpdatedStats($term, $session);
            
            // Send new payments event
            sendSSEEvent('new_payments', [
                'payments' => $newPayments,
                'stats' => $updatedStats['financial_summary'],
                'timestamp' => time()
            ]);
        }
        
        // Send heartbeat every 30 seconds
        $heartbeatCounter++;
        if ($heartbeatCounter >= 6) {
            sendSSEHeartbeat();
            $heartbeatCounter = 0;
        }
        
        // Sleep for the interval
        sleep($intervalSeconds);
    }
}