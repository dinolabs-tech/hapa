<?php include('components/parent_logic.php');

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

include('db_connection.php');
$parentId = $_SESSION['user_id'];

// Fetch student_id string from parent table
$stmt = $conn->prepare("SELECT student_id FROM parent WHERE id = ?");
$stmt->bind_param("i", $parentId);
$stmt->execute();
$result = $stmt->get_result();
$students = [];

if ($row = $result->fetch_assoc()) {
  $studentIdString = $row['student_id'];

  if (!empty($studentIdString)) {
    // Convert comma-separated IDs into array
    $studentIds = array_map('trim', explode(',', $studentIdString));

    // Sanitize and quote each ID for SQL
    $safeIds = array_map(function ($id) use ($conn) {
      return "'" . $conn->real_escape_string($id) . "'";
    }, $studentIds);

    // Create IN clause
    $inClause = implode(",", $safeIds);

    // Run query to fetch student records
    $query = "SELECT id, name, mobile, email FROM students WHERE id IN ($inClause)";
    $studentResult = $conn->query($query);

    while ($student = $studentResult->fetch_assoc()) {
      $students[] = $student;
    }
  }
}
// expose count for the template
$totalStudents = count($students);

$parent_id = $_SESSION['user_id'];

// Fetch student IDs associated with the parent
$student_ids = [];

$stmt = $conn->prepare("SELECT student_id FROM parent_student WHERE parent_id = ?");
$stmt->bind_param("i", $parent_id); // Use "s" if parent_id is a string
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $student_ids[] = $row['student_id'];
  }
}

$stmt->close(); // Optional but good practice





// Fetch session options from mastersheet
$sessionOptions = [];
$sql = "SELECT DISTINCT csession FROM mastersheet ORDER BY csession ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $sessionOptions[] = $row['csession'];
  }
}

// Determine student to view
$selected_student_id = "";
$selected_student = null;
if (isset($_GET['student_id'])) {
  $selected_student_id = $_GET['student_id'];
  $_SESSION['selected_student_id'] = $selected_student_id;

  $sql = "SELECT * FROM students WHERE id = '$selected_student_id'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $selected_student = $result->fetch_assoc();
  }
}


// Fetch notices from the database
$sql = "SELECT * FROM notices ORDER BY created_at DESC LIMIT 1";
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
              <h3 class="fw-bold mb-3">Dashboard</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div>

          </div>

          <!-- PERSONAL AI ============================ -->
          <div class="row">

            <div class="col-md-12">
              <div class="card card-primary card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Personal AI</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <p id="message" data-message="<?php echo htmlspecialchars($message); ?>"></p>

                  </div>

                </div>
              </div>

            </div>
          </div>

          <div class="row">

            <div class="col-md-12">
              <div class="card card-success card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Latest Announcement</div>
                  </div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">

                    <p>
                      <?php if (empty($notices)): ?>
                      <div class="alert alert-info">No notices to display.</div>
                    <?php else: ?>
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Message</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($notices as $notice): ?>
                            <tr>
                              <td><?= htmlspecialchars($notice['title']) ?></td>
                              <td><?= htmlspecialchars(date('Y-m-d H:i:s', strtotime($notice['created_at']))) ?></td>
                              <td>
                                <?php
                                $words = explode(' ', strip_tags($notice['message']));
                                $excerpt = implode(' ', array_slice($words, 0, 10)) . (count($words) > 10 ? '...' : '');
                                ?>
                                <span><?= htmlspecialchars($excerpt) ?></span>
                               
                              </td>
                              <td> <a href="read_notice.php?id=<?= urlencode($notice['id']) ?>"
                                  class="btn btn-primary btn-sm">Read more</a></td>
                            </tr>


                          </tbody>
                        </table>

                        <!-- Modal -->
                        <div class="modal fade" id="noticeModal<?= htmlspecialchars($notice['id']) ?>" tabindex="-1"
                          aria-labelledby="noticeModalLabel<?= htmlspecialchars($notice['id']) ?>" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="noticeModalLabel<?= htmlspecialchars($notice['id']) ?>">
                                  <?= htmlspecialchars($notice['title']) ?>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <?= nl2br(htmlspecialchars($notice['message'])) ?>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

          <div class="row">

            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">My Ward(s) </div>
                    <div class="card-tools">
                      Total Ward(s) <strong><?= count($student_ids) ?></strong>
                    </div>

                  </div>
                </div>
                <div class="card-body">
                  <?php if (empty($student_ids)): ?>
                    <div class="alert alert-warning text-center">No students are associated with this parent account.
                    </div>
                  <?php elseif (count($student_ids) == 0): ?>
                    <?php
                    $only_student_id = $student_ids[0];
                    $sql = "SELECT * FROM students WHERE id = '$only_student_id'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                      $student = $result->fetch_assoc();
                      ?>
                      <div class="card shadow">
                        <div class="card-header bg-primary text-white">Student Information</div>
                        <div class="card-body">
                          <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
                          <p><strong>ID:</strong> <?= htmlspecialchars($student['id']) ?></p>
                          <p><strong>Class:</strong> <?= htmlspecialchars($student['class']) ?></p>
                          <p><strong>Arm:</strong> <?= htmlspecialchars($student['arm']) ?></p>
                          <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                            data-bs-target="#checkResultModal">Check Result</button>
                        </div>
                      </div>
                    <?php } else {
                      echo "<div class='alert alert-danger'>Student not found.</div>";
                    } ?>
                  <?php else: ?>
                    <div class="card shadow">
                      <div class="card-header bg-info text-white">Select Student</div>
                      <div class="card-body">
                        <form method="GET">
                          <div class="mb-3">
                            <label for="student_id" class="form-label">Choose Student:</label>
                            <select name="student_id" id="student_id" class="form-select" required>
                              <?php
                              foreach ($student_ids as $id) {
                                $sql = "SELECT name FROM students WHERE id = '$id'";
                                $result = $conn->query($sql);
                                if ($result && $result->num_rows > 0) {
                                  $student = $result->fetch_assoc();
                                  echo "<option value='" . htmlspecialchars($id) . "'" . ($id == $selected_student_id ? " selected" : "") . ">" . htmlspecialchars($student['name']) . "</option>";
                                }
                              }
                              ?>
                            </select>
                          </div>
                          <button type="submit" class="btn btn-primary">View</button>
                        </form>
                      </div>
                    </div>

                    <?php if ($selected_student): ?>
                      <div class="card shadow mt-4">
                        <div class="card-header bg-secondary text-white">Student Information</div>
                        <div class="card-body">
                          <p><strong>Name:</strong> <?= htmlspecialchars($selected_student['name']) ?></p>
                          <p><strong>ID:</strong> <?= htmlspecialchars($selected_student['id']) ?></p>
                          <p><strong>Class:</strong> <?= htmlspecialchars($selected_student['class']) ?></p>
                          <p><strong>Arm:</strong> <?= htmlspecialchars($selected_student['arm']) ?></p>

                          <?php
                          // Fetch tuck shop balance
                          $tuck_sql = "SELECT vbalance FROM tuck WHERE regno = ?";
                          $tuck_stmt = $conn->prepare($tuck_sql);
                          $tuck_stmt->bind_param("s", $selected_student['id']);
                          $tuck_stmt->execute();
                          $tuck_stmt->bind_result($vbalance);
                          $tuck_stmt->fetch();
                          $tuck_stmt->close();

                          // Fetch bursary details
                          $fees_sql = "SELECT ef.*, s.name as sname, s.id_no 
               FROM student_ef_list ef 
               INNER JOIN student s ON s.id = ef.student_id 
               WHERE s.id_no = '" . $selected_student['id'] . "'
               ORDER BY s.name ASC";
                          $fees_result = $conn->query($fees_sql);
                          $fees_records = [];
                          if ($fees_result->num_rows > 0) {
                            while ($fees_row = $fees_result->fetch_assoc()) {
                              // Calculate the total paid amount for this fee record
                              $paidQuery = "SELECT SUM(amount) as paid FROM payments WHERE ef_id = " . $fees_row['id'];
                              $paidResult = $conn->query($paidQuery);
                              $paidData = $paidResult->fetch_array();
                              $paid = isset($paidData['paid']) ? $paidData['paid'] : 0;
                              $balance = $fees_row['total_fee'] - $paid;

                              // Add calculated fields to the row array
                              $fees_row['paid'] = $paid;
                              $fees_row['balance'] = $balance;

                              // Store the record
                              $fees_records[] = $fees_row;
                            }
                          }

                          // Fetch CGPA for each term
                          $cgpa_data = [];
                          $terms = ['1st Term', '2nd Term', '3rd Term'];
                          foreach ($terms as $term) {
                            $cgpa_sql = "SELECT grade FROM mastersheet WHERE term = ? AND id = ? AND csession = ?";
                            $cgpa_stmt = $conn->prepare($cgpa_sql);
                            $cgpa_stmt->bind_param("sss", $term, $selected_student['id'], $selected_student['session']);
                            $cgpa_stmt->execute();
                            $cgpa_stmt->store_result();
                            $cgpa_stmt->bind_result($grade);

                            $total_grade_points = 0;
                            $credit_units = 0;
                            while ($cgpa_stmt->fetch()) {
                              $total_grade_points += gradeToPoint($grade);
                              $credit_units++;
                            }

                            // Calculate GPA and CGPA
                            $gpa = ($credit_units > 0) ? $total_grade_points / $credit_units : 0;
                            $cgpa_data[] = ['term' => $term, 'gpa' => round($gpa, 2)];

                            $cgpa_stmt->close();
                          }
                          ?>

                          <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                            data-bs-target="#checkResultModal">Check Result</button>
                        </div>
                      </div>

                      <div class="row mt-4">
                        <div class="col-sm-6 col-md-3">
                          <div class="card card-stats card-primary card-round">
                            <div class="card-body curves-shadow">
                              <div class="row align-items-center">
                                <div class="col-icon">
                                  <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                  </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                  <div class="numbers">
                                    <p class="card-category">Tuck Shop Balance</p>
                                    <h4 class="card-title">₦
                                      <?php echo (isset($vbalance) && is_numeric($vbalance) ? number_format((float) $vbalance, 2, '.', ',') : '0.00'); ?>
                                    </h4>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                          <div class="card card-stats card-info card-round">
                            <div class="card-body skew-shadow">
                              <div class="row align-items-center">
                                <div class="col-icon">
                                  <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-user-check"></i>
                                  </div>
                                </div>
                                <?php if (!empty($fees_records)): ?>
                                  <?php foreach ($fees_records as $fees_row): ?>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                      <div class="numbers">
                                        <p class="card-category">Bursary | Payable Fee</p>
                                        <h4 class="card-title">&#8358; <?php echo number_format($fees_row['total_fee'], 2); ?>
                                        </h4>
                                      </div>
                                    </div>
                                  <?php endforeach; ?>
                                <?php else: ?>
                                  <p>No fee records found for your account.</p>
                                <?php endif; ?>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                          <div class="card card-stats card-success card-round">
                            <div class="card-body bubble-shadow">
                              <div class="row align-items-center">
                                <div class="col-icon">
                                  <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="far fa-check-circle"></i>
                                  </div>
                                </div>
                                <?php if (!empty($fees_records)): ?>
                                  <?php foreach ($fees_records as $fees_row): ?>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                      <div class="numbers">
                                        <p class="card-category">Bursary | Paid</p>
                                        <h4 class="card-title">&#8358; <?php echo number_format($fees_row['paid'], 2); ?></h4>
                                      </div>
                                    </div>
                                  <?php endforeach; ?>
                                <?php else: ?>
                                  <p>No fee records found for your account.</p>
                                <?php endif; ?>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                          <div class="card card-stats card-secondary card-round">
                            <div class="card-body skew-shadow">
                              <div class="row align-items-center">
                                <div class="col-icon">
                                  <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="fas fa-luggage-cart"></i>
                                  </div>
                                </div>
                                <?php if (!empty($fees_records)): ?>
                                  <?php foreach ($fees_records as $fees_row): ?>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                      <div class="numbers">
                                        <p class="card-category">Bursary | Balance</p>
                                        <h4 class="card-title">&#8358; <?php echo number_format($fees_row['balance'], 2); ?>
                                        </h4>
                                      </div>
                                    </div>
                                  <?php endforeach; ?>
                                <?php else: ?>
                                  <p>No fee records found for your account.</p>
                                <?php endif; ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row mt-4">
                        <div class="col-md-4">
                          <div class="card card-primary card-round">
                            <div class="card-header">
                              <div class="card-head-row">
                                <div class="card-title">Academic CGPA</div>
                              </div>
                            </div>
                            <div class="card-body pb-0">
                              <div class="mb-4 mt-2">
                                <table class="display table table-striped table-hover">
                                  <thead>
                                    <tr>
                                      <th>Term</th>
                                      <th>GPA</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php foreach ($cgpa_data as $data): ?>
                                      <tr>
                                        <td><?php echo htmlspecialchars($data['term']); ?></td>
                                        <td><?php echo number_format($data['gpa'], 2); ?></td>
                                      </tr>
                                    <?php endforeach; ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php elseif ($selected_student_id): ?>
                      <div class='alert alert-danger mt-4'>Student not found.</div>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>

              </div>
            </div>


          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row card-tools-still-right">
                    <h4 class="card-title">Academic Calendar</h4>
                  </div>
                  <p class="card-category">
                  <div class="calendar-container">
                    <div class="header1">
                      <button id="prev-month" class="btn btn-warning btn-icon btn-round"><span class="btn-label">
                          <i class="fa fa-fast-backward"></i> </button>
                      <h2 id="month-year"></h2>
                      <button id="next-month" class="btn btn-success btn-icon btn-round"><span class="btn-label">
                          <i class="fa fa-fast-forward"></i> </button>
                    </div>
                    <div class="calendar calendar-body"></div>
                  </div>

                  <div id="event-modal">
                    <!-- remove single title/description elements -->
                    <div id="event-list"></div>
                    <button id="close-modal">Close</button>
                  </div>
                  </p>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="table-responsive table-hover table-sales">
                        <script>
                          const calendarBody = document.querySelector('.calendar-body');
                          const monthYear = document.getElementById('month-year');
                          const prevMonthBtn = document.getElementById('prev-month');
                          const nextMonthBtn = document.getElementById('next-month');
                          const modal = document.getElementById('event-modal');
                          const modalTitle = document.getElementById('event-title');
                          const modalDescription = document.getElementById('event-description');
                          const closeModal = document.getElementById('close-modal');

                          let currentDate = new Date();

                          // Fetch events from PHP (embedded in the page as JSON)
                          const events = <?php echo json_encode($events); ?>;

                          function renderCalendar(date) {
                            const year = date.getFullYear();
                            const month = date.getMonth();
                            const firstDay = new Date(year, month, 1).getDay();
                            const lastDate = new Date(year, month + 1, 0).getDate();

                            // Set header
                            const monthNames = [
                              'January', 'February', 'March', 'April', 'May', 'June',
                              'July', 'August', 'September', 'October', 'November', 'December'
                            ];
                            monthYear.textContent = `${monthNames[month]} ${year}`;

                            // Clear previous calendar days
                            const days = calendarBody.querySelectorAll('.day');
                            days.forEach(day => calendarBody.removeChild(day));

                            // Fill blank days before the first day of the month
                            for (let i = 0; i < firstDay; i++) {
                              const blankDay = document.createElement('div');
                              blankDay.classList.add('day', 'inactive');
                              calendarBody.appendChild(blankDay);
                            }

                            // Fill days of the month
                            for (let i = 1; i <= lastDate; i++) {
                              const day = document.createElement('div');
                              day.classList.add('day');
                              day.textContent = i;

                              // Format the date as MM/dd/yyyy
                              const eventKey = `${String(month + 1).padStart(2, '0')}/${String(i).padStart(2, '0')}/${year}`;

                              // Check if there's an event on this day
                              if (events[eventKey]) {
                                day.classList.add('event');
                                day.addEventListener('click', () => showEvent(eventKey));
                              }

                              calendarBody.appendChild(day);
                            }
                          }

                          // function showEvent(date) {


                          closeModal.addEventListener('click', () => {
                            modal.style.display = 'none';
                          });

                          prevMonthBtn.addEventListener('click', () => {
                            currentDate.setMonth(currentDate.getMonth() - 1);
                            renderCalendar(currentDate);
                          });

                          nextMonthBtn.addEventListener('click', () => {
                            currentDate.setMonth(currentDate.getMonth() + 1);
                            renderCalendar(currentDate);
                          });

                          function showEvent(date) {

                            const eventListContainer = document.getElementById('event-list');
                            // clear previous content
                            eventListContainer.innerHTML = '';

                            // retrieve array of events for that date
                            const dayEvents = events[date] || [];

                            // for each event, create a little card/title+desc
                            dayEvents.forEach(ev => {
                              const wrapper = document.createElement('div');
                              wrapper.style.marginBottom = '1rem';

                              const h3 = document.createElement('h3');
                              h3.textContent = ev.title;
                              h3.style.margin = '0 0 0.25rem 0';

                              const p = document.createElement('p');
                              p.textContent = ev.description;
                              p.style.margin = 0;

                              wrapper.appendChild(h3);
                              wrapper.appendChild(p);
                              eventListContainer.appendChild(wrapper);
                            });

                            // show modal
                            modal.style.display = 'flex';
                          }

                          // Initial render
                          renderCalendar(currentDate);
                        </script>
                      </div>
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
  <script>
    $(document).ready(function () {
      $('.student-card').click(function () {
        const studentId = $(this).data('student-id');

        $.ajax({
          url: 'components/student_container.php',
          method: 'GET',
          data: { id: studentId },
          success: function (response) {
            $('#content-container').html(response);
          },
          error: function () {
            alert('Something went wrong while loading student details.');
          }
        });
      });
    });
  </script>

  <!-- Bootstrap Modal -->
  <div class="modal fade" id="checkResultModal" tabindex="-1" aria-labelledby="checkResultModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="GET" action="parent_checkresult.php">
          <div class="modal-header">
            <h5 class="modal-title" id="checkResultModalLabel">Check Result</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="term" class="form-label">Term</label>
              <select class="form-select" id="term" name="term" required>
                <option value="1st Term">1st Term</option>
                <option value="2nd Term">2nd Term</option>
                <option value="3rd Term">3rd Term</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="session" class="form-label">Session</label>
              <select class="form-select" id="session" name="session" required>
                <?php foreach ($sessionOptions as $option): ?>
                  <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <input type="hidden" name="student_id" value="<?= htmlspecialchars($selected_student_id ?? '') ?>">
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Check Result</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</body>

</html>