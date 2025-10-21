<?php
session_start();
if (!isset($_SESSION["staffname"])) {
    header("Location: portal/login.php");
    exit();
}

include("db_connect.php");

$comment_id = $_GET["id"];

$sql = "SELECT * FROM comments WHERE id = $comment_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Comment not found";
    exit();
}

$comment = $result->fetch_assoc();
$post_id = $comment["post_id"];
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
        <h1>Edit Comment</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li><a href="blog.php">Blog</a></li>
            <li class="current">Edit Comment</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->
   

    <!-- Quote Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
            <div class="bg-primary rounded p-5 wow zoomIn" data-wow-delay="0.9s">
                    <form action="update_comment.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $comment_id; ?>">
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

                        <div class="row g-3">
                            <div class="col-12">

                                <textarea class="form-control bg-light border-0" id="comment" name="comment"
                                    placeholder="Enter Post Comment" style="height: 150px;"
                                    required><?php echo htmlspecialchars($comment["content"]); ?></textarea>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-dark w-100 py-3">Update Comment</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
</div>
        </div>
    </div>

    <!-- Quote End -->




</main>
    <!-- Footer Start -->
    <?php include('components/footer.php'); ?>
    <!-- Footer End -->

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

    <?php include('components/scripts.php'); ?>
</body>

</html>