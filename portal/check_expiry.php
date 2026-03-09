<?php
// Check expiry for non-Student/Alumni/Superuser roles
if ($_SESSION['role'] !== 'Superuser') {
    // Fetch the subscription expiry date
    $stmtExp = $conn->prepare("SELECT expdate FROM sub WHERE id = 1 LIMIT 1");
    $stmtExp->execute();
    $stmtExp->bind_result($expdate);
    $stmtExp->fetch();
    $stmtExp->close();

    // Parse d/m/y format into a DateTime object
    $dateObj = DateTime::createFromFormat('d/m/Y', $expdate);
    // If parsing failed, treat as expired
    if (!$dateObj) {
        header("Location: expiry.php");
        exit();
    }
    $expiryTimestamp = $dateObj->getTimestamp();
    $currentTimestamp = time();

    // If today is past the expiry date, redirect to expiry page
    if ($currentTimestamp > $expiryTimestamp) {
        header("Location: expiry.php");
        exit();
    }
    // Otherwise, allow login to proceed as usual
}

?>