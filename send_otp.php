<?php
include 'db.php';
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

function createOtp() {
    return rand(100000, 999999);
}

function saveOTP($dbh, $customer_username, $otp) {
    $sql = "UPDATE customers SET otp=:otp WHERE customer_username=:customer_username";
    $query = $dbh->prepare($sql);
    $query->bindParam(':otp', $otp, PDO::PARAM_STR);
    $query->bindParam(':customer_username', $customer_username, PDO::PARAM_STR);
    $query->execute();
    return $query;
}

// Function to sanitize inputs
function validate_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
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
    $stmt = $dbh->prepare($check_query);
    if ($stmt) {
        $stmt->bindParam(1, $customer_username, PDO::PARAM_STR);
        $stmt->bindParam(2, $customer_email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $errors[] = "Username or email already exists.";
        }
        $stmt->close();
    } else {
        $errors[] = "Database error: Failed to prepare statement.";
    }

    // If there are no errors, proceed with sending OTP
    if (empty($errors)) {
        // Generate OTP
        $otp = createOtp();
        $_SESSION['otp'] = $otp;
        $_SESSION['customer_name'] = $customer_name;
        $_SESSION['customer_username'] = $customer_username;
        $_SESSION['customer_email'] = $customer_email;
        $_SESSION['customer_password'] = $customer_password;

        // Save OTP in the database
        $saveOtp = saveOTP($dbh, $customer_username, $otp);
        if ($saveOtp) {
            // Send OTP email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth = true;
                $mail->SMTPDebug = 2; // Enable verbose debug output
                $mail->Username = 'musictimessystem@gmail.com'; // SMTP username
                $mail->Password = 'Adelsembe2@'; // SMTP password
                $mail->setFrom('musictimessystem@gmail.com', 'MusicTIMeS'); 
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
        } else {
            $errors[] = "Database error: Failed to save OTP.";
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
