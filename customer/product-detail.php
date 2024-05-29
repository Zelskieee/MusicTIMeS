<?php include './layout/main.php';?>

<head>
    <title><?php echo __('product_details'); ?></title>
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: "Freeman", sans-serif;
        }

        .button {
        position: relative;
        width: 4em;
        height: 4em;
        border: none;
        background: black;
        border-radius: 5px;
        transition: background 0.5s;
        }

        .X {
        content: "";
        position: absolute;
        top: 50%;
        left: 43%;
        width: 2em;
        height: 1.5px;
        background-color: rgb(255, 255, 255);
        transform: translateX(-50%) rotate(45deg);
        }

        .Y {
        content: "";
        position: absolute;
        top: 50%;
        left: 43%;
        width: 2em;
        height: 1.5px;
        background-color: #fff;
        transform: translateX(-50%) rotate(-45deg);
        }

        .close {
        position: absolute;
        display: flex;
        padding: 0.8rem 1.5rem;
        align-items: center;
        justify-content: center;
        transform: translateX(-50%);
        top: -70%;
        left: 50%;
        width: 3em;
        height: 1.7em;
        font-size: 12px;
        background-color: rgb(19, 22, 24);
        color: rgb(187, 229, 236);
        border: none;
        border-radius: 3px;
        pointer-events: none;
        opacity: 0;
        }

        .button:hover {
        background-color: rgb(211, 21, 21);
        }

        .button:active {
        background-color: rgb(130, 0, 0);
        }

        .button:hover > .close {
        animation: close 0.2s forwards 0.25s;
        }

        @keyframes close {
        100% {
            opacity: 1;
        }
        }
</style>
</head>

<main>
    <section>
        <h2 style="text-align: center; font-weight: bold;"><?php echo __('product_details'); ?></h2>
        <hr>
        <?php
        $query = "SELECT product.*, category.category_name, enterprise.enterprise_name, enterprise.enterprise_image 
            FROM product 
            LEFT JOIN category ON category.category_id = product.category_id
            LEFT JOIN enterprise ON enterprise.enterprise_id = product.enterprise_id
            WHERE product.product_status = 'Available' AND product.product_id=$_GET[id]";

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                ?>
                <div style="">
                <button class="button" style="margin-left: 95%;" onclick="redirectToProductListing()"><span class="X"></span><span class="Y"></span><div class="close">Close</div></button>
                <form style="width: 100%; display: flex; justify-content: center;">
                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                    <input type="hidden" name="quantity" id="quantity_<?= $product['product_id'] ?>" value="1"> <!-- Default quantity is 1 -->
                    <div style="border: 1px solid black; width: 60%; padding: 20px; border-radius: 8px; box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.2);">
                        <div style="display: flex">
                            <div style="width: 300px; height: 300px; margin-right: 20px; overflow: hidden; border-radius: 8px;">
                                <img src="/musictimes/image/product/<?= $product['product_image'] ?>" style="object-fit: contain; width: 100%; height: 100%; transition: transform 0.3s ease;" class="card-img-top" alt="..." onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            </div>
                            <div>
                                <div style="font-weight: bold"><?php echo __('product_name'); ?></div>
                                <div class="mb-2"> <?= $product['product_name'] ?></div>
                                <div style="font-weight: bold"><?php echo __('category_name'); ?></div>
                                <div class="mb-2"><?= $product['category_name'] ?></div>
                                <div style="font-weight: bold"><?php echo __('product_description'); ?></div>
                                <div class="mb-2"> <?= $product['product_desc'] ?></div>
                                <div style="font-weight: bold"><?php echo __('product_quantity'); ?></div>
                                <div class="mb-2"><?= $product['product_quantity'] ?></div>
                                <div style="font-weight: bold"><?php echo __('enterprise_name'); ?></div>
                                <div class="mb-2"><?= $product['enterprise_name'] ?></div>
                                <div style="font-weight: bold"><?php echo __('product_price'); ?></div>
                                <div class="ms-0" style="transition: font-size 0.3s; font-weight: bold; font-size: 20px;"
                                    onmouseover="this.style.fontWeight='bold'; this.style.fontSize='200%'"
                                    onmouseout="this.style.fontWeight='bold'; this.style.fontSize='20px'">
                                    RM <?= $product['product_price'] ?></div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: flex-end">
                            <div style="margin-right: 20px">
                                <button type="button" class="btn btn-primary btn-sm"
                                        style="background-color: #000; color: #fff; font-size: 16px; font-weight: bold; border: 1px solid #fff;"
                                        onclick="updateQuantity('minus',<?= $product['product_quantity'] ?>,<?= $product['product_id'] ?>)">-
                                </button>
                                <span class="px-2" id="display_quantity_<?= $product['product_id'] ?>" style="font-weight: bold; font-size: 16px;">1</span>
                                <button type="button" class="btn btn-primary btn-sm"
                                        style="background-color: #000; color: #fff; font-size: 16px; font-weight: bold; border: 1px solid #fff;"
                                        onclick="updateQuantity('add',<?= $product['product_quantity'] ?>,<?= $product['product_id'] ?>)">+
                                </button>
                            </div>
                            <button type="button" class="btn btn-primary"
                                    onclick="addToCart(<?= $product['product_id'] ?>)"
                                    style="font-weight: bold; background-color: #000; color: #fff; border: 1px solid #fff; font-size: 16px; font-weight: bold;"
                                    onmouseover="this.style.backgroundColor='#fff'; this.style.color='#000'; this.style.border='1px solid #000;'"
                                    onmouseout="this.style.backgroundColor='#000'; this.style.color='#fff'; this.style.border='1px solid black;'">
                                <i class="fa-solid fa-basket-shopping fa-bounce"></i> <?php echo __('add_to_cart'); ?>
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            <?php }
        } ?>
    </section>
</main>


<?php include './layout/footer.php';?>
</body>
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
    } else if (operation == 'minus') {
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
            alert('Product added to cart');
            window.location.reload()
        },
        error: function (xhr, status, error) {
            // Handle error response
            console.error('Error adding product to cart');
        }
    });
}


</script>

</html>
