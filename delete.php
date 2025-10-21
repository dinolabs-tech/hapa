<?php
session_start();

if (isset($_SESSION["staffname"]) && ($_SESSION['role'] == 'Administrator' || $_SESSION['role'] == 'Superuser')) {
    if (isset($_GET['image'])) {
        $uploadDir = 'uploads/';
        $imageName = basename(urldecode($_GET['image']));
        $imagePath = $uploadDir . $imageName;

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
}

header('Location: gallery.php');
?>
