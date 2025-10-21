<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $image = $_FILES['image'];
    $imageName = basename($image['name']);
    $targetPath = $uploadDir . $imageName;
    $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($image['tmp_name']);
    if ($check === false) {
        echo "File is not an image.";
        exit;
    }

    // Check file size
    if ($image['size'] > 5000000) { // 5MB
        echo "Sorry, your file is too large.";
        exit;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        exit;
    }

    if (move_uploaded_file($image['tmp_name'], $targetPath)) {
        header('Location: gallery.php');
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    header('Location: index.php');
}
?>
