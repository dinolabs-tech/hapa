<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php'); ?>
<title>Gallery</title>
<style>
  .gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
  }

  .gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 5px;
  }



  .delete-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background-color: rgba(255, 0, 0, 0.7);
    color: #fff;
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 3px;
    opacity: 0;
    transition: opacity 0.3s;
  }

  .gallery-item:hover .delete-btn {
    opacity: 1;
  }

  .pagination {
    text-align: center;
    margin-top: 20px;
  }

  .pagination a {
    color: #007bff;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
    margin: 0 4px;
  }

  .pagination a.active {
    background-color: #007bff;
    color: white;
    border: 1px solid #007bff;
  }

  .pagination a:hover:not(.active) {
    background-color: #ddd;
  }

  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0, 0, 0);
    background-color: rgba(0, 0, 0, 0.9);
  }

  .modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
  }

  .close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
  }

  .close:hover,
  .close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
  }
</style>

<body class="starter-page-page">

  <?php include('components/header.php'); ?>

  <main class="main">

    <!-- Page Title -->
        <div class="page-title dark-background position-relative" style="background-image: url(assets/img/pg-header.jpg);">
      <div class="container position-relative">
        <h1>Gallery</h1>
        <!-- <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p> -->
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Gallery</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Our Gallery</h2>
          <?php if (isset($_SESSION["staffname"])) { ?>
        <?php if ($_SESSION['role'] == 'Administrator' || $_SESSION['role'] == 'Superuser') { ?>
          <a href="upload_page.php" class="btn btn-primary">Upload Image</a>
        <?php } else { ?>

        <?php } ?>

      <?php } else { ?>

      <?php } ?>
        <!-- <a href="upload_page.php" class="btn btn-primary">Upload Image</a> -->
      </div><!-- End Section Title -->

    

      <div class="gallery container" data-aos="fade-up">
        <?php
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0777, true);
        }
        $images = glob($uploadDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        $imagesPerPage = 18;
        $totalImages = count($images);
        $totalPages = ceil($totalImages / $imagesPerPage);
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $currentPage = max(1, min($totalPages, $currentPage));
        $startIndex = ($currentPage - 1) * $imagesPerPage;
        $pagedImages = array_slice($images, $startIndex, $imagesPerPage);

        foreach ($pagedImages as $image) {
          echo '<div class="gallery-item">';
          echo '<img src="' . $image . '" alt="Gallery Image" class="img-fluid rounded gallery-image" width="500px" height="500px">';
          if (isset($_SESSION["staffname"]) && ($_SESSION['role'] == 'Administrator' || $_SESSION['role'] == 'Superuser')) {
            echo '<a href="delete.php?image=' . urlencode(basename($image)) . '" class="delete-btn" onclick="return confirm(\'Are you sure you want to delete this image?\')">Delete</a>';
          }
          echo '</div>';
        }
        ?>
      </div>
      <div class="pagination justify-content-center">
        <?php
        for ($i = 1; $i <= $totalPages; $i++) {
          echo '<a href="?page=' . $i . '"' . ($i == $currentPage ? ' class="active"' : '') . '>' . $i . '</a>';
        }
        ?>
      </div>

      <div id="fullscreen-modal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="fullscreen-image">
      </div>


    </section><!-- /Starter Section Section -->

  </main>

  <?php include('components/footer.php'); ?>


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  <?php include('components/scripts.php'); ?>


  <script>
    const modal = document.getElementById("fullscreen-modal");
    const modalImg = document.getElementById("fullscreen-image");
    const galleryImages = document.querySelectorAll(".gallery-image");
    const closeBtn = document.querySelector(".close");

    galleryImages.forEach(img => {
      img.onclick = function() {
        modal.style.display = "block";
        modalImg.src = this.src;
      }
    });

    closeBtn.onclick = function() {
      modal.style.display = "none";
    }

    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>

</html>
