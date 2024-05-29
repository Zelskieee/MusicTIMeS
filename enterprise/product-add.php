<?php include './layout/main.php';?>

<head>
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Freeman", sans-serif;
        }
    </style>
</head>

<main>
    <?php include './layout/menu.php';?>

    <?php 
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
    ?>

    <?php 
        $enterprise_id = $_SESSION['enterprise_id'];
        $query = "SELECT * FROM category WHERE enterprise_id = $enterprise_id";
        $result = $conn->query($query);
    ?>
    <section>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0" style="font-weight: bold;"><?php echo __('add_product')?></h1>
            <a href="/musictimes/enterprise/product-listing.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left fa-beat-fade"></i><strong>  <?php echo __('back')?></strong></a>
        </div>
        <form action="/musictimes/enterprise/controller/product-add.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="productImage" style="font-weight: bold;"><?php echo __('image')?></label><br>
                <input type="file" accept="image/*" class="form-control-file" name="image" id="upload">
            </div>
            <br>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('product_name')?></label>
                <input type="text" name="product_name" class="form-control" autocomplete="off" placeholder="Enter Product Name" required>
            </div>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('category')?></label>
                <?php if ($result->num_rows > 0) { ?>
                    <select name="category_id" class="form-control" required>
                        <?php while($category = $result->fetch_assoc()) { ?>
                             <option value="<?=$category['category_id']?>"><?=$category['category_name']?></option>
                        <?php } ?>
                    </select>
                <?php } else { ?>
                    <p style="color: red; font-weight: bold;"><i class="fa-solid fa-face-smile-beam fa-beat-fade"></i> <?php echo __('no_category'); ?></p>
                <?php } ?>
            </div>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('product_description')?></label>
                <textarea name="product_desc" cols="30" rows="10" required class="form-control" placeholder="Enter Product Description"></textarea>
            </div>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('product_quantity')?></label>
                <input type="number" name="product_quantity" placeholder="Enter Product Quantity" class="form-control" value="1" required>
            </div>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('product_price')?> (RM)</label>
                <input type="number" name="product_price" value="1" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('product_tag')?></label>
                <input type="text" name="product_tag" autocomplete="off" placeholder="Enter Product Tag" class="form-control">
            </div>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('product_status')?></label>
                <select name="product_status" class="form-select">
                    <option value="Available"><?php echo __('available')?></option>
                    <option value="Not Available"><?php echo __('not_available')?></option>
                </select>
            </div>
            <?php if ($result->num_rows > 0) { ?>
                <div class="mb-3">
                    <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-circle-check fa-beat-fade"></i><strong>  <?php echo __('submit')?></strong></button>
                </div>
            <?php } ?>
        </form>
    </section>
</main>
<?php include './layout/footer.php';?>
</body>
</html>
