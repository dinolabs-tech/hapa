<?php
// Database connection
include 'db_connection.php';
require_once('helpers/database_locks.php');

// 1) Fetch current term_id and session_id as strings
$term_id = null;
if ($r = $conn->query("SELECT cterm FROM currentterm LIMIT 1")) {
    if ($row = $r->fetch_assoc()) {
        $term_id = $row['cterm'];
    }
    $r->free();
}
if ($term_id === null) {
    echo json_encode(["message" => "Error: Could not retrieve current term."]);
    exit;
}

// 2) fetch current session
$session_id = null;
if ($r = $conn->query("SELECT csession FROM currentsession LIMIT 1")) {
    if ($row = $r->fetch_assoc()) {
        $session_id = $row['csession'];
    }
    $r->free();
}
if ($session_id === null) {
    echo json_encode(["message" => "Error: Could not retrieve current session."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $attendance_date = $_POST["attendance_date"];
    $attendance = $_POST["attendance"];
    
    // Use advisory lock for attendance processing to prevent concurrent updates
    $lockName = "attendance_{$attendance_date}";
    
    if (!acquireLock($conn, $lockName, 10)) {
        echo json_encode(["message" => "Another attendance update is in progress for this date. Please try again."]);
        exit;
    }
    
    // Start transaction for atomic attendance update
    $conn->begin_transaction();
    
    try {
        // Prepare the statement with FOR UPDATE check first
        $checkStmt = $conn->prepare("
            SELECT student_id FROM attendance 
            WHERE date = ? AND term_id = ? AND session_id = ? 
            FOR UPDATE
        ");
        $checkStmt->bind_param("sss", $attendance_date, $term_id, $session_id);
        $checkStmt->execute();
        $checkStmt->store_result();
        $checkStmt->close();

        // 2) Prepare the statement (same SQL)
        $stmt = $conn->prepare("
            INSERT INTO attendance (
                student_id,
                name,
                class,
                arm,
                date,
                term_id,
                session_id,
                status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE status = VALUES(status)
        ");

        // 3) Bind everything as strings ("s" × 8)
        $stmt->bind_param(
            "ssssssss",
            $student_id,
            $name,
            $student_class,
            $student_arm,
            $attendance_date,
            $term_id,
            $session_id,
            $status
        );

        $success = true;
        foreach ($attendance as $student_id => $byName) {
            foreach ($byName as $full_name => $byClass) {
                $name = $full_name;

                foreach ($byClass as $student_class => $byArm) {
                    foreach ($byArm as $student_arm => $status) {
                        if (!$stmt->execute()) {
                            $success = false;
                            error_log("DB error: " . $stmt->error);
                        }
                    }
                }
            }
        }
        $stmt->close();
        
        $conn->commit();
        releaseLock($conn, $lockName);

        echo json_encode([
            "message" => $success
                ? "Attendance saved successfully!"
                : "Error saving some attendance records."
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        releaseLock($conn, $lockName);
        echo json_encode(["message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["message" => "Invalid request method."]);
}

$conn->close();
?>