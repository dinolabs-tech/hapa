<?php include('components/admin_logic.php');

// CLASS TEACHER'S COMMENT ==============================

// Fetch dropdown data
$classes = $conn->query("SELECT DISTINCT class FROM class");
$arms = $conn->query("SELECT DISTINCT arm FROM arm");
$terms = $conn->query("SELECT DISTINCT cterm FROM currentterm");
$sessions = $conn->query("SELECT DISTINCT csession FROM currentsession");

// Function to handle database queries
function executeClassQuery($conn, $sql)
{
  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Handle CSV upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csv_upload'])) {
  if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === 0) {
    $csvClass = $_POST['csv_class'];
    $csvArm = $_POST['csv_arm'];
    $csvTerm = $_POST['csv_term'];
    $csvSession = $_POST['csv_session'];

    $file = fopen($_FILES['csv_file']['tmp_name'], "r");

    fgetcsv($file); // Skip header row
    while (($row = fgetcsv($file)) !== FALSE) {
      // Skip empty lines
      if (count($row) < 16) {
        continue; // Not enough columns, skip this row
      }

      $id = trim($row[0]);
      $name = trim($row[1]);
      $comment = trim($row[2]);
      $schlopen = trim($row[3]);
      $dayspresent = trim($row[4]);
      $daysabsent = trim($row[5]);

      $attentiveness = trim($row[6]);
      $neatness = trim($row[7]);
      $politeness = trim($row[8]);
      $selfcontrol = trim($row[9]);
      $punctuality = trim($row[10]);
      $relationship = trim($row[11]);
      $handwriting = trim($row[12]);
      $music = trim($row[13]);
      $club = trim($row[14]);
      $sport = trim($row[15]);

      $sql = "INSERT INTO classcomments (id, name, comment, schlopen, dayspresent, daysabsent, attentiveness, neatness, politeness, selfcontrol, punctuality, relationship, handwriting, music, club, sport, class, arm, term, csession) 
                VALUES ('$id', '$name', '$comment', '$schlopen', '$dayspresent', '$daysabsent', '$attentiveness', '$neatness', '$politeness', '$selfcontrol', '$punctuality', '$relationship', '$handwriting', '$music', '$club', '$sport', '$csvClass', '$csvArm', '$csvTerm', '$csvSession')";
      executeClassQuery($conn, $sql);
    }
    fclose($file);
    echo "CSV file uploaded and records saved successfully!";
  }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Handle form submission for adding/updating records
  $hidden_id = $_POST['hidden_id'];
  $id = $_POST['id'];
  $name = $_POST['name'];
  $comment = $_POST['comment'];
  $schlopen = $_POST['schlopen'];
  $dayspresent = $_POST['dayspresent'];
  $daysabsent = $_POST['daysabsent'];
  $class = $_POST['class'];
  $arm = $_POST['arm'];
  $term = $_POST['term'];
  $session = $_POST['session'];

  $attentiveness = $_POST['attentiveness'];
  $neatness = $_POST['neatness'];
  $politeness = $_POST['politeness'];
  $selfcontrol = $_POST['selfcontrol'];
  $punctuality = $_POST['punctuality'];
  $relationship = $_POST['relationship'];
  $handwriting = $_POST['handwriting'];
  $music = $_POST['music'];
  $club = $_POST['club'];
  $sport = $_POST['sport'];  // <-- this is the one giving "undefined"


  if (empty($hidden_id)) {
    $sql = "INSERT INTO classcomments (id,
      name, comment, schlopen, dayspresent, daysabsent, 
      class, arm, term, csession, 
      attentiveness, neatness, politeness, selfcontrol, punctuality, 
      relationship, handwriting, music, club, sport
  ) 
  VALUES ('$id',
      '$name', '$comment', '$schlopen', '$dayspresent', '$daysabsent', 
      '$class', '$arm', '$term', '$session', 
      '$attentiveness', '$neatness', '$politeness', '$selfcontrol', '$punctuality', 
      '$relationship', '$handwriting', '$music', '$club', '$sport'
  )";

  } else {
    $sql = "UPDATE classcomments SET 
      id='$id',
      name='$name', 
      comment='$comment', 
      schlopen='$schlopen', 
      dayspresent='$dayspresent', 
      daysabsent='$daysabsent', 
      class='$class', 
      arm='$arm', 
      term='$term', 
      csession='$session',
      attentiveness='$attentiveness',
      neatness='$neatness',
      politeness='$politeness',
      selfcontrol='$selfcontrol',
      punctuality='$punctuality',
      relationship='$relationship',
      handwriting='$handwriting',
      music='$music',
      club='$club',
      sport='$sport'
  WHERE id='$hidden_id'";

  }


  $result = executeClassQuery($conn, $sql);
  echo $result === true ? "Record saved successfully!" : $result;
}

// Handle record deletion
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $sql = "DELETE FROM classcomments WHERE id='$id'";
  executeClassQuery($conn, $sql);
}

// Fetch current term
$currentTermResult = $conn->query("SELECT cterm FROM currentterm WHERE id = 1");
if ($currentTermResult && $row = $currentTermResult->fetch_assoc()) {
  $term = $row['cterm'];
}

// Fetch current session
$currentSessionResult = $conn->query("SELECT csession FROM currentsession WHERE id = 1");
if ($currentSessionResult && $row = $currentSessionResult->fetch_assoc()) {
  $session = $row['csession'];
}

// Fetch all records
$records = $conn->query("SELECT * FROM classcomments where term = '$term' AND csession = '$session' ORDER BY id DESC");

// Fetch current term
$currentTermResult = $conn->query("SELECT cterm FROM currentterm WHERE id = 1");
if ($currentTermResult && $row = $currentTermResult->fetch_assoc()) {
  $term = $row['cterm'];
}

// Fetch current session
$currentSessionResult = $conn->query("SELECT csession FROM currentsession WHERE id = 1");
if ($currentSessionResult && $row = $currentSessionResult->fetch_assoc()) {
  $session = $row['csession'];
}

// Fetch all records
$records = $conn->query("SELECT * FROM classcomments where term = '$term' AND csession = '$session' ORDER BY id DESC");


?>

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
          <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
              <h3 class="fw-bold mb-3">Class Teacher's Comments</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Results</li>
                <li class="breadcrumb-item active">Class Teacher's Comments</li>
              </ol>
            </div>

          </div>

          <!-- BULK UPLOAD ============================ -->
          <div class="row">

            <!-- Download result Template for the selected clas ============================ -->
            <div class="col-md-4">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Download Template</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <form method="POST" action="download_classteacher_template.php" id="downloadForm">
                    <div class="mb-4 mt-2">

                      <!-- CLASS Dropdown -->
                      <select name="d_class" id="d_class" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch classes again
                        $classes->data_seek(0);
                        while ($row = $classes->fetch_assoc()): ?>
                          <option value="<?php echo $row['class']; ?>"><?php echo $row['class']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <!-- ARM Dropdown -->
                      <select name="d_arm" id="d_arm" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch arms again
                        $arms->data_seek(0);
                        while ($row = $arms->fetch_assoc()): ?>
                          <option value="<?php echo $row['arm']; ?>"><?php echo $row['arm']; ?></option>
                        <?php endwhile; ?>
                      </select>

                      <!-- DOWNLOAD Button -->
                      <div class="text-end mt-3">
                        <button type="submit" name="bulk_upload" class="btn btn-primary">
                          <span class="btn-label">
                            <i class="fa fa-cloud-download-alt"></i>
                          </span>
                          Download Comment Template
                        </button>
                      </div>

                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Comments</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">
                    <p>

                    <form method="post">
                      <input type="hidden" name="hidden_id" id="hidden_id" class="form-control" placeholder="Hidden ID">

                      <input type="text" name="id" id="id" class="form-control" placeholder="ID" required>
                      <br>
                      <input type="text" name="name" id="name" required placeholder="Name" class="form-control">
                      <br>
                      <textarea name="comment" id="comment" required Placeholder="Comments"
                        class="form-control"></textarea>
                      <br>
                      <input type="number" name="schlopen" id="schlopen" required placeholder="Days School Opened"
                        class="form-control">
                      <br>
                      <input type="number" name="dayspresent" id="dayspresent" required placeholder="Days Present"
                        class="form-control">
                      <br>
                      <input type="number" name="daysabsent" id="daysabsent" required placeholder="Days Absent"
                        class="form-control">
                      <br>
                      <input type="text" name="attentiveness" id="attentiveness" placeholder="Attentiveness"
                        class="form-control" required>
                      <br>
                      <input type="text" name="neatness" id="neatness" placeholder="Neatness" class="form-control"
                        required>
                      <br>
                      <input type="text" name="politeness" id="politeness" placeholder="Politeness" class="form-control"
                        required>
                      <br>
                      <input type="text" name="selfcontrol" id="selfcontrol" placeholder="Self Control"
                        class="form-control" required>
                      <br>
                      <input type="text" name="punctuality" id="punctuality" placeholder="Punctuality"
                        class="form-control" required>
                      <br>
                      <input type="text" name="relationship" id="relationship" placeholder="Relationship"
                        class="form-control" required>
                      <br>
                      <input type="text" name="handwriting" id="handwriting" placeholder="Handwriting"
                        class="form-control" required>
                      <br>
                      <input type="text" name="music" id="music" placeholder="Music" class="form-control" required>
                      <br>
                      <input type="text" name="club" id="club" placeholder="Club" class="form-control" required>
                      <br>
                      <input type="text" name="sport" id="sport" placeholder="Sport" class="form-control" required>
                      <br>

                      <select class="form-control form-select" name="class" id="classname" required>
                        <option value="">Select Class</option>
                        <?php
                        $classes->data_seek(0); // rewind
                        while ($row = $classes->fetch_assoc()): ?>
                          <option value="<?php echo $row['class']; ?>" <?php if ($class == $row['class'])
                               echo 'selected'; ?>>
                            <?php echo $row['class']; ?>
                          </option>
                        <?php endwhile; ?>
                      </select>

                      <br>
                      <select class="form-control form-select" name="arm" id="arm" required>
                        <option value="">Select Arm</option>
                        <?php
                        $arms->data_seek(0); // rewind
                        while ($row = $arms->fetch_assoc()): ?>
                          <option value="<?php echo $row['arm']; ?>" <?php if ($arm == $row['arm'])
                               echo 'selected'; ?>>
                            <?php echo $row['arm']; ?>
                          </option>
                        <?php endwhile; ?>
                      </select>

                      <br>
                      <!-- TERM Dropdown -->
                      <select name="term" id="term" class="form-control form-select" required>
                        <?php
                        // Rewind and fetch terms
                        $terms->data_seek(0);
                        while ($row = $terms->fetch_assoc()): ?>
                          <option value="<?php echo $row['cterm']; ?>"><?php echo $row['cterm']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>

                      <!-- SESSION Dropdown -->
                      <select name="session" id="session" class="form-control form-select" required>
                        <?php
                        // Rewind and fetch sessions
                        $sessions->data_seek(0);
                        while ($row = $sessions->fetch_assoc()): ?>
                          <option value="<?php echo $row['csession']; ?>"><?php echo $row['csession']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>


                      <button type="submit" class="btn btn-success">
                        <span class="btn-label">
                          <i class="fa fa-save"></i>Save</button>
                      <button type="reset" class="btn btn-secondary">
                        <span class="btn-label">
                          <i class="fa fa-undo"></i>Reset</button>
                    </form>

                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- FILTER UPLOADED ============================ -->
            <div class="col-md-4">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Bulk Upload CSV</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">
                    <form method="post" enctype="multipart/form-data" style="margin-top: 30px;">
                      <input type="file" name="csv_file" id="csv_file" accept=".csv" class="form-control" required>
                      <br>
                      <select name="csv_class" id="csv_class" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch classes again
                        $classes->data_seek(0);
                        while ($row = $classes->fetch_assoc()): ?>
                          <option value="<?php echo $row['class']; ?>"><?php echo $row['class']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <select name="csv_arm" id="csv_arm" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch arms again
                        $arms->data_seek(0);
                        while ($row = $arms->fetch_assoc()): ?>
                          <option value="<?php echo $row['arm']; ?>"><?php echo $row['arm']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <select name="csv_term" id="csv_term" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch terms again
                        $terms->data_seek(0);
                        while ($row = $terms->fetch_assoc()): ?>
                          <option value="<?php echo $row['cterm']; ?>"><?php echo $row['cterm']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <select name="csv_session" id="csv_session" class="form-control form-select" required>
                        <?php
                        // Rewind to start to fetch sessions again
                        $sessions->data_seek(0);
                        while ($row = $sessions->fetch_assoc()): ?>
                          <option value="<?php echo $row['csession']; ?>"><?php echo $row['csession']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      <br>
                      <button type="submit" class="btn btn-primary" name="csv_upload">
                        <span class="btn-label">
                          <i class="fa fa-cloud-upload-alt"></i>Upload CSV</button>
                    </form>

                  </div>
                </div>
              </div>
            </div>

          </div>


          <div class="row">

            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Uploaded Comments</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <div class="table-responsive">
                      <!-- Display subjects -->
                      <table id="multi-filter-select" class="display table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Comment</th>
                            <th>School Open</th>
                            <th>Days Present</th>
                            <th>Days Absent</th>
                            <th>Attentiveness</th>
                            <th>Neatness</th>
                            <th>Politeness</th>
                            <th>Selfcontrol</th>
                            <th>Punctuality</th>
                            <th>Relationship</th>
                            <th>Handwriting</th>
                            <th>Music</th>
                            <th>Club</th>
                            <th>Sport</th>
                            <th>Class</th>
                            <th>Arm</th>
                            <th>Term</th>
                            <th>Session</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php while ($row = $records->fetch_assoc()): ?>
                            <tr>
                              <td><?php echo $row['id']; ?></td>
                              <td><?php echo $row['name']; ?></td>
                              <td><?php echo $row['comment']; ?></td>
                              <td><?php echo $row['schlopen']; ?></td>
                              <td><?php echo $row['dayspresent']; ?></td>
                              <td><?php echo $row['daysabsent']; ?></td>
                              <td><?php echo $row['attentiveness']; ?></td>
                               <td><?php echo $row['neatness']; ?></td>
                              <td><?php echo $row['politeness']; ?></td>
                              <td><?php echo $row['selfcontrol']; ?></td>
                              <td><?php echo $row['punctuality']; ?></td>
                              <td><?php echo $row['relationship']; ?></td>
                              <td><?php echo $row['handwriting']; ?></td>
                              <td><?php echo $row['music']; ?></td>
                              <td><?php echo $row['club']; ?></td>
                              <td><?php echo $row['sport']; ?></td>
                              <td><?php echo $row['class']; ?></td>
                              <td><?php echo $row['arm']; ?></td>
                              <td><?php echo $row['term']; ?></td>
                              <td><?php echo $row['csession']; ?></td>
                              <td>
                                <a href="javascript:void(0);"
                                  onclick="editClassCommentRecord('<?php echo $row['id']; ?>', '<?php echo $row['name']; ?>', '<?php echo $row['comment']; ?>', '<?php echo $row['schlopen']; ?>', '<?php echo $row['dayspresent']; ?>', '<?php echo $row['daysabsent']; ?>', '<?php echo $row['attentiveness']; ?>', '<?php echo $row['neatness']; ?>', '<?php echo $row['politeness']; ?>', '<?php echo $row['selfcontrol']; ?>', '<?php echo $row['punctuality']; ?>', '<?php echo $row['relationship']; ?>', '<?php echo $row['handwriting']; ?>', '<?php echo $row['music']; ?>', '<?php echo $row['club']; ?>', '<?php echo $row['sport']; ?>', '<?php echo $row['class']; ?>', '<?php echo $row['arm']; ?>', '<?php echo $row['term']; ?>', '<?php echo $row['csession']; ?>')"
                                  class="btn btn-warning"><span class="btn-label">
                                    <i class="fa fa-edit"></i></a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger"><span
                                    class="btn-label">
                                    <i class="fa fa-trash"></i></a>
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