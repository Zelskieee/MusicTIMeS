<?php
include 'C:\xampp\htdocs\musictimes\db.php';
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_GET['id'];
    $customer_username = mysqli_real_escape_string($conn, $_POST['customer_username']);
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_phone']);
    $customer_address = mysqli_real_escape_string($conn, $_POST['customer_address']);
    $image_name = null;

    // Handle image upload only if an image is selected
    if (!empty($_FILES["image"]["tmp_name"])) {
        $image_file = $_FILES["image"];

        // Validate image file
        if ($image_file["error"] !== UPLOAD_ERR_OK) {
            die('File upload failed with error code ' . $image_file["error"]);
        }

        // Destination directory
        $destination_dir = "C:/xampp/htdocs/musictimes/image/customer/";

        // Create the destination directory if it doesn't exist
        if (!file_exists($destination_dir)) {
            mkdir($destination_dir, 0777, true);
        }

        $image_extension = pathinfo($image_file["name"], PATHINFO_EXTENSION);
        $image_name = bin2hex(random_bytes(16)) . '.' . $image_extension;
        $target_path = $destination_dir . $image_name;

        // Move the temp image file to the images directory
        if (!move_uploaded_file($image_file["tmp_name"], $target_path)) {
            die('Failed to move uploaded file to destination.');
        }
    }

    // Update the database with prepared statement
    if ($image_name) {
        $query = "UPDATE customers SET 
            customer_username=?, 
            customer_name=?, 
            customer_email=?, 
            customer_phone=?, 
            customer_address=?, 
            customer_image=? 
            WHERE customer_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $customer_username, $customer_name, $customer_email, $customer_phone, $customer_address, $image_name, $customer_id);
    } else {
        $query = "UPDATE customers SET 
            customer_username=?, 
            customer_name=?, 
            customer_email=?, 
            customer_phone=?, 
            customer_address=? 
            WHERE customer_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $customer_username, $customer_name, $customer_email, $customer_phone, $customer_address, $customer_id);
    }

    // Execute the prepared statement
    if ($stmt->execute()) {
        $_SESSION['customer_username'] = $customer_username;
        header("Location: /musictimes/customer/customer-profile.php");
        exit();
    } else {
        die('Error executing the query: ' . $stmt->error);
    }
}

mysqli_close($conn);
?>
