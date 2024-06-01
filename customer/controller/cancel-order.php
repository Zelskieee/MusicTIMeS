<?php
session_start();
include '../../db.php'; // Ensure this includes the correct path to your database connection script
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderId = $_POST['orderId'];
    $cancelReason = $_POST['cancelReason'];

    // Fetch the enterprise email associated with the order
    $query = "SELECT e.enterprise_email 
              FROM order_item oi
              JOIN product p ON oi.product_id = p.product_id
              JOIN enterprise e ON p.enterprise_id = e.enterprise_id
              WHERE oi.order_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo 'Prepare failed: ' . $conn->error;
        exit;
    }
    $stmt->bind_param("i", $orderId);
    if (!$stmt->execute()) {
        echo 'Execute failed: ' . $stmt->error;
        exit;
    }
    $stmt->bind_result($enterprise_email);
    if (!$stmt->fetch()) {
        echo 'Fetch failed: ' . $stmt->error;
        exit;
    }
    $stmt->close();

    // Update the order status to 'Cancel' in the database
    $updateQuery = "UPDATE `order` SET order_status = 'Cancel', cancel_order_reason = ? WHERE order_id = ?";
    $stmt = $conn->prepare($updateQuery);
    if (!$stmt) {
        echo 'Prepare failed: ' . $conn->error;
        exit;
    }
    $stmt->bind_param("si", $cancelReason, $orderId);
    if (!$stmt->execute()) {
        echo 'Update failed: ' . $stmt->error;
        exit;
    }

    // Send email notification
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
        $mail->Subject = 'Customer Has Cancelled Their Order';
        $mailContent = "<h1>Go to the Order Listing page to view the cancelled order</h1>
                        <p>Reason for cancellation: $cancelReason</p><br>
                        <p>Thank you for using MusicTIMeS</p><br>
                        <p>Best regards,<br><span style=\"font-weight: bold;\">MusicTIMeS</span></p>";
        $mail->Body = $mailContent;

        $mail->send();
        echo 'success';
    } catch (Exception $e) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}
?>
