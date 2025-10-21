<?php
session_start();
include("db_connect.php");

$post_id = $_GET["id"];

$sql = "SELECT blog_posts.*, users.username FROM blog_posts INNER JOIN users ON blog_posts.author_id = users.id WHERE blog_posts.id = $post_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
  echo "Post not found";
  exit();
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php'); ?>


<body class="news-details-page">

  <?php include('components/header.php'); ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background position-relative" style="background-image: url(assets/img/pg-header.jpg);">
      <div class="container position-relative">
        <h1><?php echo $post["title"]; ?></h1>
        <nav class="breadcrumbs">
          <ol>

          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Blog Details Section -->
    <section id="blog-details" class="blog-details section">
      <div class="container" data-aos="fade-up">

        <article class="article">
          <div class="article-header">
           

            <h4 class="title" data-aos="fade-up" data-aos-delay="100"><?php echo $post["title"]; ?></h4>

            <div class="article-meta" data-aos="fade-up" data-aos-delay="200">
              <div class="author">
                <div class="author-info">
                  <h4><strong><?php echo $post["username"]; ?></h4>
                  <span>Author</span>
                </div>
              </div>
              <div class="post-info">
                <span><i class="bi bi-calendar4-week"></i> <?php echo date('jS F Y, h:i a', strtotime($post["created_at"])); ?></span>
              </div>
            </div>
          </div>

          <div class="article-featured-image" data-aos="zoom-in">
            <?php if ($post["image_path"]) { ?>
              <img src="assets/img/blog/<?php echo $post["image_path"]; ?>" alt="Blog Image"
                class="img-fluid w-100 rounded mb-5" style="max-width: 100%;">
            <?php } ?>
          </div>

          <div class="">
            <p><?php echo $post["content"]; ?></p>
            <?php if (isset($_SESSION["staffname"])) { ?>
              <a href="edit_post.php?id=<?php echo $post["id"]; ?>"
                class="btn btn-sm btn-primary">Edit Post</a>
              <a href="delete_post.php?id=<?php echo $post["id"]; ?>" class="btn btn-sm btn-danger">Delete Post</a>
            <?php } ?>
          </div>

          <!-- Comment List Start -->
          <div class="mb-5">
            <div class="section-title section-title-sm position-relative pb-3 mb-4">
              <h3 class="mb-0">Comments</h3>
            </div>
            <?php
            $sql = "SELECT * FROM comments WHERE post_id = $post_id ORDER BY created_at DESC";
            $comments_result = $conn->query($sql);

            if ($comments_result->num_rows > 0) {
              while ($comment = $comments_result->fetch_assoc()) {
                echo "<div class='d-flex mb-4'>";
                echo '<div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-person"></i>
                    </div>';
                echo "<div class='ps-3'>";
                echo "<h6><strong>" . htmlspecialchars($comment["name"]) . "</strong>";
                if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == true) {
                  echo " <small>(" . htmlspecialchars($comment["email"]) . ")</small>";
                }
                echo " <small><i>" . date('jS F Y, h:i a', strtotime($comment["created_at"])) . "</i></small></h6>";

                echo "<p>" . htmlspecialchars($comment["content"]) . "</p>";
                if (isset($_SESSION["staffname"])) {
                  echo "<a href='edit_comment.php?id=" . $comment["id"] . "' class='btn btn-sm btn-primary me-2'>Edit</a>";
                  echo "<a href='delete_comment.php?id=" . $comment["id"] . "' class='btn btn-sm btn-danger'>Delete</a>";
                }
                echo "</div>";
                echo "</div>";
              }
            } else {
              echo "<p>No comments yet.</p>";
            }
            ?>
          </div>

          <!-- Comment List End -->

          <!-- Comment Form Start -->
          <div class="bg-light rounded p-5">
            <div class="section-title section-title-sm position-relative pb-3 mb-4">
              <h3 class="mb-0">Leave A Comment</h3>
            </div>
            <form action="add_comment.php" method="post">
              <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
              <div class="row g-3">
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <input type="text" class="form-control bg-white border-0" id="name" name="name"
                      placeholder="Your Name" required style="height: 55px;">
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <input type="email" class="form-control bg-white border-0" id="email"
                      name="email" placeholder="Your Email" required style="height: 55px;">
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <textarea class="form-control bg-white border-0" id="comment" name="comment"
                      rows="5" placeholder="Comment" required></textarea>
                  </div>
                </div>
                <div class="col-12">
                  <button class="btn btn-primary w-100 py-3" type="submit">Leave Your Comment</button>
                </div>
              </div>
            </form>
          </div>

          <!-- Comment Form End -->
      </div>


      </article>

      </div>
    </section><!-- /Blog Details Section -->

  </main>

  <?php include('components/footer.php'); ?>


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php include('components/scripts.php'); ?>
</body>

</html>