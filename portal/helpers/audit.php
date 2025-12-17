<?php
// helpers/audit.php
// Immutable audit logging for Bursary module

require_once('db_connection.php');
require_once('money.php');
require_once('exports.php');

global $conn;
$mysqli = $conn; // Use the existing connection variable

function audit_log($action, $object_type, $object_id, $before_state, $after_state) {
    global $mysqli;
    $user_id = $_SESSION['user_id'] ?? 0;
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'cli';
    $stmt = $mysqli->prepare(
        "INSERT INTO audit_logs (user_id, action, object_type, object_id, before_state, after_state, ip) VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $before_json = json_encode($before_state, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $after_json = json_encode($after_state, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $stmt->bind_param(
        'ississs',
        $user_id,
        $action,
        $object_type,
        $object_id,
        $before_json,
        $after_json,
        $ip
    );
    if (!$stmt->execute()) {
        error_log(json_encode([
            'error' => $stmt->error,
            'action' => $action,
            'object_type' => $object_type,
            'object_id' => $object_id,
            'timestamp' => date('c'),
            'ip' => $ip
        ]));
    }
    $stmt->close();
}
?>
