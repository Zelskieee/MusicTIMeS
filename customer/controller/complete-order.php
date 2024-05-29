<?php
include '../../db.php'; // Include your database connection
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderId = $_POST['orderId'];
    $query = "UPDATE `order` SET `order_status` = 'Complete' WHERE `order_id` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $orderId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $conn->close();
}
?>
