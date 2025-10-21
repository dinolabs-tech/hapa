<?php
// Start the session to maintain user state
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
  header("Location: portal/login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php'); ?>
<title>Upload Image</title>

<body class="starter-page-page">

  <?php include('components/header.php'); ?>

  <main class="main">

    <!-- Page Title -->
        <div class="page-title dark-background position-relative" style="background-image: url(assets/img/pg-header.jpg);">
      <div class="container position-relative">
        <h1>Upload Image</h1>

        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li class="current">Upload Image</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Upload Image</h2>
      </div><!-- End Section Title -->


      <div class="container" data-aos="fade-up">

        <form action="upload.php" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-4">
              <input type="file" name="image" accept="image/*" class="form-control" required>
            </div> <br> 
            <div class="col-md-4">
              <button type="submit" class="btn btn-primary">Upload Image</button>
            </div>

          </div>

        </form>
        <br><br>
        <a href="gallery.php" class="btn btn-primary">Back to Gallery</a>
      </div>

    </section><!-- /Starter Section Section -->

  </main>

  <?php include('components/footer.php'); ?>


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php include('components/scripts.php'); ?>

</body>

</html>