<?php
// expiry.php
session_start();
include 'db_connection.php';

// Fetch the subscription expiry date
$stmtExp = $conn->prepare("SELECT expdate FROM sub WHERE id = 1 LIMIT 1");
$stmtExp->execute();
$stmtExp->bind_result($expdate);
$stmtExp->fetch();
$stmtExp->close();

// Parse d/m/y format into a DateTime object
$dateObj = DateTime::createFromFormat('d/m/Y', $expdate);

$expiryTimestamp = $dateObj->getTimestamp();
$currentTimestamp = time();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Expired</title>
    <?php include('head.php'); ?>

    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: 'Public Sans', sans-serif;
            color: #fff;
        }

        .card-expiry {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            max-width: 400px;
            width: 90%;
            padding: 2rem;
            text-align: center;
        }

        .card-expiry h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .card-expiry p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .btn-renew {
            background-color: #ff6b6b;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 50px;
            transition: background-color 0.3s ease;
        }

        .btn-renew:hover {
            background-color: #ff5252;
        }

        .form-group {
            margin-bottom: 0;
        }
    </style>
</head>

<body>

    <?php
    $activation_message = '';
    function decryptString($encrypted, $key, $iv)
    {
        // Ensure the key and iv lengths are correct
        $key = substr($key, 0, 24); // 24 bytes = 192 bits
        $iv = substr($iv, 0, 16);   // 16 bytes = 128 bits

        // Decode from Base64 then decrypt using AES-192-CBC
        $decoded = base64_decode($encrypted);
        $decrypted = openssl_decrypt($decoded, 'aes-192-cbc', $key, OPENSSL_RAW_DATA, $iv);

        return $decrypted;
    }

    // Set key and IV to match encryption
    $key = "abcdefghijklmnopqrstuvwx";
    $iv = "1234567890123456";

    $decryptedText = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $encryptedCode = $_POST['encrypted_license'] ?? '';
        if (!empty($encryptedCode)) {
            $decryptedText = decryptString($encryptedCode, $key, $iv);
            $licenseParts = explode('|', $decryptedText);

            if (count($licenseParts) === 3) {
                $expiry_date = $licenseParts[0];
                $capacity = $licenseParts[1];
                $package = $licenseParts[2];
            }
        }
    }
    ?>


    <div class="card-expiry">
        <?php echo $activation_message; ?>

        <h1>Subscription Expired</h1>
        <p>We're sorry, but your subscription has expired. To continue using EDUHIVE, please renew your subscription. Click <a href="https://www.dinolabstech.com/backend/login.php">Here</a> to purchase License
        </p>


        <form method="post" class="row g-3">

            <div class="col-md-12">
                <textarea placeholder="Paste Encrypted License....." class="form-control" id="encrypted_license"
                    name="encrypted_license" rows="2" cols="50"></textarea>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <input placeholder="Expiry Date" class="form-control" type="text" id="expiry_date" name="expiry_date"
                        readonly value="<?php echo isset($expiry_date) ? htmlspecialchars($expiry_date) : ''; ?>">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <input placeholder="Total Students" class="form-control" type="text" id="capacity" name="capacity"
                        readonly value="<?php echo isset($capacity) ? htmlspecialchars($capacity) : ''; ?>">
                </div>
            </div>

            <input type="hidden" id="package" name="package" readonly
                value="<?php echo isset($package) ? htmlspecialchars($package) : ''; ?>">


            <div class="col-md-12 d-flex">
                <button class="btn btn-renew" type="submit" name="activate">Renew Now!</button>
                <a href="https://www.dinolabstech.com/contact.php" class="btn btn-renew ms-auto">Contact Support</a>
            </div>


            <?php
            // If today is not past the expiry date, redirect to display the button
            if ($currentTimestamp < $expiryTimestamp) {

                // Check if the logged-in user has 'Administrator' or 'Superuser' roles.
                if ($_SESSION['role'] == 'Superuser') { ?>
                    <div class="col-12">
                        <a href="superdashboard.php" class="btn btn-renew">Continue to Application</a>
                    </div>
                <?php } else if ($_SESSION['role'] == 'Administrator') { ?>
                    <div class="col-12">
                        <a href="dashboard.php" class="btn btn-renew">Continue to Application</a>
                    </div>
                <?php } ?>
            <?php } ?>


        </form>
    </div>



    <?php

    if (isset($_POST['activate'])) {
        $encrypted_license = $_POST['encrypted_license'];

        if (!empty($encrypted_license)) {
            $decryptedText = decryptString($encrypted_license, $key, $iv);
            $licenseParts = explode('|', $decryptedText);

            if (count($licenseParts) === 3) {
                $expiry_date = $licenseParts[0];
                $capacity = $licenseParts[1];
                $package = $licenseParts[2];

                // Database connection details
                include('db_connection.php');

                $expiry_date = mysqli_real_escape_string($conn, $expiry_date);
                $capacity = mysqli_real_escape_string($conn, $capacity);
                $package = mysqli_real_escape_string($conn, $package);

                // Update the sub table using prepared statement
                $sql = "UPDATE sub SET expdate=? WHERE id=1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $expiry_date);

                if ($stmt->execute()) {
                    echo "<script>alert('License activated successfully!');
                             window.location.href = 'login.php';
                          </script>";
                    exit();
                } else {
                    echo "<script>alert('Error updating record: " . addslashes($stmt->error) . "');</script>";
                }


                $stmt->close();
                $conn->close();
            } else {
                echo "Invalid license format.";
            }
        } else {
            echo "Encrypted license is empty.";
        }
    }
    ?>

</body>

</html>