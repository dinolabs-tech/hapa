<?php session_start();?><!DOCTYPE html>
<html lang="en">

<?php include('components/head.php'); ?>
<head>
    <title>School Anthem</title>
</head>

<body class="starter-page-page">

  <?php include('components/header.php'); ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background position-relative" style="background-image: url(assets/img/pg-header.jpg);">
      <div class="container position-relative">
        <h1>School Anthem</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">School Anthem</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->



    <!-- History Section -->
    <section id="history" class="history section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row mt-5">
          <div class="col-lg-12">
            <div class="core-values" data-aos="fade-up" data-aos-delay="500">

              <!-- Section Title -->
              <div class="container section-title" data-aos="fade-up">
                <h2>School Anthem</h2>
              </div><!-- End Section Title -->

              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

                <div class="col-lg-6">
                 <div class="value-card text-justify"> <!-- Justify the text -->
                    <div class="value-icon text-center">
                      <i class="bi bi-music-note-list"></i>
                    </div>
                   <p style="text-align: justify;">
                      HAPA College <br>
                      Forever more Let your wisdom ring through Throughout the world <br>
                      Throughout my life Your standards I’ll pursue Throughout the world <br>
                      I will live my life A testament to you (2x)
                    </p>
                  </div>
                </div>

                <div class="col-lg-6">
                   <div class="value-card text-justify"> <!-- Justify the text -->
                    <div class="value-icon text-center">
                      <i class="bi bi-music-note-list"></i>
                    </div>
                    <!-- <h4>Community Engagement</h4> -->
                    <p style="text-align: justify;">
                      With an open mind <br>
                      I know I will find knowledge throughout All my years whether far or near <br>
                      I will always hear the sound Of your voice in my ears as I Live my life <br>
                      I will always strive To be all that I could be <br>
                      Hail to thee HAPA College
                    </p>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /History Section -->


    <!-- History Section -->
    <section id="history" class="history section">
  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="row mt-5">
      <div class="col-lg-12">
        <div class="core-values" data-aos="fade-up" data-aos-delay="500">
          <!-- Section Title -->
          <div class="container section-title" data-aos="fade-up">
            <h2>School Pledge</h2>
          </div><!-- End Section Title -->

          <div class="row justify-content-center"> <!-- Center the card -->
            <div class="col-lg-4"> <!-- Set card size -->
              <div class="value-card text-justify"> <!-- Justify the text -->
                <div class="value-icon text-center">
                  <i class="bi bi-shield-check"></i>
                </div>

                <p style="text-align: justify;">
                  I pledge to myself and all HAPA family <br>
                  That wherever I may go <br>
                  I shall be true ambassador of my college <br>
                  So that all shall see that I stand for loyalty <br>
                  And a light to the world, <br>
                  Always excelling in character and learning <br>
                  Relying on God, as my only source, <br>
                  Onward I will go, no matter the cost <br>
                  And in spite of every challenge <br>
                  So help me God
                </p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>
<!-- /History Section -->



  </main>

  <?php include('components/footer.php'); ?>


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php include('components/scripts.php'); ?>

</body>

</html>