<?php
session_start();
if (!isset($_SESSION["staffname"])) {
    header("Location: portal/login.php");
    exit();
}
include("db_connect.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];

    $sql = "INSERT INTO categories (name, description) VALUES ('$name', '$description')";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_categories.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php'); ?>
<title>Create Categories</title>
<body>
        <!-- Navbar Start -->
    <?php include('components/header.php'); ?>
    <!-- Navbar End -->

    <main class="main">

        <!-- Page Title -->
        <div class="page-title dark-background" style="background-image: url(assets/img/education/showcase-1.webp);">
            <div class="container position-relative">
                <h1>Create Category</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li class="current">Create Category</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->



    <!-- Contact Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
           
            <div class="row g-5">
                <div class="col-lg-12 wow slideInUp" data-wow-delay="0.3s">

                <form action="create_category.php" method="post">
                
                        <div class="row g-3">
                            <div class="col-12">
                                <input type="text" class="form-control border-0 bg-light px-4" placeholder="Category Name" style="height: 55px;"  id="name" name="name">
                            </div>
                            <div class="col-12">
                                <textarea class="form-control border-0 bg-light px-4 py-3" rows="4" placeholder="Description" id="description" name="description"></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Create Category</button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <!-- Contact End -->

    <!-- Footer Start -->
    <?php include('components/footer.php'); ?>
    <!-- Footer End -->
  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

    <?php include('components/scripts.php'); ?>
</body>

</html>