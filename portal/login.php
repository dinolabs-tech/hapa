<?php
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
    $password = "dinolabs"; // Note: In production, you should hash this password
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
                // Prepare SQL for student login (selecting status)
                $stmt1 = $conn->prepare("SELECT id, name, password, class, arm, term, session, status, result FROM students WHERE id=? AND password=?");
                $stmt1->bind_param("ss", $user, $pass);
                $stmt1->execute();
                $stmt1->store_result();

                // Prepare SQL for other users
                $stmt2 = $conn->prepare("SELECT id, staffname, username, password, role FROM login WHERE username=? AND password=?");
                $stmt2->bind_param("ss", $user, $pass);
                $stmt2->execute();
                $stmt2->store_result();

                // Prepare SQL for Parents
                $stmt3 = $conn->prepare("SELECT id, name, mobile, email, username, password FROM parent WHERE username=? AND password=?");
                $stmt3->bind_param("ss", $user, $pass);
                $stmt3->execute();
                $stmt3->store_result();

                if ($stmt1->num_rows > 0) {
                    // Student login
                    $stmt1->bind_result($id, $name, $password, $class, $arm, $term, $enrolled_session, $status, $result);
                    $stmt1->fetch();

                    // Store the retrieved data in session variables
                    $_SESSION['user_id'] = $id;
                    $_SESSION['name'] = $name;
                    $_SESSION['user_class'] = $class;
                    $_SESSION['user_arm'] = $arm;
                    $_SESSION['term'] = $term;
                    $_SESSION['student_session'] = $enrolled_session;
                    $_SESSION['role'] = 'Student';
                    $_SESSION['access'] = $result;

                    // Close statements before redirect
                    $stmt1->close();
                    $stmt2->close();
                    $stmt3->close();

                    // Redirect based on student status
                    if ($status == 0) {
                        header("Location: students.php");
                    } elseif ($status == 1) {
                        $_SESSION['role'] = 'Alumni';
                        header("Location: alumni.php");
                    }
                    exit();
                } elseif ($stmt2->num_rows > 0) {
                    // Other users login
                    $stmt2->bind_result($id, $staffname, $username, $password, $role);
                    $stmt2->fetch();

                    // Set session variables
                    $_SESSION['user_id'] = $id;
                    $_SESSION['role'] = $role;
                    $_SESSION['staffname'] = $staffname;

                    // Close statements before redirect
                    $stmt1->close();
                    $stmt2->close();
                    $stmt3->close();

                    // check license expiry
                    include('check_expiry.php');

                    // Redirect based on role
                    switch ($role) {
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
                } elseif ($stmt3->num_rows > 0) {
                    // Parent login
                    $stmt3->bind_result($id, $parentname, $mobile, $email, $username, $password);
                    $stmt3->fetch();

                    // Store the retrieved data in session variables
                    $_SESSION['user_id'] = $id;
                    $_SESSION['name'] = $parentname;
                    $_SESSION['mobile'] = $mobile;
                    $_SESSION['email'] = $email;
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
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
             background-image: url('assets/img/g4.jpg');
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
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
            padding: 50px 40px;
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
            background: linear-gradient(135deg, #1572e8, #6861ce);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(21, 114, 232, 0.4);
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
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Welcome Section -->
        <div class="welcome-section">
            <div class="icon">
                <i class="fas fa-user-lock"></i>
            </div>
            <h2>Welcome Back!</h2>
            <p>Login to access your EduHive dashboard and manage your school efficiently.</p>
            
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
                <div class="logo-icon">E</div>
                <span class="logo-text">EduHive</span>
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
                <a href="../index.php">
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