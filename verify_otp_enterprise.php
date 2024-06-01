<?php
include 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST['otp'];
    $enterprise_email = $_POST['enterprise_email'];

    $query = "SELECT otp FROM enterprise WHERE enterprise_email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $enterprise_email);
    $stmt->execute();
    $stmt->bind_result($stored_otp);
    $stmt->fetch();
    $stmt->close();

    if ($otp == $stored_otp) {
        $update_query = "UPDATE enterprise SET is_verified=1, otp=NULL, verified_at=NOW() WHERE enterprise_email=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("s", $enterprise_email);
        if ($stmt->execute()) {
            // Send email notification using PHPMailer
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
                $mail->Subject = 'Enterprise Account Verified';
                $mailContent = "
                    <h2>Enterprise Account Verified</h2>
                    <p>Hello,</p>
                    <p>Your enterprise account has been successfully verified.</p>
                    <p>Thank you for using our service.</p>
                    <p>Best regards,<br><span style=\"font-weight: bold;\">MusicTIMeS</span></p>
                ";
                $mail->Body = $mailContent;

                $mail->send();
                echo "<script>alert('Account verified successfully and email notification sent.'); window.location.href = 'login_enterprise.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Account verified successfully, but email notification failed to send.'); window.location.href = 'login_enterprise.php';</script>";
            }
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "<script>alert('Invalid OTP. Please try again.'); window.location.href = 'otp_input_enterprise.php?enterprise_email=" . urlencode($enterprise_email) . "';</script>";
    }
} else {
    echo "Invalid request.";
}
?>
