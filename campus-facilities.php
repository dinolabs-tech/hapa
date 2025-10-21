<?php session_start();?>
<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php');?>
<head>
    <title>Campus Facilities</title>
</head>

<body class="campus-facilities-page">

<?php include('components/header.php');?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background position-relative" style="background-image: url(assets/img/pg-header.jpg);">
      <div class="container position-relative">
        <h1>Campus &amp; Facilities</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Campus Facilities</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Campus Facilities Section -->
    <section id="campus-facilities" class="campus-facilities section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <!-- Introduction -->     <div class="intro-row">
          <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
              <div class="intro-content">
                <h2 class="fw-bold">Experience Our Campus</h2>
                <!-- <p class="lead">Discover state-of-the-art facilities designed to inspire learning and growth</p> -->
                <p>Our state-of-the-art facilities provide an environment conducive to learning, innovation, and exploration. From cutting-edge laboratories to well-equipped libraries, we spare no effort in providing our ambassadors with the tools they need to excel in an ever-evolving world.</p>
                <div class="stats-container">
                  <!-- <div class="stat-item">
                    <span class="stat-number">120+</span>
                    <span class="stat-label">Acres</span>
                  </div> -->
                  <div class="stat-item">
                    <span class="stat-number">45+</span>
                    <span class="stat-label">Teachers</span>
                  </div>
                  <div class="stat-item">
                    <span class="stat-number">180+</span>
                    <span class="stat-label">Students</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
              <div class="intro-image-container">
                <div class="intro-image main-image">
                  <img src="assets/img/facilities/1.jpg" alt="Main Campus" class="img-fluid rounded">
                </div>
                <div class="intro-image accent-image">
                  <img src="assets/img/facilities/2.jpg" alt="Campus Feature" class="img-fluid rounded">
                </div>
                <!-- <div class="tour-button">
                  <a href="#" class="btn-tour"><i class="bi bi-play-circle-fill"></i> Virtual Tour</a>
                </div> -->
              </div>
            </div>
          </div>
        </div>

        <!-- Facilities Tabs -->
        <div class="facilities-tabs" data-aos="fade-up" data-aos-delay="200">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="academic-tab" data-bs-toggle="tab" data-bs-target="#campus-facilities-academic" type="button" role="tab">
                <i class="bi bi-book"></i> Science Lab.
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="athletic-tab" data-bs-toggle="tab" data-bs-target="#campus-facilities-athletic" type="button" role="tab">
                <i class="bi bi-trophy"></i> ICT Lab
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="residential-tab" data-bs-toggle="tab" data-bs-target="#campus-facilities-residential" type="button" role="tab">
                <i class="bi bi-house-door"></i> Library
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="community-tab" data-bs-toggle="tab" data-bs-target="#campus-facilities-community" type="button" role="tab">
                <i class="bi bi-people"></i> Arts
              </button>
            </li>
           
          </ul>

          <div class="tab-content">
            <!-- Academic Facilities Tab -->
            <div class="tab-pane fade show active" id="campus-facilities-academic" role="tabpanel">
              <div class="row gy-4">
                <div class="col-md-7" data-aos="fade-right" data-aos-delay="100">
                  <div class="facility-highlight">
                    <div class="facility-slider">
                      <div class="facility-slide">
                        <img src="assets/img/facilities/science.jpg" alt="Science Laboratory" class="img-fluid rounded">
                        <div class="slide-caption">Science Laboratory</div>
                      </div>
                    </div>
                    <div class="facility-description">
                      <h3>World-Class Learning Spaces</h3>
                      <p>A world-class science laboratory provides a safe, efficient, and innovative environment for hands-on learning. With modern infrastructure, quality materials, and well-equipped workstations, students can confidently perform experiments while adhering to safety protocols. These spaces encourage collaboration, spark curiosity, and develop problem-solving skills, preparing learners for global scientific standards.</p>
                      <!-- <ul class="feature-list">
                        <li><i class="bi bi-check-circle-fill"></i> Modern infrastructure and high-quality materials</li>
                        <li><i class="bi bi-check-circle-fill"></i> Ample workstations with proper safety equipment</li>
                        <li><i class="bi bi-check-circle-fill"></i> Layout designed for collaboration and efficiency</li>
                        <li><i class="bi bi-check-circle-fill"></i> Promotes curiosity, innovation, and problem-solving skills</li>
                      </ul> -->
                    </div>
                  </div>
                </div>
                <div class="col-md-5" data-aos="fade-left" data-aos-delay="200">
                  <div class="facility-cards">
                    <div class="facility-card">
                      <div class="icon-container">
                        <i class="bi bi-laptop"></i>
                      </div>
                      <h4>Technology Labs</h4>
                      <p>Technology Labs within science laboratories bridge the gap between scientific theory and real-world application. These labs are equipped with advanced tools such as digital microscopes, data collection devices, and interactive systems. Technology Labs foster innovation by providing students with access to cutting-edge resources, enhancing their ability to conduct experiments, analyze results, and explore new scientific concepts.</p>
                      <!-- <span class="info-badge"><i class="bi bi-info-circle"></i> 24 Labs</span> -->
                    </div>

                    <div class="facility-card">
                      <div class="icon-container">
                        <i class="bi bi-flask"></i>
                      </div>
                      <h4>Research Centers</h4>
                      <ul class="feature-list">
                        <li><i class="bi bi-check-circle-fill"></i> Modern infrastructure and high-quality materials</li>
                        <li><i class="bi bi-check-circle-fill"></i> Ample workstations with proper safety equipment</li>
                        <li><i class="bi bi-check-circle-fill"></i> Layout designed for collaboration and efficiency</li>
                        <li><i class="bi bi-check-circle-fill"></i> Promotes curiosity, innovation, and problem-solving skills</li>
                      </ul>
                      <!-- <span class="info-badge"><i class="bi bi-info-circle"></i> 42 Facilities</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- ICT Facilities Tab -->
            <div class="tab-pane fade" id="campus-facilities-athletic" role="tabpanel">
              <div class="row gy-4">
                <div class="col-md-7" data-aos="fade-right" data-aos-delay="100">
                  <div class="facility-highlight">
                    <div class="facility-slider">
                      <div class="facility-slide">
                        <img src="assets/img/facilities/ict.jpg" alt="ICT" class="img-fluid rounded">
                        <div class="slide-caption">Technology Laboratory</div>
                      </div>
                    </div>
                    <div class="facility-description">
                      <h3>State-of-the-Art Tech. Facilities</h3>
                      <p>An ICT Lab is a modern technology-driven environment designed to equip students with essential digital skills. These labs provide access to up-to-date computers, reliable internet, and cutting-edge software for learning, research, and innovation. ICT Labs promote digital literacy, foster creativity, and support collaboration, preparing students for success in a technology-driven world.</p>
                      <!-- <ul class="feature-list">
                        <li><i class="bi bi-check-circle-fill"></i> Access to modern computers and internet connectivity</li>
                        <li><i class="bi bi-check-circle-fill"></i> Latest software for learning, research, and innovation</li>
                        <li><i class="bi bi-check-circle-fill"></i> Collaborative workspaces that encourage teamwork</li>
                        <li><i class="bi bi-check-circle-fill"></i> Develops digital literacy and tech problem-solving skills</li>
                      </ul> -->
                    </div>
                  </div>
                </div>
                <div class="col-md-5" data-aos="fade-left" data-aos-delay="200">
                  <div class="facility-cards">
                    <div class="facility-card">
                      <div class="icon-container">
                        <i class="bi bi-laptop"></i>
                      </div>
                      <h4>Technology Center</h4>
                       <div class="facility-description">
                     <ul class="feature-list">
                        <li><i class="bi bi-check-circle-fill"></i> Access to modern computers and internet connectivity</li>
                        <li><i class="bi bi-check-circle-fill"></i> Latest software for learning, research, and innovation</li>
                        <li><i class="bi bi-check-circle-fill"></i> Collaborative workspaces that encourage teamwork</li>
                        <li><i class="bi bi-check-circle-fill"></i> Develops digital literacy and tech problem-solving skills</li>
                      </ul>
                    </div>
                      <span class="info-badge"><i class="bi bi-info-circle"></i> International Standards</span>
                    </div>

                    <!-- <div class="facility-card">
                      <div class="icon-container">
                        <i class="bi bi-stopwatch"></i>
                      </div>
                      <h4>Training Facilities</h4>
                      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vestibulum ante vel magna convallis.</p>
                      <span class="info-badge"><i class="bi bi-info-circle"></i> Pro Equipment</span>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>

            <!-- Library Facilities Tab -->
            <div class="tab-pane fade" id="campus-facilities-residential" role="tabpanel">
              <div class="row gy-4">
                <div class="col-md-7" data-aos="fade-right" data-aos-delay="100">
                  <div class="facility-highlight">
                    <div class="facility-slider">
                      <div class="facility-slide">
                        <img src="assets/img/facilities/library.jpg" alt="Library" class="img-fluid rounded">
                        <div class="slide-caption">Library</div>
                      </div>
                    </div>
                    <div class="facility-description">
                      <h3>Comfortable Study Environment</h3>
                      <p>A modern library is a resource-rich environment that promotes reading, research, and lifelong learning. Equipped with a wide range of books, digital resources, and comfortable study areas, libraries support academic excellence and personal development. They foster independent learning, critical thinking, and easy access to information for students and educators alike.</p>
                     
                    </div>
                  </div>
                </div>
                <div class="col-md-5" data-aos="fade-left" data-aos-delay="200">
                  <div class="facility-cards">
                    <div class="facility-card">
                      <div class="icon-container">
                        <i class="bi bi-book"></i>
                      </div>
                      <h4>Dining Facilities</h4>
                       <ul class="feature-list">
                        <li><i class="bi bi-check-circle-fill"></i> Extensive collection of books and academic resources</li>
                        <li><i class="bi bi-check-circle-fill"></i> Access to digital materials and online research tools</li>
                        <li><i class="bi bi-check-circle-fill"></i> Quiet, comfortable spaces for reading and study</li>
                        <li><i class="bi bi-check-circle-fill"></i> Encourages independent learning and critical thinking</li>
                      </ul>
                      <!-- <span class="info-badge"><i class="bi bi-info-circle"></i> 5 Locations</span> -->
                    </div>

                    <!-- <div class="facility-card">
                      <div class="icon-container">
                        <i class="bi bi-shield-check"></i>
                      </div>
                      <h4>Security Services</h4>
                      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vestibulum ante vel magna convallis.</p>
                      <span class="info-badge"><i class="bi bi-info-circle"></i> 24/7 Support</span>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>

            <!-- Arts Facilities Tab -->
            <div class="tab-pane fade" id="campus-facilities-community" role="tabpanel">
              <div class="row gy-4">
                <div class="col-md-7" data-aos="fade-right" data-aos-delay="100">
                  <div class="facility-highlight">
                    <div class="facility-slider">
                      <div class="facility-slide">
                        <img src="assets/img/facilities/arts.jpg" alt="Arts" class="img-fluid rounded">
                        <div class="slide-caption">Arts & Culture</div>
                      </div>
                    </div>
                    <div class="facility-description">
                      <h3>Creative Arts & Cultural Expression</h3>
                      <p>An Arts and Culture space nurtures creativity, self-expression, and appreciation for diverse traditions. Through visual arts, music, drama, and cultural activities, students explore their talents, build confidence, and develop cultural awareness. These programs enrich education by promoting creativity, collaboration, and respect for heritage.</p>
                   
                    </div>
                  </div>
                </div>
                <div class="col-md-5" data-aos="fade-left" data-aos-delay="200">
                  <div class="facility-cards">
                    <div class="facility-card">
                      <div class="icon-container">
                        <i class="bi bi-music-note-beamed"></i>
                      </div>
                      <h4>Our Culture</h4>
                         <ul class="feature-list">
                        <li><i class="bi bi-check-circle-fill"></i>Opportunities for visual arts, music, drama, and dance</li>
                        <li><i class="bi bi-check-circle-fill"></i> Encourages creativity, confidence, and self-expression</li>
                        <li><i class="bi bi-check-circle-fill"></i> Promotes cultural awareness and appreciation for diversity</li>
                        <li><i class="bi bi-check-circle-fill"></i> Fosters collaboration and talent development</li>
                      </ul>
                      <!-- <span class="info-badge"><i class="bi bi-info-circle"></i> 3 Venues</span> -->
                    </div>

                    <!-- <div class="facility-card">
                      <div class="icon-container">
                        <i class="bi bi-shop"></i>
                      </div>
                      <h4>Campus Stores</h4>
                      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vestibulum ante vel magna convallis.</p>
                      <span class="info-badge"><i class="bi bi-info-circle"></i> 8 Locations</span>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Campus Gallery -->
        <div class="campus-gallery-section" data-aos="fade-up" data-aos-delay="300">

          <div class="gallery-grid">
            <div class="gallery-item large" data-aos="zoom-in" data-aos-delay="100">
              <img src="assets/img/facilities/fac-1.jpg" alt="Library" class="img-fluid" loading="lazy">
              <div class="gallery-overlay">
                <!-- <h4>Spacious Playing Environment</h4> -->
              </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="200">
              <img src="assets/img/facilities/fac-2.jpg" alt="Student Center" class="img-fluid" loading="lazy">
              <div class="gallery-overlay">
                <!-- <h4>Spacious Multipurpose Hall</h4> -->
              </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="300">
              <img src="assets/img/facilities/fac-3.jpg" alt="Dormitory" class="img-fluid" loading="lazy">
              <div class="gallery-overlay">
                <!-- <h4>Spacious Playing Environment</h4> -->
              </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="400">
              <img src="assets/img/facilities/fac-4.jpg" alt="Study Areas" class="img-fluid" loading="lazy">
              <div class="gallery-overlay">
                <!-- <h4>Spacious Multipurpose Hall</h4> -->
              </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="500">
              <img src="assets/img/facilities/fac-5.jpg" alt="Sports Complex" class="img-fluid" loading="lazy">
              <div class="gallery-overlay">
                <!-- <h4>Spacious Playing Environment</h4> -->
              </div>
            </div>
          </div>
        </div>

       
      </div>

    </section><!-- /Campus Facilities Section -->

  </main>

  <?php include ('components/footer.php');?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php include ('components/scripts.php');?>

</body>

</html>