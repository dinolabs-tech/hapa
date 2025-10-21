<?php
session_start();

include("db_connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<?php include('components/head.php'); ?>
<head>
    <title>Home</title>
</head>

<body class="index-page">

  <?php include('components/header.php'); ?>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="false">
        <div class="carousel-inner">

          <div class="carousel-item active">
            <img src="assets/img/banner/banner1.jpg" class="d-block w-100 video-background" alt="Banner 1">
          </div>

          <div class="carousel-item">
            <img src="assets/img/banner/banner2.jpg" class="d-block w-100 video-background" alt="Banner 2">
          </div>

          <div class="carousel-item">
            <img src="assets/img/banner/banner3.jpg" class="d-block w-100 video-background" alt="Banner 2">
          </div>

          <div class="carousel-item">
            <img src="assets/img/banner/banner4.jpg" class="d-block w-100 video-background" alt="Banner 2">
          </div>

          <div class="carousel-item">
            <img src="assets/img/banner/banner5.jpg" class="d-block w-100 video-background" alt="Banner 2">
          </div>

        </div>



        <!-- Overlay -->
        <div class="overlay"></div>

        <!-- Hero Text Content -->
        <div class="container content-overlay text-white">
          <div class="row justify-content-center text-center text-md-start">
            <div class="col-lg-8 col-md-10" data-aos="zoom-out" data-aos-delay="100">
              <div class="hero-content">
                <h1 class="display-5 fw-bold">Learn Anytime, Anywhere.<br class="d-none d-md-block" /> Accelerate Your Future.</h1>
                <p class="lead">Everyone has the capacity to be Excellent. Hapa College is a place where young minds develop and learn to maximize their potentials.</p>
                <div class="cta-buttons d-flex flex-column flex-md-row gap-3 justify-content-center justify-content-md-start mt-3">
                  <a href="about.php#about" class="btn btn-primary px-4 py-2">Start Your Journey</a>
                  <a href="admissions.php#admissions" class="btn btn-outline-light px-4 py-2">Discover Our Programs</a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section>



    <style>
      .hero,
      .hero-container,
      .carousel {
        margin: 0 !important;
        /* Remove all margins */
        padding: 0 !important;
        /* Remove padding to ensure no extra space */
        width: 100%;
      }

      .carousel {
        position: relative;
        overflow: hidden;
      }

      .carousel-images {
        display: flex;
        width: 100%;
      }

      .carousel-image {
        width: 100%;
        height: auto;
        object-fit: cover;
        display: none;
      }

      .carousel-image.active {
        display: block;
        animation: fade 1s ease-in-out;
      }

      .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        /* Semi-transparent dark overlay */
        z-index: 1;
      }

      .content-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        z-index: 2;
        /* Ensures text and buttons are above the overlay */
      }

      .hero-content {
        color: #fff;
        /* White text for contrast */
        text-align: left;
      }

      .hero-content h1 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
      }

      .hero-content p {
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
      }

      .cta-buttons a {
        display: inline-block;
        padding: 0.8rem 1.5rem;
        margin-right: 1rem;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
      }

      .btn-primary {
        background-color: #007bff;
        color: #fff;
      }

      .btn-primary:hover {
        background-color: #0056b3;
      }

      .btn-secondary {
        background-color: transparent;
        color: #fff;
        border: 2px solid #fff;
      }

      .btn-secondary:hover {
        background-color: #fff;
        color: #000;
      }

      /* Fade animation */
      @keyframes fade {
        from {
          opacity: 0.4;
        }

        to {
          opacity: 1;
        }
      }

      /* Responsive adjustments */
      @media (max-width: 768px) {
        .hero-content h1 {
          font-size: 2rem;
        }

        .hero-content p {
          font-size: 1rem;
        }
      }

      .video-background {
        height: 100vh;
        object-fit: cover;
      }

      .hero {
        position: relative;
        overflow: hidden;
      }

      .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        /* dark overlay */
        z-index: 1;
      }

      .hero-content {
        z-index: 2;
        position: relative;
        padding: 2rem 1rem;
      }

      @media (max-width: 768px) {
        .hero-content h1 {
          font-size: 1.75rem;
        }

        .hero-content p {
          font-size: 1rem;
        }

        .cta-buttons a {
          width: 100%;
          text-align: center;
        }
      }
    </style>

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const images = document.querySelectorAll('.carousel-image');
        let currentIndex = 0;

        function showNextImage() {
          images[currentIndex].classList.remove('active');
          currentIndex = (currentIndex + 1) % images.length;
          images[currentIndex].classList.add('active');
        }

        // Start carousel - change image every 5 seconds
        setInterval(showNextImage, 5000);
      });
    </script>
    <!-- /Hero Section -->

    <!-- History Section -->
    <section id="history" class="history section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">


        <div class="row align-items-center g-5">
          <div class="col-lg-6">
            <div class="about-content" data-aos="fade-up" data-aos-delay="200">

              <h2>Why Study at HAPA College?</h2>
              <p>At HAPA College Oba – Ile, Akure . We specialize by the grace of God in nurturing each student for success. Regardless of a learner's abilities, talents, and interests, our teaching approaches are geared towards stimulating the minds of all students.
                Our personalized learning approach help us get to know each scholar's weaknesses, strengths, and struggles and the appropriate approach to get them back on track
              </p>

              <h3>Strategic Objectives</h3>
              <div class="timeline">
                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    <h4>We prepare for life.</h4>
                    <!-- <p>Etiam at tincidunt arcu. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p> -->
                  </div>
                </div>

                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    <h4>We Create a culture of excellent behavior</h4>
                    <!-- <p>Donec dignissim, odio ac imperdiet luctus, ante nisl accumsan justo, nec tempus augue mi in nulla.</p> -->
                  </div>
                </div>

                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    <h4>We raise scholars who are driven by godly principles to achieve excellent performance in their academic and career pursuits.</h4>
                    <!-- <p>Suspendisse potenti. Nullam lacinia dictum auctor. Phasellus euismod sem at dui imperdiet, ac tincidunt mi placerat.</p> -->
                  </div>
                </div>

                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    <h4>We create a warm, friendly and welcoming atmosphere which is reflected in our students and staff.</h4>
                    <!-- <p>Vestibulum ultrices magna ut faucibus sollicitudin. Sed eget venenatis enim, nec imperdiet ex.</p> -->
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="about-image" data-aos="zoom-in" data-aos-delay="300">
              <img src="assets/img/home/proprietor.jpg" alt="Campus" class="img-fluid rounded">
            </div>
          </div>
        </div>



      </div>

      <div class="row mt-5">
        <div class="col-lg-12">
          <div class="core-values" data-aos="fade-up" data-aos-delay="500">
            <h3 class="text-center mb-4">School Philosophy</h3>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
              <div class="col">
                <div class="value-card">
                  <div class="value-icon">
                    <i class="bi bi-book"></i>
                  </div>
                  <h4>Agents of Change</h4>
                  <p>We prepare for life. With the creation of HAPA College as our Contribution towards the betterment of education in our society.</p>
                </div>
              </div>

              <div class="col">
                <div class="value-card">
                  <div class="value-icon">
                    <i class="bi bi-people"></i>
                  </div>
                  <h4>Student Empowerment</h4>
                  <p>We empower our students to make choices about their future goals and how to achieve their goals.</p>
                </div>
              </div>

              <div class="col">
                <div class="value-card">
                  <div class="value-icon">
                    <i class="bi bi-lightbulb"></i>
                  </div>
                  <h4>Community for Young Learners</h4>
                  <p>We build a community for young learners that will grow with integrity and compassion and encourage them to become self-reliant. </p>
                </div>
              </div>

              <div class="col">
                <div class="value-card">
                  <div class="value-icon">
                    <i class="bi bi-globe"></i>
                  </div>
                  <h4>Success</h4>
                  <p>We believe success is achieved only by being diligent and concentrating on the duty at hand. </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      </div>

    </section><!-- /History Section -->

    <!-- Featured Programs Section -->
    <section id="featured-programs" class="featured-programs section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Affiliated Institutions</h2>
        <p>HAPA College is an Approved Education Agent to all our Partner Foreign Institutions. Currently we are working
          with institutions in Canada, United Kingdom, Cyprus, United States of America, Spain and Netherlands. Below
          are the list of our top institutions</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">
          <ul class="program-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
            <li data-filter="*" class="filter-active">All Institutions</li>
            <li data-filter=".filter-usa">USA</li>
            <li data-filter=".filter-canada">Canada</li>
            <li data-filter=".filter-netherlands">Netherlands</li>
          </ul>

          <div class="row g-4 isotope-container">
            <div class="col-lg-6 isotope-item filter-usa" data-aos="zoom-in" data-aos-delay="100">
              <div class="program-item">
                <div class="program-badge">Bachelor's Degree</div>
                <div class="row g-0">
                  <div class="col-md-4">
                    <div class="program-image-wrapper">
                      <img src="assets/img/education/seattle.png" class="img-fluid" alt="Program">
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="program-content">
                      <h3>SEATTLE COLLEGES</h3>
                      <div class="program-highlights">
                        <span><i class="bi bi-geo-alt"></i> USA</span>
                        <!-- <span><i class="bi bi-people-fill"></i> 120 Credits</span>
                        <span><i class="bi bi-calendar3"></i> Fall &amp; Spring</span> -->
                      </div>
                      <p>
                      <ul>
                        <li>
                          Opportunity to work while studying
                        </li>
                        <li>No IELTS Requirements</li>
                        <li>Seattle Colleges students qualify for federal grants and loans</li>
                      </ul>
                      </p>
                      <a href="affiliates.php" class="program-btn"><span>Learn More</span> <i class="bi bi-arrow-right"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Program Item -->

            <div class="col-lg-6 isotope-item filter-canada" data-aos="zoom-in" data-aos-delay="200">
              <div class="program-item">
                <div class="program-badge">Bachelor's Degree</div>
                <div class="row g-0">
                  <div class="col-md-4">
                    <div class="program-image-wrapper">
                      <img src="assets/img/education/okanagan.png" class="img-fluid" alt="Program">
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="program-content">
                      <h3>OKANAGAN COLLEGE</h3>
                      <div class="program-highlights">
                        <span><i class="bi bi-geo-alt"></i> Canada</span>
                        <!-- <span><i class="bi bi-people-fill"></i> 90 Credits</span>
                        <span><i class="bi bi-calendar3"></i> Fall Only</span> -->
                      </div>
                      <p>
                      <ul>
                        <li>Part-Time Work Opportunity. </li>
                        <li>Post-Graduate Work Permit</li>
                        <li>Experiential Learning</li>
                        <li>Accommodation. </li>
                      </ul>
                      </p>
                      <a href="affiliates.php" class="program-btn"><span>Learn More</span> <i class="bi bi-arrow-right"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Program Item -->

            <div class="col-lg-6 isotope-item filter-canada" data-aos="zoom-in" data-aos-delay="300">
              <div class="program-item">
                <div class="program-badge">Bachelor's Degree</div>
                <div class="row g-0">
                  <div class="col-md-4">
                    <div class="program-image-wrapper">
                      <img src="assets/img/education/selkirk.png" class="img-fluid" alt="Program">
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="program-content">
                      <h3>SELKIRK COLLEGES</h3>
                      <div class="program-highlights">
                        <span><i class="bi bi-geo-alt"></i> Canada</span>
                        <!-- <span><i class="bi bi-people-fill"></i> 150 Credits</span>
                        <span><i class="bi bi-calendar3"></i> Fall Only</span> -->
                      </div>
                      <p>
                      <ul>
                        <li>
                          Opportunity to work while studying
                        </li>
                        <li>No IELTS Requirements</li>
                        <li>Seattle Colleges students qualify for federal grants and loans</li>
                      </ul>
                      </p>
                      <a href="affiliates.php" class="program-btn"><span>Learn More</span> <i class="bi bi-arrow-right"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Program Item -->

            <div class="col-lg-6 isotope-item filter-netherlands" data-aos="zoom-in" data-aos-delay="100">
              <div class="program-item">
                <div class="program-badge">Master's Degree</div>
                <div class="row g-0">
                  <div class="col-md-4">
                    <div class="program-image-wrapper">
                      <img src="assets/img/education/wlogo.png" class="img-fluid" alt="Program">
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="program-content">
                      <h3>Wittenberg University
                      </h3>
                      <div class="program-highlights">
                        <span><i class="bi bi-geo-alt"></i> Netherlands</span>
                      </div>
                      <p>
                      <ul>
                        <li>
                          Study Visa will be processed by The university,
                        </li>
                        <li>Amazing! IELTS is not required for Nigerian students;</li>
                        <li>International Students can work after graduation </li>
                      </ul>
                      </p>
                      <a href="affiliates.php" class="program-btn"><span>Learn More</span> <i class="bi bi-arrow-right"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Program Item -->


          </div>
        </div>

      </div>

    </section><!-- /Featured Programs Section -->

    <!-- Students Life Block Section -->
    <section id="students-life-block" class="students-life-block section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Students Life</h2>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center gy-4">
          <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
            <div class="students-life-img position-relative">
              <img src="assets/img/home/student_life.jpg" class="img-fluid rounded-4 shadow-sm"
                alt="Students Life">
              <div class="img-overlay">
                <!-- <h3>Discover Campus Life</h3> -->
                <a href="students-life.php" class="explore-btn">Explore More <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
            <div class="students-life-content">

              <div class="row g-4 mb-4">
                <div class="col-md-6" data-aos="zoom-in" data-aos-delay="200">
                  <div class="student-activity-item">
                    <div class="icon-box">
                      <i class="bi bi-people"></i>
                    </div>
                    <h4>Student Clubs</h4>
                  </div>
                </div>

                <div class="col-md-6" data-aos="zoom-in" data-aos-delay="300">
                  <div class="student-activity-item">
                    <div class="icon-box">
                      <i class="bi bi-trophy"></i>
                    </div>
                    <h4>Sports Events</h4>
                  </div>
                </div>

                <div class="col-md-6" data-aos="zoom-in" data-aos-delay="400">
                  <div class="student-activity-item">
                    <div class="icon-box">
                      <i class="bi bi-music-note-beamed"></i>
                    </div>
                    <h4>Arts &amp; Culture</h4>
                    </div>
                </div>

                <div class="col-md-6" data-aos="zoom-in" data-aos-delay="500">
                  <div class="student-activity-item">
                    <div class="icon-box">
                      <i class="bi bi-globe-americas"></i>
                    </div>
                    <h4>Conduicive Learning Experience</h4>
                  </div>
                </div>
              </div>

              <div class="students-life-cta" data-aos="fade-up" data-aos-delay="600">
                <a href="students-life.php" class="btn btn-primary">View All Student Activities</a>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Students Life Block Section -->

    <!-- Testimonials Section -->
    <!-- <section id="testimonials" class="testimonials section">

      
      <div class="container section-title" data-aos="fade-up">
        <h2>Testimonials</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div>

      <div class="container">

        <div class="testimonial-masonry">

          <div class="testimonial-item" data-aos="fade-up">
            <div class="testimonial-content">
              <div class="quote-pattern">
                <i class="bi bi-quote"></i>
              </div>
              <p>Implementing innovative strategies has revolutionized our approach to market challenges and competitive
                positioning.</p>
              <div class="client-info">
                <div class="client-image">
                  <img src="assets/img/person/person-f-7.webp" alt="Client">
                </div>
                <div class="client-details">
                  <h3>Rachel Bennett</h3>
                  <span class="position">Strategy Director</span>
                </div>
              </div>
            </div>
          </div>

          <div class="testimonial-item highlight" data-aos="fade-up" data-aos-delay="100">
            <div class="testimonial-content">
              <div class="quote-pattern">
                <i class="bi bi-quote"></i>
              </div>
              <p>Exceptional service delivery and innovative solutions have transformed our business operations, leading
                to remarkable growth and enhanced customer satisfaction across all touchpoints.</p>
              <div class="client-info">
                <div class="client-image">
                  <img src="assets/img/person/person-m-7.webp" alt="Client">
                </div>
                <div class="client-details">
                  <h3>Daniel Morgan</h3>
                  <span class="position">Chief Innovation Officer</span>
                </div>
              </div>
            </div>
          </div>

          <div class="testimonial-item" data-aos="fade-up" data-aos-delay="200">
            <div class="testimonial-content">
              <div class="quote-pattern">
                <i class="bi bi-quote"></i>
              </div>
              <p>Strategic partnership has enabled seamless digital transformation and operational excellence.</p>
              <div class="client-info">
                <div class="client-image">
                  <img src="assets/img/person/person-f-8.webp" alt="Client">
                </div>
                <div class="client-details">
                  <h3>Emma Thompson</h3>
                  <span class="position">Digital Lead</span>
                </div>
              </div>
            </div>
          </div>

          <div class="testimonial-item" data-aos="fade-up" data-aos-delay="300">
            <div class="testimonial-content">
              <div class="quote-pattern">
                <i class="bi bi-quote"></i>
              </div>
              <p>Professional expertise and dedication have significantly improved our project delivery timelines and
                quality metrics.</p>
              <div class="client-info">
                <div class="client-image">
                  <img src="assets/img/person/person-m-8.webp" alt="Client">
                </div>
                <div class="client-details">
                  <h3>Christopher Lee</h3>
                  <span class="position">Technical Director</span>
                </div>
              </div>
            </div>
          </div>

          <div class="testimonial-item highlight" data-aos="fade-up" data-aos-delay="400">
            <div class="testimonial-content">
              <div class="quote-pattern">
                <i class="bi bi-quote"></i>
              </div>
              <p>Collaborative approach and industry expertise have revolutionized our product development cycle,
                resulting in faster time-to-market and increased customer engagement levels.</p>
              <div class="client-info">
                <div class="client-image">
                  <img src="assets/img/person/person-f-9.webp" alt="Client">
                </div>
                <div class="client-details">
                  <h3>Olivia Carter</h3>
                  <span class="position">Product Manager</span>
                </div>
              </div>
            </div>
          </div>

          <div class="testimonial-item" data-aos="fade-up" data-aos-delay="500">
            <div class="testimonial-content">
              <div class="quote-pattern">
                <i class="bi bi-quote"></i>
              </div>
              <p>Innovative approach to user experience design has significantly enhanced our platform's engagement
                metrics and customer retention rates.</p>
              <div class="client-info">
                <div class="client-image">
                  <img src="assets/img/person/person-m-13.webp" alt="Client">
                </div>
                <div class="client-details">
                  <h3>Nathan Brooks</h3>
                  <span class="position">UX Director</span>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>

    </section> -->
    <!-- /Testimonials Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">
          <div class="col-lg-6">
            <div class="stats-overview" data-aos="fade-right" data-aos-delay="200">
              <h2 class="stats-title">Excellence in Education for Over 10 Years</h2>
              <p class="stats-description">HAPA College was established in the year 2013, is a Christian co-educational school that is driven by a God-ordained unction to manifest greatly in the younger generation.
                We are what your child needs to attain a God-ordained destiny.
              </p>
              <div class="stats-cta">
                <!-- HAPA College is a center of Excellence  -->
                <!-- <a class="btn btn-primary"> HAPA College is a center of Excellence </a> -->
                <button class="btn btn-primary">HAPA College is a center of Excellence </button>
                <!-- <a href="#" class="btn btn-outline">Virtual Tour</a> -->
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="stats-card" data-aos="zoom-in" data-aos-delay="300">
                  <div class="stats-icon">
                    <i class="bi bi-people-fill"></i>
                  </div>
                  <div class="stats-number">
                    <span data-purecounter-start="0" data-purecounter-end="95" data-purecounter-duration="1"
                      class="purecounter"></span>%
                  </div>
                  <div class="stats-label">Success Rate</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="stats-card" data-aos="zoom-in" data-aos-delay="400">
                  <div class="stats-icon">
                    <i class="bi bi-mortarboard"></i>
                  </div>
                  <div class="stats-number">
                    <span data-purecounter-start="0" data-purecounter-end="183" data-purecounter-duration="1"
                      class="purecounter"></span>+
                  </div>
                  <div class="stats-label">Students Enrolled</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="stats-card" data-aos="zoom-in" data-aos-delay="500">
                  <div class="stats-icon">
                    <i class="bi bi-award"></i>
                  </div>
                  <div class="stats-number">
                    <span data-purecounter-start="0" data-purecounter-end="5" data-purecounter-duration="1"
                      class="purecounter"></span>+
                  </div>
                  <div class="stats-label">Awards</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="stats-card" data-aos="zoom-in" data-aos-delay="600">
                  <div class="stats-icon">
                    <i class="bi bi-person-workspace"></i>
                  </div>
                  <div class="stats-number"><span data-purecounter-start="0" data-purecounter-end="40"
                      data-purecounter-duration="1" class="purecounter"></span>+
                  </div>
                  <div class="stats-label">Certified Teachers</div>
                </div>
              </div>
            </div>
          </div>
        </div>

   
      </div>

    </section><!-- /Stats Section -->


 

    <section id="recent-news" class="recent-news section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Trending Post</h2>
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

            $sql_total_posts = "SELECT COUNT(*) AS total FROM posts";
            $result_total_posts = $conn->query($sql_total_posts);
            $total_posts = $result_total_posts->fetch_assoc()['total'];
            $total_pages = ceil($total_posts / $posts_per_page);

            if ($result_trending_posts->num_rows > 0) {
              while ($trending_post = $result_trending_posts->fetch_assoc()) {
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


    </section>


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

  </main>

  <?php include('components/footer.php'); ?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>



  <?php include('components/scripts.php'); ?>

</body>

</html>