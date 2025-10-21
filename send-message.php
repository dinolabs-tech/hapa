<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// required files
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

session_start(); // Start session to carry messages across pages

if (isset($_POST["message"])) {
    $user_email = $_POST["email"];
    $user_name = $_POST["name"];
    $user_message = $_POST["message"];

    try {
        // ========== First: Send the user's message to YOU ==========
        $mailToAdmin = new PHPMailer(true);

        $mailToAdmin->isSMTP();
        $mailToAdmin->Host       = 'mail.hapacollege.com'; // <-- change this
        $mailToAdmin->SMTPAuth   = true;
        $mailToAdmin->Username   = 'admin@hapacollege.com'; // <-- change this
        $mailToAdmin->Password   = 'HapaCollege@1';     // <-- change this
        $mailToAdmin->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailToAdmin->Port       = 587;

        $mailToAdmin->setFrom('admin@hapacollege.com', 'Website Contact Form');
        $mailToAdmin->addAddress('hapacollege2013@yahoo.com'); // company email address, either gmail or webmail
        $mailToAdmin->addReplyTo($user_email, $user_name);

        $mailToAdmin->isHTML(true);
        $mailToAdmin->Subject = "New Contact Form Submission from $user_name";
        $mailToAdmin->Body = "
            <p><strong>Name:</strong> {$user_name}</p>
            <p><strong>Email:</strong> {$user_email}</p>
            <p><strong>Message:</strong><br>{$user_message}</p>
        ";
        $mailToAdmin->send();

        // ========== Second: Send confirmation to USER ==========
        $mailToUser = new PHPMailer(true);

        $mailToUser->isSMTP();
        $mailToUser->Host       = 'mail.hapacollege.com'; // <-- change this
        $mailToUser->SMTPAuth   = true;
        $mailToUser->Username   = 'admin@hapacollege.com'; // <-- change this
        $mailToUser->Password   = 'HapaCollege@1';     // <-- change this
        $mailToUser->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailToUser->Port       = 587;

        $mailToUser->setFrom('admin@hapacollege.com', 'Hapa College');
        $mailToUser->addAddress($user_email);
        $mailToUser->addReplyTo('admin@hapacollege.com', 'Hapa College');

        $mailToUser->isHTML(true);
        $mailToUser->Subject = 'Thank You for Reaching Out!';
        $mailToUser->Body = "
            <p>Dear $user_name,</p>
            <p>Thank you for reaching out to us! We appreciate the time you took to share your feedback and inquiries with Hapa College</p>
            <p>Our team is reviewing your message and will respond shortly.</p>
            <p>Best regards,<br>Hapa College</p>
        ";
        $mailToUser->send();

        // ========== Success ==========
        $_SESSION['success_message'] = "Message was sent successfully. Thank you for contacting us!";
        header("Location: contact.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error_message'] = "Message could not be sent. Error: {$mailToAdmin->ErrorInfo}";
        header("Location: contact.php");
        exit();
    }
}
?>
