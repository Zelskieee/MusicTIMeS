<?php
include 'db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST['otp'];

    if ($otp == $_SESSION['otp']) {
        $customer_name = $_SESSION['customer_name'];
        $customer_username = $_SESSION['customer_username'];
        $customer_email = $_SESSION['customer_email'];
        $customer_password = $_SESSION['customer_password'];

        // Hash the password
        $hashed_password = password_hash($customer_password, PASSWORD_DEFAULT);

        // Insert data into the database
        $query = "INSERT INTO customers (customer_name, customer_username, customer_email, customer_password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ssss", $customer_name, $customer_username, $customer_email, $hashed_password);
            if ($stmt->execute()) {
                // Registration successful
                echo "<script>alert('Registration successful'); 
                window.location.href='index.php';</script>";
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Database error: Failed to prepare statement.";
        }

        // Clear session
        unset($_SESSION['otp']);
        unset($_SESSION['customer_name']);
        unset($_SESSION['customer_username']);
        unset($_SESSION['customer_email']);
        unset($_SESSION['customer_password']);
    } else {
        echo "<script>alert('Invalid OTP'); 
        window.location.href='register.php';</script>";
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>
