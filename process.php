<?php
session_start();
header('Content-Type: application/json');

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Helper function to generate OTP
function generateOTP($length = 6) {
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

// Function to send email using PHPMailer
function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    
    try {
        // SMTP server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Use your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'niharikasharma7909@gmail.com'; // Your SMTP username
        $mail->Password = 'hlnifixyucuqxfma'; // Your SMTP password or App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email settings
        $mail->setFrom('nihaarika7909@gmail.com', 'php mailer');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

// Action handling
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'send_otp') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
            exit;
        }

        $otp = generateOTP();
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        $subject = "Your OTP Code";
        $message = "<p>Your OTP code is: <strong>$otp</strong></p>";

        if (sendEmail($email, $subject, $message)) {
            echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully to your email.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP via email.']);
        }
        exit;
    }

    if ($action === 'verify_otp') {
        $otp = $_POST['otp'] ?? '';
        $email = $_POST['email'] ?? '';

        if (empty($otp) || empty($email)) {
            echo json_encode(['status' => 'error', 'message' => 'OTP and email are required']);
            exit;
        }

        if ($_SESSION['otp'] == $otp && $_SESSION['email'] == $email) {
            unset($_SESSION['otp'], $_SESSION['email']);
            echo json_encode(['status' => 'success', 'message' => 'OTP verified successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
        }
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
?>