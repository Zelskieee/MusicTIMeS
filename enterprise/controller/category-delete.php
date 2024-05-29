<?php
include 'C:\xampp\htdocs\MusicTIMeS\db.php';

// Assuming you get the category ID from the URL
$category_id = $_GET['id'];

// SQL query to delete the category
$query = "DELETE FROM category WHERE category_id = '$category_id'";

// Fetch category details for display
$result = $conn->query("SELECT * FROM category WHERE category_id = '$category_id'");
if ($result->num_rows > 0) {
    $category = $result->fetch_assoc();
} else {
    echo 'Category not found.';
    exit();
}

// Check if the form is submitted and the confirmation button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // Attempt to delete the category
    if ($conn->query($query)) {
        echo '<script>
            alert("Category deleted successfully!");
            window.location.href = "/musictimes/enterprise/category-listing.php";
        </script>';
        exit();
    } else {
        echo '<script>alert("Error deleting category: SQL error");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Category</title>
    <link rel="icon" href="/musictimes/image/logo.ico" type="image/x-icon">
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Freeman", sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .confirmation-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            position: relative; /* Add this for positioning the icon */
        }

        p {
            color: black;
            margin-bottom: 20px;
        }

        .confirm-btn-container {
            position: relative;
        }

        .confirm-btn {
            font-family: "Freeman", sans-serif;
            background-color: #FF0000;
            color: #FFF;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .confirm-btn:hover {
            background-color: white;
            border: solid 1px #FF0000;
            color: #FF0000;
        }

        .cancel-link {
            font-family: "Freeman", sans-serif;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
            transition: color 0.3s ease;
            border: none;
            background: none;
            cursor: pointer;
            margin-left: 165px;
            padding: 10px;
        }

        .cancel-link:hover {
            color: lightgrey;
        }
    </style>
</head>
<body>

<div class="confirmation-container">
    <h1>Delete Category</h1>
    <form method="post" onsubmit="return confirm('Are you sure you want to delete this category?')">
    <p style="font-weight: bold;"><?=$category['category_name']?></p>
        <p>Click <strong style="font-weight: bold;">Confirm</strong> to delete the category.</p>
        <div class="confirm-btn-container">
            <button type="submit" name="confirm_delete" value="Confirm" class="confirm-btn"><i class="fa-regular fa-trash-can fa-beat-fade"></i> Confirm</div>
            <button type="button" class="cancel-link"><i class="fa-solid fa-arrow-left fa-beat-fade"></i>   Cancel</button>
    </form>
</div>
</body>
</html>

<script>
    document.querySelector('.cancel-link').addEventListener('click', function() {
        window.location.href = '/musictimes/enterprise/category-listing.php';
    });
</script>
