<?php include('components/admin_logic.php'); ?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>

<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <?php include('adminnav.php'); ?>
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
            class="d-flex d-none d-lg-block align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
              <h3 class="fw-bold mb-3">Student's Result</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Results</li>
                <li class="breadcrumb-item active">Student Result</li>
              </ol>
            </div>

          </div>

          <!-- BULK DOWNLOAD ============================ -->
          <div class="row">
            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Bulk Download Results</div>
                  </div>
                </div>
                <div class="card-body">
                  <?php
                  // Fetch classes and arms for dropdowns
                  $classes = $conn->query("SELECT DISTINCT class FROM class ORDER BY class");
                  $arms = $conn->query("SELECT DISTINCT arm FROM arm ORDER BY arm");
                  ?>
                  <form action="bulk_result_download.php" method="get">
                    <div class="row">
                      <div class="col-md-4">
                        <label for="class_select">Class:</label>
                        <select name="class" id="class_select" class="form-control form-select" required>
                          <option value="" selected disabled>Select Class</option>
                          <?php while($c = $classes->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($c['class']); ?>"><?php echo htmlspecialchars($c['class']); ?></option>
                          <?php endwhile; ?>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label for="arm_select">Arm:</label>
                        <select name="arm" id="arm_select" class="form-control form-select" required>
                          <option value="" selected disabled>Select Arm</option>
                          <?php while($a = $arms->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($a['arm']); ?>"><?php echo htmlspecialchars($a['arm']); ?></option>
                          <?php endwhile; ?>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label>&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary rounded-5">Download PDF</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- INDIVIDUAL RESULTS ============================ -->
          <div class="row">

            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Check Result</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">
                    <?php
                    // Fetch enrolled students
                    $students = $conn->query("SELECT * FROM students WHERE status = 0 ORDER BY name");
                    ?>
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered" id="basic-datatables">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Arm</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php while ($row = $students->fetch_assoc()): ?>
                            <tr>
                              <td><?php echo htmlspecialchars($row['id']); ?></td>
                              <td><?php echo htmlspecialchars($row['name']); ?></td>
                              <td><?php echo htmlspecialchars($row['class']); ?></td>
                              <td><?php echo htmlspecialchars($row['arm']); ?></td>
                              <td>
                                <a href="admincheckresult.php?student_id=<?php echo urlencode($row['id']); ?>&session=<?php echo urlencode($current_session); ?>&term=<?php echo urlencode($current_term); ?>" class="btn btn-success btn-icon btn-round">
                                  <i class="fa fa-eye"></i>
                                </a>
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
          </div>


        </div>
      </div>

      </script>
      <?php include('footer.php'); ?>
    </div>

    <!-- Custom template | don't include it in your project! -->
    <?php include('cust-color.php'); ?>
    <!-- End Custom template -->
  </div>
  <?php include('scripts.php'); ?>
</body>

</html>
