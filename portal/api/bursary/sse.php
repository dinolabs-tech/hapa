<?php
/**
 * EduHive API - Server-Sent Events Endpoint for Bursary
 * Provides real-time updates for the bursary dashboard
 */

// Include required files
require_once __DIR__ . '/../../db_connection.php';
require_once __DIR__ . '/../../helpers/sse_handler.php';

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

// Get optional parameters
$term = $_GET['term'] ?? $currentTerm;
$session = $_GET['session'] ?? $currentSession;
$maxDuration = intval($_GET['duration'] ?? 300); // 5 minutes default

// Run SSE connection
handleSSEConnection($term, $session, $maxDuration);