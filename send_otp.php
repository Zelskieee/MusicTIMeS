<?php
include 'db.php';
require 'vendor/autoload.php'; // Include PHPMailer
include 'config.php'; // Include SMTP configuration

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Function to sanitize inputs
function validate_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $customer_name = validate_input($_POST['customer_name']);
    $customer_username = validate_input($_POST['customer_username']);
    $customer_email = filter_var(validate_input($_POST['customer_email']), FILTER_VALIDATE_EMAIL);
    $customer_password = $_POST['customer_password'];
    $confirm_password = $_POST['confirm_password'];

    // Initialize error message array
    $errors = [];

    // Check if the passwords match
    if ($customer_password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Validate email
    if (!$customer_email) {
        $errors[] = "Invalid email format.";
    }

    // Check if the username or email already exists
    $check_query = "SELECT * FROM customers WHERE customer_username = ? OR customer_email = ?";
    $stmt = $conn->prepare($check_query);
    if ($stmt) {
        $stmt->bind_param("ss", $customer_username, $customer_email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = "Username or email already exists.";
        }
        $stmt->close();
    } else {
        $errors[] = "Database error: Failed to prepare statement.";
    }

    // If there are no errors, proceed with sending OTP
    if (empty($errors)) {
        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['customer_name'] = $customer_name;
        $_SESSION['customer_username'] = $customer_username;
        $_SESSION['customer_email'] = $customer_email;
        $_SESSION['customer_password'] = $customer_password;

        // Send OTP email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST; // Set the SMTP server to send through
            $mail->Port = SMTP_PORT;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->SMTPAuth = true;
            $mail->SMTPDebug = 2; // Enable verbose debug output
            $mail->Username = SMTP_USERNAME; // SMTP username
            $mail->Password = SMTP_PASSWORD; // SMTP password
            $mail->setFrom(FROM_EMAIL, FROM_NAME); // Fake sender email
            $mail->addAddress($customer_email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code for MusicTIMeS Account Registration';
            $mailContent = "<h1>MusicTIMeS</h1><p>Your OTP code is {$otp}</p>";
            $mail->Body = $mailContent;

            if ($mail->send()) {
                echo "<script>showOTPForm();</script>";
            } else {
                $errors[] = "Email could not be sent.";
            }
        } catch (Exception $e) {
            $errors[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            error_log("Mailer Error: {$mail->ErrorInfo}");
        }
    }

    // If there are errors, redirect back to the registration form with error messages
    if (!empty($errors)) {
        $error_message = implode(" ", $errors);
        header("Location: register.php?error=" . urlencode($error_message));
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>
