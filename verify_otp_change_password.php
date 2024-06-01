<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the entered OTP and customer email from the form
    $entered_otp = $_POST['otp'];
    $customer_email = $_POST['customer_email'];

    // Check if the entered OTP matches the OTP stored in the session
    if ($entered_otp === $_SESSION['otp']) {
        // OTP is correct, redirect to new_password.php with email
        header("Location: new_password.php?customer_email=" . urlencode($customer_email));
        exit();
    } else {
        // Invalid OTP, redirect back with error
        header("Location: new_password.php?customer_email=" . urlencode($error_message) . "&customer_email=" . urlencode($customer_email));
        exit();
    }
} else {
    // If accessed directly without POST data, redirect to the forgot password page
    header("Location: forgot_password.php");
    exit();
}
?>
