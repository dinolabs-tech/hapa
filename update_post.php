<?php
session_start();
if (!isset($_SESSION["staffname"])) {
    header("Location: portal/login.php");
    exit();
}

include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST["id"];
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $content = mysqli_real_escape_string($conn, $_POST["content"]);
    $category_id = $_POST["category"];

    // Image upload handling (similar to save_post.php)
    $target_dir = "assets/img/blog/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;

    // Check if a new image has been uploaded
    if ($_FILES["image"]["name"] != "") {
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check == false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // echo "The file " . basename($_FILES["image"]["name"]) . " has been uploaded.";
                header("Location: blog.php");
                exit();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        $image_path = basename($_FILES["image"]["name"]);
        $sql = "UPDATE blog_posts SET title = '$title', content = '$content', category_id = '$category_id' " . ($_FILES["image"]["name"] != "" ? ", image_path = '$image_path'" : "") . " WHERE id = $post_id";
    } else {
        $sql = "UPDATE blog_posts SET title = '$title', content = '$content', category_id = '$category_id' WHERE id = $post_id";
    }


    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>