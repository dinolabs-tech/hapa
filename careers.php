<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php'); ?>

<head>
  <title>Careers</title>
</head>

<body class="events-page">

  <?php include('components/header.php'); ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background position-relative" style="background-image: url(assets/img/pg-header.jpg);">
      <div class="container position-relative">
        <h1>Careers</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Careers</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Events 2 Section -->
    <section id="events-2" class="events-2 section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
          <h2>Vacancies</h2>
        </div><!-- End Section Title -->

        <div class="row g-4">
          <div class="col-lg-6 col-md-6">

            <div class="card">
              <div class="card-header">
                <strong>Our Culture</strong>
                
              </div>
              <div class="card-body">
                <p>We value innovation, collaboration, and continuous learning. Our employees enjoy a supportive work environment with opportunities for growth.</p>

                <ul>
                  <div class="bi bi-check-circle"> &nbsp; Competitive salaries and benefits</div>
                  <div class="bi bi-check-circle"> &nbsp;Flexible work arrangements</div>
                  <div class="bi bi-check-circle"> &nbsp;Professional development programs</div>
                </ul>
              </div>

            </div>



          </div>

          <div class="col-lg-6 col-md-6">

            <div class="card">
              <div class="card-header">
                <strong>
                  How to Apply
                </strong>
              </div>
              <div class="card-body">
                <img src="assets/img/education/events-5.webp" alt="Featured Event" class="img-fluid container rounded">
                <p class="py-2" style="text-align: center;">To apply for a job, please submit your resume and cover letter through our email.</p>
                
              </div>
              <div class="card-footer">
                <a href="mailto:hapacollege2013@yahoo.com" class="btn btn-primary">Submit Now</a>
              </div>

            </div>


          </div>
        </div>

      </div>

    </section><!-- /Events 2 Section -->

  </main>

  <?php include('components/footer.php'); ?>


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php include('components/scripts.php'); ?>

</body>

</html>