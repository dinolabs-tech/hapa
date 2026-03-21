<?php
/**
 * Database Locking and Transaction Helper Functions
 * 
 * This file provides utilities to prevent race conditions in database operations.
 * 
 * Usage:
 * require_once('helpers/database_locks.php');
 */

/**
 * Execute a callback within a database transaction with automatic commit/rollback.
 * 
 * @param mysqli $conn Database connection
 * @param callable $callback The function to execute within the transaction
 * @return mixed The return value of the callback, or false on failure
 * @throws Exception If transaction fails
 */
function withTransaction($conn, $callback) {
    $conn->begin_transaction();
    try {
        $result = $callback($conn);
        $conn->commit();
        return $result;
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
}

/**
 * Acquire an advisory lock for a specific resource.
 * This prevents race conditions at the application level.
 * 
 * @param mysqli $conn Database connection
 * @param string $lockName The name of the lock (e.g., 'exam_submission_123')
 * @param int $timeout Timeout in seconds to wait for the lock (default 10)
 * @return bool True if lock acquired, false otherwise
 */
function acquireLock($conn, $lockName, $timeout = 10) {
    $lockName = $conn->real_escape_string($lockName);
    $result = $conn->query("SELECT GET_LOCK('$lockName', $timeout)");
    if ($result) {
        $row = $result->fetch_row();
        return $row[0] == 1;
    }
    return false;
}

/**
 * Release an advisory lock.
 * 
 * @param mysqli $conn Database connection
 * @param string $lockName The name of the lock to release
 * @return bool True if lock released, false otherwise
 */
function releaseLock($conn, $lockName) {
    $lockName = $conn->real_escape_string($lockName);
    $result = $conn->query("SELECT RELEASE_LOCK('$lockName')");
    if ($result) {
        $row = $result->fetch_row();
        return $row[0] == 1;
    }
    return false;
}

/**
 * Execute a callback with an advisory lock.
 * Automatically acquires and releases the lock.
 * 
 * @param mysqli $conn Database connection
 * @param string $lockName The name of the lock
 * @param callable $callback The function to execute while holding the lock
 * @param int $timeout Timeout in seconds to wait for the lock
 * @return mixed The return value of the callback, or false if lock not acquired
 * @throws Exception If callback throws
 */
function withLock($conn, $lockName, $callback, $timeout = 10) {
    if (!acquireLock($conn, $lockName, $timeout)) {
        return false;
    }
    try {
        $result = $callback($conn);
        return $result;
    } finally {
        releaseLock($conn, $lockName);
    }
}

/**
 * Select a row with FOR UPDATE lock for read-modify-write operations.
 * 
 * @param mysqli $conn Database connection
 * @param string $table Table name
 * @param string $where WHERE clause (without 'WHERE')
 * @param array $params Parameters to bind
 * @param string $types Parameter types (i, s, d, b)
 * @return array|null The fetched row or null
 */
function selectForUpdate($conn, $table, $where, $params = [], $types = '') {
    $sql = "SELECT * FROM $table WHERE $where FOR UPDATE";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return null;
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row;
}

/**
 * Insert or update with atomic operation (upsert).
 * 
 * @param mysqli $conn Database connection
 * @param string $table Table name
 * @param array $data Associative array of column => value
 * @param array $uniqueKeys Columns that form the unique constraint
 * @param array $updateColumns Columns to update on duplicate (if empty, all except unique keys)
 * @return int|bool Insert ID on success, false on failure
 */
function upsert($conn, $table, $data, $uniqueKeys, $updateColumns = []) {
    $columns = array_keys($data);
    $values = array_values($data);
    $placeholders = implode(',', array_fill(0, count($values), '?'));
    
    $sql = "INSERT INTO $table (" . implode(',', $columns) . ") VALUES ($placeholders)";
    
    if (!empty($updateColumns)) {
        $updates = [];
        foreach ($updateColumns as $col) {
            $updates[] = "$col = VALUES($col)";
        }
        $sql .= " ON DUPLICATE KEY UPDATE " . implode(',', $updates);
    } else {
        // Update all non-unique columns
        $updates = [];
        foreach ($columns as $col) {
            if (!in_array($col, $uniqueKeys)) {
                $updates[] = "$col = VALUES($col)";
            }
        }
        if (!empty($updates)) {
            $sql .= " ON DUPLICATE KEY UPDATE " . implode(',', $updates);
        }
    }
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    
    // Build type string based on values
    $types = '';
    foreach ($values as $val) {
        if (is_int($val)) {
            $types .= 'i';
        } elseif (is_float($val)) {
            $types .= 'd';
        } else {
            $types .= 's';
        }
    }
    
    $stmt->bind_param($types, ...$values);
    $result = $stmt->execute();
    $insertId = $stmt->insert_id;
    $stmt->close();
    
    return $result ? ($insertId > 0 ? $insertId : true) : false;
}

/**
 * Check if a record exists, with row lock for atomic check-and-insert.
 * 
 * @param mysqli $conn Database connection
 * @param string $table Table name
 * @param array $conditions Associative array of column => value for WHERE
 * @return bool True if exists, false otherwise
 */
function existsWithLock($conn, $table, $conditions) {
    $where = [];
    $params = [];
    $types = '';
    
    foreach ($conditions as $col => $val) {
        $where[] = "$col = ?";
        $params[] = $val;
        $types .= is_int($val) ? 'i' : (is_float($val) ? 'd' : 's');
    }
    
    $sql = "SELECT 1 FROM $table WHERE " . implode(' AND ', $where) . " FOR UPDATE LIMIT 1";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    
    return $exists;
}
?>