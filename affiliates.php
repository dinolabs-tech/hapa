<?php session_start();?>
<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php'); ?>
<head>
    <title>Study Abroad</title>
</head>

<body class="students-life-page">

  <?php include('components/header.php'); ?>

  <main class="main">

    <!-- Page Title -->
   <div class="page-title dark-background position-relative" style="background-image: url(assets/img/pg-header.jpg);">
      <div class="container position-relative">
        <h1>Study Abroad</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Study Abroad</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Students Life Section -->
    <section id="affiliates" class="students-life section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <!-- Hero Banner -->
        <div class="students-life-banner" data-aos="zoom-in" data-aos-delay="200">
          <div class="banner-content" data-aos="fade-right" data-aos-delay="300">
            <h2>Approved Educational Agent</h2>
            <p>HAPA College is an Approved Education Agent to all our Partner Foreign Institutions. Currently we are
              working with institutions in Canada, United Kingdom, Cyprus, United States of America, Spain and
              Netherlands. At HAPA College, we are committed to fulfil the following roles for our students:</p>
          </div>
          <img src="assets/img/education/showcase-2.webp" alt="Campus Life" class="img-fluid">
        </div>

        <!-- Life Categories -->
        <div class="life-categories mt-5" data-aos="fade-up" data-aos-delay="200">
          <div class="row g-4">

            <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="200">
              <div class="category-card">
                <div class="icon-container">
                  <i class="bi bi-trophy-fill"></i>
                </div>
                <h4>Securing Admission</h4>
                <p>Assisting our students with their university/college admission applications</p>
              </div>
            </div>

            <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="400">
              <div class="category-card">
                <div class="icon-container">
                  <i class="bi bi-trophy-fill"></i>
                </div>
                <h4>Accomodation</h4>
                <p>We provide and coordinate student accommodation, including processing requests where necessary.</p>
              </div>
            </div>

            <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="300">
              <div class="category-card">
                <div class="icon-container">
                  <i class="bi bi-trophy-fill"></i>
                </div>
                <h4>Insurance</h4>
                <p>All payments are insured. In the event that admission is not granted, students are eligible for a full refund.</p>

              </div>
            </div>

          </div>
          <p></p>
          <div class="row g-4">

            <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="300">
              <div class="category-card">
                <div class="icon-container">
                  <i class="bi bi-calendar-event"></i>
                </div>
                <h4>Translation of Documents</h4>
                <p>Document translation support for admission and immigration requirements.</p>
              </div>
            </div>

            <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="300">
              <div class="category-card">
                <div class="icon-container">
                  <i class="bi bi-trophy-fill"></i>
                </div>
                <h4>Flights</h4>
                <p>Assist students in the process of visa applications, travel plans
                  including flights and airport pick-up</p>
              </div>
            </div>

            <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="300">
              <div class="category-card">
                <div class="icon-container">
                  <i class="bi bi-trophy-fill"></i>
                </div>
                <h4>Jobs & More</h4>
                <p>in securing part time job before or on arrival at their preferred country</p>
              </div>
            </div>

            <p></p>
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
              <h6>Contact us today to Kick start your admission process.</h6>
            </div><!-- End Section Title -->

          </div>
        </div>

        <!-- Tabs Section -->
        <div class="students-life-tabs mt-5" data-aos="fade-up" data-aos-delay="200">
          <ul class="nav nav-pills mb-4 justify-content-center" id="studentLifeTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="uk-tab" data-bs-toggle="pill" data-bs-target="#students-life-uk"
                type="button" role="tab" aria-controls="uk" aria-selected="true">
                UK Universities
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="australia-tab" data-bs-toggle="pill"
                data-bs-target="#students-life-australia" type="button" role="tab" aria-controls="australia"
                aria-selected="false">
                Australian Universities
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="usa-tab" data-bs-toggle="pill" data-bs-target="#students-life-usa"
                type="button" role="tab" aria-controls="usa" aria-selected="false">
                USA Universities
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="college-tab" data-bs-toggle="pill" data-bs-target="#students-life-college"
                type="button" role="tab" aria-controls="college" aria-selected="false">
                Colleges &amp; Polytechnics
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="canada-tab" data-bs-toggle="pill" data-bs-target="#students-life-canada"
                type="button" role="tab" aria-controls="canada" aria-selected="false">
                Canadian Universities
              </button>
            </li>
          </ul>

          <div class="tab-content" id="studentLifeTabsContent">

            <!-- UK niversities Tab -->
            <div class="tab-pane fade show active" id="students-life-uk" role="tabpanel" aria-labelledby="uk-tab">
              <div class="row g-4">

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Brunel University London</h5>
                  </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Coventry University</h5>
                  </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>De Montfort University</h5>
                  </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Glasgow Caledonian University</h5>
                  </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Kingston University London</h5>
                  </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Cranfield University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>De Montfort University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Leeds Beckett University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>London South Bank University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Loughborough University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Middlesex University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Nottingham Trent University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Royal Holloway, University of London</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Swansea University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Bradford</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Essex</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Greenwich</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Hertfordshire</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Liverpool</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Reading</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of South Wales</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Strathclyde</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Surrey</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of the West of England</h5>
                  </div>
                </div>

              </div>

            </div>

            <!-- Australia Universities Tab -->
            <div class="tab-pane fade" id="students-life-australia" role="tabpanel" aria-labelledby="australia-tab">
              <div class="row g-4">

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Australian National University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Central Queensland University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Charles Sturt University</h5>
                  </div>
                </div>


                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Edith Cowan University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>James Cook University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Murdoch University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Canberra</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Tasmania</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Wollongong</h5>
                  </div>
                </div>
              </div>
            </div>

            <!-- USA Universities Tab -->
            <div class="tab-pane fade" id="students-life-usa" role="tabpanel" aria-labelledby="usa-tab">
              <div class="row g-4">

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Adelphi University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Arizona State University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Auburn University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Arkansas State University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>California State University - East Bay</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Central Michigan University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Clark University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Cleveland State University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Colorado State University - Pueblo</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Embry-Riddle Aeronautical University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>lowa State University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Long Island University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>San Francisco State University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Southeast Missouri State University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Arizona</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Central Missouri</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Colorado - Denver</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Delaware</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Idaho</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Illinois at Chicago</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Missouri St. Louis</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of New Haven</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of South Florida</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Wisconsin - Stout</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Webster University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Western Michigan University</h5>
                  </div>
                </div>
              </div>


            </div>

            <!-- Colleges & Polytechnic Tab -->
            <div class="tab-pane fade" id="students-life-college" role="tabpanel" aria-labelledby="college-tab">
              <div class="row g-4">

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>College of San Mateo</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Santa Monica College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Seattle Colleges</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>SUNY Oswego</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Valencia College</h5>
                  </div>
                </div>


                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Algonquin College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Bow Valley College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>British Columbia Institute of Technology</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Canada College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Canadore College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Centennial College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>CDI College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Conestoga College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Douglas College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Fanshawe College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Fleming College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>George Brown College 13 Georgian College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Herzing College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Humber College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Langara College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Manitoba Institute</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Milestone College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Niagara College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Okanagan College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Red Deer Polytechnic at Sterling College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Red River College Polytechnic</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Saskatchewan Polytechnic</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Selkirk College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Seneca College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Sheridan College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Southern Alberta Institute of Technology</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>St. Clair College</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Vancouver Community College</h5>
                  </div>
                </div>
              </div>
            </div>

            <!-- Canada Universities Tab -->
            <div class="tab-pane fade" id="students-life-canada" role="tabpanel" aria-labelledby="canada-tab">
              <div class="row g-4">

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Algoma University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Brock University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Kwantlen Polytechnic University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Ryerson University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Thompson Rivers University</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Manitoba</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Regina</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Saskatchewan</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>University of Windsor</h5>
                  </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="support-card" style="padding-top: 40px; justify-content: center; align-items: center; height: 50px;">
                    <h5>Yorkville University</h5>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>


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