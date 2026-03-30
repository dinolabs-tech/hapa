<?php
/**
 * EduHive CBT - Modern Single Page Application
 * 
 * Features:
 * - Loads all questions once via AJAX
 * - Client-side navigation (no page reloads)
 * - Auto-saving answers
 * - Client-side timer with server sync
 * - Single submission at end
 * - Works with same API as mobile app
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get subject from URL
$subject = $_GET['subid'] ?? null;
if (!$subject) {
    header("Location: students.php");
    exit();
}

// Include database for session info
include 'db_connection.php';

$userId = $_SESSION['user_id'];
$userName = $_SESSION['name'] ?? 'Student';
$userClass = $_SESSION['user_class'] ?? '';
$userArm = $_SESSION['user_arm'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBT Exam - <?php echo htmlspecialchars($subject); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --success-color: #1cc88a;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .exam-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .exam-header {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .timer-display {
            font-size: 2.5rem;
            font-weight: bold;
            font-family: 'Courier New', monospace;
        }
        
        .timer-warning {
            color: var(--warning-color);
            animation: pulse 1s infinite;
        }
        
        .timer-danger {
            color: var(--danger-color);
            animation: pulse 0.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .question-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .question-number {
            background: var(--primary-color);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
        }
        
        .question-text {
            font-size: 1.2rem;
            line-height: 1.6;
            color: #333;
        }
        
        .options-container {
            margin-top: 25px;
        }
        
        .option-item {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .option-item:hover {
            border-color: var(--primary-color);
            background: #f0f4ff;
        }
        
        .option-item.selected {
            border-color: var(--primary-color);
            background: #e8f0fe;
        }
        
        .option-item.selected .option-letter {
            background: var(--primary-color);
            color: white;
        }
        
        .option-letter {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #e9ecef;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            transition: all 0.3s ease;
        }
        
        .option-text {
            font-size: 1rem;
            color: #333;
        }
        
        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .question-nav {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .question-nav-item {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            background: #f8f9fa;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 3px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .question-nav-item:hover {
            border-color: var(--primary-color);
        }
        
        .question-nav-item.answered {
            background: var(--success-color);
            border-color: var(--success-color);
            color: white;
        }
        
        .question-nav-item.current {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }
        
        .progress-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .loading-content {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
        }
        
        .result-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .score-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--success-color), #2dce89);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
        }
        
        .hidden {
            display: none !important;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-content">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 mb-0" id="loadingText">Initializing exam...</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="exam-container" id="examContainer">
        <!-- Header -->
        <div class="exam-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-1"><i class="bi bi-book"></i> <?php echo htmlspecialchars($subject); ?></h4>
                    <p class="text-muted mb-0">
                        <small><?php echo htmlspecialchars($userName); ?> | <?php echo htmlspecialchars($userClass); ?> <?php echo htmlspecialchars($userArm); ?></small>
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex align-items-center justify-content-md-end">
                        <i class="bi bi-clock me-2 fs-4"></i>
                        <span id="timerDisplay" class="timer-display">00:00:00</span>
                    </div>
                    <small class="text-muted">Time Remaining</small>
                </div>
            </div>
        </div>

        <!-- Question Navigation -->
        <div class="question-nav mb-3">
            <div class="progress-info">
                <span><i class="bi bi-question-circle"></i> Questions: <strong id="answeredCount">0</strong> / <strong id="totalQuestions">0</strong></span>
                <span id="autoSaveStatus" class="text-muted"><i class="bi bi-cloud-check"></i> Auto-save enabled</span>
            </div>
            <div id="questionNav" class="d-flex flex-wrap"></div>
        </div>

        <!-- Question Card -->
        <div class="question-card" id="questionCard">
            <div id="questionContent">
                <!-- Question will be loaded here -->
            </div>
            
            <!-- Navigation Buttons -->
            <div class="navigation-buttons">
                <button id="prevBtn" class="btn btn-outline-secondary" onclick="previousQuestion()">
                    <i class="bi bi-chevron-left"></i> Previous
                </button>
                <button id="nextBtn" class="btn btn-primary" onclick="nextQuestion()">
                    Next <i class="bi bi-chevron-right"></i>
                </button>
                <button id="submitBtn" class="btn btn-success hidden" onclick="submitExam()">
                    <i class="bi bi-check-circle"></i> Submit Exam
                </button>
            </div>
        </div>

        <!-- Result Card (Hidden initially) -->
        <div class="result-card hidden" id="resultCard">
            <h3 class="mb-4">Exam Completed!</h3>
            <div class="score-circle" id="scoreCircle">0%</div>
            <h4 id="scoreText">Score: 0 / 0</h4>
            <p class="text-muted mt-3" id="resultMessage">Your exam has been submitted successfully.</p>
            <a href="students.php" class="btn btn-primary mt-3">
                <i class="bi bi-house"></i> Return to Dashboard
            </a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global State
        let examData = null;
        let questions = [];
        let currentQuestionIndex = 0;
        let answers = {}; // { questionId: 'A' | 'B' | 'C' | 'D' }
        let timeRemaining = 0;
        let timerInterval = null;
        let examSubmitted = false;
        let autoSaveInterval = null;

        // API Base URL
        const API_BASE = 'api/cbt';
        const USER_ID = '<?php echo $userId; ?>';
        const SUBJECT = '<?php echo addslashes($subject); ?>';

        // Initialize Exam
        async function initExam() {
            try {
                showLoading('Starting exam session...');
                
                // Start exam session
                const startResponse = await apiCall('start.php', 'POST', { subject: SUBJECT });
                
                if (startResponse.status !== 'success') {
                    throw new Error(startResponse.message || 'Failed to start exam');
                }
                
                examData = startResponse.data;
                timeRemaining = examData.time_limit_seconds;
                
                // Load questions
                showLoading('Loading questions...');
                const questionsResponse = await apiCall('questions.php?subject=' + encodeURIComponent(SUBJECT), 'GET');
                
                if (questionsResponse.status !== 'success') {
                    throw new Error(questionsResponse.message || 'Failed to load questions');
                }
                
                questions = questionsResponse.data.questions;
                
                if (questions.length === 0) {
                    throw new Error('No questions available for this subject');
                }
                
                // Initialize UI
                document.getElementById('totalQuestions').textContent = questions.length;
                renderQuestionNav();
                renderQuestion(0);
                startTimer();
                startAutoSave();
                
                hideLoading();
                
            } catch (error) {
                console.error('Exam initialization error:', error);
                showError(error.message);
            }
        }

        // API Call Helper
        async function apiCall(endpoint, method, data = null) {
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-User-Id': USER_ID
                }
            };
            
            if (data && method !== 'GET') {
                options.body = JSON.stringify(data);
            }
            
            const response = await fetch(API_BASE + '/' + endpoint, options);
            return await response.json();
        }

        // Render Question Navigation
        function renderQuestionNav() {
            const nav = document.getElementById('questionNav');
            nav.innerHTML = '';
            
            questions.forEach((q, index) => {
                const item = document.createElement('div');
                item.className = 'question-nav-item' + 
                    (index === currentQuestionIndex ? ' current' : '') + 
                    (answers[q.id] ? ' answered' : '');
                item.textContent = index + 1;
                item.onclick = () => goToQuestion(index);
                nav.appendChild(item);
            });
        }

        // Render Question
        function renderQuestion(index) {
            const question = questions[index];
            const container = document.getElementById('questionContent');
            const selectedAnswer = answers[question.id] || null;
            
            let optionsHtml = '';
            const optionLetters = ['A', 'B', 'C', 'D'];
            
            Object.entries(question.options).forEach(([letter, text]) => {
                const isSelected = selectedAnswer === letter;
                optionsHtml += `
                    <div class="option-item ${isSelected ? 'selected' : ''}" onclick="selectAnswer('${letter}')">
                        <span class="option-letter">${letter}</span>
                        <span class="option-text">${escapeHtml(text)}</span>
                    </div>
                `;
            });
            
            container.innerHTML = `
                <div class="d-flex align-items-start mb-4">
                    <span class="question-number">${index + 1}</span>
                    <div class="question-text mb-0">${question.question}</div>
                </div>
                <div class="options-container">
                    ${optionsHtml}
                </div>
            `;
            
            // Update navigation buttons
            document.getElementById('prevBtn').disabled = index === 0;
            
            if (index === questions.length - 1) {
                document.getElementById('nextBtn').classList.add('hidden');
                document.getElementById('submitBtn').classList.remove('hidden');
            } else {
                document.getElementById('nextBtn').classList.remove('hidden');
                document.getElementById('submitBtn').classList.add('hidden');
            }
            
            // Update navigation
            renderQuestionNav();
            updateAnsweredCount();
        }

        // Select Answer
        function selectAnswer(letter) {
            const question = questions[currentQuestionIndex];
            answers[question.id] = letter;
            renderQuestion(currentQuestionIndex);
        }

        // Navigation Functions
        function nextQuestion() {
            if (currentQuestionIndex < questions.length - 1) {
                currentQuestionIndex++;
                renderQuestion(currentQuestionIndex);
            }
        }

        function previousQuestion() {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                renderQuestion(currentQuestionIndex);
            }
        }

        function goToQuestion(index) {
            currentQuestionIndex = index;
            renderQuestion(index);
        }

        // Update Answered Count
        function updateAnsweredCount() {
            const count = Object.keys(answers).length;
            document.getElementById('answeredCount').textContent = count;
        }

        // Timer Functions
        function startTimer() {
            updateTimerDisplay();
            timerInterval = setInterval(() => {
                timeRemaining--;
                updateTimerDisplay();
                
                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    autoSubmit();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const hours = Math.floor(timeRemaining / 3600);
            const minutes = Math.floor((timeRemaining % 3600) / 60);
            const seconds = timeRemaining % 60;
            
            const display = document.getElementById('timerDisplay');
            display.textContent = 
                String(hours).padStart(2, '0') + ':' + 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
            
            // Color coding
            const totalSeconds = examData.time_limit_seconds;
            display.classList.remove('timer-warning', 'timer-danger');
            
            if (timeRemaining <= 60) {
                display.classList.add('timer-danger');
            } else if (timeRemaining <= totalSeconds / 4) {
                display.classList.add('timer-warning');
            }
        }

        // Auto-save (localStorage backup)
        function startAutoSave() {
            autoSaveInterval = setInterval(() => {
                localStorage.setItem('cbt_' + USER_ID + '_' + SUBJECT, JSON.stringify({
                    answers: answers,
                    timeRemaining: timeRemaining,
                    timestamp: Date.now()
                }));
            }, 5000);
        }

        // Submit Exam
        async function submitExam() {
            if (examSubmitted) return;
            
            if (!confirm('Are you sure you want to submit your exam? This action cannot be undone.')) {
                return;
            }
            
            await performSubmission();
        }

        async function autoSubmit() {
            if (examSubmitted) return;
            
            alert('Time is up! Your exam will be submitted automatically.');
            await performSubmission();
        }

        async function performSubmission() {
            try {
                examSubmitted = true;
                showLoading('Submitting exam...');
                
                clearInterval(timerInterval);
                clearInterval(autoSaveInterval);
                
                // Format answers for API
                const formattedAnswers = Object.entries(answers).map(([questionId, answer]) => ({
                    question_id: questionId,
                    answer: answer
                }));
                
                const response = await apiCall('submit.php', 'POST', {
                    subject: SUBJECT,
                    answers: formattedAnswers
                });
                
                if (response.status === 'success') {
                    showResult(response.data);
                    localStorage.removeItem('cbt_' + USER_ID + '_' + SUBJECT);
                } else {
                    throw new Error(response.message || 'Submission failed');
                }
                
            } catch (error) {
                console.error('Submission error:', error);
                hideLoading();
                alert('Error submitting exam: ' + error.message + '. Please try again.');
                examSubmitted = false;
            }
        }

        // Show Result
        function showResult(data) {
            hideLoading();
            
            document.getElementById('questionCard').classList.add('hidden');
            document.querySelector('.question-nav').classList.add('hidden');
            document.querySelector('.exam-header').classList.add('hidden');
            
            const resultCard = document.getElementById('resultCard');
            resultCard.classList.remove('hidden');
            
            const percentage = data.score || 0;
            document.getElementById('scoreCircle').textContent = percentage + '%';
            document.getElementById('scoreText').textContent = 
                `Score: ${data.correct_answers} / ${data.total_questions}`;
            
            // Color based on score
            const circle = document.getElementById('scoreCircle');
            if (percentage >= 70) {
                circle.style.background = 'linear-gradient(135deg, #1cc88a, #2dce89)';
            } else if (percentage >= 50) {
                circle.style.background = 'linear-gradient(135deg, #f6c23e, #fdd045)';
            } else {
                circle.style.background = 'linear-gradient(135deg, #e74a3b, #f05a4b)';
            }
        }

        // Utility Functions
        function showLoading(text) {
            document.getElementById('loadingText').textContent = text;
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        function showError(message) {
            hideLoading();
            document.getElementById('examContainer').innerHTML = `
                <div class="result-card">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                    <h3 class="mt-4">Error</h3>
                    <p class="text-muted">${escapeHtml(message)}</p>
                    <a href="students.php" class="btn btn-primary mt-3">
                        <i class="bi bi-house"></i> Return to Dashboard
                    </a>
                </div>
            `;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Prevent accidental page leave
        window.addEventListener('beforeunload', (e) => {
            if (!examSubmitted && Object.keys(answers).length > 0) {
                e.preventDefault();
                e.returnValue = 'You have unsaved answers. Are you sure you want to leave?';
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (examSubmitted) return;
            
            switch(e.key) {
                case 'ArrowLeft':
                    previousQuestion();
                    break;
                case 'ArrowRight':
                    nextQuestion();
                    break;
                case 'a':
                case 'A':
                    selectAnswer('A');
                    break;
                case 'b':
                case 'B':
                    selectAnswer('B');
                    break;
                case 'c':
                case 'C':
                    selectAnswer('C');
                    break;
                case 'd':
                case 'D':
                    selectAnswer('D');
                    break;
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initExam);
    </script>
</body>
</html>