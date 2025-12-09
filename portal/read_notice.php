<?php include('components/parent_logic.php');

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

include('db_connection.php');


// Fetch notices from the database
$sql = "SELECT * FROM notices ORDER BY created_at DESC";
$result = $conn->query($sql);

$notices = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $notices[] = $row;
  }
}

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

      <div class="container" id="content-container">
        <div class="page-inner">
          <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4 d-none d-lg-block">
            <div>
              <h3 class="fw-bold mb-3">Announcements</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Announcements</li>
              </ol>
            </div>

          </div>
          
          <div class="row">

            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <!-- <div class="card-title">My Ward(s) </div> -->
                    
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <div class="table-responsive">
                        <p>

                          <?php if (empty($notices)): ?>
                          <div class="alert alert-info">No notices to display.</div>
                        <?php else: ?>
                          <table id="basic-datatables" class="table table-striped">
                            <thead>
                              <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($notices as $notice): ?>
                                <tr>
                                  <td><?= htmlspecialchars($notice['title']) ?></td>
                                  <td><?= htmlspecialchars(date('Y-m-d H:i:s', strtotime($notice['created_at']))) ?></td>
                                  <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                      data-bs-target="#noticeModal<?= htmlspecialchars($notice['id']) ?>">View</button>
                                  </td>
                                </tr>

                                <!-- Modal -->
                                <div class="modal fade" id="noticeModal<?= htmlspecialchars($notice['id']) ?>" tabindex="-1"
                                  aria-labelledby="noticeModalLabel<?= htmlspecialchars($notice['id']) ?>"
                                  aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="noticeModalLabel<?= htmlspecialchars($notice['id']) ?>">
                                          <?= htmlspecialchars($notice['title']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                          aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                        <?= nl2br(htmlspecialchars($notice['message'])) ?>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                          data-bs-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        <?php endif; ?>
                        </p>
                      </div>
                    </div>

                  </div>
                </div>

              </div>
            </div>

          </div>
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