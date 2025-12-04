<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <?php include('db_connection.php'); ?>
    <?php include('logo_header.php'); ?>
  </div>
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">
        <li class="nav-item">
          <a href="alumni.php">
            <i class="fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <?php if ($_SESSION['access'] == 0) { ?>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#" onclick="showPopup()">
              <i class="fas fa-chart-bar"></i>
              <p>Result</p>
            </a>
          </li>
        <?php } elseif ($_SESSION['access'] == 1) { ?>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#"
              onclick="alert('You are still owing. Please contact the Admin.'); return false;">
              <i class="fas fa-chart-bar"></i>
              <p>Result</p>
            </a>
          </li>
        <?php } ?>


        <!-- Overlay -->
        <div id="overlay" class="overlay" onclick="closePopup()"></div>

        <!-- Popup Form -->
        <div id="popup" class="popup">
          <span class="close" onclick="closePopup()">&times;</span>
          <form id="filterForm">
            <p></p>
            <p></p>
            <select id="session" name="session">
              <option value="">Select Session</option>
              <!-- Options will be added dynamically -->
            </select>

            <select id="term" name="term">
              <option value="">Select Term</option>
              <!-- Options will be added dynamically -->
            </select>

            <button type="button" onclick="checkResult()">Check Result</button>
          </form>
        </div>

        <script>
          // JavaScript to handle the popup
          function showPopup() {
            document.getElementById('popup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
            loadOptions(); // Load options for session and term
          }

          function closePopup() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
          }

          function loadOptions() {
            const sessionSelect = document.getElementById('session');
            const termSelect = document.getElementById('term');

            // Clear existing options
            sessionSelect.innerHTML = '<option value="">Select Session</option>';
            termSelect.innerHTML = '<option value="">Select Term</option>';

            // Add static term options
            const terms = ['1st Term', '2nd Term', '3rd Term'];
            terms.forEach(term => {
              const option = document.createElement('option');
              option.value = term;
              option.textContent = term;
              termSelect.appendChild(option);
            });

            // Fetch session values from the server using AJAX
            fetch('get_sessions.php')
              .then(response => response.json())
              .then(data => {
                data.sessions.forEach(session => {
                  const option = document.createElement('option');
                  option.value = session;
                  option.textContent = session;
                  sessionSelect.appendChild(option);
                });
              })
              .catch(error => {
                console.error('Error fetching sessions:', error);
              });
          }


          function checkResult() {
            const session = document.getElementById('session').value;
            const term = document.getElementById('term').value;

            if (session && term) {
              window.location.href = `checkresult.php?session=${encodeURIComponent(session)}&term=${encodeURIComponent(term)}`;
            } else {
              alert('Please select both session and term.');
            }
          }
        </script>




        <?php
        // Conditional display for "Transcript" link based on user's 'access' level.
        if ($_SESSION['access'] == 0) {
          // Assume student_id is stored in $_SESSION['student_id'] after login
          $loggedInStudentId = $_SESSION['user_id'] ?? null;
          $testimonial_count = 0; // Default to 0

          if ($loggedInStudentId) {
            $testimonial_check_sql = "SELECT COUNT(*) FROM testimonial WHERE student_id = ?";
            $stmt_testimonial = $conn->prepare($testimonial_check_sql);
            // Assuming student_id in testimonial table is VARCHAR, bind as string
            $stmt_testimonial->bind_param("s", $loggedInStudentId);
            $stmt_testimonial->execute();
            $stmt_testimonial->bind_result($testimonial_count);
            $stmt_testimonial->fetch();
            $stmt_testimonial->close();
          }
        ?>
          <li class="nav-item">
            <?php if ($testimonial_count > 0): ?>
              <a href="adminchecktestimonial.php?student_id=<?php echo htmlspecialchars($loggedInStudentId); ?>">
                <i class="fas fa-award"></i>
                <p>Testimonial </p>
                <span class="badge badge-success"> Available</span>
              </a>
            <?php else: ?>
              <a href="#" onclick="alert('Testimonial not available. Please contact the Admin.'); return false;">
                <i class="fas fa-award"></i>
                <p>Testimonial</p>
                <span class="badge badge-danger">Unavailable</span>
              </a>
            <?php endif; ?>
          </li>
          <li class="nav-item">
            <a href="transcript.php">
              <i class="fas fa-desktop"></i>
              <p>Transcript</p>
            </a>
          </li>
        <?php } elseif ($_SESSION['access'] == 1) { ?>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#"
              onclick="alert('You are still owing. Please contact the Admin.'); return false;">
              <i class="fas fa-chart-bar"></i>
              <p>Transcript</p>
            </a>
          </li>
        <?php } ?>


        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#threads">
            <i class="fas fa-comment-dots"></i>
            <p>Discussion Threads</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="threads">
            <ul class="nav nav-collapse">
              <li>
                <a href="threads.php">
                  <span class="sub-item">Threads</span>
                </a>
              </li>
              <li>
                <a href="create_thread.php">
                  <span class="sub-item">Create Thread</span>
                </a>
              </li>
            </ul>
          </div>
        </li>


      </ul>
    </div>
  </div>
</div>
<!-- End Sidebar -->