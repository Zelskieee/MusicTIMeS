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
    </style>
</head>
<main>
    <?php include './layout/menu.php'; ?>

    <?php 
        // Get enterprise_id from the session (adjust the variable name if needed)
        $enterprise_id = isset($_SESSION['enterprise_id']) ? $_SESSION['enterprise_id'] : null;

        // Validate and sanitize the enterprise_id to prevent SQL injection
        $enterprise_id = intval($enterprise_id);

        // Get filter, sort, and search parameters from GET request
        $payment_method_filter = isset($_GET['payment_method']) ? $_GET['payment_method'] : '';
        $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'latest';
        $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

        // Prepare the base SQL query
        $query = "
            SELECT 
                payment.payment_id,
                payment.order_id,
                payment.transaction_id,
                payment.payment_method,
                (SELECT SUM(order_item.order_quantity * product.product_price)
                FROM order_item
                JOIN product ON order_item.product_id = product.product_id
                WHERE order_item.order_id = payment.order_id AND product.enterprise_id = ?) AS total_amount
            FROM payment
            JOIN `order` ON payment.order_id = `order`.order_id
            WHERE payment.order_id IN (
                SELECT order_item.order_id
                FROM order_item
                JOIN product ON order_item.product_id = product.product_id
                WHERE product.enterprise_id = ?
            )
        ";

        // Add filter for payment method if selected
        if ($payment_method_filter) {
            $query .= " AND payment.payment_method = ?";
        }

        // Add search functionality
        if ($search_query) {
            $query .= " HAVING total_amount LIKE ?";
        }

        // Add sorting functionality
        switch ($sort_order) {
            case 'latest':
                $query .= " ORDER BY payment.payment_id DESC";
                break;
            case 'oldest':
                $query .= " ORDER BY payment.payment_id ASC";
                break;
            case 'alphabetical':
                $query .= " ORDER BY payment.payment_method ASC";
                break;
        }

        // Prepare and execute the SQL query with prepared statement
        $stmt = $conn->prepare($query);

        // Prepare parameters for binding
        $query_params = [$enterprise_id, $enterprise_id];
        $param_types = 'ii';

        if ($payment_method_filter) {
            $query_params[] = &$payment_method_filter;
            $param_types .= 's';
        }

        if ($search_query) {
            $like_query = '%' . $search_query . '%';
            $query_params[] = &$like_query;
            $param_types .= 's';
        }

        $stmt->bind_param($param_types, ...$query_params);
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();
    ?>
    <section>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h1 style="text-align: center; font-weight: bold;"><?php echo __('payment_list')?></h1>
            <div>
                <form method="GET" style="display: flex; gap: 10px;">
                    <select name="payment_method" style="border-radius: 8px; border: solid 2px black; padding: 2px;">
                        <option value=""><?php echo __('all_methods') ?></option>
                        <option value="Online Banking" <?php if ($payment_method_filter == 'Online Banking') echo 'selected'; ?>><?php echo __('bank') ?></option>
                        <option value="Credit/Debit card" <?php if ($payment_method_filter == 'Credit/Debit card') echo 'selected'; ?>><?php echo __('card') ?></option>
                    </select>
                    <select name="sort_order" style="border-radius: 8px; border: solid 2px black; padding: 2px;">
                        <option value="latest" <?php if ($sort_order == 'latest') echo 'selected'; ?>><?php echo __('latest') ?></option>
                        <option value="oldest" <?php if ($sort_order == 'oldest') echo 'selected'; ?>><?php echo __('oldest') ?></option>
                        <option value="alphabetical" <?php if ($sort_order == 'alphabetical') echo 'selected'; ?>><?php echo __('alphabetical') ?></option>
                    </select>
                    <input type="text" name="search_query" style="border-radius: 8px;" autocomplete="off" placeholder="<?php echo __('search') ?>" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" style="border-radius: 8px; transition: background-color 0.5s, color 0.5s; background-color: white; color: black;" onmouseover="this.style.backgroundColor='black'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='black';"><i class="fa-solid fa-magnifying-glass fa-beat-fade"></i> <?php echo __('search'); ?></button>
                </form>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?php echo __('order_id')?></th>
                    <th><?php echo __('transaction_id')?></th>
                    <th><?php echo __('payment_method')?></th>
                    <th><?php echo __('total_amount')?> (RM)</th>
                    <th><?php echo __('status')?></th>
                </tr> 
            </thead>
            <tbody>
                <?php 
                if ($result->num_rows > 0) {
                    while($payment = $result->fetch_assoc()) { ?>
                         <tr>
                            <td><?=$payment['order_id']?></td>
                            <td><?=$payment['transaction_id']?></td>
                            <td>
                            <?php 
                            if ($payment['payment_method'] == 'Online Banking') {
                                echo __('bank');
                            } elseif ($payment['payment_method'] == 'Credit/Debit card') {
                                echo __('card');
                            } else {
                                echo $payment['payment_method'];
                            }
                            ?>
                            </td>
                            <td><?=number_format($payment['total_amount'], 2)?></td>
                            <td style="text-align: center; color: green; font-weight: bold; font-size: 18px;"><?php echo __('success')?></td>
                         </tr>
                <?php }} else { ?>
                    <tr>
                        <td colspan="5">
                            <?php 
                            if ($payment_method_filter == 'Online Banking') {
                                echo __('No data found');
                            } else {
                                echo __('No payment found');
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
</main>
<?php include './layout/footer.php'; ?>
</body>
</html>
