<?php
session_start();
include '../../db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'added_quantity' => 0, 'cart_quantity' => 0, 'cart_items' => '', 'cart_sidebar' => ''];

if (!isset($_SESSION['customer_id'])) {
    $response['message'] = 'Customer not logged in';
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_SESSION['customer_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $query = "INSERT INTO cart (customer_id, product_id, cart_quantity, created_cart, updated_cart) 
              VALUES (?, ?, ?, NOW(), NOW()) 
              ON DUPLICATE KEY UPDATE cart_quantity = cart_quantity + VALUES(cart_quantity), updated_cart = NOW()";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('iii', $customer_id, $product_id, $quantity);

    if ($stmt->execute()) {
        // Get the updated cart quantity and details
        $quantity_query = "SELECT SUM(cart_quantity) AS total_quantity FROM cart WHERE customer_id = ?";
        $quantity_stmt = $conn->prepare($quantity_query);
        $quantity_stmt->bind_param('i', $customer_id);
        $quantity_stmt->execute();
        $quantity_result = $quantity_stmt->get_result();
        $quantity_row = $quantity_result->fetch_assoc();

        // Fetch updated cart HTML from main.php
        ob_start();
        include '../layout/main.php';
        $cart_sidebar_html = ob_get_clean();

        $response['success'] = true;
        $response['added_quantity'] = $quantity;
        $response['cart_quantity'] = $quantity_row['total_quantity'];
        $response['cart_sidebar'] = $cart_sidebar_html;
    } else {
        $response['message'] = 'Error adding product to cart: ' . $conn->error;
    }

    $stmt->close();
} else {
    $response['message'] = 'Invalid request method';
}

$conn->close();
echo json_encode($response);
?>
