<?php
/**
 * EduHive API - CORS Handler
 * Handles Cross-Origin Resource Sharing for mobile app and web requests
 * 
 * Security: Uses whitelist-based origin validation with mobile-friendly handling
 * Usage: Include this file at the top of all API endpoints
 * require_once __DIR__ . '/../cors.php';
 */

// Define allowed origins (whitelist)
// Add your production and development domains here
$allowed_origins = [
    // Production domains
    'https://hapacollege.com',
    'https://www.hapacollege.com',
    'https://hapacollege.com/eduhive',
    
    // Mobile app schemes (Cordova/Capacitor/Ionic)
    'file://',
    'ionic://localhost',
    'capacitor://localhost',
    'http://localhost',
    'https://localhost',
    
    // Android WebView specific
    'null',
    
    // Development domains (remove in production if not needed)
    'http://localhost:8080',
    'http://localhost:3000',
    'http://127.0.0.1',
    'http://127.0.0.1:8080',
    'http://127.0.0.1:3000',
];

// Mobile app user agents that should be allowed
$mobile_user_agents = [
    'eduhive',
    'cordova',
    'capacitor',
    'ionic',
    'android',
    'iphone',
    'ipad',
];

// For Cordova mobile apps, the origin might be null, file://, or undefined
// We need to handle this case specially
$origin = $_SERVER['HTTP_ORIGIN'] ?? null;
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Helper function to check if request is from mobile app
function isMobileAppRequest($user_agent, $origin) {
    // Check for mobile app user agents
    $mobile_patterns = ['eduhive', 'cordova', 'capacitor', 'ionic'];
    foreach ($mobile_patterns as $pattern) {
        if (stripos($user_agent, $pattern) !== false) {
            return true;
        }
    }
    
    // Check for mobile app origin schemes
    if ($origin === null || $origin === 'null' || $origin === '') {
        return true;
    }
    
    if (strpos($origin, 'file://') === 0) {
        return true;
    }
    
    if (strpos($origin, 'ionic://') === 0 || strpos($origin, 'capacitor://') === 0) {
        return true;
    }
    
    // Check for Android WebView
    if (strpos($origin, 'http://localhost') === 0 || strpos($origin, 'https://localhost') === 0) {
        return true;
    }
    
    return false;
}

// Check if origin is allowed
$origin_allowed = false;
$origin_to_use = '*';

// Check if this is a mobile app request
$is_mobile_app = isMobileAppRequest($user_agent, $origin);

if ($is_mobile_app) {
    // Mobile app requests - allow them
    $origin_allowed = true;
    // For mobile apps, we can use wildcard or the actual origin
    $origin_to_use = $origin ?: '*';
} elseif ($origin === null || $origin === '' || $origin === 'null') {
    // No origin header - could be mobile app or direct API call
    // Allow these requests (mobile apps often don't send Origin)
    $origin_allowed = true;
    $origin_to_use = '*';
} elseif (in_array($origin, $allowed_origins)) {
    // Origin is in whitelist
    $origin_allowed = true;
    $origin_to_use = $origin;
} elseif (strpos($origin, 'file://') === 0) {
    // Cordova mobile app
    $origin_allowed = true;
    $origin_to_use = '*';
} elseif (strpos($origin, 'ionic://') === 0 || strpos($origin, 'capacitor://') === 0) {
    // Ionic/Capacitor mobile app
    $origin_allowed = true;
    $origin_to_use = '*';
} elseif (strpos($origin, 'dinolabstech.com') !== false) {
    // Any subdomain of dinolabstech.com
    $origin_allowed = true;
    $origin_to_use = $origin;
} else {
    // Origin not in whitelist - log for debugging
    $origin_allowed = false;
    
    // Log blocked request for monitoring
    $log_message = sprintf(
        "[%s] CORS blocked - Origin: %s, IP: %s, URI: %s, User-Agent: %s",
        date('Y-m-d H:i:s'),
        $origin ?? 'null',
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        $_SERVER['REQUEST_URI'] ?? 'unknown',
        $user_agent
    );
    error_log($log_message);
}

// If origin not allowed, return 403 for actual requests
if (!$origin_allowed && $request_method !== 'OPTIONS') {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Access denied. Invalid origin.',
        'code' => 'CORS_BLOCKED',
        'origin' => $origin
    ]);
    exit;
}

// Set CORS headers
// For mobile apps, use wildcard to avoid origin matching issues
if ($is_mobile_app || $origin === null || $origin === 'null' || $origin === '') {
    header('Access-Control-Allow-Origin: *');
} else {
    header("Access-Control-Allow-Origin: $origin_to_use");
}

// Allow credentials (cookies, authorization headers)
// Note: When using wildcard origin, credentials are not allowed
// For mobile apps, we use token-based auth via headers, not cookies
if (!$is_mobile_app && $origin !== null && $origin !== 'null' && $origin !== '') {
    header('Access-Control-Allow-Credentials: true');
}

// Cache preflight response for 1 hour (3600 seconds)
header('Access-Control-Max-Age: 3600');

// Handle OPTIONS preflight request
if ($request_method === 'OPTIONS') {
    // Allowed methods
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
    
    // Allowed headers - be permissive for mobile apps
    $default_headers = 'Content-Type, Authorization, X-Requested-With, X-User-ID, X-Idempotency-Key, X-Request-ID, X-Request-Hash, Accept, Origin, X-CSRF-Token, Cache-Control';
    
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        // Echo back the requested headers
        $requested_headers = $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'];
        header('Access-Control-Allow-Headers: ' . $requested_headers);
    } else {
        header('Access-Control-Allow-Headers: ' . $default_headers);
    }
    
    // Exit with 204 No Content for preflight
    http_response_code(204);
    exit;
}

// Set content type for actual requests
header('Content-Type: application/json');

// Expose specific headers to the client
header('Access-Control-Expose-Headers: X-Request-ID, X-RateLimit-Remaining, Content-Type');
