<?php include './layout/main.php'; ?>

<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$customerId = $_SESSION['customer_id']; // Assuming customer ID is stored in session
?>

<main>
<?php
// Get filter and sort parameters from GET request
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'latest';
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Prepare the base SQL query
$query = "SELECT `order`.*, GROUP_CONCAT(p.product_name, ' (', oi.order_quantity, ')') AS product_names_quantities, 
          GROUP_CONCAT(DISTINCT e.enterprise_name) AS enterprise_names 
          FROM `order`
          LEFT JOIN order_item oi ON oi.order_id = `order`.order_id
          LEFT JOIN product p ON p.product_id = oi.product_id
          LEFT JOIN enterprise e ON p.enterprise_id = e.enterprise_id
          WHERE `order`.customer_id = $customerId AND `order`.order_status NOT IN ('Preparing', 'Complete', 'Cancel')";

// Add search functionality
if ($search_query) {
    $query .= " AND (p.product_name LIKE '%$search_query%' OR e.enterprise_name LIKE '%$search_query%')";
}

// Add sorting functionality
switch ($sort_order) {
    case 'latest':
        $query .= " GROUP BY `order`.order_id ORDER BY `order`.order_id DESC";
        break;
    case 'oldest':
        $query .= " GROUP BY `order`.order_id ORDER BY `order`.order_id ASC";
        break;
    case 'alphabetical':
        $query .= " GROUP BY `order`.order_id ORDER BY p.product_name ASC";
        break;
    default:
        $query .= " GROUP BY `order`.order_id ORDER BY `order`.order_id DESC";
        break;
}

$result = $conn->query($query);
?>

<head>
    <title><?php echo __('track_order')?></title>
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

        .btn-secondary {
            border: 1px solid black;
            background-color: black;
            width: 90%;
            margin-right: 20px;
            font-weight: bold;
            box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }

        .btn-secondary a {
            color: white;
        }

        .btn-default {
            border: 1px solid black;
            background-color: white;
            width: 90%;
            margin-right: 20px;
            transition: all 0.3s ease;
        }

        .btn-default a {
            color: black;
        }

        .btn-default:hover {
            border: 1px solid lightgray;
            background-color: lightgray;
        }

        .btn.btn-danger.btn-sm:hover {
            font-weight: bold;
            background-color: white;
            color: red;
            border: solid 1px red;
        }

        .btn.btn-primary {
            background-color: black;
            color: white;
            border: solid 1px black;
        }

        .btn.btn-primary:hover {
            background-color: white;
            color: black;
            border: solid 1px black;
        }
    </style>
</head>
<section style="border:1px solid lightgray; margin:20px; border-radius:8px; box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);">
    <div style="display:flex; align-items:center; width:80%; justify-content: space-between; margin-bottom:20px;">
        <h1 style="font-weight: bold; font-size:30px; margin-right:30px; width: 100%;"><?php echo __('track_order')?></h1>
        
        <button class="btn btn-default" type="button">
            <a href="./my-order.php"><?php echo __('my_order')?></a></button>
        <button class="btn btn-default" type="button" type="submit">
            <a href="./cancel-order.php"><?php echo __('cancel_order')?></a></button>
        <button class="btn btn-secondary" type="button">
            <a href="./track-order.php"><?php echo __('track_order')?></a></button>
        <button class="btn btn-default" type="button">
            <a href="./customer-feedback.php"><?php echo __('feedback')?></a>
        </button>
    </div>

    <form id="filter-form" method="GET" style="display: flex; align-items: center; gap: 10px; margin-left: 73%; margin-bottom: 20px;">
            <select name="sort_order" style="border-radius: 8px; border: solid 2px black; padding: 2px;">
                <option value="latest" <?php if ($sort_order == 'latest') echo 'selected'; ?>><?php echo __('latest'); ?></option>
                <option value="oldest" <?php if ($sort_order == 'oldest') echo 'selected'; ?>><?php echo __('oldest'); ?></option>
                <option value="alphabetical" <?php if ($sort_order == 'alphabetical') echo 'selected'; ?>><?php echo __('alphabetical'); ?></option>
            </select>
            <input type="text" name="search_query" id="filter-input" style="border-radius: 8px;" autocomplete="off" placeholder="<?php echo __('search_products'); ?>" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" style="border-radius: 8px; transition: background-color 0.5s, color 0.5s; background-color: black; color: white;" onmouseover="this.style.backgroundColor='white'; this.style.color='black';" onmouseout="this.style.backgroundColor='black'; this.style.color='white';"><i class="fa-solid fa-magnifying-glass fa-beat-fade"></i> <?php echo __('search'); ?></button>
        </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?php echo __('enterprise_name') ?></th>
                <th><?php echo __('product_name_quantity') ?></th>
                <th><?php echo __('tracking_number') ?></th>
                <th><?php echo __('status') ?></th>
                <th><?php echo __('tracking_location') ?></th>
                <th><?php echo __('action') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($customer = $result->fetch_assoc()) { 
                $orderId = $customer['order_id'];
                $orderItemsStr = $customer['product_names_quantities'];
                $enterpriseNamesStr = $customer['enterprise_names'];
            ?>
            <tr data-order-id="<?php echo $orderId; ?>">
                <td class="text-center"><?php echo $enterpriseNamesStr; ?></td>
                <td class="text-center"><?php echo $orderItemsStr; ?></td>
                <td class="text-center"><?php echo $customer['tracking_number']; ?></td>
                <td class="text-center">
                    <?php

                    if (isset($customer['order_status']) && $customer['order_status'] === 'Shipping') {
                        echo __('ship');
                    }
                    ?>
                </td>
                <td class="text-center" style="width:300px; height: 200px;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127482.66733710174!2d101.60458777899329!3d3.138674073378546!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc362abd08e7d3%3A0x232e1ff540d86c99!2sKuala%20Lumpur%2C%20Federal%20Territory%20of%20Kuala%20Lumpur!5e0!3m2!1sen!2smy!4v1711816032867!5m2!1sen!2smy" width="300" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </td>
                <td>
                    <button class="btn btn-primary receive-btn" data-order-id="<?php echo $orderId; ?>"><i class="fa-solid fa-box-open fa-bounce"></i> <?php echo __('receive') ?></button>
                </td>
            </tr>
            <?php } ?>
        <?php } else {
            echo '<tr><td colspan="6" class="text-center" style="color: red;"><i class="fa-solid fa-face-frown-open fa-beat-fade"></i>' . '&nbsp' .  __('no_data') . '</td></tr>';
        }?>
        </tbody>
    </table>

    <script>
        $(document).ready(function () {
            $('.receive-btn').click(function () {
                var orderId = $(this).data('order-id');
                $.ajax({
                    url: './controller/complete-order.php',
                    method: 'POST',
                    data: {orderId: orderId},
                    success: function (response) {
                        console.log(response);
                        if (response.trim() === 'success') {
                            alert('Order marked as received successfully');
                            $('tr[data-order-id="' + orderId + '"]').remove();
                            window.location.href = './customer-feedback.php';
                        } else {
                            alert('Failed to mark order as received');
                        }
                    },
                    error: function (err) {
                        console.log(err);
                        alert('Failed to mark order as received. Please try again later');
                    }
                });
            });
        });
    </script>
</section>
</main>
<?php include './layout/footer.php'; ?>
</body>
</html>
