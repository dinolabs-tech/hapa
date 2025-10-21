<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php');?>


<body class="page-404">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.webp" alt=""> -->
        <i class="bi bi-buildings"></i>
        <h1 class="sitename">NiceSchool</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.html">Home</a></li>
          <li class="dropdown"><a href="about.html"><span>About</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="about.html">About Us</a></li>
              <li><a href="admissions.html">Admissions</a></li>
              <li><a href="academics.html">Academics</a></li>
              <li><a href="faculty-staff.html">Faculty &amp; Staff</a></li>
              <li><a href="campus-facilities.html">Campus &amp; Facilities</a></li>
            </ul>
          </li>

          <li><a href="students-life.html">Students Life</a></li>
          <li><a href="news.html">News</a></li>
          <li><a href="events.html">Events</a></li>
          <li><a href="alumni.html">Alumni</a></li>
          <li class="dropdown"><a href="#"><span>More Pages</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="news-details.html">News Details</a></li>
              <li><a href="event-details.html">Event Details</a></li>
              <li><a href="privacy.html">Privacy</a></li>
              <li><a href="terms-of-service.html">Terms of Service</a></li>
              <li><a href="404.html" class="active">Error 404</a></li>
              <li><a href="starter-page.html">Starter Page</a></li>
            </ul>
          </li>

          <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul>
          </li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/education/showcase-1.webp);">
      <div class="container position-relative">
        <h1>404</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">404</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Error 404 Section -->
    <section id="error-404" class="error-404 section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="error-wrapper">
          <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
              <div class="error-illustration">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
                <div class="circle circle-3"></div>
              </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
              <div class="error-content">
                <span class="error-badge" data-aos="zoom-in" data-aos-delay="400">Error</span>
                <h1 class="error-code" data-aos="fade-up" data-aos-delay="500">404</h1>
                <h2 class="error-title" data-aos="fade-up" data-aos-delay="600">Page Not Found</h2>
                <p class="error-description" data-aos="fade-up" data-aos-delay="700">
                  The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
                </p>

                <div class="error-actions" data-aos="fade-up" data-aos-delay="800">
                  <a href="/" class="btn-home">
                    <i class="bi bi-house-door"></i> Back to Home
                  </a>
                  <a href="#" class="btn-help">
                    <i class="bi bi-question-circle"></i> Help Center
                  </a>
                </div>

                <div class="error-suggestions" data-aos="fade-up" data-aos-delay="900">
                  <h3>You might want to:</h3>
                  <ul>
                    <li><a href="#"><i class="bi bi-arrow-right-circle"></i> Check our sitemap</a></li>
                    <li><a href="#"><i class="bi bi-arrow-right-circle"></i> Contact support</a></li>
                    <li><a href="#"><i class="bi bi-arrow-right-circle"></i> Return to previous page</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Error 404 Section -->

  </main>

  <?php include('components/footer.php');?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <?php include ('components/scripts.php');?>

</body>

</html>