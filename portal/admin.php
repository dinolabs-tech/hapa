<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session to maintain user state
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
include 'db_connection.php';

// Check connection to the database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Messages array
$admin_messages = [];

// Fetch current term and session
$current_term_result = $conn->query("SELECT cterm FROM currentterm WHERE id=1");
if (!$current_term_result) {
    die("Error fetching current term: " . $conn->error);
}
$current_term = $current_term_result->fetch_assoc()['cterm'];

$current_session_result = $conn->query("SELECT csession FROM currentsession WHERE id=1");
if (!$current_session_result) {
    die("Error fetching current session: " . $conn->error);
}
$current_session = $current_session_result->fetch_assoc()['csession'];

$next_term_result = $conn->query("SELECT Next FROM nextterm WHERE id=1");
if (!$next_term_result) {
    die("Error fetching Next Term: " . $conn->error);
}
$next_term = $next_term_result->fetch_assoc()['Next'];


// Fetch existing records
$arms = [];
$arm_result = $conn->query("SELECT * FROM arm");
while ($row = $arm_result->fetch_assoc()) {
    $arms[] = $row;
}

$classes = [];
$class_result = $conn->query("SELECT * FROM class");
while ($row = $class_result->fetch_assoc()) {
    $classes[] = $row;
}


// Handle delete requests
if (isset($_GET['delete'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM $table WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $admin_messages[] = ucfirst($table) . " record deleted successfully!";
          // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit(); // Stop further script execution
    } else {
        $admin_messages[] = "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
}

// Check if the form is submitted for arm
if (isset($_POST['arm_submit'])) {
    // Get the value from the form
    $arm = $_POST['arm'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO arm (arm) VALUES (?)");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind the parameter
    $stmt->bind_param("s", $arm);

    // Execute the statement
    if ($stmt->execute()) {
           // Redirect back to the same page to refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error inserting data: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
  
}

// Check if the form is submitted for class
if (isset($_POST['class_submit'])) {
    // Get the value from the form
    $class = $_POST['class'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO class (class) VALUES (?)");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind the parameter
    $stmt->bind_param("s", $class);

    // Execute the statement
    if ($stmt->execute()) {
          // Redirect back to the same page to refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error inserting data: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
  
}

// Check if the form is submitted for current term
if (isset($_POST['term_submit'])) {
  // Get the value from the form
  $term = $_POST['cterm'];

  // Prepare the SQL statement to update current term
  $stmt = $conn->prepare("UPDATE currentterm SET cterm = ? WHERE id = 1");

  // Check if the statement was prepared successfully
  if ($stmt === false) {
      die("Error preparing statement: " . $conn->error);
  }

  // Bind the parameter
  $stmt->bind_param("s", $term);

  // Execute the statement
  if ($stmt->execute()) {
      // Close the first statement
      $stmt->close();

      // Now update the students table with the new term
      $stmt2 = $conn->prepare("UPDATE students SET term = ? WHERE status = 0");
      if ($stmt2 === false) {
          die("Error preparing students update: " . $conn->error);
      }

      $stmt2->bind_param("s", $term);
      if ($stmt2->execute()) {
          $stmt2->close();
          // Redirect back to the same page to refresh
          header("Location: " . $_SERVER['PHP_SELF']);
          exit;
      } else {
          echo "Error updating students: " . $stmt2->error;
      }
  } else {
      echo "Error updating current term: " . $stmt->error;
  }
}


// Check if the form is submitted for academic session
if (isset($_POST['currentsession_submit'])) {
  // Get the value from the form
  $csession = $_POST['csession'];

  // Prepare the SQL statement to update the current session
  $stmt = $conn->prepare("UPDATE currentsession SET csession = ? WHERE id = 1");

  // Check if the statement was prepared successfully
  if ($stmt === false) {
      die("Error preparing statement: " . $conn->error);
  }

  // Bind the parameter
  $stmt->bind_param("s", $csession);

  // Execute the statement
  if ($stmt->execute()) {
      // Close the first statement
      $stmt->close();

      // Now update the students table with the new session
      $stmt2 = $conn->prepare("UPDATE students SET session = ? WHERE status = 0");
      if ($stmt2 === false) {
          die("Error preparing students update: " . $conn->error);
      }

      $stmt2->bind_param("s", $csession);
      if ($stmt2->execute()) {
          $stmt2->close();
          // Redirect back to the same page to refresh
          header("Location: " . $_SERVER['PHP_SELF']);
          exit;
      } else {
          echo "Error updating students session: " . $stmt2->error;
      }
  } else {
      echo "Error updating current session: " . $stmt->error;
  }
}




// Check if the form is submitted for Next Term
if (isset($_POST['nextterm_submit'])) {
  // Get the value from the form
  $nextterm_raw = $_POST['nextterm'];

  // Convert the date to dd-mm-yyyy format
  $dateObj = DateTime::createFromFormat('Y-m-d', $nextterm_raw);
  if ($dateObj !== false) {
      $nextterm = $dateObj->format('d-m-Y');
  } else {
      die("Invalid date format.");
  }

  // Prepare the SQL statement
  $stmt = $conn->prepare("UPDATE nextterm SET Next = ? WHERE id = 1");

  // Check if the statement was prepared successfully
  if ($stmt === false) {
      die("Error preparing statement: " . $conn->error);
  }

  // Bind the parameter
  $stmt->bind_param("s", $nextterm);

  // Execute the statement
  if ($stmt->execute()) {
      // Redirect back to the same page to refresh
      header("Location: " . $_SERVER['PHP_SELF']);
      exit;
  } else {
      echo "Error inserting data: " . $stmt->error;
  }

  // Close the statement
  $stmt->close();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['startNewSession']) && $_POST['startNewSession'] === 'true') {
  $errors = [];
  try {
      
      // Step 1: Update status for the highest class
      $stmt = $conn->prepare("UPDATE students SET status = ? WHERE class = ?");
      $status = 1;
      $class = 'SSS 3';
      $stmt->bind_param("is", $status, $class);
      $stmt->execute();
      $stmt->close();

       // Step 1: Update status for the JSS3 class
       $stmt1 = $conn->prepare("UPDATE students SET status = ? WHERE class = ?");
       $status = 1;
       $class = 'JSS 3';
       $stmt1->bind_param("is", $status, $class);
       $stmt1->execute();
       $stmt1->close();

      // Step 2: Promote students
      $promotionMapping = [
          'SSS 2' => 'SSS 3',
          'SSS 1' => 'SSS 2',
          // 'YEAR 9' => 'YEAR 10',
          'JSS 2' => 'JSS 3',
          'JSS 1' => 'JSS 2'
      ];

      foreach ($promotionMapping as $fromClass => $toClass) {
          $stmt = $conn->prepare("UPDATE students SET class = ? WHERE class = ?");
          $stmt->bind_param("ss", $toClass, $fromClass);
          $stmt->execute();
          if ($stmt->affected_rows === 0) {
              $errors[] = "No students found in $fromClass to promote.";
          }
          $stmt->close();
      }

      $conn->close();

      // Send JSON response
      header('Content-Type: application/json');
      if (empty($errors)) {
          echo json_encode(['success' => true, 'message' => 'Students promoted successfully!']);
      } else {
          echo json_encode(['success' => false, 'message' => implode("<br>", $errors)]);
      }
  } catch (Exception $e) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Error during promotion process: ' . $e->getMessage()]);
  }
  exit();
}



// Fetch the logged-in Staff name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();



// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php');?>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
     <?php include('adminnav.php');?>
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
                <h3 class="fw-bold mb-3">Admin</h3>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active">Home</li>
                  <li class="breadcrumb-item active">Admin</li>
              </ol>
              </div>
           
            </div>

            <!-- ================ STUDENT ENROLLED PANEL =================== -->
            <div class="row">
              <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-success card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Current Term</p>
                          
                            <form action="" method="POST">
                                  <select class="form-control form-select" id="cterm" name="cterm" required>
                                      <option value="1st Term">1st Term</option>
                                      <option value="2nd Term">2nd Term</option>
                                      <option value="3rd Term">3rd Term</option>
                                  </select>
                                  <br>
                                <input class="btn btn-warning" type="submit" name="term_submit" value="Add Term">
                            </form>
                              <br>
                            <h5 style="text-align:center;">Current Term:  <?php echo $current_term; ?></h5>
                      
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-secondary card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Current Session</p>
                         
                          <form action="" method="POST">
                        <input class="form-control" type="text" id="csession" name="csession" placeholder="Current Session (XXXX/XXXX)" required>
                        <br>
                        <input class="btn btn-success" type="submit" name="currentsession_submit" value="Add Current Session">
                    </form>
                    <br />
                    <h5 style="text-align:center;">Current Session: <?php echo $current_session; ?></h5>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-danger card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Next Term Begins</p>
                         
                          <form action="" method="POST">
                        <input class="form-control" type="date" id="nextterm" name="nextterm" placeholder="Next Term Begins" required>
                        <br>
                        <input class="btn btn-success" type="submit" name="nextterm_submit" value="Add Next Term">
                    </form>
                    <br />
                    <h5 style="text-align:center;">Next Term Begins: <?php echo $next_term; ?></h5>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>



              <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-primary card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Promote Students</p>
                          <br>
                              <!-- Button to trigger promotion action -->
                              <!-- <button type="button" class="btn btn-success" onclick="startNewSessionAndPromote()"><span class="btn-label">
                              <i class="fa fa-user-graduate"></i> Promote Students</button>
                              <br>
                              <div id="notification" style="display: none;color:black;"></div> -->
                             <!-- Button to trigger promotion action -->
                              <a href="promote.php" style="color:white;"><button type="button" class="btn btn-success"><span class="btn-label">
                              <i class="fa fa-user-graduate"></i> Promote Students</button></a>
                              <br>
                              <div id="notification" style="display: none;color:black;"></div>
                            
                           
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

           
             
            </div>

            <!-- ===================== ADMIN WIDGETS PANEL ENDS HERE ======================= -->


            <div class="row">
              <div class="col-md-6">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Academic Arm</div>
                    </div>
                  </div>
                  <div class="card-body">

                  <p>
                        <?php foreach ($admin_messages as $adminmessage): ?>
                            <p class="message"><?php echo $adminmessage; ?></p>
                        <?php endforeach; ?>

                        <!-- Insert Forms -->
                        <form action="" method="POST">
                            <input class="form-control " type="text" id="arm" name="arm" placeholder="Enter Academic Arm" required><br>
                            <input class="btn btn-success" type="submit" name="arm_submit" value="Add Arm">
                        </form>
                        <br />
                        <div class="table-responsive">
                        <table
                        id="multi-filter-select"
                        class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Arm</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($arms as $arm): ?>
                                    <tr>
                                        <td><?php echo $arm['arm']; ?></td>
                                        <td>
                                            <a href="?delete=true&table=arm&id=<?php echo $arm['id']; ?>" class="btn btn-danger"><span class="btn-label">
                                            <i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    </p>


                  
                  </div>
                </div>
              </div>
             

              <div class="col-md-6">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Academic Class</div>
                    </div>
                  </div>
                  <div class="card-body">


                          <form action="" method="POST">
                            <input class="form-control" type="text" id="class" name="class" placeholder="Enter Academic Class" required><br>
                            <input class="btn btn-success" type="submit" name="class_submit" value="Add Class">
                        </form>
                        <br />
                        <div class="table-responsive"> 
                        <table
                        id="multi-filter-select"
                        class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($classes as $class): ?>
                                    <tr>
                                        <td><?php echo $class['class']; ?></td>
                                        <td>
                                            <a href="?delete=true&table=class&id=<?php echo $class['id']; ?>" class="btn btn-danger"><span class="btn-label">
                                            <i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>

                  </div>
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


   <script>
function startNewSessionAndPromote() {
    fetch("", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "startNewSession=true"
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        const notificationDiv = document.getElementById("notification");
        notificationDiv.style.display = "block";
        
        if (data.success) {
            notificationDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        } else {
            notificationDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
    })
    .catch(error => {
        console.error("Error:", error);
        const notificationDiv = document.getElementById("notification");
        notificationDiv.style.display = "block";
        notificationDiv.innerHTML = `<div class="alert alert-danger">An error occurred: ${error.message}</div>`;
    });
}
</script>
  </body>
</html>
<?php include 'backup.php';?>