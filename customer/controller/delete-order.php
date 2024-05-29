<?php
include '../../db.php';

$order_id = $_GET['order_id'];

$update_query = "UPDATE `order` SET is_deleted = TRUE WHERE order_id = $order_id";

if ($conn->query($update_query) === TRUE) {
    echo "Order marked as deleted successfully";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
