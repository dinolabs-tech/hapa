<?php
// Database connection
include 'db_connection.php';


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

    // 3) Bind everything as strings ("s" × 9)
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

    echo json_encode([
        "message" => $success
            ? "Attendance saved successfully!"
            : "Error saving some attendance records."
    ]);
} else {
    echo json_encode(["message" => "Invalid request method."]);
}

$conn->close();
?>