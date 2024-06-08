<?php
include 'db.php';
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

function createOtp() {
    return rand(100000, 999999);
}

function saveOTP($conn, $enterprise_email, $otp) {
    $sql = "UPDATE enterprise SET otp=?, otp_sent_at=NOW() WHERE enterprise_email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $otp, $enterprise_email);
    $stmt->execute();
    return $stmt;
}

if (isset($_GET['enterprise_email'])) {
    $enterprise_email = $_GET['enterprise_email'];
    
    $otp = createOtp();
    $_SESSION['otp'] = $otp;

    $saveOtp = saveOTP($conn, $enterprise_email, $otp);
    if ($saveOtp) {
        $query = "SELECT enterprise_email FROM enterprise WHERE enterprise_email=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $enterprise_email);
        $stmt->execute();
        $stmt->bind_result($enterprise_email);
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
            $mail->addAddress($enterprise_email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code for MusicTIMeS Enterprise Account Registration';
            $mailContent = "<h1>MusicTIMeS</h1><p>Your OTP code is <strong>{$otp}</strong></p>";
            $mail->Body = $mailContent;

            if ($mail->send()) {
                header("Location: otp_input_enterprise.php?enterprise_email=" . urlencode($enterprise_email));
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
