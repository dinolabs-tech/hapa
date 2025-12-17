<?php
// helpers/money.php
// Safe money handling for Bursary module

/**
 * Convert naira amount (float/string) to kobo (int)
 * Example: 1500.50 => 150050
 */
function money_to_kobo($amount) {
    // Accepts string or float, returns int
    $amount = preg_replace('/[^\d\.]/', '', (string)$amount);
    return (int)round(floatval($amount) * 100);
}

/**
 * Convert kobo (int) to naira string (e.g. "₦1,500.50")
 */
function money_format_naira($kobo, $symbol = '₦') {
    $naira = $kobo;
    return $symbol . number_format($naira, 2);
}

/**
 * Parse user input (e.g. "1,500.50") to kobo
 */
function money_parse_input($input) {
    $input = str_replace(',', '', $input);
    return money_to_kobo($input);
}

/**
 * Validate money amount (must be >= 0 and integer)
 */
function money_validate($kobo) {
    return is_int($kobo) && $kobo >= 0;
}
?>
