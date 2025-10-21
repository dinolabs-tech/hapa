<?php
include('components/parent_logic.php');

include('db_connection.php');

?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>

<body>
  <div class="wrapper">
  

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
          <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
              <h3 class="fw-bold mb-3">Deposit</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Bursary</li>
                <li class="breadcrumb-item active">Deposit</li>
              </ol>
            </div>

          </div>




          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Deposit</h4>
              </div>
              <div class="card-body">

                <p>
                  <?php if (!empty($register_message)): ?>
                  <div class="message"><?php echo htmlspecialchars($register_message); ?></div>
                <?php endif; ?>

                <?php if (!empty($bulk_message)): ?>
                  <div class="message"><?php echo htmlspecialchars($bulk_message); ?></div>
                <?php endif; ?>

                <!-- Registration Form -->
                <form method="post" class="row g-3" enctype="multipart/form-data">

                  <div class="col-md-2">
                    <input type="text" id="id defaultInput" class="form-control form-control" name="id" required
                      onkeyup="fetchStudentDetails(this.value)" placeholder="Student ID">
                  </div>

                  <div class="col-md-6">
                    <input type="text" id="name" name="name" class="form-control form-control" readonly
                      placeholder="Students Name">
                  </div>

                  <div class="col-md-2">
                    <input type="text" id="class" class="form-control form-control" name="class" readonly
                      placeholder="Students Class">
                  </div>

                  <div class="col-md-2">
                    <input type="text" id="arm" class="form-control form-control" name="arm" readonly
                      placeholder="Students Arm">
                  </div>

                  <div class="col-md-2">
                    <input type="text" id="term" class="form-control form-control" name="term" readonly
                      placeholder="Current Term">
                  </div>

                  <div class="col-md-2">
                    <input type="text" id="gender" class="form-control form-control" name="gender" readonly
                      placeholder="Gender">
                  </div>

                  <div class="col-md-2">
                    <input type="text" id="session" class="form-control form-control" name="session" readonly
                      placeholder="Academic Session">
                  </div>

                  <div class="col-md-6">
                    <input type="text" id="depositor_name" name="depositor_name" class="form-control" required
                      placeholder="Depositor's Name"
                      value="<?php echo htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                  </div>


                  <div class="col-md-2">
                    <input type="text" id="depositor_mobile" name="depositor_mobile" class="form-control form-control"
                      required placeholder="Mobile" value="<?php echo htmlspecialchars($_SESSION['mobile'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                  </div>

                  <div class="col-md-2">
                    <input type="text" id="amount_deposited" name="amount_deposited" class="form-control form-control"
                      required placeholder="Amount">
                  </div>

                  <div class="col-md-2">
                    <input type="text" id="narration" name="narration" class="form-control form-control" required
                      placeholder="Narration">
                  </div>

                  <div class="col-md-6">
                    <input type="file" id="file_upload" name="file_upload" accept=".jpg,.jpeg"
                      class="form-control form-control" required placeholder="Payment Receipt">
                  </div>

                  <button type="submit" class="btn btn-primary"> Submit Deposit</button>

                  <button type="reset" class="btn btn-secondary">Reset</button>
                </form>


                </p>
              </div>

            </div>

          </div>
            <!-- Sidebar -->
    <?php

    include('parentnav.php');

    ?>
    <!-- End Sidebar -->
        </div>

        <?php include('footer.php'); ?>
      </div>

      <!-- Custom template | don't include it in your project! -->
      <?php include('cust-color.php'); ?>
      <!-- End Custom template -->
    </div>
    <?php include('scripts.php'); ?>
</body>

</html>
