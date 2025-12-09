<?php

include('components/parent_logic.php');

include('db_connection.php');


// PAYEMNT STATUS====================
// Fetch all students for display where id equals the logged in student id,
// ordering the most recent records (by date) at the top.
// Fetch the student_id associated with the logged-in parent
$parent_id = $_SESSION['user_id'];
$student_query = $conn->query("SELECT student_id FROM parent_student WHERE parent_id = '$parent_id'");

if ($student_query && $student_query->num_rows > 0) {
    $student_data = $student_query->fetch_assoc();
    $student_id = $student_data['student_id'];

    // Fetch payment records for the student
    $students = $conn->query("SELECT * FROM prebursary WHERE id = '" . $student_id . "' ORDER BY date DESC");
} else {
    // Handle the case where no student is associated with the parent
    $students = null; // Or display a message
}


// Fetch the logged-in student's name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name FROM parent WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>

<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <?php

    include('parentnav.php');

    ?>
    <!-- End Sidebar -->

    <div class="main-panel">
      <div class="main-header">
        <div class="main-header-logo">
          <!-- Logo Header -->
          <?php include('logo_header.php'); ?>
          <!-- End Logo Header -->
        </div>
        <!-- Navbar Header -->
        <?php include('navbar.php'); ?>
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
                  <h4 class="card-title">Payment Status</h4>   
                  </div>
                  <div class="card-body">
                  <div class="table-responsive table-hover">
                  <table id="basic-datatables">
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
                      <?php if ($students): ?>
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
                      <?php else: ?>
                        <tr>
                          <td colspan="12">No payment records found for this student.</td>
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
