<?php
include 'C:\xampp\htdocs\MusicTIMeS\db.php';
session_start();

// Function to sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $enterprise_id = intval($_GET['id']);
    $enterprise_username = sanitize_input($_POST['enterprise_username']);
    $enterprise_name = sanitize_input($_POST['enterprise_name']);
    $enterprise_email = sanitize_input($_POST['enterprise_email']);
    $enterprise_phone = sanitize_input($_POST['enterprise_phone']);
    $enterprise_address = sanitize_input($_POST['enterprise_address']);
    $image_name = null;

    // Handle image upload only if an image is selected
    if (!empty($_FILES["image"]["tmp_name"])) {
        $image_file = $_FILES["image"];

        // Validate image file
        if ($image_file["error"] !== UPLOAD_ERR_OK) {
            die('File upload failed with error code ' . $image_file["error"]);
        }

        // Destination directory
        $destination_dir = "C:/xampp/htdocs/musictimes/image/enterprise/";

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
        $query = "UPDATE enterprise SET 
            enterprise_username=?, 
            enterprise_name=?, 
            enterprise_email=?, 
            enterprise_phone=?, 
            enterprise_address=?, 
            enterprise_image=? 
            WHERE enterprise_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $enterprise_username, $enterprise_name, $enterprise_email, $enterprise_phone, $enterprise_address, $image_name, $enterprise_id);
    } else {
        $query = "UPDATE enterprise SET 
            enterprise_username=?, 
            enterprise_name=?, 
            enterprise_email=?, 
            enterprise_phone=?, 
            enterprise_address=? 
            WHERE enterprise_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $enterprise_username, $enterprise_name, $enterprise_email, $enterprise_phone, $enterprise_address, $enterprise_id);
    }

    // Execute the prepared statement
    if ($stmt->execute()) {
        $_SESSION['enterprise_username'] = $enterprise_username;
        header("Location: /musictimes/enterprise/enterprise-profile.php");
        exit();
    } else {
        die('Error executing the query: ' . $stmt->error);
    }
}

mysqli_close($conn);
?>
