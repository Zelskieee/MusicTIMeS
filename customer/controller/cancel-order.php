
<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
// Assuming you have a database connection established already
include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['orderId'];

    // // Update the order_status to "cancelled" in the database
    $sql = "UPDATE `order` SET order_status = 'Cancel', cancel_order_reason='{$_POST['cancelReason']}' WHERE order_id = $orderId;";

    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
