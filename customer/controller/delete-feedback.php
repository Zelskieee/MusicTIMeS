<?php
include '../../db.php';

$feedback_id = $_GET['feedback_id'];

$update_query = "UPDATE `feedback` SET is_deleted = TRUE WHERE feedback_id = $feedback_id";

if ($conn->query($update_query) === TRUE) {
    echo "Feedback marked as deleted successfully";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
