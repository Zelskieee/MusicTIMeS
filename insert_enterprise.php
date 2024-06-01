<?php
include 'db.php';

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to validate the input fields
function validate_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define variables and set to empty values
    $enterprise_name = $enterprise_username = $enterprise_email = $password = $confirm_password = "";
    $errors = [];

    // Validate and sanitize inputs
    $enterprise_name = validate_input($_POST["enterprise_name"]);
    $enterprise_username = validate_input($_POST["enterprise_username"]);
    $enterprise_email = filter_var(validate_input($_POST["enterprise_email"]), FILTER_VALIDATE_EMAIL);
    $password = validate_input($_POST["enterprise_password"]);
    $confirm_password = validate_input($_POST["confirm_password"]);

    // Debugging statements
    error_log("Enterprise Name: " . $enterprise_name);
    error_log("Enterprise Username: " . $enterprise_username);
    error_log("Enterprise Email: " . $enterprise_email);
    error_log("Password: " . $password);
    error_log("Confirm Password: " . $confirm_password);

    // Check for empty fields
    if (empty($enterprise_name) || empty($enterprise_username) || empty($enterprise_email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    }

    // Validate email
    if (!$enterprise_email) {
        $errors[] = "Invalid email format.";
    }

    // Validate password
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if username or email already exists
    $sql = "SELECT * FROM enterprise WHERE enterprise_username = ? OR enterprise_email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ss", $enterprise_username, $enterprise_email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Username or email already exists.";
        }
        $stmt->close();
    } else {
        $errors[] = "Database error: Failed to prepare statement.";
    }

    // Handle file uploads
    $allowed_file_type = 'application/pdf';
    $upload_dir = 'image/enterprise/';
    $certificate_path = "";

    if (isset($_FILES['ssm_certificate']) && $_FILES['ssm_certificate']['type'] == $allowed_file_type && $_FILES['ssm_certificate']['error'] == 0) {
        $certificate_path = $upload_dir . basename($_FILES['ssm_certificate']['name']);
        if (!move_uploaded_file($_FILES['ssm_certificate']['tmp_name'], $certificate_path)) {
            $errors[] = "Failed to upload SSM certificate.";
        }
    } else {
        $errors[] = "SSM Certificate is required and must be a PDF file.";
    }

    // Check if there are errors
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO enterprise (enterprise_name, enterprise_username, enterprise_email, ssm_certificate, enterprise_password) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssss", $enterprise_name, $enterprise_username, $enterprise_email, $certificate_path, $hashed_password);

            if ($stmt->execute()) {
                // Registration success, redirect to OTP page
                header("Location: send_otp_enterprise.php?enterprise_email=" . urlencode($enterprise_email));
                exit();
            } else {
                $errors[] = "Failed to register enterprise. Please try again.";
            }

            $stmt->close();
        } else {
            $errors[] = "Database error: Failed to prepare statement.";
        }
    }

    // If there are errors, redirect back to the form with error messages
    if (!empty($errors)) {
        $error_message = implode(" ", $errors);
        header("Location: enterprise-register.php?error=" . urlencode($error_message));
        exit();
    }
} else {
    header("Location: enterprise-register.php");
    exit();
}
?>
