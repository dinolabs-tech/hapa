 <header id="header" class="header d-flex align-items-center fixed-top">
   <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

     <a href="index.php" class="logo d-flex align-items-center">
       <!-- Uncomment the line below if you also wish to use an image logo -->
       <img src="assets/img/hapa.png" alt="">
       <!-- <i class="bi bi-buildings"></i> -->
       <h1 class="sitename">HAPA COLLEGE</h1>
     </a>
     <nav id="navmenu" class="navmenu">
       <ul>
         <li><a href="index.php" class="active">Home</a></li>
         <li class="dropdown"><a href="about.php"><span>About</span> <i
               class="bi bi-chevron-down toggle-dropdown"></i></a>
           <ul>
             <!-- <li><a href="about.php">About Us</a></li> -->
             <!-- <li><a href="admissions.php">Admissions</a></li> -->
             <!-- <li><a href="academics.php">Academics</a></li> -->
             <li><a href="anthem.php">School Anthem</a></li>
             <!-- <li><a href="faculty-staff.php">Faculty &amp; Staff</a></li> -->
             <li><a href="campus-facilities.php">Campus &amp; Facilities</a></li>
             <li><a href="school_prospectus.pdf">Prospectus [Download]</a></li>
           </ul>
         </li>

          <li class="dropdown"><a href="admissions.php"><span>Admissions</span>
        <i class="bi bi-chevron-down toggle-dropdown"></i></a>
        <ul>
          <li><a href="e-form.pdf">E-Form [Download]</a></li>
        </ul>
        </li>
        
        
         <li><a href="academics.php">Academics</a></li>
        
         <li><a href="students-life.php">Students Life</a></li>

         <?php if (isset($_SESSION["staffname"])) { ?>
           <?php if ($_SESSION['role'] == 'Administrator' || $_SESSION['role'] == 'Teacher' || $_SESSION['role'] == 'Superuser') { ?>
             <li class="dropdown"><a href="#"><span>Blog</span> <i
                   class="bi bi-chevron-down toggle-dropdown"></i></a>
               <ul>
                 <li><a href="blog.php">Blog Post</a></li>
                 <li><a href="create_post.php">Create Post</a></li>
                 <li><a href="manage_categories.php">Manage Categories</a></li>
                 <li><a href="dashboard.php">Dashboard</a></li>
               </ul>
             </li>
           <?php } else { ?>
             <a href="blog.php" class="nav-item nav-link">Blog</a>
           <?php } ?>

         <?php } else { ?>

           <a href="blog.php" class="nav-item nav-link">Blog</a>

         <?php } ?>

         <!-- <li><a href="events.php">Events</a></li> -->
         <!-- <li><a href="alumni.php">Alumni</a></li> -->
         <!-- <li class="dropdown"><a href="#"><span>More Pages</span> <i
               class="bi bi-chevron-down toggle-dropdown"></i></a>
           <ul>
             <li><a href="news.php">News</a></li>
             <li><a href="news-details.php">News Details</a></li>
             <li><a href="event-details.php">Event Details</a></li>
             <li><a href="privacy.php">Privacy</a></li>
             <li><a href="terms-of-service.php">Terms of Service</a></li>
             <li><a href="404.php">Error 404</a></li>
             <li><a href="starter-page.php">Starter Page</a></li>
           </ul>
         </li> -->

         <!-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i
                    class="bi bi-chevron-down toggle-dropdown"></i></a>
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
          </li> -->
          
         <!--<li><a href="careers.php">Careers</a></li>-->
         <li><a href="careers.php">Careers</a></li>
         <li><a href="affiliates.php">Study Abroad</a></li>
         <li><a href="contact.php">Contact</a></li>


         <?php if (isset($_SESSION["staffname"])) { ?>
           <?php if ($_SESSION['role'] == 'Administrator' || $_SESSION['role'] == 'Teacher') { ?>
             <li><a href="portal/dashboard.php">Portal</a></li>
           <?php } elseif ($_SESSION['role'] == 'Superuser') { ?>
<li><a href="portal/superdashboard.php">Portal</a></li>
           <?php } ?>

         <?php } else { ?>

<li><a href="portal/login.php">Portal</a></li>
         <?php } ?>


       </ul>
       <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
     </nav>

   </div>
 </header>