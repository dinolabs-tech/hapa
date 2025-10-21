<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php');?>


<body class="blog-details-page">

<?php include('components/header.php');?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/blog/blog-hero-1.webp);">
      <div class="container position-relative">
        <h1>Blog Details</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li><a href="blog.html">Blog</a></li>
            <li class="current">Blog Details</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Blog Details Section -->
    <section id="blog-details" class="blog-details section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-5">

          <div class="col-lg-8">

            <article class="blog-post">

              <h2 class="title">Sample Blog Post Title</h2>

              <div class="meta-box" style="display: flex; align-items: center; gap: 15px;">
                <p class="post-category">Category</p>

                <p class="post-date">
                  <time datetime="2025-01-01">Jan 1, 2025</time>
                </p>
                <p class="post-author">John Doe</p>
              </div>

              <div class="post-img">
                <img src="assets/img/blog/blog-post-1.webp" alt="" class="img-fluid rounded" loading="lazy">
              </div>
              <p></p>
              <div class="content">
                <p>
                  This is a sample blog post content. You can add your content here.
                </p>
              </div>

              <div class="comments indented-comments">
                <h4 class="comments-count">Comments</h4>

                <div id="comment-1" class="comment">
                  <div class="d-flex">
                    <div>
                      <h5><a href="">Georgia Reader</a>
                      </h5>
                      <time datetime="2020-01-01">01 Jan, 2025</time>
                      <p>
                        Sample comment text.
                      </p>
                    </div>
                  </div>
                </div>

                <div id="comment-2" class="comment">
                  <div class="d-flex">
                    <div>
                      <h5><a href="">Aron Alvarado</a>
                      </h5>
                      <time datetime="2020-01-01">01 Jan, 2025</time>
                      <p>
                        Sample comment text.
                      </p>
                    </div>
                  </div>
                </div>

                <div class="card rounded-4">
                  <div class="card-body">
                    <div class="container form-container-overlap">
                      <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="col-lg-10">
                          <div class="contact-form-wrapper">

                            <form action="" method="post" class="php-email-form">
                              <div class="row g-3">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <div class="input-with-icon">
                                      <input type="text" class="form-control" name="name" placeholder="First Name"
                                        required="">
                                    </div>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group">
                                    <div class="input-with-icon">
                                      <input type="email" class="form-control" name="email" placeholder="Email Address"
                                        required="">
                                    </div>
                                  </div>
                                </div>

                                <div class="col-12">
                                  <div class="form-group">
                                    <div class="input-with-icon">
                                      <textarea class="form-control" name="message" placeholder="Write Message..."
                                        style="height: 180px" required=""></textarea>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-12 text-center">
                                  <button type="submit" class="btn btn-primary btn-submit">Post Comment</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>

              </div>

            </article>

          </div>

          <div class="col-lg-4">

            <div class="sidebar">

              <div class="sidebar-item search-form">
                <h3 class="sidebar-title">Search</h3>
                <form action="" class="mt-3" style="position: relative;">
                  <input type="text" class="form-control" placeholder="Enter keywords">
                  <button type="submit"
                    style="position: absolute; top: 0; right: 0; border: 0; background: none; padding: 8px; cursor: pointer;"><i
                      class="bi bi-search"></i></button>
                </form>
              </div>
              <p></p>
              <div class="sidebar-item categories">
                <h3 class="sidebar-title">Categories</h3>
                <ul class="mt-3">
                  <li><a href="#">Category 1 <span>(10)</span></a></li>
                  <li><a href="#">Category 2 <span>(5)</span></a></li>
                  <li><a href="#">Category 3 <span>(12)</span></a></li>
                </ul>
              </div>

              <div class="sidebar-item recent-posts">
                <h3 class="sidebar-title">Recent Posts</h3>
                <div class="mt-3">
                  <div class="post-item mt-3" style="display: flex; align-items: center;">
                    <img src="assets/img/blog/blog-post-1.webp" alt="" class="flex-shrink-0 rounded"
                      style="width: 60px; height: 60px; margin-right: 10px;">
                    <div>
                      <h4><a href="#">Recent Post 1</a></h4>
                      <time datetime="2025-01-01">Jan 1, 2025</time>
                    </div>
                  </div>
                  <div class="post-item mt-3" style="display: flex; align-items: center;">
                    <img src="assets/img/blog/blog-post-2.webp" alt="" class="flex-shrink-0 rounded"
                      style="width: 60px; height: 60px; margin-right: 10px;">
                    <div>
                      <h4><a href="#">Recent Post 2</a></h4>
                      <time datetime="2025-01-01">Jan 1, 2025</time>
                    </div>
                  </div>
                </div>
              </div>

            </div>

          </div>

        </div>

      </div>
    </section><!-- End Blog Details Section -->

  </main>

  <?php include ('components/footer.php');?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <?php include ('components/scripts.php');?>
  
</body>

</html>