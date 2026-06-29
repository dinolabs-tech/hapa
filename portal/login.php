<?php

/**
 * EduHive - Login Page
 * Side-by-side layout matching desktop application design
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$login_error = '';

// Database connection (includes security helpers)
include 'db_connection.php';

// Create Database if it doesnt exist
include 'database_schema.php';

// Check if Superuser exists, if not create one
$check_superuser = $conn->prepare("SELECT id FROM login WHERE role = 'Superuser' LIMIT 1");
$check_superuser->execute();
$check_superuser->store_result();

if ($check_superuser->num_rows == 0) {
    // Superuser doesn't exist, create one
    $stmt_superuser = $conn->prepare("INSERT INTO login (staffname, username, password, role) VALUES (?, ?, ?, ?)");
    $staffname = "Dinolabs Superuser";
    $username = "Dinolabs";
    $password = hash_password("dinolabs");
    $role = "Superuser";
    $stmt_superuser->bind_param("ssss", $staffname, $username, $password, $role);
    $stmt_superuser->execute();
    $stmt_superuser->close();
}
$check_superuser->close();

// Existing login logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!csrf_verify()) {
        $login_error = "Invalid request. Please refresh the page and try again.";
    } else {
        // Regenerate CSRF token after verification
        csrf_regenerate();

        // Rate limiting for login attempts
        if (!rate_limit_check('login', 5, 300)) {
            $login_error = "Too many login attempts. Please try again in 5 minutes.";
        } else {
            // Get the posted credentials with proper validation
            $user = validate_string($_POST['username'], 1, 50);
            $pass = $_POST['password']; // Don't sanitize password before verification

            if ($user === false) {
                $login_error = "Invalid username format.";
            } else {
                // Prepare SQL for student login (selecting by ID only, then verify password in PHP)
                $stmt1 = $conn->prepare("SELECT id, name, password, class, arm, term, session, status, result FROM students WHERE id=?");
                $stmt1->bind_param("s", $user);
                $stmt1->execute();
                $stmt1->store_result();

                // Prepare SQL for other users (selecting by username only)
                $stmt2 = $conn->prepare("SELECT id, staffname, username, password, role FROM login WHERE username=?");
                $stmt2->bind_param("s", $user);
                $stmt2->execute();
                $stmt2->store_result();

                // Prepare SQL for Parents (selecting by username only)
                $stmt3 = $conn->prepare("SELECT id, name, mobile, email, username, password FROM parent WHERE username=?");
                $stmt3->bind_param("s", $user);
                $stmt3->execute();
                $stmt3->store_result();

                if ($stmt1->num_rows > 0) {
                    // Student login
                    $stmt1->bind_result($student_id, $student_name, $stored_password, $student_class, $student_arm, $student_term, $student_session, $student_status, $student_result);
                    $stmt1->fetch();

                    // Verify password (works with both hashed and plain text)
                    if (verify_password($pass, $stored_password)) {
                        // Store the retrieved data in session variables
                        $_SESSION['user_id'] = $student_id;
                        $_SESSION['name'] = $student_name;
                        $_SESSION['user_class'] = $student_class;
                        $_SESSION['user_arm'] = $student_arm;
                        $_SESSION['term'] = $student_term;
                        $_SESSION['student_session'] = $student_session;
                        $_SESSION['role'] = 'Student';
                        $_SESSION['access'] = $student_result;

                        // Close statements before redirect
                        $stmt1->close();
                        $stmt2->close();
                        $stmt3->close();

                        // Redirect based on student status
                        if ($student_status == 0) {
                            header("Location: students.php");
                        } elseif ($student_status == 1) {
                            $_SESSION['role'] = 'Alumni';
                            header("Location: alumni.php");
                        }
                        exit();
                    } else {
                        $login_error = "Invalid username or password.";
                    }
                } elseif ($stmt2->num_rows > 0) {
                    // Other users login
                    $stmt2->bind_result($staff_id, $staff_name, $staff_username, $stored_password, $staff_role);
                    $stmt2->fetch();

                    // Verify password (works with both hashed and plain text)
                    if (verify_password($pass, $stored_password)) {
                        // Set session variables
                        $_SESSION['user_id'] = $staff_id;
                        $_SESSION['role'] = $staff_role;
                        $_SESSION['staffname'] = $staff_name;

                        // Close statements before redirect
                        $stmt1->close();
                        $stmt2->close();
                        $stmt3->close();

                        // check license expiry
                        include('check_expiry.php');

                        // Redirect based on role
                        switch ($staff_role) {
                            case 'Ceo':
                            case 'Administrator':
                            case 'Teacher':
                            case 'Tuckshop':
                            case 'Admission':
                            case 'Bursary':
                                header("Location: dashboard.php");
                                break;
                            case 'Superuser':
                                header("Location: superdashboard.php");
                                break;
                        }
                        exit();
                    } else {
                        $login_error = "Invalid username or password.";
                    }
                } elseif ($stmt3->num_rows > 0) {
                    // Parent login
                    $stmt3->bind_result($parent_id, $parent_name, $parent_mobile, $parent_email, $parent_username, $stored_password);
                    $stmt3->fetch();

                    // Verify password (works with both hashed and plain text)
                    if (verify_password($pass, $stored_password)) {
                        // Store the retrieved data in session variables
                        $_SESSION['user_id'] = $parent_id;
                        $_SESSION['name'] = $parent_name;
                        $_SESSION['mobile'] = $parent_mobile;
                        $_SESSION['email'] = $parent_email;
                        $_SESSION['role'] = 'Parent';

                        // Close statements before redirect
                        $stmt1->close();
                        $stmt2->close();
                        $stmt3->close();

                        // Redirect to parent page
                        header("Location: parent_dashboard.php");
                        exit();
                    } else {
                        $login_error = "Invalid username or password.";
                    }
                } else {
                    $login_error = "Invalid username or password.";
                }

                // Close statements and connection
                $stmt1->close();
                $stmt2->close();
                $stmt3->close();
            }
        }
    }
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EduHive</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
            background: #0a1628;
        }

        /* Background image */
        .login-bg-image {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('assets/img/g4.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 0;
        }

        /* Dark overlay for background image */
        .login-bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(10, 22, 40, 0.85) 0%, rgba(15, 23, 42, 0.8) 20%, rgba(30, 58, 95, 0.75) 40%, rgba(102, 126, 234, 0.7) 60%, rgba(118, 75, 162, 0.75) 80%, rgba(15, 23, 42, 0.8) 100%);
            z-index: 1;
        }

        /* Animated gradient mesh background */
        .login-bg {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #0a1628 0%, #0F172A 20%, #1e3a5f 40%, #667eea 60%, #764ba2 80%, #0F172A 100%);
            background-size: 400% 400%;
            animation: gradientMesh 12s ease infinite;
            z-index: 2;
            opacity: 0.6;
        }

        @keyframes gradientMesh {
            0% {
                background-position: 0% 50%;
            }

            25% {
                background-position: 50% 0%;
            }

            50% {
                background-position: 100% 50%;
            }

            75% {
                background-position: 50% 100%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Dot grid overlay */
        .login-grid-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 3;
            pointer-events: none;
        }

        /* Wave overlay at bottom */
        .login-wave-overlay {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.03" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,112C1248,107,1344,117,1392,122.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            z-index: 4;
            pointer-events: none;
        }

        /* Glow orbs */
        .glow-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 5;
        }

        .glow-orb-1 {
            width: 400px;
            height: 400px;
            background: rgba(37, 99, 235, 0.15);
            top: -10%;
            right: -5%;
            animation: orbFloat 8s ease-in-out infinite;
        }

        .glow-orb-2 {
            width: 300px;
            height: 300px;
            background: rgba(212, 168, 83, 0.08);
            bottom: -10%;
            left: 30%;
            animation: orbFloat 10s ease-in-out infinite reverse;
        }

        .glow-orb-3 {
            width: 250px;
            height: 250px;
            background: rgba(14, 165, 233, 0.1);
            top: 40%;
            left: -5%;
            animation: orbFloat 6s ease-in-out infinite 2s;
        }

        @keyframes orbFloat {

            0%,
            100% {
                transform: translateY(0px) scale(1);
            }

            50% {
                transform: translateY(-40px) scale(1.1);
            }
        }

        /* Animated particles */
        .login-particle {
            position: fixed;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            z-index: 6;
            pointer-events: none;
            animation: particleFloat 10s infinite;
        }

        .login-particle:nth-child(1) {
            left: 5%;
            top: 20%;
            animation-delay: 0s;
        }

        .login-particle:nth-child(2) {
            left: 15%;
            top: 60%;
            animation-delay: 2s;
            width: 6px;
            height: 6px;
            background: rgba(212, 168, 83, 0.4);
        }

        .login-particle:nth-child(3) {
            left: 30%;
            top: 80%;
            animation-delay: 4s;
        }

        .login-particle:nth-child(4) {
            left: 45%;
            top: 15%;
            animation-delay: 1s;
            width: 3px;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
        }

        .login-particle:nth-child(5) {
            left: 60%;
            top: 70%;
            animation-delay: 3s;
            width: 5px;
            height: 5px;
            background: rgba(212, 168, 83, 0.3);
        }

        .login-particle:nth-child(6) {
            left: 75%;
            top: 25%;
            animation-delay: 5s;
        }

        .login-particle:nth-child(7) {
            left: 85%;
            top: 55%;
            animation-delay: 2.5s;
            width: 4px;
            height: 4px;
        }

        .login-particle:nth-child(8) {
            left: 92%;
            top: 85%;
            animation-delay: 6s;
            width: 3px;
            height: 3px;
            background: rgba(212, 168, 83, 0.5);
        }

        @keyframes particleFloat {
            0% {
                opacity: 0;
                transform: translateY(0) translateX(0) scale(0);
            }

            20% {
                opacity: 1;
                transform: translateY(-40px) translateX(10px) scale(1);
            }

            80% {
                opacity: 0.8;
                transform: translateY(-120px) translateX(-20px) scale(0.8);
            }

            100% {
                opacity: 0;
                transform: translateY(-180px) translateX(0) scale(0);
            }
        }

        .login-container {
            position: relative;
            z-index: 10;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: flex;
        }

        /* Left Side - Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, #1572e8, #6861ce);
            padding: 50px 40px;
            color: white;
            width: 350px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: 20px 0 0 20px;
        }

        .welcome-section .icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
        }

        .welcome-section h2 {
            font-size: 28px;
            margin-bottom: 15px;
            text-align: center;
        }

        .welcome-section p {
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.7;
            margin-bottom: 30px;
            text-align: center;
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .feature-list li span {
            width: 30px;
            height: 30px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        /* Right Side - Login Form */
        .form-section {
            flex: 1;
            padding: 25px 40px;
            overflow-y: auto;
            max-height: 90vh;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #1572e8, #6861ce);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }

        .logo-text {
            font-size: 20px;
            font-weight: 700;
            color: #333;
        }

        .form-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }

        .form-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 10px;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #1572e8;
            box-shadow: 0 0 0 3px rgba(21, 114, 232, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 5px;
            font-size: 14px;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: #666;
        }

        .password-wrapper .form-control {
            padding-right: 50px;
        }

        .btn {
            padding: 14px 30px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, rgba(21, 114, 232, 0.85), rgba(104, 97, 206, 0.85));
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 4px 20px rgba(21, 114, 232, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: 0.6s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(21, 114, 232, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, rgba(21, 114, 232, 0.95), rgba(104, 97, 206, 0.95));
            border-color: rgba(255, 255, 255, 0.3);
        }

        .btn-primary:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(21, 114, 232, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .alert-danger {
            background: #fee;
            color: #c0392b;
            border: 1px solid #fcc;
        }

        .form-links {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            font-size: 14px;
        }

        .form-links a {
            color: #1572e8;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .form-links a:hover {
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            color: #999;
            font-size: 13px;
        }

        .register-link {
            text-align: center;
            font-size: 14px;
            color: #666;
        }

        .register-link a {
            color: #1572e8;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 450px;
            }

            .welcome-section {
                width: 100%;
                border-radius: 20px 20px 0 0;
                padding: 30px 20px;
            }

            .welcome-section h2 {
                font-size: 24px;
            }

            .feature-list {
                display: none;
            }

            .form-section {
                padding: 15px 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Background Image -->
    <div class="login-bg-image"></div>
    <div class="login-bg-overlay"></div>

    <!-- Glassmorphism Background Elements -->
    <div class="login-bg"></div>
    <div class="login-grid-overlay"></div>
    <div class="login-wave-overlay"></div>

    <!-- Glow orbs -->
    <div class="glow-orb glow-orb-1"></div>
    <div class="glow-orb glow-orb-2"></div>
    <div class="glow-orb glow-orb-3"></div>

    <!-- Animated Particles -->
    <div class="login-particle"></div>
    <div class="login-particle"></div>
    <div class="login-particle"></div>
    <div class="login-particle"></div>
    <div class="login-particle"></div>
    <div class="login-particle"></div>
    <div class="login-particle"></div>
    <div class="login-particle"></div>
    <div class="login-container">
        <!-- Left Side - Welcome Section -->
        <div class="welcome-section">
            <div class="icon">
                <i class="fas fa-user-lock"></i>
            </div>
            <h2>Welcome Back!</h2>
            <p>Login to access your Portal dashboard.</p>

            <ul class="feature-list">
                <li>
                    <span><i class="fas fa-check"></i></span>
                    Access Student Records
                </li>
                <li>
                    <span><i class="fas fa-check"></i></span>
                    Generate Results
                </li>
                <li>
                    <span><i class="fas fa-check"></i></span>
                    Manage Fee Payments
                </li>
                <li>
                    <span><i class="fas fa-check"></i></span>
                    Track Attendance
                </li>
            </ul>
        </div>

        <!-- Right Side - Login Form -->
        <div class="form-section">
            <a href="index.php" class="logo">
                <div class="logo-icon"><img src="assets/img/logo.png" alt=""></div>
                <span class="logo-text">Hapa College</span>
            </a>

            <h1 class="form-title">Sign In</h1>
            <p class="form-subtitle">Enter your credentials to access your account</p>

            <?php if (!empty($login_error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($login_error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" id="username" class="form-control"
                            placeholder="Enter your username" required autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon password-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter your password" required autocomplete="current-password">
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="password-toggle-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div class="divider">
                <span><i class="fas fa-home"></i></span>
            </div>

            <div class="register-link">
                <a href="index.php">
                    </i> Homepage
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');

            if (passwordInput && toggleIcon) {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                toggleIcon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            }
        }
    </script>
</body>

</html>