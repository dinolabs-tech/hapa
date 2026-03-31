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
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login</title>

    <!-- Fonts and Icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Custom Styles -->
    <style>
        body {
            background-image: url('assets/img/g4.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background-color: rgba(0, 0, 0, 0.7);
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1s ease-in;
            max-width: 300px;
            width: 100%;
        }

        .card-body {
            padding: 2.5rem;
        }

        .error {
            color: #dc3545;
            margin-bottom: 1.5rem;
            font-weight: 500;
            text-align: center;
        }

        .input-group-text {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            border-radius: 5px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: none;
            color: white;
        }

        .password-wrapper {
            position: relative;
            flex: 1;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            padding: 5px;
            font-size: 14px;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: white;
        }

        .password-wrapper .form-control {
            padding-right: 40px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 0.75rem;
            font-size: 1.1rem;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .login-title {
            color: white;
            font-family: 'Public Sans', sans-serif;
            font-weight: 600;
            font-size: 2rem;
        }

        .back-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: white;
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <main>
        <div class="container">
            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="login-title text-center mb-4">Login</h2>
                                    <?php if (!empty($login_error)): ?>
                                        <p class="error"><?php echo htmlspecialchars($login_error); ?></p>
                                    <?php endif; ?>
                                    <form method="post" action="login.php">
                                        <?php echo csrf_field(); ?>
                                        <div class="input-group mb-3">
                                            <label for="yourUsername" class="visually-hidden">Username</label>
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" name="username" class="form-control" id="yourUsername" placeholder="Enter Username..." required>
                                        </div>
                                        <div class="input-group mb-4">
                                            <label for="yourPassword" class="visually-hidden">Password</label>
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <div class="password-wrapper">
                                                <input type="password" name="password" class="form-control" id="yourPassword" placeholder="Password" required>
                                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                                    <i class="fas fa-eye" id="password-toggle-icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary w-100" type="submit">Login</button>
                                    </form>
                                    <div class="text-center mt-3">
                                        <a href="../index.php" class="back-link">Back to Homepage</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Core JS Files -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    
    <!-- Password Toggle Script -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('yourPassword');
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