<footer id="footer" class="footer position-relative dark-background">

  <div class="container footer-top">
    <div class="row gy-4">
      <div class="col-lg-6 col-md-6 footer-about">
        <a href="index.html" class="logo d-flex align-items-center">
          <span class="sitename">HAPA College</span>
        </a>
        <div class="footer-contact pt-3">

          <p>KM 3, Akure Owo Express Road, Oba Ile,</p>
          <p>Akure, Ondo State, Nigeria.</p>

          <p class="mt-3"><span>+234-803-504-2727, +234-803-883-8583</span></p>
          <p><span>admin@hapacollege.com</span></p>
        </div>
        <div class="social-links d-flex mt-4">
          <a href=""><i class="bi bi-twitter-x"></i></a>
          <a href=""><i class="bi bi-facebook"></i></a>
          <a href=""><i class="bi bi-instagram"></i></a>
          <a href=""><i class="bi bi-linkedin"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-md-3 footer-links">
        <h4>Popular Links</h4>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About us</a></li>
          <li><a href="students-life.php">Student Life</a></li>
          <li><a href="blog.php">Blog</a></li>
          <li><a href="gallery.php">Gallery</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-3 footer-links">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="admissions.php">Admissions</a></li>
          <!-- <li><a href="academics.php">Academics</a></li> -->
          <li><a href="anthem.php">School Anthem</a></li>
          <li><a href="campus-facilities.php">Campus & Facilities</a></li>
          <li><a href="affiliates.php">Study Abroad</a></li>
          <?php if (isset($_SESSION["user_id"])) { ?>
            <li><a href="portal/logout.php">Logout</a></li>
          <?php } else { ?>
            <li><a href="portal/login.php">Login</a></li>
          <?php } ?>

        </ul>
      </div>



    </div>
  </div>

  <div class="container copyright text-center mt-4">
    <p>&copy; <?= date('Y'); ?> <span>Copyright</span> <strong class="px-1 sitename">HAPA College</strong> <span>All Rights Reserved</span></p>
    <div class="credits">
      Developed by <a href="https://dinolabstech.com/">Dinolabs Tech Services</a>
    </div>
  </div>

</footer>