<?php
include 'db.php';
session_start();
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generateOTP() {
    return rand(100000, 999999); // Generate a 6-digit OTP
}

if (isset($_POST['submit'])) {
    $customer_email = $_POST['customer_email'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM customers WHERE customer_email = ?");
    $stmt->bind_param("s", $customer_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        if ($result->num_rows > 0) {
            // Email exists, generate OTP and send email
            $otp = generateOTP();
            $_SESSION['otp'] = $otp;
            $_SESSION['customer_email'] = $customer_email;

            // Send OTP to email
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'musictimessystem@gmail.com'; // SMTP username
            $mail->Password = 'kppuqpaokzlwtcww'; // SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('noreply@musictimessystem.com', 'MusicTIMeS');
            $mail->addAddress($customer_email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code for MusicTIMeS Forgot Password';
            $mail->Body = 'Your OTP code is <strong>' . $otp . '</strong>';

            if ($mail->send()) {
                header("Location: input_otp_change_password.php?customer_email=" . urlencode($customer_email));
                exit();
            } else {
                $error_message = "Mailer Error: " . $mail->ErrorInfo;
            }
        } else {
            // Invalid email, display error message
            $error_message = "Invalid email";
        }
    } else {
        // Query execution failed
        $error_message = "Database error: " . $stmt->error;
    }

    // Redirect to forgot_password.php with error message
    header("Location: forgot_password.php?error=" . urlencode($error_message));
    exit();
}

$stmt->close();
$conn->close();
?>
