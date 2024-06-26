<?php
include 'db.php';

if (isset($_POST['submit'])) {

    $customer_username = mysqli_real_escape_string($conn, $_POST['customer_username']);
    $customer_password = mysqli_real_escape_string($conn, $_POST['customer_password']);

    $query = "SELECT * FROM customers WHERE customer_username = '$customer_username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($user['is_verified'] == 1) {
            if (password_verify($customer_password, $user['customer_password'])) {
                // Login successful, redirect to customer.php
                session_start();
                $_SESSION['customer_id'] = $user['customer_id'];
                $_SESSION['customer_username'] = $customer_username;
                header("Location: ./customer/homepage.php");
                exit();
            } else {
                // Invalid password, display error message
                $error_message = "Invalid password";
            }
        } else {
            // Account not verified, display error message
            $error_message = "Account not verified";
        }
    } else {
        // Invalid username, display error message
        $error_message = "Invalid username";
    }

    // Redirect to index.php with error message
    header("Location: index.php?error=" . urlencode($error_message));
    exit();

    mysqli_close($conn);
}
?>
