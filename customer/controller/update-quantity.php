<?php
session_start();
include '../../db.php';

if (!isset($_SESSION['customer_id'])) {
    header('Location: ../../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id']) && isset($_POST['action'])) {
    $cartId = $_POST['cart_id'];
    $action = $_POST['action'];

    // Check if the cart item belongs to the current customer
    $customerId = $_SESSION['customer_id'];
    $checkQuery = "SELECT * FROM cart WHERE cart_id = $cartId AND customer_id = $customerId";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        $updateQuery = "UPDATE cart SET cart_quantity = CASE 
                            WHEN cart_quantity > 1 AND '$action' = 'minus' THEN cart_quantity - 1 
                            WHEN '$action' = 'add' THEN cart_quantity + 1
                            ELSE cart_quantity 
                        END
                        WHERE cart_id = $cartId";

        if ($conn->query($updateQuery) === TRUE) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
