<?php include './layout/main.php'; ?>

<head>
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: "Freeman", sans-serif;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #dee2e6; 
        }

        .table th, .table td {
            border: 1px solid #dee2e6; 
        }

        .table thead th {
            background-color: lightgrey;
            text-align: center;
            border-top: none; 
        }

        .table tbody tr:first-child td:first-child {
            border-top-left-radius: 0px;
        }

        .table tbody tr:first-child td:last-child {
            border-top-right-radius: 0px;
        }

        .table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 20px;
        }

        .table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 20px;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table img {
            object-fit: cover;
            width: 200px;
            height: 100px;
            transition: transform 0.3s ease;
        }
        
        .table img:hover {
            transform: scale(1.1);
        }

        .btn.btn-secondary {
            font-weight: bold;
            background-color: white;
            border: solid 1px black;
            color: black;
        }

        .btn.btn-secondary:hover {
            font-weight: bold;
            background-color: black;
            color: white;
        }

        .btn.btn-danger.btn-sm:hover {
            font-weight: bold;
            background-color: white;
            color: red;
            border: solid 1px red;
        }

        .filter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-form {
            display: flex;
            gap: 10px;
        }

        .filter-options {
            margin-bottom: 20px;
            margin-left: 60%;
        }
    </style>
</head>

<main>
    <?php include './layout/menu.php'; ?>

    <?php 
        $enterprise_id = $_SESSION['enterprise_id'];
        
        // Get filter and sort parameters from GET request
        $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'latest';
        $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
        $product_status_filter = isset($_GET['product_status']) ? $_GET['product_status'] : '';

        // Prepare the base SQL query
        $query = "SELECT product.*, category.category_name 
                  FROM product 
                  LEFT JOIN category ON category.category_id = product.category_id 
                  WHERE product.enterprise_id = $enterprise_id";

        // Add filter for product status if selected
        if ($product_status_filter) {
            $query .= " AND product.product_status = '$product_status_filter'";
        }

        // Add search functionality
        if ($search_query) {
            $query .= " AND product.product_name LIKE '%$search_query%'";
        }

        // Add sorting functionality
        switch ($sort_order) {
            case 'latest':
                $query .= " ORDER BY product.product_id DESC";
                break;
            case 'oldest':
                $query .= " ORDER BY product.product_id ASC";
                break;
            case 'alphabetical':
                $query .= " ORDER BY product.product_name ASC";
                break;
        }

        $result = $conn->query($query);
    ?>

    <section>
        <div class="filter-container">
            <h1 style="font-weight: bold;"><?php echo __('product_list')?></h1>
            <div>
                <a href="/musictimes/enterprise/product-add.php" class="btn btn-secondary"><i class="fa-solid fa-plus fa-beat-fade"></i>  <?php echo __('add_product')?></a>
            </div>
        </div>
        <div class="filter-options">
            <form method="GET" class="filter-form">
                <select name="product_status" style="border-radius: 8px; border: solid 2px black; padding: 2px;">
                    <option value=""><?php echo __('all_statuses') ?></option>
                    <option value="Available" <?php if ($product_status_filter == 'Available') echo 'selected'; ?>><?php echo __('available') ?></option>
                    <option value="Not Available" <?php if ($product_status_filter == 'Not Available') echo 'selected'; ?>><?php echo __('not_available') ?></option>
                </select>
                <select name="sort_order" style="border-radius: 8px; border: solid 2px black; padding: 2px;">
                    <option value="latest" <?php if ($sort_order == 'latest') echo 'selected'; ?>><?php echo __('latest') ?></option>
                    <option value="oldest" <?php if ($sort_order == 'oldest') echo 'selected'; ?>><?php echo __('oldest') ?></option>
                    <option value="alphabetical" <?php if ($sort_order == 'alphabetical') echo 'selected'; ?>><?php echo __('alphabetical') ?></option>
                </select>
                <input type="text" name="search_query" autocomplete="off" style="border-radius: 8px;" placeholder="<?php echo __('search_products') ?>" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" style="border-radius: 8px; transition: background-color 0.5s, color 0.5s; background-color: white; color: black;" onmouseover="this.style.backgroundColor='black'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='black';"><i class="fa-solid fa-magnifying-glass fa-beat-fade"></i> <?php echo __('search'); ?></button>
            </form>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?php echo __('image')?></th>
                    <th><?php echo __('category')?></th>
                    <th><?php echo __('name')?></th>
                    <th><?php echo __('description')?></th>
                    <th><?php echo __('quantity')?></th>
                    <th><?php echo __('tag')?></th>
                    <th><?php echo __('price')?> (RM)</th>
                    <th><?php echo __('status')?></th>
                    <th><?php echo __('action')?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result->num_rows > 0) {
                    while($product = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><img src="/musictimes/image/product/<?=$product['product_image']?>" alt="" srcset=""></td>
                            <td><?=$product['category_name']?></td>
                            <td><?=$product['product_name']?></td>
                            <td><?=$product['product_desc']?></td>
                            <td style="<?php if ($product['product_quantity'] == 0) echo 'color: red; font-weight: bold;'; ?>">
                                <?=$product['product_quantity']?>
                            </td>
                            <td><?=$product['product_tag']?></td>
                            <td><?=$product['product_price']?></td>
                            <td>
                                <?php
                                if ($product['product_status'] == 'Available') {
                                    echo '<span style="color: green; font-weight: bold; font-size: 18px;">' . __('available') . '</span>';
                                } else {
                                    echo '<span style="color: red; font-weight: bold; font-size: 18px;">' . __('not_available') . '</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="/musictimes/enterprise/product-edit.php?id=<?=$product['product_id']?>" class="btn btn-secondary btn-sm" style="font-weight: bold;"><i class="fa-solid fa-file-pen fa-beat-fade"></i>  <?php echo __('edit')?></a>
                                <a href="/musictimes/enterprise/controller/product-delete.php?id=<?=$product['product_id']?>" class="btn btn-danger btn-sm" style="font-weight: bold;"><i class="fa-regular fa-trash-can fa-beat-fade"></i>  <?php echo __('delete')?></a>
                            </td>
                        </tr>
                <?php }} else { ?>
                    <tr>
                        <td colspan="9" style="color: red;"><i class="fa-solid fa-face-frown-open fa-bounce"></i> <?php echo __('no_product') ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
</main>
<?php include './layout/footer.php'; ?>
</body>
</html>
