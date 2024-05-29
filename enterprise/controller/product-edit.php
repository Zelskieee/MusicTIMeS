<?php
session_start();
include 'C:\xampp\htdocs\MusicTIMeS\db.php';

$product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
$product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
$product_quantity = mysqli_real_escape_string($conn, $_POST['product_quantity']);
$product_desc = mysqli_real_escape_string($conn, $_POST['product_desc']);
$product_tag = mysqli_real_escape_string($conn, $_POST['product_tag']);
$product_status = mysqli_real_escape_string($conn, $_POST['product_status']);
$category_id  = mysqli_real_escape_string($conn, $_POST['category_id']);
$enterprise_id   = $_SESSION['enterprise_id'];
$image_file = $_FILES["image"];
$image_name = null;
$product_id = $_GET['id'];

if ($_FILES['image']['error']!=4){
    // Exit if image file is zero bytes
    if (filesize($image_file["tmp_name"]) <= 0) { die('Uploaded file has no contents.'); }
    // Exit if is not a valid image file
    $image_type = exif_imagetype($image_file["tmp_name"]);
    if (!$image_type) { die('Uploaded file is not an image.');}
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

    $query = "UPDATE product
    SET 
    category_id = '$category_id',
    enterprise_id = '$enterprise_id',
    product_name = '$product_name',
    product_desc = '$product_desc',
    product_quantity = '$product_quantity',
    product_tag = '$product_tag',
    product_price = '$product_price',
    product_status = '$product_status',
    product_image = '$image_name'
    WHERE
    product_id = '$product_id';
    ";
}else{
    $query = "UPDATE product
    SET 
      category_id = '$category_id',
      enterprise_id = '$enterprise_id',
      product_name = '$product_name',
      product_desc = '$product_desc',
      product_quantity = '$product_quantity',
      product_tag = '$product_tag',
      product_price = '$product_price',
      product_status = '$product_status'
    WHERE
      product_id = '$product_id';
    ";
}

if ($conn->query($query)){
    header("Location: /musictimes/enterprise/product-listing.php");
    mysqli_close($conn);
}else{
    echo "sql error!";
    die();
}
?>
