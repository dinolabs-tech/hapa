<?php
/**
 * InputValidator Class
 * Provides comprehensive validation for form inputs
 */

class InputValidator {
    private $errors = [];
    private $data = [];
    private $validated = [];

    /**
     * Create a new validator instance
     * @param array $data Data to validate (default $_POST)
     */
    public function __construct(array $data = null) {
        $this->data = $data ?? $_POST;
    }

    /**
     * Get all validation errors
     * @return array Array of error messages
     */
    public function errors() {
        return $this->errors;
    }

    /**
     * Check if validation passed
     * @return bool True if no errors
     */
    public function passes() {
        return empty($this->errors);
    }

    /**
     * Check if validation failed
     * @return bool True if there are errors
     */
    public function fails() {
        return !empty($this->errors);
    }

    /**
     * Get validated data
     * @return array Validated and sanitized data
     */
    public function validated() {
        return $this->validated;
    }

    /**
     * Add an error message
     * @param string $field Field name
     * @param string $message Error message
     */
    private function addError($field, $message) {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    /**
     * Get field value
     * @param string $field Field name
     * @return mixed Field value or null
     */
    private function getValue($field) {
        return $this->data[$field] ?? null;
    }

    /**
     * Validate required field
     * @param string $field Field name
     * @param string $message Custom error message
     * @return $this
     */
    public function required($field, $message = null) {
        $value = $this->getValue($field);
        if ($value === null || $value === '') {
            $this->addError($field, $message ?? "The {$field} field is required.");
        }
        return $this;
    }

    /**
     * Validate string field
     * @param string $field Field name
     * @param int $min Minimum length
     * @param int $max Maximum length
     * @param string $message Custom error message
     * @return $this
     */
    public function string($field, $min = 0, $max = 255, $message = null) {
        $value = $this->getValue($field);
        if ($value === null || $value === '') return $this;
        
        $length = strlen(trim($value));
        if ($length < $min || $length > $max) {
            $this->addError($field, $message ?? "The {$field} must be between {$min} and {$max} characters.");
        } else {
            $this->validated[$field] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }
        return $this;
    }

    /**
     * Validate integer field
     * @param string $field Field name
     * @param int $min Minimum value
     * @param int $max Maximum value
     * @param string $message Custom error message
     * @return $this
     */
    public function integer($field, $min = null, $max = null, $message = null) {
        $value = $this->getValue($field);
        if ($value === null || $value === '') return $this;
        
        $int = filter_var($value, FILTER_VALIDATE_INT);
        if ($int === false) {
            $this->addError($field, $message ?? "The {$field} must be an integer.");
        } else {
            if ($min !== null && $int < $min) {
                $this->addError($field, "The {$field} must be at least {$min}.");
            } elseif ($max !== null && $int > $max) {
                $this->addError($field, "The {$field} may not be greater than {$max}.");
            } else {
                $this->validated[$field] = $int;
            }
        }
        return $this;
    }

    /**
     * Validate positive integer (ID)
     * @param string $field Field name
     * @param string $message Custom error message
     * @return $this
     */
    public function id($field, $message = null) {
        return $this->integer($field, 1, null, $message ?? "The {$field} must be a positive integer.");
    }

    /**
     * Validate numeric/float field
     * @param string $field Field name
     * @param float $min Minimum value
     * @param float $max Maximum value
     * @param string $message Custom error message
     * @return $this
     */
    public function numeric($field, $min = null, $max = null, $message = null) {
        $value = $this->getValue($field);
        if ($value === null || $value === '') return $this;
        
        $float = filter_var($value, FILTER_VALIDATE_FLOAT);
        if ($float === false) {
            $this->addError($field, $message ?? "The {$field} must be a number.");
        } else {
            if ($min !== null && $float < $min) {
                $this->addError($field, "The {$field} must be at least {$min}.");
            } elseif ($max !== null && $float > $max) {
                $this->addError($field, "The {$field} may not be greater than {$max}.");
            } else {
                $this->validated[$field] = $float;
            }
        }
        return $this;
    }

    /**
     * Validate email field
     * @param string $field Field name
     * @param string $message Custom error message
     * @return $this
     */
    public function email($field, $message = null) {
        $value = $this->getValue($field);
        if ($value === null || $value === '') return $this;
        
        $email = filter_var(trim($value), FILTER_VALIDATE_EMAIL);
        if ($email === false) {
            $this->addError($field, $message ?? "The {$field} must be a valid email address.");
        } else {
            $this->validated[$field] = $email;
        }
        return $this;
    }

    /**
     * Validate date field
     * @param string $field Field name
     * @param string $format Expected date format
     * @param string $message Custom error message
     * @return $this
     */
    public function date($field, $format = 'Y-m-d', $message = null) {
        $value = $this->getValue($field);
        if ($value === null || $value === '') return $this;
        
        $date = DateTime::createFromFormat($format, $value);
        if ($date === false) {
            $this->addError($field, $message ?? "The {$field} must be a valid date.");
        } else {
            $errors = DateTime::getLastErrors();
            if ($errors && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
                $this->addError($field, $message ?? "The {$field} must be a valid date.");
            } else {
                $this->validated[$field] = $date->format($format);
            }
        }
        return $this;
    }

    /**
     * Validate field against regex pattern
     * @param string $field Field name
     * @param string $pattern Regex pattern
     * @param string $message Custom error message
     * @return $this
     */
    public function regex($field, $pattern, $message = null) {
        $value = $this->getValue($field);
        if ($value === null || $value === '') return $this;
        
        if (!preg_match($pattern, $value)) {
            $this->addError($field, $message ?? "The {$field} format is invalid.");
        } else {
            $this->validated[$field] = $value;
        }
        return $this;
    }

    /**
     * Validate field is in a list of allowed values
     * @param string $field Field name
     * @param array $allowed Allowed values
     * @param string $message Custom error message
     * @return $this
     */
    public function in($field, array $allowed, $message = null) {
        $value = $this->getValue($field);
        if ($value === null || $value === '') return $this;
        
        if (!in_array($value, $allowed, true)) {
            $this->addError($field, $message ?? "The selected {$field} is invalid.");
        } else {
            $this->validated[$field] = $value;
        }
        return $this;
    }

    /**
     * Validate alphanumeric field
     * @param string $field Field name
     * @param string $message Custom error message
     * @return $this
     */
    public function alphanumeric($field, $message = null) {
        return $this->regex($field, '/^[a-zA-Z0-9_]+$/', $message ?? "The {$field} may only contain letters, numbers, and underscores.");
    }

    /**
     * Validate phone number
     * @param string $field Field name
     * @param string $message Custom error message
     * @return $this
     */
    public function phone($field, $message = null) {
        $value = $this->getValue($field);
        if ($value === null || $value === '') return $this;
        
        $phone = preg_replace('/[\s\-]/', '', $value);
        if (!preg_match('/^(\+234|0)?[0-9]{10}$/', $phone)) {
            $this->addError($field, $message ?? "The {$field} must be a valid phone number.");
        } else {
            $this->validated[$field] = $phone;
        }
        return $this;
    }

    /**
     * Validate field confirmation (e.g., password confirmation)
     * @param string $field Field name
     * @param string $confirmField Confirmation field name
     * @param string $message Custom error message
     * @return $this
     */
    public function confirmed($field, $confirmField = null, $message = null) {
        $confirmField = $confirmField ?? $field . '_confirmation';
        $value = $this->getValue($field);
        $confirm = $this->getValue($confirmField);
        
        if ($value !== $confirm) {
            $this->addError($field, $message ?? "The {$field} confirmation does not match.");
        }
        return $this;
    }

    /**
     * Validate field is unique in database
     * @param string $field Field name
     * @param mysqli $conn Database connection
     * @param string $table Table name
     * @param string $column Column name (default: field name)
     * @param mixed $exclude Value to exclude (for updates)
     * @param string $message Custom error message
     * @return $this
     */
    public function unique($field, $conn, $table, $column = null, $exclude = null, $message = null) {
        $value = $this->getValue($field);
        if ($value === null || $value === '') return $this;
        
        $column = $column ?? $field;
        $stmt = $conn->prepare("SELECT COUNT(*) FROM {$table} WHERE {$column} = ?");
        
        if ($exclude !== null) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM {$table} WHERE {$column} = ? AND {$column} != ?");
            $stmt->bind_param('ss', $value, $exclude);
        } else {
            $stmt->bind_param('s', $value);
        }
        
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        if ($count > 0) {
            $this->addError($field, $message ?? "The {$field} has already been taken.");
        }
        return $this;
    }

    /**
     * Validate file upload
     * @param string $field Field name
     * @param array $allowedTypes Allowed MIME types
     * @param int $maxSize Maximum size in bytes
     * @param string $message Custom error message
     * @return $this
     */
    public function file($field, $allowedTypes = [], $maxSize = null, $message = null) {
        if (!isset($_FILES[$field])) {
            return $this;
        }
        
        $file = $_FILES[$field];
        
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return $this;
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->addError($field, "Error uploading file.");
            return $this;
        }
        
        if (!empty($allowedTypes) && !in_array($file['type'], $allowedTypes)) {
            $this->addError($field, $message ?? "The {$field} must be a valid file type.");
            return $this;
        }
        
        if ($maxSize !== null && $file['size'] > $maxSize) {
            $this->addError($field, "The {$field} must not exceed " . round($maxSize / 1024, 1) . " KB.");
            return $this;
        }
        
        $this->validated[$field] = $file;
        return $this;
    }

    /**
     * Validate image upload
     * @param string $field Field name
     * @param int $maxSize Maximum size in bytes
     * @param string $message Custom error message
     * @return $this
     */
    public function image($field, $maxSize = null, $message = null) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        return $this->file($field, $allowedTypes, $maxSize, $message ?? "The {$field} must be a valid image.");
    }

    /**
     * Apply custom validation rule
     * @param string $field Field name
     * @param callable $callback Validation callback (returns true if valid)
     * @param string $message Error message
     * @return $this
     */
    public function custom($field, callable $callback, $message) {
        $value = $this->getValue($field);
        if (!$callback($value)) {
            $this->addError($field, $message);
        }
        return $this;
    }

    /**
     * Sometimes - only validate if field is present
     * @param callable $callback Callback with validator for conditional validation
     * @return $this
     */
    public function sometimes(callable $callback) {
        $callback($this);
        return $this;
    }

    /**
     * Get first error for a field
     * @param string $field Field name
     * @return string|null First error message or null
     */
    public function first($field) {
        return $this->errors[$field][0] ?? null;
    }

    /**
     * Get all errors as flat array
     * @return array Flat array of error messages
     */
    public function all() {
        $all = [];
        foreach ($this->errors as $field => $messages) {
            foreach ($messages as $message) {
                $all[] = $message;
            }
        }
        return $all;
    }

    /**
     * Static factory method
     * @param array $data Data to validate
     * @return static
     */
    public static function make(array $data = null) {
        return new static($data);
    }
}
?>