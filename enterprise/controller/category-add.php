<?php
include 'C:\xampp\htdocs\MusicTIMeS\db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = $_POST['category_name'];
    $enterprise_id = $_POST['enterprise_id'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO category (category_name, enterprise_id) VALUES (?, ?)");
    $stmt->bind_param("si", $category_name, $enterprise_id);

    if ($stmt->execute()) {
        header("Location: /musictimes/enterprise/category-listing.php");
    } else {
        echo "SQL error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
