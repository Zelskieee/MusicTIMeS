<?php
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to sanitize inputs
function validate_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $customer_name = validate_input($_POST['customer_name']);
    $customer_username = validate_input($_POST['customer_username']);
    $customer_email = filter_var(validate_input($_POST['customer_email']), FILTER_VALIDATE_EMAIL);
    $customer_password = $_POST['customer_password'];
    $confirm_password = $_POST['confirm_password'];

    // Initialize error message array
    $errors = [];

    // Check if the passwords match
    if ($customer_password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Validate email
    if (!$customer_email) {
        $errors[] = "Invalid email format.";
    }

    // Check if the username or email already exists
    $check_query = "SELECT * FROM customers WHERE customer_username = ? OR customer_email = ?";
    $stmt = $conn->prepare($check_query);
    if ($stmt) {
        $stmt->bind_param("ss", $customer_username, $customer_email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = "Username or email already exists.";
        }
        $stmt->close();
    } else {
        $errors[] = "Database error: Failed to prepare statement.";
    }

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($customer_password, PASSWORD_DEFAULT);

        // Insert data into the database
        $query = "INSERT INTO customers (customer_name, customer_username, customer_email, customer_password, otp) VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ssss", $customer_name, $customer_username, $customer_email, $hashed_password);
            if ($stmt->execute()) {
                // Redirect to OTP sending script
                header("Location: send_otp.php?customer_email=" . urlencode($customer_email));
                exit();
            } else {
                $errors[] = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Database error: Failed to prepare statement.";
        }
    }

    // If there are errors, redirect back to the registration form with error messages
    if (!empty($errors)) {
        $error_message = implode(" ", $errors);
        header("Location: register.php?error=" . urlencode($error_message));
        exit();
    }

    // Close the database connection
    $conn->close();
} else {
    header("Location: register.php");
    exit();
}
?>
