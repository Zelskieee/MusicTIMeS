<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../../db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $cart_id = $_GET['id'];

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the delete statement
    $query = "DELETE FROM cart WHERE cart_id = ?";
    $stmt = $conn->prepare($query);

    // Bind the cart_id parameter
    $stmt->bind_param("i", $cart_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Item deleted successfully";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request";
}
?>
