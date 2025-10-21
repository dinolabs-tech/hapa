<?php
session_start();
if (!isset($_SESSION["staffname"])) {
  header("Location: portal/login.php");
  exit();
}

include("db_connect.php");
?>

<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php'); ?>


<body class="alumni-page">

  <?php include('components/header.php'); ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background position-relative" style="background-image: url(assets/img/pg-header.jpg);">
      <div class="container position-relative">
        <h1>Dashboard</h1>
        <nav class="breadcrumbs">
          <ol>
             <li><a href="index.php">Home</a></li>
            <li class="current">Dashboard</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Alumni Section -->
    <section id="alumni" class="alumni section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="alumni-engagement">
         

          <div class="engagement-cards">
            <div class="row">
              <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="engagement-card">
                  <div class="card-icon">
                    <i class="bi bi-newspaper"></i>
                  </div>
                  <h4>Total Posts</h4>
                   <?php
              $sql_posts = "SELECT COUNT(*) AS total_posts FROM blog_posts";
              $result_posts = $conn->query($sql_posts);
              $posts_data = $result_posts->fetch_assoc();
              $total_posts = $posts_data["total_posts"];
              ?>
              <p class="card-text"><?php echo $total_posts; ?></p>
                </div>
              </div>

              <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="engagement-card">
                  <div class="card-icon">
                    <i class="bi bi-chat-dots"></i>
                  </div>
                  <h4>Total Comments</h4>
                    <?php
              $sql_comments = "SELECT COUNT(*) AS total_comments FROM comments";
              $result_comments = $conn->query($sql_comments);
              $comments_data = $result_comments->fetch_assoc();
              $total_comments = $comments_data["total_comments"];
              ?>
              <p class="card-text"><?php echo $total_comments; ?></p>

                 </div>
              </div>

             </div>
          </div>
        </div>

      </div>

    </section><!-- /Alumni Section -->


    <section id="recent-news" class="recent-news section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Recent Post</h2>
  </div><!-- End Section Title -->

  <div class="container">
  <div class="swiper mySwiper" data-aos="fade-up">
    <div class="swiper-wrapper">
      <?php
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $posts_per_page = 5;
        $offset = ($page - 1) * $posts_per_page;

        $sql_trending_posts = "SELECT blog_posts.id, blog_posts.title, blog_posts.created_at, blog_posts.image_path, COUNT(comments.id) AS total_comments FROM blog_posts LEFT JOIN comments ON blog_posts.id = comments.post_id GROUP BY blog_posts.id ORDER BY total_comments DESC LIMIT $posts_per_page OFFSET $offset";
        $result_trending_posts = $conn->query($sql_trending_posts);

        $sql_total_posts = "SELECT COUNT(*) AS total FROM blog_posts";
        $result_total_posts = $conn->query($sql_total_posts);
        $total_posts = $result_total_posts->fetch_assoc()['total'];
        $total_pages = ceil($total_posts / $posts_per_page);

        if ($result_trending_posts->num_rows > 0) {
            while($trending_post = $result_trending_posts->fetch_assoc()) {
                ?>
                <div class="swiper-slide">
                  <article>
                    <div class="post-img">
                      <img src="assets/img/blog/<?php echo $trending_post['image_path'] ?: 'default.jpg'; ?>" alt="" class="img-fluid rounded" style="width:100%; height: 250px; object-fit: cover;">
                    </div>
                    <p class="post-category">Trending</p>
                    <h2 class="title">
                      <a href="post.php?id=<?php echo $trending_post['id']; ?>">
                        <?php echo htmlspecialchars($trending_post['title']); ?>
                      </a>
                    </h2>
                    <div class="d-flex align-items-center">
                      
                      <div class="post-meta">
                        <p class="post-author">Total Comments: <?php echo $trending_post['total_comments']; ?></p>
                        <p class="post-date">
                          <time datetime="<?php echo $trending_post['created_at']; ?>">
                            <?php echo date('M j, Y', strtotime($trending_post['created_at'])); ?>
                          </time>
                        </p>
                      </div>
                    </div>
                  </article>
                </div>
                <?php
            }
        } else {
            echo "<p class='swiper-slide'>No trending posts found.</p>";
        }
      ?>
    </div>

    <!-- Navigation Arrows -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>

    <!-- Pagination Dots -->
    <div class="swiper-pagination"></div>
  </div>
</div>
<!-- Initialize Swiper -->
<script>
  const swiper = new Swiper(".mySwiper", {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 30,
    autoplay: {
      delay: 5000, // Auto-swipe every 5 seconds
      disableOnInteraction: false, // Keeps autoplay after user interactions
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      768: {
        slidesPerView: 2,
      },
      1200: {
        slidesPerView: 3,
      }
    }
  });
</script>

</section>

  </main>

  <?php include('components/footer.php'); ?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <?php include('components/scripts.php'); ?>

</body>

</html>