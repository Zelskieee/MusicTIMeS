<?php
session_start();
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to sanitize inputs
function validate_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_otp'])) {
    // Sanitize inputs
    $submitted_otp = validate_input($_POST['otp']);
    $customer_email = $_SESSION['customer_email'];

    // Check OTP in the database
    $check_query = "SELECT * FROM customers WHERE customer_email = ? AND otp = ?";
    $stmt = $conn->prepare($check_query);
    if ($stmt) {
        $stmt->bind_param("ss", $customer_email, $submitted_otp);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // OTP is correct, update verification status
            $update_query = "UPDATE customers SET is_verified = 1, otp = NULL, verified_at = NOW() WHERE customer_email = ?";
            $update_stmt = $conn->prepare($update_query);
            if ($update_stmt) {
                $update_stmt->bind_param("s", $customer_email);
                if ($update_stmt->execute()) {
                    // Verification successful
                    echo "<script>alert('Verification successful'); 
                    window.location.href='index.php';</script>";
                    exit();
                } else {
                    $errors[] = "Error: " . $update_stmt->error;
                }
                $update_stmt->close();
            } else {
                $errors[] = "Database error: Failed to prepare update statement.";
            }
        } else {
            // OTP is incorrect
            $errors[] = "Invalid OTP. Please try again.";
        }
        $stmt->close();
    } else {
        $errors[] = "Database error: Failed to prepare check statement.";
    }

    // If there are errors, redirect back to the OTP form with error messages
    if (!empty($errors)) {
        $error_message = implode(" ", $errors);
        header("Location: register.php?error=" . urlencode($error_message));
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>
