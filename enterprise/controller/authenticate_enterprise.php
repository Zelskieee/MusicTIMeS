<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../db.php';

if (isset($_POST['submit'])) {

    $enterprise_username = mysqli_real_escape_string($conn, $_POST['enterprise_username']);
    $enterprise_password = mysqli_real_escape_string($conn, $_POST['enterprise_password']);

    $query = "SELECT * FROM enterprise WHERE enterprise_username = '$enterprise_username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($enterprise_password, $user['enterprise_password'])) {
            // Login successful, redirect to customer.php
            session_start();
            $_SESSION['enterprise_id'] = $user['enterprise_id'];
            $_SESSION['enterprise_username'] = $enterprise_username;
            header("Location: ../product-listing.php");
            exit();
        } else {
            // Invalid password, display error message
            $error_message = "Invalid password";
        }
    } else {
        // Invalid username, display error message
        $error_message = "Invalid username";
    }

    // Redirect to index.php with error message
    header("Location: ../../login_enterprise.php?error=" . urlencode($error_message));
    exit();

    mysqli_close($conn);
}
?>