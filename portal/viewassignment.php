<?php include('components/students_logic.php');?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?> <!-- Includes the head section of the HTML document (meta tags, title, CSS links) -->
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <?php 
      
      $role=isset($_SESSION['role']) ? $_SESSION['role']:'';
      //set the appropriate url based on the user role
      if ($role ==='Student') {
        include('studentnav.php'); 
      }elseif ($role ==='Administrator'){
        include('adminnav.php'); 
      }elseif ($role==='Alumni') {
        include('alumninav.php'); 
      }

      ?>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <?php include('logo_header.php');?>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
         <?php include('navbar.php');?>
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4 d-none d-lg-block"
            >
              <div>
                <h3 class="fw-bold mb-3">Assignment</h3>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active">Home</li>
                  <li class="breadcrumb-item active">Assignment</li>
              </ol>
              </div>
           
            </div>

         
              
          
            <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                  <h4 class="card-title">Assignment | 
                    <?php echo htmlspecialchars($student_class); ?> <?php echo htmlspecialchars($student_arm); ?>
                    </h4>   
                  </div>
                  <div class="card-body">
                  <div class="table-responsive table-hover">
                  <table class="basic-datatables">
                          <thead>
                              <tr>
                                  <th>Assignment</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php if (!empty($assignment_files)): ?>
                                  <?php foreach ($assignment_files as $file): ?>
                                      <tr>
                                          <td><?php echo htmlspecialchars($file); ?></td>
                                          <td><a href="<?php echo $assignment_dir . $file; ?>" download class="btn btn-warning"><span class="btn-label">
                                          <i class="fa fa-cloud-download-alt"></i></a></td>
                                      </tr>
                                  <?php endforeach; ?>
                              <?php else: ?>
                                  <tr>
                                      <td colspan="2" class="no-assignments">Asignments not found for your class.</td>
                                  </tr>
                              <?php endif; ?>
                          </tbody>
                      </table>
                  </div>
                </div>
              </div>
        
          </div>
        </div>
     
        <?php include('footer.php');?>
      </div>

      <!-- Custom template | don't include it in your project! -->
      <?php include('cust-color.php');?>
      <!-- End Custom template -->
    </div>
   <?php include('scripts.php');?>
  </body>
</html>
