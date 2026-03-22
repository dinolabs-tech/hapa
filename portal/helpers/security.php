<?php
/**
 * Security Helper Functions
 * Provides input sanitization, validation, and CSRF protection
 */

if (!defined('SECURITY_HELPER_LOADED')) {
    define('SECURITY_HELPER_LOADED', true);

    /**
     * Sanitize string input - removes dangerous characters
     * @param string $input The input to sanitize
     * @return string Sanitized string
     */
    function sanitize_string($input) {
        if ($input === null) return '';
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Sanitize input for database (basic cleaning, use prepared statements for SQL)
     * @param string $input The input to sanitize
     * @return string Sanitized string
     */
    function sanitize_input($input) {
        if ($input === null) return '';
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return $input;
    }

    /**
     * Validate and sanitize integer ID
     * @param mixed $value The value to validate
     * @param bool $positiveOnly Whether the ID must be positive
     * @return int|false Returns the integer or false if invalid
     */
    function validate_id($value, $positiveOnly = true) {
        if ($value === null || $value === '') return false;
        $int = filter_var($value, FILTER_VALIDATE_INT);
        if ($int === false) return false;
        if ($positiveOnly && $int <= 0) return false;
        return $int;
    }

    /**
     * Validate string with length constraints
     * @param mixed $value The value to validate
     * @param int $minLength Minimum length (default 1)
     * @param int $maxLength Maximum length (default 255)
     * @return string|false Returns sanitized string or false if invalid
     */
    function validate_string($value, $minLength = 1, $maxLength = 255) {
        if ($value === null) return false;
        $value = trim($value);
        $length = strlen($value);
        if ($length < $minLength || $length > $maxLength) return false;
        return sanitize_string($value);
    }

    /**
     * Validate email address
     * @param mixed $value The value to validate
     * @return string|false Returns email or false if invalid
     */
    function validate_email($value) {
        if ($value === null) return false;
        $value = trim($value);
        $email = filter_var($value, FILTER_VALIDATE_EMAIL);
        return $email !== false ? $email : false;
    }

    /**
     * Validate numeric amount (for payments, fees, etc.)
     * @param mixed $value The value to validate
     * @param float $min Minimum value (default 0)
     * @param float $max Maximum value (default PHP_FLOAT_MAX)
     * @return float|false Returns float or false if invalid
     */
    function validate_amount($value, $min = 0, $max = PHP_FLOAT_MAX) {
        if ($value === null || $value === '') return false;
        $float = filter_var($value, FILTER_VALIDATE_FLOAT);
        if ($float === false) return false;
        if ($float < $min || $float > $max) return false;
        return $float;
    }

    /**
     * Validate date string
     * @param mixed $value The value to validate
     * @param string $format Expected date format (default Y-m-d)
     * @return string|false Returns date string or false if invalid
     */
    function validate_date($value, $format = 'Y-m-d') {
        if ($value === null || $value === '') return false;
        $date = DateTime::createFromFormat($format, $value);
        if ($date === false) return false;
        $errors = DateTime::getLastErrors();
        if ($errors && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
            return false;
        }
        return $date->format($format);
    }

    /**
     * Validate alphanumeric string (for usernames, etc.)
     * @param mixed $value The value to validate
     * @param int $minLength Minimum length
     * @param int $maxLength Maximum length
     * @return string|false Returns string or false if invalid
     */
    function validate_alphanumeric($value, $minLength = 1, $maxLength = 50) {
        if ($value === null) return false;
        $value = trim($value);
        $length = strlen($value);
        if ($length < $minLength || $length > $maxLength) return false;
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $value)) return false;
        return $value;
    }

    /**
     * Validate enum value against allowed values
     * @param mixed $value The value to validate
     * @param array $allowed Array of allowed values
     * @return string|false Returns value or false if invalid
     */
    function validate_enum($value, array $allowed) {
        if ($value === null) return false;
        return in_array($value, $allowed, true) ? $value : false;
    }

    /**
     * Validate phone number (Nigerian format)
     * @param mixed $value The value to validate
     * @return string|false Returns phone or false if invalid
     */
    function validate_phone($value) {
        if ($value === null) return false;
        $value = trim($value);
        // Remove spaces and dashes
        $value = preg_replace('/[\s\-]/', '', $value);
        // Nigerian phone: starts with 0 or +234, followed by 10 or 7-8 digits
        if (!preg_match('/^(\+234|0)?[0-9]{10}$/', $value)) return false;
        return $value;
    }

    /**
     * Generate CSRF token
     * @return string CSRF token
     */
    function csrf_token() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Generate CSRF hidden field for forms
     * @return string HTML hidden input field
     */
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }

    /**
     * Verify CSRF token
     * @param string|null $token The token to verify (defaults to POST value)
     * @return bool True if valid, false otherwise
     */
    function csrf_verify($token = null) {
        if ($token === null) {
            $token = $_POST['csrf_token'] ?? '';
        }
        if (!isset($_SESSION['csrf_token'])) return false;
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Regenerate CSRF token after verification (prevents token reuse)
     */
    function csrf_regenerate() {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    /**
     * Verify CSRF and regenerate (convenience function)
     * @param string|null $token The token to verify
     * @return bool True if valid, false otherwise
     */
    function csrf_verify_and_regenerate($token = null) {
        $valid = csrf_verify($token);
        if ($valid) {
            csrf_regenerate();
        }
        return $valid;
    }

    /**
     * Safe redirect with URL validation
     * @param string $url The URL to redirect to
     * @param bool $allowExternal Whether to allow external URLs
     */
    function safe_redirect($url, $allowExternal = false) {
        if (!$allowExternal) {
            // Only allow relative URLs or same-domain URLs
            $parsed = parse_url($url);
            if (isset($parsed['host']) || isset($parsed['scheme'])) {
                // External URL - redirect to home
                $url = '/';
            }
        }
        header('Location: ' . $url);
        exit();
    }

    /**
     * Get sanitized GET parameter
     * @param string $key The parameter key
     * @param mixed $default Default value if not set
     * @return mixed Sanitized value or default
     */
    function get_input($key, $default = null) {
        $value = $_GET[$key] ?? $default;
        if ($value === $default) return $default;
        return is_string($value) ? sanitize_input($value) : $value;
    }

    /**
     * Get sanitized POST parameter
     * @param string $key The parameter key
     * @param mixed $default Default value if not set
     * @return mixed Sanitized value or default
     */
    function post_input($key, $default = null) {
        $value = $_POST[$key] ?? $default;
        if ($value === $default) return $default;
        return is_string($value) ? sanitize_input($value) : $value;
    }

    /**
     * Get validated integer from GET
     * @param string $key The parameter key
     * @param bool $positiveOnly Whether the value must be positive
     * @return int|false Validated integer or false
     */
    function get_int($key, $positiveOnly = true) {
        return validate_id($_GET[$key] ?? null, $positiveOnly);
    }

    /**
     * Get validated integer from POST
     * @param string $key The parameter key
     * @param bool $positiveOnly Whether the value must be positive
     * @return int|false Validated integer or false
     */
    function post_int($key, $positiveOnly = true) {
        return validate_id($_POST[$key] ?? null, $positiveOnly);
    }

    /**
     * Get validated amount from POST
     * @param string $key The parameter key
     * @param float $min Minimum value
     * @return float|false Validated amount or false
     */
    function post_amount($key, $min = 0) {
        return validate_amount($_POST[$key] ?? null, $min);
    }

    /**
     * Escape output for HTML
     * @param mixed $value The value to escape
     * @return string Escaped value
     */
    function e($value) {
        if ($value === null) return '';
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate a secure random token
     * @param int $length Length in bytes (will be doubled in hex)
     * @return string Random hex token
     */
    function generate_token($length = 32) {
        return bin2hex(random_bytes($length));
    }

    /**
     * Check if request is AJAX
     * @return bool True if AJAX request
     */
    function is_ajax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Check if request is POST
     * @return bool True if POST request
     */
    function is_post() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Check if request is GET
     * @return bool True if GET request
     */
    function is_get() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Rate limiting - check if action is allowed
     * @param string $action Action identifier
     * @param int $maxAttempts Maximum attempts allowed
     * @param int $windowSeconds Time window in seconds
     * @return bool True if action is allowed
     */
    function rate_limit_check($action, $maxAttempts = 5, $windowSeconds = 300) {
        $key = 'rate_limit_' . $action;
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $fullKey = $key . '_' . $ip;
        
        if (!isset($_SESSION[$fullKey])) {
            $_SESSION[$fullKey] = ['count' => 0, 'start' => time()];
        }
        
        $data = &$_SESSION[$fullKey];
        
        // Reset if window expired
        if (time() - $data['start'] > $windowSeconds) {
            $data = ['count' => 0, 'start' => time()];
        }
        
        $data['count']++;
        
        return $data['count'] <= $maxAttempts;
    }

    /**
     * Log security event
     * @param string $event Event type
     * @param string $message Event message
     * @param array $context Additional context
     */
    function log_security_event($event, $message, $context = []) {
        $logEntry = date('Y-m-d H:i:s') . " | {$event} | {$message}";
        if (!empty($context)) {
            $logEntry .= " | " . json_encode($context);
        }
        $logEntry .= " | IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        $logEntry .= "\n";
        
        $logFile = dirname(__DIR__) . '/logs/security.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        
        @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
?>