<?php include './layout/main.php'; ?>

<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    if (empty($_POST['feedback_desc']) || empty($_POST['selected_rating']) || empty($_FILES['feedback_media']['name'])) {
        echo '<script>alert("Please fill in all the required fields and upload an image."); window.history.back();</script>';
        exit();
    }

    $product_id = $_POST['product_id'];
    $customer_id = $_POST['customer_id'];
    $feedback_desc = $_POST['feedback_desc'];
    $rating = $_POST['selected_rating'];
    $order_id = $_POST['order_id'];

    $feedback_media = "";
    if ($_FILES['feedback_media']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../image/feedback/';
        $uploadFile = $uploadDir . basename($_FILES['feedback_media']['name']);
        if (move_uploaded_file($_FILES['feedback_media']['tmp_name'], $uploadFile)) {
            $feedback_media = $uploadFile;
        }
    }

    // Check if feedback already exists
    $check_query = "SELECT * FROM feedback WHERE product_id = ? AND customer_id = ? AND order_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("iii", $product_id, $customer_id, $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing feedback
        $update_query = "UPDATE feedback SET feedback_desc = ?, feedback_media = ?, rating = ? WHERE product_id = ? AND customer_id = ? AND order_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssiiii", $feedback_desc, $feedback_media, $rating, $product_id, $customer_id, $order_id);
    } else {
        // Insert new feedback
        $insert_query = "INSERT INTO feedback (product_id, customer_id, feedback_desc, feedback_media, rating, order_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iissii", $product_id, $customer_id, $feedback_desc, $feedback_media, $rating, $order_id);
    }

    $stmt->execute();
    $stmt->close();

    echo '<script>alert("Feedback submitted successfully!"); window.location.href="customer-feedback.php";</script>';
}
?>
<head>
    <title><?php echo __('rate')?></title>
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Freeman", sans-serif;
        }
        .selected {
            color: #ffbf03de;
            transition: all 0.5s ease;
            cursor: pointer;
        }
        .product-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        .product-info img {
            width: 300px;
            height: auto;
        }
        .product-info .product-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        form {
            width: 80%;
            margin: auto;
        }
        .card-img-top {
            width: 300px; 
            height: auto;
            border-radius: 15px; 
            transition: transform 0.3s ease, box-shadow 0.3s ease; 
        }
        .card-img-top:hover {
            transform: scale(1.1); 
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); 
        }
        .header-container {
            display: flex;
            justify-content: space-between;
        }
        .header-container h1 {
            margin-left: 720px;
            font-weight: bold;
        }
        .button-container {
            margin-top: 10px;
        }
        .btn {
            display: flex;
        }
    </style>
</head>
<main>
<section style="padding:20px">
    <div class="header-container">
        <h1><?php echo __('rate')?></h1>
        <div class="button-container">
            <a href="customer-feedback.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left fa-beat-fade" style="margin-top: 5px; margin-right: 5px;"></i> <?php echo __('back')?></a>
        </div>
    </div>
    <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <?php
            $product_id = $_GET['product_id'];
            $order_id = $_GET['order_id'];
            $product_query = "SELECT * FROM product WHERE product_id = $product_id";
            $product_result = $conn->query($product_query);
            if ($product_result->num_rows > 0) {
                $product = $product_result->fetch_assoc();
                echo '<div class="product-info">
                    <img src="../image/product/'.$product['product_image'].'" class="card-img-top" alt="Product Image" style="margin-top: 20px; margin-bottom: 20px;">
                    <div class="product-name">'.$product['product_name'].'</div>
                </div>';
            }
        ?> 
        <input type="hidden" id="product_id" name="product_id" value="<?php echo $product_id; ?>">
        <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $_SESSION['customer_id']; ?>">
        <input type="hidden" id="order_id" name="order_id" value="<?php echo $order_id; ?>">

        <label style="font-weight:bold" for="feedback_desc" class="font-weight-bold"><?php echo __('feedback_description')?></label><br>
        <textarea style="width:100%;" id="feedback_desc" name="feedback_desc" style="border-radius: 10px;" autocomplete="off" required></textarea><br><br>

        <label style="font-weight:bold" for="feedback_media" class="font-weight-bold"><?php echo __('feedback_media')?></label><br>
        <input style="width:100%;" type="file" id="feedback_media" name="feedback_media" required><br><br>

        <div style="font-weight:bold"><?php echo __('feedback_rating')?></div>
        <div id="rating" style="font-size: 50px;">
            <span class="star" data-rating="1">&#9733;</span>
            <span class="star" data-rating="2">&#9733;</span>
            <span class="star" data-rating="3">&#9733;</span>
            <span class="star" data-rating="4">&#9733;</span>
            <span class="star" data-rating="5">&#9733;</span>
        </div>
        <input type="hidden" id="selected_rating" name="selected_rating" value="" required>

        <button type="submit" class="btn btn-secondary mt-4"><i class="fa-solid fa-circle-check fa-beat-fade" style="margin-top: 5px; margin-right: 5px;"></i> <strong><?php echo __('submit')?></strong></button>
    </form>
</section>
<script>
    function validateForm() {
        const feedbackDesc = document.getElementById('feedback_desc').value;
        const feedbackMedia = document.getElementById('feedback_media').value;
        const selectedRating = document.getElementById('selected_rating').value;

        if (!feedbackDesc || !feedbackMedia || !selectedRating) {
            alert('Please fill in all the required fields and upload an image.');
            return false;
        }
        return true;
    }

    $(document).ready(function () {
        var selectedRating = 0;

        $('.star').click(function () {
            var rating = $(this).data('rating');
            if (selectedRating == rating) {
                selectedRating = 0;
            } else {
                selectedRating = rating;
            }

            $('.star').each(function () {
                var starRating = $(this).data('rating');
                if (starRating <= selectedRating) {
                    $(this).addClass('selected');
                } else {
                    $(this).removeClass('selected');
                }
            });

            $('#selected_rating').val(selectedRating);
        });
    });
</script>
</main>
<?php include './layout/footer.php'; ?>
</body>
</html>
