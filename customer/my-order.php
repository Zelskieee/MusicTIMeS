<?php include './layout/main.php'; ?>

<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<main>
<?php
// Get filter and sort parameters from GET request
$order_status_filter = isset($_GET['order_status']) ? $_GET['order_status'] : '';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'latest';
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Prepare the base SQL query
$query = "SELECT `order`.*, GROUP_CONCAT(p.product_name, ' (', oi.order_quantity, ')') AS product_names_quantities, 
          GROUP_CONCAT(DISTINCT e.enterprise_name) AS enterprise_names 
          FROM `order`
          LEFT JOIN order_item oi ON oi.order_id = `order`.order_id
          LEFT JOIN product p ON p.product_id = oi.product_id
          LEFT JOIN enterprise e ON p.enterprise_id = e.enterprise_id
          WHERE customer_id = $_SESSION[customer_id] AND `order`.order_status != 'Cancel'";

// Add search functionality
if ($search_query) {
    $query .= " AND (p.product_name LIKE '%$search_query%' OR e.enterprise_name LIKE '%$search_query%')";
}

// Add order status filter
if ($order_status_filter) {
    $query .= " AND `order`.order_status = '$order_status_filter'";
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
    <title><?php echo __('my_order')?></title>
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
        <h1 style="font-weight: bold; font-size:30px; margin-right:30px; width:100%"><?php echo __('my_order')?></h1>
        <button class="btn btn-secondary button" type="button">
            <a href="./my-order.php"><?php echo __('my_order')?></a>
        </button>
        <button class="btn btn-default" type="button">
            <a href="./cancel-order.php"><?php echo __('cancel_order')?></a>
        </button>
        <button class="btn btn-default" type="button">
            <a href="./track-order.php"><?php echo __('track_order')?></a>
        </button>
        <button class="btn btn-default" type="button">
            <a href="./customer-feedback.php"><?php echo __('feedback')?></a>
        </button>
    </div>

    <div id="form-filter">
    <form id="filter-form" method="GET" style="display: flex; align-items: center; gap: 10px; margin-left: 61%; margin-bottom: 20px;">
            <select name="order_status" style="border-radius: 8px; border: solid 2px black; padding: 2px;">
                <option value=""><?php echo __('select_order_status'); ?></option>
                <option value="Preparing" <?php if ($order_status_filter == 'Preparing') echo 'selected'; ?>><?php echo __('preparing'); ?></option>
                <option value="Shipping" <?php if ($order_status_filter == 'Shipping') echo 'selected'; ?>><?php echo __('ship'); ?></option>
                <option value="Complete" <?php if ($order_status_filter == 'Complete') echo 'selected'; ?>><?php echo __('complete'); ?></option>
            </select>
            <select name="sort_order" style="border-radius: 8px; border: solid 2px black; padding: 2px;">
                <option value="latest" <?php if ($sort_order == 'latest') echo 'selected'; ?>><?php echo __('latest'); ?></option>
                <option value="oldest" <?php if ($sort_order == 'oldest') echo 'selected'; ?>><?php echo __('oldest'); ?></option>
                <option value="alphabetical" <?php if ($sort_order == 'alphabetical') echo 'selected'; ?>><?php echo __('alphabetical'); ?></option>
            </select>
            <input type="text" name="search_query" id="filter-input" style="border-radius: 8px;" autocomplete="off" placeholder="<?php echo __('search_products'); ?>" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" style="border-radius: 8px; transition: background-color 0.5s, color 0.5s; background-color: black; color: white;" onmouseover="this.style.backgroundColor='white'; this.style.color='black';" onmouseout="this.style.backgroundColor='black'; this.style.color='white';"><i class="fa-solid fa-magnifying-glass fa-beat-fade"></i> <?php echo __('search'); ?></button>
        </form>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?php echo __('enterprise_name')?></th>
                <th><?php echo __('product_name_quantity') ?></th>
                <th><?php echo __('total_amount')?> (RM)</th>
                <th><?php echo __('order_date')?></th>
                <th><?php echo __('status')?></th>
                <th><?php echo __('action')?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($customer = $result->fetch_assoc()) { 
                $orderId = $customer['order_id'];
                $orderItemsStr = $customer['product_names_quantities'];
                $enterpriseNamesStr = $customer['enterprise_names'];
            ?>
            <tr>
                <td class="text-center"><?php echo $enterpriseNamesStr; ?></td>
                <td class="text-center"><?php echo $orderItemsStr; ?></td>
                <td class="text-center" style="font-weight: bold;"><?php echo $customer['total_amount']; ?></td>
                <td class="text-center" style="font-weight: bold;"><?php
                    $orderDate = $customer['created_order'];

                    $dateTime = new DateTime($orderDate);

                    $formattedDate = $dateTime->format('d/m/Y');
                    $formattedTime = $dateTime->format('h:i A');

                    echo $formattedDate . ' ' . $formattedTime;
                    ?>
                </td>
                <td class="text-center" style="font-weight: bold;">
                    <?php 
                    if ($customer['order_status'] == 'Complete') {
                        echo '<span style="color: green; font-weight: bold; font-size: 18px;">' . __('complete') . '</span>';
                    } else if ($customer['order_status'] == 'Preparing') {
                        echo '<span style="font-weight: bold; font-size: 18px;">' . __('preparing') . '</span>';
                    }
                    else if ($customer['order_status'] == 'Shipping') {
                        echo '<span style="font-weight: bold; font-size: 18px;">' . __('ship') . '</span>';
                    }
                    ?>
                </td>
                <td>
                    <button class="btn btn-primary" onclick="location.href='my-order-detail.php?order_id=<?php echo $orderId; ?>'" type="button"><i class="fa-regular fa-eye fa-beat"></i> <?php echo __('view_order')?></button>
                    <?php if ($customer['order_status'] !== 'Complete') { ?>
                    <button class="btn btn-danger btn-sm cancel-button" data-order-id="<?php echo $orderId; ?>" type="button"><i class="fa-regular fa-rectangle-xmark fa-beat"></i> <?php echo __('cancel_order')?></button>
                    <?php } ?>
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
        $('.cancel-button').click(function () {
            var orderId = $(this).data('order-id');
            var cancelReason = prompt('Please enter the reason for cancellation:');
            
            // Check if cancelReason is not null and not empty
            if (cancelReason !== null && cancelReason.trim() !== '') { 
                $.ajax({
                    url: '../customer/controller/cancel-order.php',
                    method: 'POST',
                    data: {orderId: orderId, cancelReason: cancelReason},
                    success: function (response) {
                        console.log(response);
                        if (response.trim() === 'success') {
                            alert('Order cancelled successfully');
                            console.log('Order cancelled, reloading page...');
                            
                            // Alternative reload methods
                            window.location.href = window.location.href;
                            // OR
                            // window.location.reload(true); // Forces reload from the server
                        } else {
                            alert('Failed to cancel order');
                            console.log('test debug');
                        }
                    },
                    error: function (err) {
                        console.log(err);
                        alert('Failed to cancel order. Please try again later');
                    }
                });
            } else {
                alert('Cancellation reason is required. Please provide a reason.');
            }
        });
    });
</script>


</section>
</main>
<?php include './layout/footer.php'; ?>
</body>
</html>
