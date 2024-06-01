<?php
// Establish database connection (assuming $conn is your database connection object)
include '../../db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Retrieve customer_id from session or wherever it's stored
if (!isset($_SESSION['customer_id'])) {
    die("Missing customer_id.");
}

$customer_id = $_SESSION['customer_id'];

// Retrieve customer email from database
$sql_email = "SELECT customer_email FROM customers WHERE customer_id = ?";
$stmt_email = $conn->prepare($sql_email);
$stmt_email->bind_param("i", $customer_id);
$stmt_email->execute();
$result_email = $stmt_email->get_result();

if ($result_email->num_rows > 0) {
    $customer_email = $result_email->fetch_assoc()['customer_email'];
} else {
    die("Customer email not found.");
}

// Query to get the products in the cart grouped by enterprise_id
$sql = "SELECT c.product_id, c.cart_quantity, p.product_price, p.enterprise_id
        FROM cart c
        JOIN product p ON c.product_id = p.product_id
        WHERE c.customer_id = $customer_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $orders = [];

    // Group products by enterprise_id
    while ($row = $result->fetch_assoc()) {
        $enterprise_id = $row['enterprise_id'];
        if (!isset($orders[$enterprise_id])) {
            $orders[$enterprise_id] = [
                'products' => [],
                'total_amount' => 0
            ];
        }
        $orders[$enterprise_id]['products'][] = $row;
        $orders[$enterprise_id]['total_amount'] += $row['cart_quantity'] * $row['product_price'];
    }

    foreach ($orders as $enterprise_id => $order) {
        // Insert data into the order table
        $order_date = date('Y-m-d H:i:s'); // Get current date and time
        $order_status = "Preparing"; // Set order status to "Preparing"
        $total_amount = $order['total_amount'];

        $insert_order_query = "INSERT INTO `order` (customer_id, enterprise_id, order_date, total_amount, order_status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_order_query);
        $stmt->bind_param("iisss", $customer_id, $enterprise_id, $order_date, $total_amount, $order_status);

        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;

            // Insert data into the order_item table
            foreach ($order['products'] as $product) {
                $product_id = $product['product_id'];
                $order_quantity = $product['cart_quantity'];
                $product_price = $product['product_price'];

                $insert_order_item_query = "INSERT INTO order_item (order_id, product_id, order_quantity, enterprise_id) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_order_item_query);
                $stmt->bind_param("iiii", $order_id, $product_id, $order_quantity, $enterprise_id);

                if (!$stmt->execute()) {
                    die("Error inserting order item: " . $stmt->error);
                }

                // Update product quantity
                $update_product_query = "UPDATE product SET product_quantity = product_quantity - ? WHERE product_id = ?";
                $stmt = $conn->prepare($update_product_query);
                $stmt->bind_param("ii", $order_quantity, $product_id);

                if (!$stmt->execute()) {
                    die("Error updating product quantity: " . $stmt->error);
                }
            }

            // Insert data into the payment table
            $transaction_id = mt_rand(100000, 999999); // Replace with actual transaction ID
            $payment_method = "Credit/Debit card"; // Replace with actual payment method

            $insert_payment_query = "INSERT INTO payment (order_id, customer_id, transaction_id, total_amount, payment_method) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_payment_query);
            $stmt->bind_param("iiiss", $order_id, $customer_id, $transaction_id, $total_amount, $payment_method);

            if (!$stmt->execute()) {
                die("Error inserting payment: " . $stmt->error);
            }
        } else {
            die("Error inserting order: " . $stmt->error);
        }
    }

    // Clear the cart after successful checkout
    $delete_cart_query = "DELETE FROM cart WHERE customer_id = ?";
    $stmt = $conn->prepare($delete_cart_query);
    $stmt->bind_param("i", $customer_id);

    if (!$stmt->execute()) {
        die("Error clearing cart: " . $stmt->error);
    }

    // Send order notification email
    require '../../vendor/autoload.php'; // Adjust the path as necessary
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
        $mail->Subject = 'Your Purchase Is Successful And Secure With MusicTIMeS';
        $mailContent = "<h1>Your order will be processed by the enterprise.</h1>
                        <p>Thank you for ordering through MusicTIMeS</p> <br>
                        <p>Best regards,<br><span style=\"font-weight: bold;\">MusicTIMeS</span></p>";
        $mail->Body = $mailContent;

        if ($mail->send()) {
            // Email sent successfully
            echo "Email sent successfully.";
        } else {
            echo "Email could not be sent. Mailer Error: " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }

    // Redirect to a success page with alert
    echo "<script>alert('Your purchase is successful. Thank you!'); window.location.href='../my-order.php';</script>";
    exit();
} else {
    echo "No items in cart.";
}
?>
