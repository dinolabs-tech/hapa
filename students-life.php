<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php'); ?>

<head>
  <title>Student Life</title>
</head>

<body class="students-life-page">

  <?php include('components/header.php'); ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background position-relative" style="background-image: url(assets/img/pg-header.jpg);">
      <div class="container position-relative">
        <h1>Student Life</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Students Life</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Students Life Section -->
    <section id="students-life" class="students-life section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <!-- Hero Banner -->
        <div class="students-life-banner" data-aos="zoom-in" data-aos-delay="200">
          <div class="banner-content" data-aos="fade-right" data-aos-delay="300">
            <h2>Experience life at HAPA College</h2>
            <!-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p> -->
          </div>
          <img src="assets/img/students/1.jpg" alt="Campus Life" class="img-fluid">
        </div>

        <!-- Life Categories -->
        <div class="life-categories mt-5" data-aos="fade-up" data-aos-delay="200">
          <div class="row g-4">
            <div class="col-md-2 col-sm-6" data-aos="fade-up" data-aos-delay="100">
              <div class="category-card">
                <div class="icon-container">
                  <i class="bi bi-music-note-list"></i>
                </div>
                <h4>Music</h4>
              </div>
            </div>

            <div class="col-md-2 col-sm-6" data-aos="fade-up" data-aos-delay="200">
              <div class="category-card">
                <div class="icon-container">
                  <i class="bi bi-trophy-fill"></i>
                </div>
                <h4>Clubs</h4>
              </div>
            </div>

            <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="300">
              <div class="category-card">
                <div class="icon-container">
                  <i class="bi bi-calendar-event"></i>
                </div>
                <h4>Spiritual Development</h4>
                <p>Fellowship</p>
              </div>
            </div>


            <div class="col-md-2 col-sm-6" data-aos="fade-up" data-aos-delay="400">
              <div class="category-card">
                <div class="icon-container">
                  <i class="bi bi-house-door-fill"></i>
                </div>
                <h4>Entrepreneurship Classes</h4>
              </div>
            </div>

            <div class="col-md-2 col-sm-6" data-aos="fade-up" data-aos-delay="400">
              <div class="category-card">
                <div class="icon-container">
                  <i class="bi bi-house-door-fill"></i>
                </div>
                <h4>Sports</h4>
              </div>
            </div>
          </div>
        </div>



        <!-- Tabs Section -->
        <div class="students-life-tabs mt-5" data-aos="fade-up" data-aos-delay="200">
          <div class="section-header text-center">
            <h3>School Activities</h3>
            <!-- <p>Take a glimpse into our vibrant student community</p> -->
          </div>

          <ul class="nav nav-pills mb-4 justify-content-center" id="studentLifeTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="clubs-tab" data-bs-toggle="pill" data-bs-target="#students-life-clubs" type="button" role="tab" aria-controls="clubs" aria-selected="true">
                <i class="bi bi-people"></i> Parent - Teacher Forum
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="athletics-tab" data-bs-toggle="pill" data-bs-target="#students-life-athletics" type="button" role="tab" aria-controls="athletics" aria-selected="false">
                <i class="bi bi-trophy"></i> Christmas Carol
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="facilities-tab" data-bs-toggle="pill" data-bs-target="#students-life-facilities" type="button" role="tab" aria-controls="facilities" aria-selected="false">
                <i class="bi bi-building"></i> Interhouse Sports Competition
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="support-tab" data-bs-toggle="pill" data-bs-target="#students-life-support" type="button" role="tab" aria-controls="support" aria-selected="false">
                <i class="bi bi-shield-check"></i> Cultural Day
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="valedictory-tab" data-bs-toggle="pill" data-bs-target="#students-life-valedictory" type="button" role="tab" aria-controls="valedictory" aria-selected="false">
                <i class="bi bi-shield-check"></i> Valedictory Service
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="founder-tab" data-bs-toggle="pill" data-bs-target="#students-life-founder" type="button" role="tab" aria-controls="founder" aria-selected="false">
                <i class="bi bi-shield-check"></i> Founder's Day Celebration
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="thanksgiving-tab" data-bs-toggle="pill" data-bs-target="#students-life-thanksgiving" type="button" role="tab" aria-controls="thanksgiving" aria-selected="false">
                <i class="bi bi-shield-check"></i> Thanksgiving Day
              </button>
            </li>
          </ul>

          <div class="tab-content" id="studentLifeTabsContent">
            <!-- Parent Teacher forum   -->
            <div class="tab-pane fade show active" id="students-life-clubs" role="tabpanel" aria-labelledby="clubs-tab">
              <div class="row g-4">

                <div class="col-lg-12" data-aos="fade-left" data-aos-delay="300">
                  <div class="support-card">
                    <div class="icon">
                      <i class="bi bi-people-fill"></i>
                    </div>
                    <h5>Join a Community That Shares Your Interests</h5>
                    <p>A key opportunity for parents/guardians to learn more about their wards, new developments and school updates, ensuring their voices areheard and decisions representedin HAPA college</p>
                  </div>
                </div>

              </div>
            </div>

            <!-- Christmas Carol -->
            <div class="tab-pane fade" id="students-life-athletics" role="tabpanel" aria-labelledby="athletics-tab">
              <div class="row g-4 mb-4 align-items-center">
                <div class="col-lg-12" data-aos="fade-left" data-aos-delay="300">
                  <div class="support-card">
                    <div class="icon">
                      <i class="bi bi-people-fill"></i>
                    </div>
                    <h5>Christmas Carol</h5>
                    <p>As Christmas Carol approaches every year, we come together to celebrate through song, merriment and dance. It's always a day to remember.</p>
                  </div>
                </div>
              </div>
            </div>


            <!-- Interhouse Sports -->
            <div class="tab-pane fade" id="students-life-facilities" role="tabpanel" aria-labelledby="facilities-tab">
              <div class="row g-4">
                <div class="col-lg-12" data-aos="fade-left" data-aos-delay="300">
                  <div class="support-card">
                    <div class="icon">
                      <i class="bi bi-people-fill"></i>
                    </div>
                    <h5>Inter House Sports Competition</h5>
                    <p>Andrenaline pumping and friendly rivalry comes to HAPA college every year, as student cheer on their teams ans reveal in the spirit of sportmanship and teamwork.</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Cultural Day -->
            <div class="tab-pane fade" id="students-life-support" role="tabpanel" aria-labelledby="support-tab">
              <div class="row g-4">
                <div class="col-lg-12" data-aos="fade-left" data-aos-delay="300">
                  <div class="support-card">
                    <div class="icon">
                      <i class="bi bi-people-fill"></i>
                    </div>
                    <h5>Cultural Day</h5>
                    <p>A day for the vibrant celebration of diversity, traditions an unity. We immerse ourselves in the diverstiy of our different cultures on this day every year</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Valedictory Service -->
                 <div class="tab-pane fade" id="students-life-valedictory" role="tabpanel" aria-labelledby="support-tab">
              <div class="row g-4">
                <div class="col-lg-12" data-aos="fade-left" data-aos-delay="300">
                  <div class="support-card">
                    <div class="icon">
                      <i class="bi bi-people-fill"></i>
                    </div>
                    <h5>Prize giving Awards and Valedictory Service</h5>
                    <p>An avenue to recognize excellence and hard work, our prize giving and awards ceremony honours outstanding achievments at the end of every session.</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Founder's Day Celebration -->
                 <div class="tab-pane fade" id="students-life-founder" role="tabpanel" aria-labelledby="support-tab">
              <div class="row g-4">
                <div class="col-lg-12" data-aos="fade-left" data-aos-delay="300">
                  <div class="support-card">
                    <div class="icon">
                      <i class="bi bi-people-fill"></i>
                    </div>
                    <h5>Founder's Day Celebration</h5>
                    <p>Every year, we mark the founding of HAPA college, reflecton our rich heritage and commemorate the values that shape us.</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Thanksgiving Day -->
                 <div class="tab-pane fade" id="students-life-thanksgiving" role="tabpanel" aria-labelledby="support-tab">
              <div class="row g-4">
                <div class="col-lg-12" data-aos="fade-left" data-aos-delay="300">
                  <div class="support-card">
                    <div class="icon">
                      <i class="bi bi-people-fill"></i>
                    </div>
                    <h5>Thanksgiving Day</h5>
                    <p>Celegrated on the first day of a new year (i.e First resumption day in the second term), to appreciate our maker, who is our source.</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Athletics Tab -->
            <!-- <div class="tab-pane fade" id="students-life-athletics" role="tabpanel" aria-labelledby="athletics-tab">
              <div class="row g-4 mb-4 align-items-center">
                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                  <h3>Athletics &amp; Recreation Programs</h3>
                  <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>

                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
                  <div class="stats-container">
                    <div class="stat-item">
                      <span class="number">15+</span>
                      <span class="label">Sports Teams</span>
                    </div>
                    <div class="stat-item">
                      <span class="number">20+</span>
                      <span class="label">Championships</span>
                    </div>
                    <div class="stat-item">
                      <span class="number">300+</span>
                      <span class="label">Athletes</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="athletic-programs-slider swiper init-swiper" data-aos="fade-up" data-aos-delay="400">
                <script type="application/json" class="swiper-config">
                  {
                    "loop": true,
                    "speed": 600,
                    "autoplay": {
                      "delay": 5000
                    },
                    "slidesPerView": 1,
                    "spaceBetween": 30,
                    "pagination": {
                      "el": ".swiper-pagination",
                      "type": "bullets",
                      "clickable": true
                    },
                    "breakpoints": {
                      "576": {
                        "slidesPerView": 2
                      },
                      "992": {
                        "slidesPerView": 3
                      },
                      "1200": {
                        "slidesPerView": 4
                      }
                    }
                  }
                </script>
                <div class="swiper-wrapper">
                  <div class="swiper-slide">
                    <div class="sport-card">
                      <img src="assets/img/education/activities-2.webp" class="img-fluid" loading="lazy" alt="Swimming">
                      <div class="sport-info">
                        <h5>Swimming</h5>
                        <div class="badge">Varsity</div>
                      </div>
                    </div>
                  </div>

                  <div class="swiper-slide">
                    <div class="sport-card">
                      <img src="assets/img/education/activities-4.webp" class="img-fluid" loading="lazy" alt="Basketball">
                      <div class="sport-info">
                        <h5>Basketball</h5>
                        <div class="badge">Varsity</div>
                      </div>
                    </div>
                  </div>

                  <div class="swiper-slide">
                    <div class="sport-card">
                      <img src="assets/img/education/activities-6.webp" class="img-fluid" loading="lazy" alt="Soccer">
                      <div class="sport-info">
                        <h5>Soccer</h5>
                        <div class="badge">Varsity</div>
                      </div>
                    </div>
                  </div>

                  <div class="swiper-slide">
                    <div class="sport-card">
                      <img src="assets/img/education/activities-8.webp" class="img-fluid" loading="lazy" alt="Tennis">
                      <div class="sport-info">
                        <h5>Tennis</h5>
                        <div class="badge">Varsity</div>
                      </div>
                    </div>
                  </div>

                  <div class="swiper-slide">
                    <div class="sport-card">
                      <img src="assets/img/education/activities-10.webp" class="img-fluid" loading="lazy" alt="Volleyball">
                      <div class="sport-info">
                        <h5>Volleyball</h5>
                        <div class="badge">Varsity</div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="swiper-pagination"></div>
              </div>
            </div> -->

            <!-- Facilities Tab -->
            <!-- <div class="tab-pane fade" id="students-life-facilities" role="tabpanel" aria-labelledby="facilities-tab">
              <div class="row g-4">
                <div class="col-lg-8" data-aos="fade-right" data-aos-delay="200">
                  <div class="facilities-gallery">
                    <div class="row g-3">
                      <div class="col-md-8">
                        <img src="assets/img/education/campus-4.webp" alt="Housing" class="img-fluid rounded">
                      </div>
                      <div class="col-md-4">
                        <img src="assets/img/education/campus-5.webp" alt="Dining" class="img-fluid rounded">
                      </div>
                      <div class="col-md-4">
                        <img src="assets/img/education/campus-6.webp" alt="Library" class="img-fluid rounded">
                      </div>
                      <div class="col-md-8">
                        <img src="assets/img/education/campus-7.webp" alt="Recreation" class="img-fluid rounded">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-4" data-aos="fade-left" data-aos-delay="300">
                  <div class="facilities-info">
                    <h3>Modern Campus Facilities</h3>
                    <p>Cras mattis consectetur purus sit amet fermentum. Maecenas faucibus mollis interdum. Aenean lacinia bibendum nulla sed consectetur.</p>

                    <div class="facilities-list">
                      <div class="facility-item">
                        <i class="bi bi-house-door"></i>
                        <h5>Residence Halls</h5>
                        <p>10 modern residence halls with various room configurations</p>
                      </div>

                      <div class="facility-item">
                        <i class="bi bi-cup-hot"></i>
                        <h5>Dining Options</h5>
                        <p>5 dining locations with diverse meal options</p>
                      </div>

                      <div class="facility-item">
                        <i class="bi bi-book"></i>
                        <h5>Libraries</h5>
                        <p>3 libraries with extensive physical and digital collections</p>
                      </div>

                      <div class="facility-item">
                        <i class="bi bi-bicycle"></i>
                        <h5>Recreation Center</h5>
                        <p>State-of-the-art fitness equipment and facilities</p>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div> -->

            <!-- Support Services Tab -->
            <!-- <div class="tab-pane fade" id="students-life-support" role="tabpanel" aria-labelledby="support-tab">
              <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                  <div class="support-card">
                    <div class="icon">
                      <i class="bi bi-heart-pulse"></i>
                    </div>
                    <h5>Health &amp; Wellness</h5>
                    <p>Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida at eget metus.</p>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                  <div class="support-card">
                    <div class="icon">
                      <i class="bi bi-briefcase"></i>
                    </div>
                    <h5>Career Services</h5>
                    <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vivamus sagittis lacus vel augue.</p>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                  <div class="support-card">
                    <div class="icon">
                      <i class="bi bi-universal-access"></i>
                    </div>
                    <h5>Accessibility</h5>
                    <p>Nullam id dolor id nibh ultricies vehicula ut id elit. Sed posuere consectetur est lobortis.</p>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card">
                    <div class="icon">
                      <i class="bi bi-mortarboard"></i>
                    </div>
                    <h5>Academic Support</h5>
                    <p>Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida at eget metus.</p>
                  </div>
                </div>
              </div>

              <div class="row mt-5" data-aos="fade-up" data-aos-delay="500">
                <div class="col-md-6 offset-md-3 text-center">
                  <div class="contact-info-box">
                    <h4>Need Assistance?</h4>
                    <p>Our student support team is available Monday through Friday, 8am to 5pm.</p>
                    <a href="contact.php" class="btn btn-explore mt-2">Contact Student Services <i class="bi bi-arrow-right"></i></a>
                  </div>
                </div>
              </div>
            </div> -->
          </div>
        </div>

        <!-- Student Life Gallery -->
        <div class="students-life-gallery mt-5" data-aos="fade-up" data-aos-delay="200">
          <div class="section-header text-center">
            <h3>Life in HAPA College</h3>
            <p>Take a glimpse into our vibrant student community</p>
          </div>

          <div class="row g-3">
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
              <a href="assets/img/students/loc1.jpg" class="gallery-item glightbox">
                <img src="assets/img/students/loc1.jpg" class="img-fluid" loading="lazy" alt="Student Life">
                <div class="gallery-overlay">
                  <!-- <span>Campus Events</span> -->
                </div>
              </a>
            </div>

            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
              <a href="assets/img/students/loc2.jpg" class="gallery-item glightbox">
                <img src="assets/img/students/loc2.jpg" class="img-fluid" loading="lazy" alt="Student Life">
                <div class="gallery-overlay">
                  <!-- <span>Student Clubs</span> -->
                </div>
              </a>
            </div>

            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
              <a href="assets/img/students/loc3.jpg" class="gallery-item glightbox">
                <img src="assets/img/students/loc3.jpg" class="img-fluid" loading="lazy" alt="Student Life">
                <div class="gallery-overlay">
                  <!-- <span>Graduation Day</span> -->
                </div>
              </a>
            </div>

            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="400">
              <a href="assets/img/students/loc4.jpg" class="gallery-item glightbox">
                <img src="assets/img/students/loc4.jpg" class="img-fluid" loading="lazy" alt="Student Life">
                <div class="gallery-overlay">
                  <!-- <span>Study Groups</span> -->
                </div>
              </a>
            </div>

            <!--<div class="col-md-6" data-aos="zoom-in" data-aos-delay="500">-->
            <!--  <a href="assets/img/education/students-5.webp" class="gallery-item glightbox">-->
            <!--    <img src="assets/img/students/loc5.jpg" class="img-fluid" loading="lazy" alt="Student Life">-->
            <!--    <div class="gallery-overlay">-->
            <!--       <span>Campus Facilities</span> -->
            <!--    </div>-->
            <!--  </a>-->
            <!--</div>-->
          </div>
        </div>


        <!-- <div class="cta-wrapper mt-5" data-aos="fade-up" data-aos-delay="200">
          <div class="cta-content">
            <div class="row align-items-center">
              <div class="col-lg-8" data-aos="fade-right" data-aos-delay="300">
                <h3>Ready to Join Our Community?</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce varius felis nec sem viverra, nec tincidunt felis mollis.</p>
              </div>
              <div class="col-lg-4" data-aos="fade-left" data-aos-delay="400">
                <div class="cta-buttons">
                  <a href="#" class="btn btn-primary">Schedule a Visit</a>
                  <a href="#" class="btn btn-outline">Apply Now</a>
                </div>
              </div>
            </div>
          </div>
        </div> -->

      </div>

    </section><!-- /Students Life Section -->

  </main>

  <?php include('components/footer.php'); ?>


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <?php include('components/scripts.php'); ?>

</body>

</html>