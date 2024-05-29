<?php
// C:\xampp\htdocs\musictimes\enterprise\controller\change-password.php

include '../../db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['enterprise_id'])) {
    header('Location: ../../login_enterprise.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the enterprise ID from the session
    $enterprise_id = $_SESSION['enterprise_id'];
    
    // Retrieve the submitted form data and sanitize
    $old_password = filter_input(INPUT_POST, 'old_password', FILTER_SANITIZE_STRING);
    $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

    // Validate the form data
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        header('Location: ../enterprise-profile.php?error=All fields are required');
        exit();
    }

    if ($new_password !== $confirm_password) {
        header('Location: ../enterprise-profile.php?error=New password do not match');
        exit();
    }

    // Retrieve the user's current password from the database
    $stmt = $conn->prepare("SELECT enterprise_password FROM enterprise WHERE enterprise_id = ?");
    $stmt->bind_param("i", $enterprise_id);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    if (!$db_password || !password_verify($old_password, $db_password)) {
        header('Location: ../enterprise-profile.php?error=Old password is incorrect');
        exit();
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $stmt = $conn->prepare("UPDATE enterprise SET enterprise_password = ? WHERE enterprise_id = ?");
    $stmt->bind_param("si", $hashed_password, $enterprise_id);
    if ($stmt->execute()) {
        header('Location: ../enterprise-profile.php?success=Password changed successfully');
    } else {
        header('Location: ../enterprise-profile.php?error=Failed to change password');
    }
    $stmt->close();
    $conn->close();
    exit();
}

// Only reach here if not POST request, display form (if exists) or error
header('Location: ../enterprise-profile.php');
exit();
?>
