<?php include('components/admin_logic.php');
include('components/birthday_logic.php');

// 5. Get current expiry date from Sub table where id = 1
$sql_expiry = "SELECT expdate FROM sub WHERE id = 1";
$result = $conn->query($sql_expiry);
$expirydate = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['expdate'] : 'N/A';

// convert expiry date to YYYY-MM-DD
list($day, $month, $year) = explode('/', $expirydate);
$expirydatefofrmatted = "$year-$month-$day";

$today = date("Y-m-d");

// convert both to datetime objects
$date1 = new datetime($today);
$date2 = new datetime($expirydatefofrmatted);

// calculate the diffrence
$diff = $date1->diff($date2);
$difference = $diff->days;

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
            <div class="col-md-4">
              <h3 class="fw-bold mb-3">Dashboard</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>

            </div>
            <?php if ($_SESSION['role'] == 'Administrator') {
              if ($difference <= '30') {
            ?>

                <div class="col-md-8 d-flex">
                  <marquee behavior="" direction="" class="text-danger fw-bold mt-2" style="border-left: solid red 5px; height:40px; border-bottom:solid 1px red; border-radius:10px;"><a href="expiry.php">
                      <h5 class="mt-1">Your license will expire in <?= $difference; ?> day(s) time, click on me to make payment</h5>
                    </a></marquee>
                  <span class="ms-auto card bg-danger p-3 text-white fw-bold col-4 col-md-2 text-center"><a href="expiry.php" class="text-white"><?= $expirydate ?></a></span>
                </div>
            <?php
              }
            } ?>
          </div>

          <!-- PERSONAL AI ============================ -->
          <div class="row">

            <div class="col-md-12">
              <div class="card card-primary card-round curves-shadow">
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




          <!-- ================ STUDENT ENROLLED PANEL =================== -->
          <div class="row">
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-success card-round">
                <div class="card-body skew-shadow">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-success bubble-shadow-small">
                        <i class="fas fa-user-graduate"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Students Enrolled</p>
                        <h4 class="card-title"><?php echo $total_students; ?></h4>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-secondary card-round">
                <div class="card-body curves-shadow">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-secondary bubble-shadow-small">
                        <i class="fas fa-graduation-cap"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Alumni Students</p>
                        <h4 class="card-title"><?php echo $total_alumni; ?></h4>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-primary card-round">
                <div class="card-body bubble-shadow">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-primary bubble-shadow-small">
                        <i class="fas fa-calendar-alt"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Current Term</p>
                        <h4 class="card-title"><?php echo $current_term; ?></h4>

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
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Current Session</p>
                        <h4 class="card-title"><?php echo $current_session; ?></h4>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- ===================== ADMIN WIDGETS PANEL ENDS HERE ======================= -->


          <div class="row">
            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">Total number of Students</div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="chart-container" style="min-height: 375px">
                    <canvas id="adminChart"></canvas>
                  </div>
                  <div id="myChartLegend"></div>
                </div>
              </div>
            </div>

          </div>


          <!-- ACADEMIC CALENDAR -->
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
                      <button id="prev-month" class="btn btn-warning"><span class="btn-label">
                          <i class="fa fa-fast-backward"></i> </button>
                      <h2 id="month-year"></h2>
                      <button id="next-month" class="btn btn-success"><span class="btn-label">
                          <i class="fa fa-fast-forward"></i></button>
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
<!-- ACADEMIC CALENDAR ENDS HERE -->

         
<?php
// Birthday celebrants logic
include 'db_connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentMonth = date('m');
$sql = "SELECT * FROM students WHERE MONTH(STR_TO_DATE(dob, '%d/%m/%y')) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentMonth);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!-- 🎂 Birthday Celebrants Section -->
<div class="row mt-5">
  <div class="col-md-12">
    <div class="card card-round">
      <div class="card-header">
        <div class="card-head-row">
          <h4 class="card-title text-danger">🎉🎂🎊 Happy Birthday to Our Stars in <?php echo date('F'); ?>! 🎊🎂🎉</h4>
        </div>
      </div>
      <div class="card-body">
        <div class="row justify-content-center g-4">
          <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
              <?php
                $dobObj = DateTime::createFromFormat('d/m/y', $row['dob']) ?: DateTime::createFromFormat('d/m/Y', $row['dob']);
                $formattedDob = $dobObj ? $dobObj->format('d F') : $row['dob'];
              ?>
              <div class="col-md-4 col-sm-6">
                <div class="card shadow border-3 border-warning text-center">
                  <div class="card-body">
                    <div class="fs-1 mb-2">🎉</div>
                    <h6 class="card-title text-primary"><?php echo htmlspecialchars($row['name']); ?></h6>
                    <p class="card-text text-muted">🎂 Born on: <?php echo $formattedDob; ?></p>
                    <p><?php echo htmlspecialchars($row['class']); ?> <?php echo htmlspecialchars($row['arm']); ?></p>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="alert alert-secondary text-center fs-5 mt-4">
                🥹 No birthday celebrants this month.<br>Stay tuned for next month's celebration!
              </div>
            </div>
          <?php endif; ?>
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




  <div class="chatbot-icon" onclick="toggleChatbot()">AI</div>


  <!-- Chatbot popup -->
  <div class="chatbot-popup" id="chatbotPopup">
    <div class="chatbot-header" id="chatbotHeader">
      Analyze Academic Performance
      <span class="close-button" onclick="closeChatbot()">X</span>
    </div>
    <div class="chatbot-body" id="chatbotBody">
      <!-- Chatbot messages will be displayed here -->
    </div>
    <div class="adminchatbot-input">
      <input type="text" id="studentIdInput" placeholder="Enter Student ID">
      <button onclick="getChatbotResponse()">Send</button>
    </div>
  </div>

  <script>
    var phrases = [
      "Analyze the student's academic performance",
      "Unlock the student's potential",
      "Provide personalized insights to the student",
      "Help the student improve their grades",
      "Assist the student in acing their exams"
    ];

    function getRandomPhrase() {
      return phrases[Math.floor(Math.random() * phrases.length)];
    }

    function setChatbotHeader() {
      document.getElementById("chatbotHeader").innerHTML = getRandomPhrase() + '<span class="close-button" onclick="closeChatbot()">X</span>';
    }

    function toggleChatbot() {
      var popup = document.getElementById("chatbotPopup");
      if (popup.style.display === "none") {
        popup.style.display = "flex";
      } else {
        popup.style.display = "none";
      }
    }

    function closeChatbot() {
      var popup = document.getElementById("chatbotPopup");
      popup.style.display = "none";
    }

    function getChatbotResponse() {
      var studentId = document.getElementById("studentIdInput").value;
      var chatbotBody = document.getElementById("chatbotBody");

      var xhr = new XMLHttpRequest();
      xhr.open("GET", "admin_chatbot.php?student_id=" + encodeURIComponent(studentId), true);

      xhr.onload = function () {
        console.log("Status:", xhr.status);
        console.log("Response:", xhr.responseText);
        if (xhr.status >= 200 && xhr.status < 300) {
          chatbotBody.innerHTML = xhr.responseText;
        } else {
          chatbotBody.innerHTML = "Error: " + xhr.statusText;
        }
      };

      xhr.onerror = function () {
        console.error("Request failed");
        chatbotBody.innerHTML = "Error: Network error.";
      };

      xhr.send();
    }

    window.onload = setChatbotHeader;
  </script>


</body>

</html>
<?php include 'backup.php'; ?>