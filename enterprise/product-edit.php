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
        $query = 'select * from category';
        $categories = mysqli_query($conn, $query);
        $totalCategoriesRows = mysqli_num_rows($categories);

        $query = 'SELECT * FROM product WHERE product_id='.$_GET['id'].'';
        $result = mysqli_query($conn, $query);
        $product = $result->fetch_assoc();
    ?>
    <section>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0" style="font-weight: bold;"><?php echo __('edit_product')?></h1>
            <a href="/musictimes/enterprise/product-listing.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left fa-beat-fade"></i><strong> <?php echo __('back')?></strong></a>
        </div>
        <form action="/musictimes/enterprise/controller/product-edit.php?id=<?=$product['product_id']?>" method="post"  enctype="multipart/form-data">
            <img src="/musictimes/image/product/<?=$product['product_image']?>" alt="" srcset="" width="300px" id="img">
            <div class="form-group">
                <label for="productImage" style="font-weight: bold;"><?php echo __('image')?></label><br>
                <input type="file" accept="image/*" class="form-control-file" name="image" id="upload">
            </div>
            <br>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('product_name')?></label>
                <input type="text" name="product_name" class="form-control" placeholder="Enter Product Name" value="<?=$product['product_name']?>" required>
            </div>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('category')?></label>
                <select name="category_id" class="form-control dropdown-icon" required>
                <?php if($totalCategoriesRows > 0){
                        while ($data = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?=$data['category_id']?>" <?php if($product['category_id'] == $data['category_id']){ echo 'selected';} ?>><?=$data['category_name']?></option>
                <?php } }?>
                </select>
            </div>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('product_description')?></label>
                <textarea name="product_desc" cols="30" rows="10" required class="form-control" placeholder="Enter Product Description"><?=$product['product_desc']?></textarea>
            </div>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('product_quantity')?></label>
                <input type="number" name="product_quantity" placeholder="Enter Product Quantity" class="form-control" value="<?=$product['product_quantity']?>" required>
            </div>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('product_price')?> (RM)</label>
                <input type="number" name="product_price" value="<?=$product['product_price']?>" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('product_tag')?></label>
                <input type="text" name="product_tag" placeholder="Enter Product Tag" class="form-control" value="<?=$product['product_tag']?>">
            </div>
            <div class="mb-3">
                <label for="" class="form-label" style="font-weight: bold;"><?php echo __('product_status')?></label>
                <select name="product_status" class="form-select">
                    <option value="Available" <?php if($product['product_status'] == 'Available'){ echo 'selected';}?>><?php echo __('available')?></option>
                    <option value="Not Available" <?php if($product['product_status'] == 'Not Available'){ echo 'selected';}?>><?php echo __('not_available')?></option>
                </select>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-circle-check fa-beat-fade"></i><strong>  <?php echo __('submit')?></strong></button>
            </div>
        </form>
    </section>
</main>
<?php include './layout/footer.php';?>
<script>
     $('#upload').change(function(){
        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) 
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
        else
        {
            $('#img').attr('src', '/assets/no_preview.png');
        }
    });
</script>