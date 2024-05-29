<?php
session_start();

// Check if the logout parameter is set
if (isset($_POST['logout']) && $_POST['logout'] == 'true') {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other page
    // header("Location: ../index.php");
    exit();
}
?>