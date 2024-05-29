<?php
// C:\xampp\htdocs\musictimes\customer\controller\change-password.php

include '../../db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the customer ID from the session
    $customer_id = $_SESSION['customer_id'];
    
    // Retrieve the submitted form data and sanitize
    $old_password = filter_input(INPUT_POST, 'old_password', FILTER_SANITIZE_STRING);
    $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

    // Validate the form data
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        header('Location: ../customer-profile.php?error=All fields are required');
        exit();
    }

    if ($new_password !== $confirm_password) {
        header('Location: ../customer-profile.php?error=New password do not match');
        exit();
    }

    // Retrieve the user's current password from the database
    $stmt = $conn->prepare("SELECT customer_password FROM customers WHERE customer_id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    if (!$db_password || !password_verify($old_password, $db_password)) {
        header('Location: ../customer-profile.php?error=Old password is incorrect');
        exit();
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $stmt = $conn->prepare("UPDATE customers SET customer_password = ? WHERE customer_id = ?");
    $stmt->bind_param("si", $hashed_password, $customer_id);
    if ($stmt->execute()) {
        header('Location: ../customer-profile.php?success=Password changed successfully');
    } else {
        header('Location: ../customer-profile.php?error=Failed to change password');
    }
    $stmt->close();
    $conn->close();
    exit();
}

// Only reach here if not POST request, display form (if exists) or error
header('Location: ../customer-profile.php');
exit();
?>
