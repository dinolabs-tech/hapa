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
                <h3 class="fw-bold mb-3">Payment Status</h3>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active">Home</li>
                  <li class="breadcrumb-item active">Bursary</li>
                  <li class="breadcrumb-item active">Payment Status</li>
              </ol>
              </div>
           
            </div>

         
              
          
            <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                  <h4 class="card-title">Payment Status | 
                    <?php echo htmlspecialchars($student_class); ?> <?php echo htmlspecialchars($student_arm); ?>
                    </h4>   
                  </div>
                  <div class="card-body">
                  <div class="table-responsive table-hover">
                  <table class="basic-datatables">
                    <thead>
                      <tr>
                        <th>Reg No</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Class</th>
                        <th>Arm</th>
                        <th>Term</th>
                        <th>Session</th>
                        <th>Depositor</th>
                        <th>Mobile</th>
                        <th>Amount</th>
                        <th>Narration</th>
                        <th>Remark</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php while ($row = $students->fetch_assoc()): ?>
                        <tr>
                          <td><?= $row['id'] ?></td>
                          <td><?= $row['name'] ?></td>
                          <td><?= $row['gender'] ?></td>
                          <td><?= $row['class'] ?></td>
                          <td><?= $row['arm'] ?></td>
                          <td><?= $row['term'] ?></td>
                          <td><?= $row['session'] ?></td>
                          <td><?= $row['depositor'] ?></td>
                          <td><?= $row['mobile'] ?></td>
                          <td><?= $row['amount'] ?></td>
                          <td><?= $row['narration'] ?></td>
                          <td>
                          <?php 
                              if ($row['status'] == 0) {
                                echo '<span class="badge bg-warning text-dark">Pending</span>';
                              } elseif ($row['status'] == 1) {
                                echo '<span class="badge bg-success">Approved</span>';
                              }
                            ?>
                          </td>
                        </tr>
                      <?php endwhile; ?>
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
