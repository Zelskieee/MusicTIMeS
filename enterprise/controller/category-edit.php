<?php
include 'C:\xampp\htdocs\MusicTIMeS\db.php';

    $category_id = $_GET['id'];
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

    $query = "UPDATE category  SET 
    category_name='$category_name'
    WHERE category_id = '$category_id'";

    if ($conn->query($query)){
        header("Location: /musictimes/enterprise/category-listing.php");
        mysqli_close($conn);
    }else{
        echo "sql error!";
        die();
    }

   

?>
