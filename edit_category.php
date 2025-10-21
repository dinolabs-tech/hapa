<?php
session_start();
if (!isset($_SESSION["staffname"])) {
    header("Location: portal/login.php");
    exit();
}

include("db_connect.php");

$category_id = $_GET["id"];

$sql = "SELECT * FROM categories WHERE id = $category_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Category not found";
    exit();
}

$category = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php'); ?>

<body>
     <!-- Navbar Start -->
    <?php include('components/header.php'); ?>
    <!-- Navbar End -->

    <main class="main">

        <!-- Page Title -->
        <div class="page-title dark-background" style="background-image: url(assets/img/education/showcase-1.webp);">
            <div class="container position-relative">
                <h1>Edit Category</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li class="current">Edit CAtegory</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->



    <!-- Contact Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
           
            <div class="row g-5">
                <div class="col-lg-12 wow slideInUp" data-wow-delay="0.3s">

                <form action="update_category.php" method="post">
                <input type="hidden" name="id" value="<?php echo $category_id; ?>">
                        <div class="row g-3">
                            
                            <div class="col-12">
                                <input type="text" class="form-control border-0 bg-light px-4" placeholder="Name" style="height: 55px;"  id="name" name="name" value="<?php echo $category["name"]; ?>">
                            </div>
                            <div class="col-12">
                                <textarea class="form-control border-0 bg-light px-4 py-3" rows="4" placeholder="Description" id="description" name="description"><?php echo $category["description"]; ?></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Update Category</button>
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