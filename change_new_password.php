<?php
include 'db.php';
session_start();

if (isset($_POST['submit'])) {
    if (isset($_SESSION['customer_email']) && isset($_POST['customer_password'])) {
        $customer_email = $_SESSION['customer_email'];
        $customer_password = password_hash($_POST['customer_password'], PASSWORD_DEFAULT);

        // Prepare the SQL statement to prevent SQL injection
        $stmt_check = $conn->prepare("SELECT * FROM customers WHERE customer_email = ?");
        $stmt_check->bind_param("s", $customer_email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Update the password
            $stmt_update = $conn->prepare("UPDATE customers SET customer_password = ? WHERE customer_email = ?");
            $stmt_update->bind_param("ss", $customer_password, $customer_email);
            $result_update = $stmt_update->execute();

            if ($result_update) {
                // Password changed successfully
                echo "<script>alert('Password changed successfully'); window.location.href='index.php';</script>";
                exit();
            } else {
                echo "Error: " . $stmt_update->error;
            }
        } else {
            echo "Error: Email not found.";
        }

        $stmt_check->close();
        $stmt_update->close();
    } else {
        echo "Error: Email or password not set.";
    }

    $conn->close();
}
?>
