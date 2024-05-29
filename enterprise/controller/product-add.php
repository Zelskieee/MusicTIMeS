<?php
session_start();
include 'C:\xampp\htdocs\MusicTIMeS\db.php';

// Sanitize input
$product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
$product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
$product_quantity = mysqli_real_escape_string($conn, $_POST['product_quantity']);
$product_desc = mysqli_real_escape_string($conn, $_POST['product_desc']);
$product_tag = mysqli_real_escape_string($conn, $_POST['product_tag']);
$product_status = mysqli_real_escape_string($conn, $_POST['product_status']);
$category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
$enterprise_id = $_SESSION['enterprise_id'];
$image_file = $_FILES["image"];
$image_name = null;

// Check if category_id is set
if (!isset($_POST['category_id'])) {
    $_SESSION['error_message'] = "Please add a category first before adding a product.";
    header("Location: /musictimes/enterprise/product-add.php");
    exit();
}

if (isset($image_file)) {
    // Exit if image file is zero bytes
    if (filesize($image_file["tmp_name"]) <= 0) {
        $_SESSION['error_message'] = 'Uploaded file has no contents.';
        header("Location: /musictimes/enterprise/product-add.php");
        exit();
    }
    // Exit if it is not a valid image file
    $image_type = exif_imagetype($image_file["tmp_name"]);
    if (!$image_type) {
        $_SESSION['error_message'] = 'Uploaded file is not an image.';
        header("Location: /musictimes/enterprise/product-add.php");
        exit();
    }
    // Get file extension based on file type, to prepend a dot we pass true as the second parameter
    $image_extension = image_type_to_extension($image_type, true);
    // Create a unique image name
    $image_name = bin2hex(random_bytes(16)) . $image_extension;
    // Move the temp image file to the images directory
    move_uploaded_file(
        // Temp image location
        $image_file["tmp_name"],
        // New image location
        "C:/xampp/htdocs/musictimes/image/product/" . $image_name
    );
}

$query = "INSERT INTO product 
(category_id,enterprise_id,product_name,product_desc,product_quantity,product_tag,product_price,product_status,product_image) 
values 
('$category_id','$enterprise_id','$product_name','$product_desc','$product_quantity','$product_tag','$product_price','$product_status','$image_name')";

if ($conn->query($query)) {
    $_SESSION['success_message'] = "Product added successfully.";
    header("Location: /musictimes/enterprise/product-listing.php");
} else {
    $_SESSION['error_message'] = "SQL error: " . $conn->error;
    header("Location: /musictimes/enterprise/product-add.php");
}

mysqli_close($conn);
exit();
?>
