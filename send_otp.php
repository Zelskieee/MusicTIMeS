<?php
include 'db.php';
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

function createOtp() {
    return rand(100000, 999999);
}

function saveOTP($conn, $customer_email, $otp) {
    $sql = "UPDATE customers SET otp=?, otp_sent_at=NOW() WHERE customer_email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $otp, $customer_email);
    $stmt->execute();
    return $stmt;
}

if (isset($_GET['customer_email'])) {
    $customer_email = $_GET['customer_email'];
    
    $otp = createOtp();
    $_SESSION['otp'] = $otp;

    $saveOtp = saveOTP($conn, $customer_email, $otp);
    if ($saveOtp) {
        $query = "SELECT customer_email FROM customers WHERE customer_email=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $customer_email);
        $stmt->execute();
        $stmt->bind_result($customer_email);
        $stmt->fetch();
        $stmt->close();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = 'musictimessystem@gmail.com';
            $mail->Password = 'kppuqpaokzlwtcww';
            $mail->setFrom('noreply@musictimessystem.com', 'MusicTIMeS');
            $mail->addAddress($customer_email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code for MusicTIMeS Account Registration';
            $mailContent = "<h1>MusicTIMeS</h1><p>Your OTP code is <strong>{$otp}</strong></p>";
            $mail->Body = $mailContent;

            if ($mail->send()) {
                header("Location: otp_input.php?customer_email=" . urlencode($customer_email));
                exit();
            } else {
                echo "Email could not be sent.";
            }
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Database error: Failed to save OTP.";
    }
} else {
    echo "Invalid request.";
}
?>
