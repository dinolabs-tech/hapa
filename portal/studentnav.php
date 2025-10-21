<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <?php include('logo_header.php'); ?>
  </div>
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">
        <li class="nav-item">
          <a href="students.php">
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
              onclick="alert('Your result is being reviewed and is currently unavailable. Please get in touch with the school authority to resolve any outstanding issues.'); return false;">
              <i class="fas fa-chart-bar"></i>
              <p>Result</p>
            </a>
          </li>
        <?php } ?>



        <li class="nav-item">
          <a href="viewassignment.php">
            <i class="fas fa-tasks"></i>
            <p>Assignments</p>
          </a>

        </li>
        <li class="nav-item">
          <a href="viewnotes.php">
            <i class="fas fa-pencil-alt"></i>
            <p>Notes</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="viewcurriculum.php">
            <i class="fas fa-chalkboard-teacher"></i>
            <p>Curriculum</p>
          </a>
        </li>

        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#test">
            <i class="fas fa-laptop"></i>
            <p>CBT</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="test">
            <ul class="nav nav-collapse">
              <li>
                <a href="sublist.php">
                  <span class="sub-item">Take Test</span>
                </a>
              </li>
              <li>
                <!-- <a href="result.php"> -->
                <a href="#" data-bs-toggle="collapse" onclick="showcbtPopup()">
                  <span class="sub-item">CBT Result</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#bursary">
            <i class="fas fa-hand-holding-usd"></i>
            <p>Bursary</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="bursary">
            <ul class="nav nav-collapse">
              <li>
                <a href="bpayment.php">
                  <span class="sub-item">Deposit</span>
                </a>
              </li>
              <li>
                <a href="paymentstatus.php">
                  <span class="sub-item">Payment Status</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Result Popup Overlay -->
        <div id="overlay" class="overlay" onclick="closePopup()"></div>

        <!-- Popup Form -->
        <div id="popup" class="popup">
          <span class="close" onclick="closePopup()">&times;</span>
          <form id="filterForm">
            <p></p>
            <p></p>
            <select id="session" name="session">
              <option value="" selected disabled>Select Session</option>
              <!-- Options will be added dynamically -->
            </select>

            <select id="term" name="term">
              <option value="" selected disabled>Select Term</option>
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



        <!-- CBT result popup overlay -->
        <div id="cbt_overlay" class="overlay" onclick="closecbtPopup()"></div>

        <!-- Popup Form -->
        <div id="cbt_popup" class="popup">
          <span class="close" onclick="closecbtPopup()">&times;</span>
          <form id="filtercbtForm">
            <p></p>
            <p></p>
            <select id="cbt_session" name="cbt_session">
              <option value="" selected disabled>Select Session</option>
              <!-- Options will be added dynamically -->
            </select>

            <select id="cbt_term" name="cbt_term">
              <option value="" selected disabled>Select Term</option>
              <!-- Options will be added dynamically -->
            </select>

            <button type="button" onclick="checkcbtResult()">Check CBT Result</button>
          </form>
        </div>

        <script>
          // JavaScript to handle the CBT popup
          function showcbtPopup() {
            document.getElementById('cbt_popup').style.display = 'block';
            document.getElementById('cbt_overlay').style.display = 'block';
            loadCbtOptions(); // Load options for session and term
          }

          function closecbtPopup() {
            document.getElementById('cbt_popup').style.display = 'none';
            document.getElementById('cbt_overlay').style.display = 'none';
          }

          function loadCbtOptions() {
            const sessionSelect = document.getElementById('cbt_session');
            const termSelect = document.getElementById('cbt_term');

            // Clear existing options
            sessionSelect.innerHTML = '<option value="" selected disabled>Select Session</option>';
            termSelect.innerHTML = '<option value="" selected disabled>Select Term</option>';

            // Fetch session and term values from the server using AJAX
            fetch('get_cbt_sessions.php')
              .then(response => response.json())
              .then(data => {
                // Assuming data.sessions and data.terms are arrays
                data.sessions.forEach(session => {
                  const option = document.createElement('option');
                  option.value = session;
                  option.textContent = session;
                  sessionSelect.appendChild(option);
                });
                data.terms.forEach(term => {
                  const option = document.createElement('option');
                  option.value = term;
                  option.textContent = term;
                  termSelect.appendChild(option);
                });
              })
              .catch(error => {
                console.error('Error fetching CBT sessions and terms:', error);
              });
          }


          function checkcbtResult() {
            const session = document.getElementById('cbt_session').value;
            const term = document.getElementById('cbt_term').value;

            if (session && term) {
              window.location.href = `result.php?session=${encodeURIComponent(session)}&term=${encodeURIComponent(term)}&cbt=true`;
            } else {
              alert('Please select both session and term.');
            }
          }
        </script>

        <li class="nav-item">
          <a href="viewtimetable.php">
            <i class="fas fa-th-list"></i>
            <p>Class Schedule</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="idcard.php">
            <i class="fas fa-id-card"></i>
            <p>Download ID Card</p>
          </a>
        </li>



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
