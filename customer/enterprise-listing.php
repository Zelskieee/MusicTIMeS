<?php include './layout/main.php'; ?>
<head>
    <title><?php echo __('enterprise')?></title>
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Freeman", sans-serif;
        }
    </style>
</head>
<main>
    <section class="enterprises-container">
        <h2 style="text-align: center; font-weight: bold;"><?php echo __('enterprise')?></h2>
        <hr>

        <div class="row">
            <?php
            // Fetch enterprises and their top 3 products with images
            $query = "SELECT enterprise.*, 
                        GROUP_CONCAT(product.product_name ORDER BY product.product_id ASC SEPARATOR ', ') AS top_products,
                        GROUP_CONCAT(product.product_image ORDER BY product.product_id ASC SEPARATOR ', ') AS product_images
                        FROM enterprise
                        LEFT JOIN product ON enterprise.enterprise_id = product.enterprise_id
                        GROUP BY enterprise.enterprise_id";

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($enterprise = $result->fetch_assoc()) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-header">
                                <h4 style="font-weight: bold;"><i class="fa-solid fa-shop fa-bounce"></i> <?= $enterprise['enterprise_name'] ?></h4>
                            </div>
                            <div class="card-body" style="width: 100%; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                <?php 
                                if ($enterprise['enterprise_image']) {
                                    $image_path = "/musictimes/image/enterprise/" . $enterprise['enterprise_image'];
                                    echo '<img src="' . $image_path . '" alt="Enterprise Logo" class="enterprise-image" style="width: 50%;">';
                                } else {
                                    echo '<img src="/musictimes/image/default-profile-image.png" alt="Default Enterprise Logo" class="enterprise-image" style="width: 50%;">';
                                }
                                ?>
                                <p style="font-weight: bold;"><?php echo __('product')?></p>

                                <div class="product-list">
                                    <?php
                                    $productNames = explode(', ', $enterprise['top_products']);
                                    $productImages = explode(', ', $enterprise['product_images']);
                                    
                                    $productCount = 0;

                                    foreach ($productImages as $key => $productImage) {
                                        if (!empty($productImage) && isset($productNames[$key])) {
                                            // Start a new row for every 3 products
                                            if ($productCount % 3 === 0) {
                                                echo '<div class="row">';
                                            }
                                            ?>
                                            <div class="col-md-4 product-item">
                                                <img src="../image/product/<?= $productImage ?>" alt="Product Image" style="width: 100%; margin-bottom: 5px; object-fit: cover; height: 100px; border-radius: 8px;">
                                                <p><?= $productNames[$key] ?></p>
                                            </div>
                                            <?php
                                            // End the row after every 3 products
                                            if ($productCount % 3 === 2 || $key === count($productImages) - 1) {
                                                echo '</div>';
                                            }

                                            $productCount++;
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <p>No enterprises found.</p>
            <?php } ?>
        </div>
    </section>
</main>
<?php include './layout/footer.php'; ?>
