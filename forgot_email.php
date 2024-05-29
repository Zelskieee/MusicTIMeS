<?php
include 'db.php';
session_start();

if (isset($_POST['submit'])) {
    $customer_email = $_POST['customer_email'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM customers WHERE customer_email = ?");
    $stmt->bind_param("s", $customer_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        if ($result->num_rows > 0) {
            // Email exists, start the session and redirect
            $_SESSION['customer_email'] = $customer_email;
            header("Location: new_password.php");
            exit();
        } else {
            // Invalid email, display error message
            $error_message = "Invalid email";
        }
    } else {
        // Query execution failed
        $error_message = "Database error: " . $stmt->error;
    }

    // Redirect to forgot_password.php with error message
    header("Location: forgot_password.php?error=" . urlencode($error_message));
    exit();
}

$stmt->close();
$conn->close();
?>
