<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} elseif ($_SESSION['role'] == 'Administrator' || $_SESSION['role'] == 'Teacher' || $_SESSION['role'] == 'Admission' || $_SESSION['role'] == 'Bursary') {
    header("Location: dashboard.php");
    exit();
} elseif ($_SESSION['role'] == 'Superuser') {
    header("Location: superdashboard.php");
    exit();
} elseif ($_SESSION['role'] == 'Student') {
    header("Location: students.php");
    exit();
} elseif ($_SESSION['role'] == 'Parent') {
    header("Location: parent_dashboard.php");
    exit();
} elseif ($_SESSION['role'] == 'Tuckshop') {
    header("Location: tuckdashboard.php");
    exit();
} elseif ($_SESSION['role'] == 'Alumni') {
    header("Location: alumni.php");
    exit();
}
