<?php include './layout/main.php'; ?>

<head>
    <title><?php echo __('product'); ?></title>
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Freeman", sans-serif;
        }

        .filter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filter-container h2 {
            margin: 0;
        }

        .filter-container form {
            display: flex;
            align-items: center;
        }

        .filter-container form input[type="text"] {
            padding: 5px;
            font-size: 16px;
            margin-right: 10px;
        }

        .filter-container form button {
            padding: 5px 10px;
            font-size: 16px;
            background-color: #000;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .filter-container form button:hover {
            background-color: #fff;
            color: #000;
            border: 1px solid #000;
        }

        .filter-container form select {
            padding: 5px;
            font-size: 16px;
            margin-right: 10px;
        }
    </style>
</head>

<main>
    <section>
        <div class="filter-container">
            <h2 style="font-weight: bold; margin-left: 680px;"><?php echo __('product'); ?></h2>
            <form id="filter-form" method="GET">
                <select name="price_filter" style="border-radius: 8px; border: solid 2px black;">
                    <option value=""><?php echo __('select_price_filter'); ?></option>
                    <option value="low_to_high" <?php if (isset($_GET['price_filter']) && $_GET['price_filter'] == 'low_to_high') echo 'selected'; ?>><?php echo __('low_to_high_price'); ?></option>
                    <option value="high_to_low" <?php if (isset($_GET['price_filter']) && $_GET['price_filter'] == 'high_to_low') echo 'selected'; ?>><?php echo __('high_to_low_price'); ?></option>
                </select>
                <select name="enterprise_filter" style="border-radius: 8px; border: solid 2px black;">
                    <option value=""><?php echo __('select_enterprise'); ?></option>
                    <?php
                    // Fetch enterprises for the dropdown
                    $enterprise_query = "SELECT enterprise_id, enterprise_name FROM enterprise";
                    $enterprise_result = $conn->query($enterprise_query);
                    while ($enterprise = $enterprise_result->fetch_assoc()) {
                        $selected = isset($_GET['enterprise_filter']) && $_GET['enterprise_filter'] == $enterprise['enterprise_id'] ? 'selected' : '';
                        echo "<option value='{$enterprise['enterprise_id']}' $selected>{$enterprise['enterprise_name']}</option>";
                    }
                    ?>
                </select>
                <input type="text" name="search_query" id="filter-input" style="border-radius: 8px;" autocomplete="off" placeholder="<?php echo __('search_products'); ?>" value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                <button type="submit" style="border-radius: 8px; transition: background-color 0.5s; color 0.5s;"><i class="fa-solid fa-magnifying-glass fa-beat-fade"></i> <?php echo __('search'); ?></button>
            </form>
        </div>
        <hr>
        <div class="row" id="product-list">
            <?php
            // Get filter parameters from GET request
            $price_filter = isset($_GET['price_filter']) ? $_GET['price_filter'] : '';
            $enterprise_filter = isset($_GET['enterprise_filter']) ? $_GET['enterprise_filter'] : '';
            $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

            // Prepare the base SQL query
            $query = "SELECT product.*, category.category_name, enterprise.enterprise_name, enterprise.enterprise_image 
                    FROM product 
                    LEFT JOIN category ON category.category_id = product.category_id
                    LEFT JOIN enterprise ON enterprise.enterprise_id = product.enterprise_id
                    WHERE product.product_status = 'Available'";

            // Add search functionality
            if ($search_query) {
                $query .= " AND (product.product_name LIKE '%$search_query%' OR enterprise.enterprise_name LIKE '%$search_query%')";
            }

            // Add enterprise filter
            if ($enterprise_filter) {
                $query .= " AND product.enterprise_id = '$enterprise_filter'";
            }

            // Add price filter functionality
            switch ($price_filter) {
                case 'low_to_high':
                    $query .= " ORDER BY product.product_price ASC";
                    break;
                case 'high_to_low':
                    $query .= " ORDER BY product.product_price DESC";
                    break;
            }

            $result = $conn->query($query);
            $product_count = $result->num_rows;

            if ($product_count > 0) {
                while ($product = $result->fetch_assoc()) {
            ?>
                    <div class="col-lg-3 col-md-6 col-2 product-item" style="transition: transform 0.3s ease-in-out;height: 100%;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="card mb-3" style="height: 98%;" style="height: 98%; transform-origin: center top;">
                            <a href="product-detail.php?id=<?php echo $product['product_id'] ?>">
                                <img src="../image/product/<?=$product['product_image']?>" style="object-fit: cover;" class="card-img-top" alt="Product Image" height="300px">
                            </a>
                            <div class="card-body d-flex justify-content-between" style="flex-direction:column">
                                <h5 class="card-title">
                                    <div style="cursor:pointer" class="d-flex justify-content-between aling-items-center" onclick="redirectToPage('product-detail.php?id=<?php echo $product['product_id']?>')">
                                        <h4 class="m-0"> <?=$product['product_name']?></h4>
                                        <span><?=$product['category_name']?></span>
                                    </div>
                                </h5>
                                <p class="card-text">
                                    <div class="d-flex justify-content-between aling-items-center">
                                        <i class="ms-0"> <?=$product['product_tag']?></i>
                                        <span><?=$product['product_quantity']?> <?php echo __('quantity'); ?><span style="color: green; font-weight: bold; text-align: center;"><?php echo __('available'); ?></span></span>

                                    </div>
                                </p>
                                <?php if (isset($product['enterprise_name']) && isset($product['enterprise_image'])) { ?>
                                    <div class="enterprise-info" style="display: inline-flex; align-items: center; margin-right: 10px;">
                                        <?php
                                        // Fetch the enterprise image from the enterprise folder if it exists, otherwise use the default image
                                        $imagePath = !empty($product['enterprise_image']) ? "../image/enterprise/" . $product['enterprise_image'] : "../image/default-profile-image.png";
                                        ?>
                                        <img src="<?= htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') ?>" alt="Enterprise Logo" style="width: 15%; border-radius: 50%;">
                                        <p style="margin: 0; padding-left: 10px;"><?= htmlspecialchars($product['enterprise_name'], ENT_QUOTES, 'UTF-8') ?></p>
                                    </div>
                                <?php } ?>
                                <p class="card-text">
                                    <div class="d-flex justify-content-between aling-items-center">
                                        <b class="ms-0" style="transition: font-size 0.3s; font-weight: bold;" onmouseover="this.style.fontWeight='bold'; this.style.fontSize='140%'" onmouseout="this.style.fontWeight='bold'; this.style.fontSize='100%'">RM <?=$product['product_price']?></b>
                                        <div>
                                            <button class="btn btn-primary btn-sm" style="background-color: #000; color: #fff; font-size: 16px; font-weight: bold; border: 1px solid #fff;" onclick="updateQuantity('subtract', <?=$product['product_quantity']?>, <?=$product['product_id']?>)">-</button>
                                            <span class="px-2" id="display_quantity_<?=$product['product_id']?>" style="font-weight: bold; font-size: 16px;">1</span>
                                            <button class="btn btn-primary btn-sm" style="background-color: #000; color: #fff; font-size: 16px; font-weight: bold; border: 1px solid #fff;" onclick="updateQuantity('add', <?=$product['product_quantity']?>, <?=$product['product_id']?>)">+</button>
                                        </div>
                                    </div>
                                </p>
                                <div class="d-grid gap-2">
                                    <button onclick="addToCart(<?=$product['product_id']?>)" class="btn btn-primary" type="button" style="font-weight: bold; background-color: #000; color: #fff; border: 1px solid #fff;" onmouseover="this.style.backgroundColor='#fff'; this.style.color='#000'; this.style.border='1px solid #000;'" onmouseout="this.style.backgroundColor='#000'; this.style.color='#fff'; this.style.border='1px solid black;'"> <i class="fa-solid fa-basket-shopping fa-bounce"></i> <?php echo __('add_to_cart'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>

            <?php
                }
            } else {
                echo "<script>document.getElementById('no-results-message').style.display = 'block';</script>";
            }
            ?>
        </div>
        <div id="no-results-message" style="display: none; text-align: center; font-size: 18px; color: red; margin-top: 20px;">
            <i class="fa-solid fa-face-tired fa-bounce"></i> <?php echo __('no_product'); ?>
        </div>
    </section>
</main>
<script>
function redirectToProductListing() {
    window.location.href = "product-listing.php";
}

function updateQuantity(operation, max_quantity, product_id) {
    var quantityElement = document.getElementById('display_quantity_' + product_id);
    var quantity = parseInt(quantityElement.innerText);

    if (operation == 'add') {
        if (quantity < max_quantity) {
            quantityElement.innerText = quantity + 1;
        }
    } else if (operation == 'subtract') {
        if (quantity > 1) {
            quantityElement.innerText = quantity - 1;
        }
    }
}

function addToCart(product_id) {
    var quantity = document.getElementById('display_quantity_' + product_id).innerText;

    $.ajax({
        type: 'POST',
        url: './controller/add-to-cart.php',
        data: {
            product_id: product_id,
            quantity: quantity
        },
        success: function (response) {
            // Handle success response
            alert('Product added to cart.');
            window.location.reload();
        },
        error: function (xhr, status, error) {
            // Handle error response
            console.error('Error adding product to cart.');
        }
    });
}
</script>
<?php include './layout/footer.php'; ?>
</body>
</html>
