<?php
include 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST['otp'];
    $customer_email = $_POST['customer_email'];

    $query = "SELECT otp FROM customers WHERE customer_email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $customer_email);
    $stmt->execute();
    $stmt->bind_result($stored_otp);
    $stmt->fetch();
    $stmt->close();

    if ($otp == $stored_otp) {
        $update_query = "UPDATE customers SET is_verified=1, otp=NULL, verified_at=NOW() WHERE customer_email=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("s", $customer_email);
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
                $mail->addAddress($customer_email);

                $mail->isHTML(true);
                $mail->Subject = 'Account Verified';
                $mailContent = "
                    <h2>Account Verified</h2>
                    <p>Hello,</p>
                    <p>Your account has been successfully verified.</p>
                    <p>Thank you for using our service.</p>
                    <p>Best regards,<br><span style=\"font-weight: bold;\">MusicTIMeS</span></p>
                ";
                $mail->Body = $mailContent;

                $mail->send();
                echo "<script>alert('Account verified successfully and email notification sent.'); window.location.href = 'index.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Account verified successfully, but email notification failed to send.'); window.location.href = 'index.php';</script>";
            }
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "<script>alert('Invalid OTP. Please try again.'); window.location.href = 'otp_input.php?customer_email=" . urlencode($customer_email) . "';</script>";
    }
} else {
    echo "Invalid request.";
}
?>
