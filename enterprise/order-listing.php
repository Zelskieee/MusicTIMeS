<?php 
include './layout/main.php'; 
require '../vendor/autoload.php'; // Adjust the path as necessary

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
?>
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

        .order-status-preparing {
            color: yellow;
        }

        .order-status-cancel {
            color: red;
        }

        .order-status-shipping, .order-status-complete {
            color: green;
        }

        .btn-disabled {
            cursor: not-allowed;
            opacity: 0.6;
            border-radius: 15px;
            color: black;
            background-color: white;
            padding: 10px;
        }

        .ship-button {
            background-color: white;
            color: black;
            border: 1px solid black;
            border-radius: 15px; 
            padding: 10px;
            font-size: 16px; 
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease; 
        }

        .ship-button:hover {
            background-color: black;
            color: white;
        }
    </style>
</head>
<main>
    <?php include './layout/menu.php'; ?>

    <?php 
        $enterprise_id = $_SESSION['enterprise_id'];
        $order_status_filter = isset($_GET['order_status']) ? $_GET['order_status'] : '';
        $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'latest';
        $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

        $query = "SELECT
            `order`.order_id,
            `order`.customer_id,
            customers.customer_name,
            `order`.order_status,
            `order`.tracking_number,
            DATE_FORMAT(`order`.created_order, '%d/%m/%Y %h:%i %p') AS created_order,
            DATE_FORMAT(`order`.updated_order, '%d/%m/%Y %h:%i %p') AS updated_order
        FROM
            `order`
        JOIN
            customers ON `order`.customer_id = customers.customer_id
        WHERE
            `order`.order_id IN (
                SELECT DISTINCT oi.order_id 
                FROM order_item oi 
                JOIN product p ON p.product_id = oi.product_id
                WHERE p.enterprise_id = $enterprise_id
            )";

        if ($order_status_filter) {
            $query .= " AND LOWER(`order`.order_status) = LOWER('$order_status_filter')";
        }

        if ($search_query) {
            $query .= " AND (customers.customer_name LIKE '%$search_query%' OR EXISTS (
                SELECT 1 FROM order_item oi
                JOIN product p ON p.product_id = oi.product_id
                WHERE oi.order_id = `order`.order_id AND p.product_name LIKE '%$search_query%'
            ))";
        }

        switch ($sort_order) {
            case 'latest':
                $query .= " ORDER BY `order`.created_order DESC";
                break;
            case 'oldest':
                $query .= " ORDER BY `order`.created_order ASC";
                break;
            case 'alphabetical':
                $query .= " ORDER BY customers.customer_name ASC";
                break;
        }

        $result = $conn->query($query);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ship_order_id']) && isset($_POST['tracking_number'])) {
            $ship_order_id = $_POST['ship_order_id'];
            $tracking_number = $_POST['tracking_number'];

            $update_status_query = "UPDATE `order` SET order_status = 'Shipping', tracking_number = ? WHERE order_id = ? AND order_status != 'Complete'";
            $stmt = $conn->prepare($update_status_query);
            $stmt->bind_param("si", $tracking_number, $ship_order_id);

            if ($stmt->execute()) {
                $customer_email_query = "SELECT customer_email FROM customers WHERE customer_id = (SELECT customer_id FROM `order` WHERE order_id = $ship_order_id)";
                $customer_email_result = $conn->query($customer_email_query);
                $customer_email_row = $customer_email_result->fetch_assoc();
                $customer_email = $customer_email_row['customer_email'];

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->Port = 587;
                    $mail->SMTPSecure = 'tls';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'musictimessystem@gmail.com';
                    $mail->Password = 'kppuqpaokzlwtcww';
                    $mail->setFrom('noreply@musictimessystem.com', 'MusicTIMeS');
                    $mail->addAddress($customer_email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Your Order Start Shipping';
                    $mailContent = "<h1>Your order start shipping today, go to Track Order page to see location of the postage</h1>
                                    <p>Thank you for ordering through MusicTIMeS</p> <br>
                                    <p>Best regards,<br><span style=\"font-weight: bold;\">MusicTIMeS</span></p>";
                    $mail->Body = $mailContent;

                    if ($mail->send()) {
                        echo "<script>
                            alert('Order status updated to Shipping and email sent to customer');
                            window.location.href = window.location.pathname;
                            </script>";
                        exit();
                    } else {
                        echo "Email could not be sent. Mailer Error: " . $mail->ErrorInfo;
                    }
                } catch (Exception $e) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }
            } else {
                echo "<script>alert('Error updating order status: " . $stmt->error . "');</script>";
            }
        }
    ?>

    <section>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h1 style="text-align: center; font-weight: bold;"><?php echo __('order_list') ?></h1>
            <div>
                <form method="GET" style="display: flex; gap: 10px;">
                    <select name="order_status" style="border-radius: 8px; border: solid 2px black; padding: 2px;">
                        <option value=""><?php echo __('all_statuses') ?></option>
                        <option value="Preparing" <?php if ($order_status_filter == 'Preparing') echo 'selected'; ?>><?php echo __('preparing') ?></option>
                        <option value="Cancel" <?php if ($order_status_filter == 'Cancel') echo 'selected'; ?>><?php echo __('cancel') ?></option>
                        <option value="Shipping" <?php if ($order_status_filter == 'Shipping') echo 'selected'; ?>><?php echo __('ship') ?></option>
                        <option value="Complete" <?php if ($order_status_filter == 'Complete') echo 'selected'; ?>><?php echo __('complete') ?></option>
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
                    <th><?php echo __('customer_name') ?></th>
                    <th><?php echo __('product_name_quantity') ?></th>
                    <th><?php echo __('total') ?> (RM)</th>
                    <th><?php echo __('status') ?></th>
                    <th><?php echo __('order_date') ?></th>
                    <th><?php echo __('update_order') ?></th>
                    <th><?php echo __('action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result->num_rows > 0) {
                    while($order = $result->fetch_assoc()) { 
                        $orderId = $order['order_id'];
                        $getProductDetails = "SELECT p.product_name, oi.order_quantity, p.product_price 
                                              FROM order_item oi 
                                              JOIN product p ON p.product_id = oi.product_id 
                                              WHERE oi.order_id = $orderId AND p.enterprise_id = $enterprise_id";
                        $productDetailsResult = $conn->query($getProductDetails);
                        $totalAmount = 0;
                        ?>
                        <tr>
                            <td><?=$order['customer_name']?></td>
                            <td>
                                <p>
                                    <?php 
                                    $productDetails = [];
                                    while ($orderItem = $productDetailsResult->fetch_assoc()) { 
                                        $totalAmount += $orderItem['product_price'] * $orderItem['order_quantity'];
                                        $productDetails[] = $orderItem['product_name'] . ' (' . $orderItem['order_quantity'] . ')';
                                    } 
                                    echo implode(', ', $productDetails);
                                    ?>
                                </p>
                            </td>
                            <td><?=number_format($totalAmount, 2)?></td>
                            <td class="order-status-<?= strtolower($order['order_status']) ?>">
                                <?php 
                                    if ($order['order_status'] == 'Complete') {
                                        echo '<span style="color: green; font-weight: bold; font-size: 18px;">' . __('complete') . '</span>';
                                    } else if ($order['order_status'] == 'Preparing') {
                                        echo '<span style="font-weight: bold; font-size: 18px;">' . __('preparing') . '</span>';
                                    } else if ($order['order_status'] == 'Shipping') {
                                        echo '<span style="font-weight: bold; font-size: 18px;">' . __('ship') . '</span>';
                                    } else if ($order['order_status'] == 'Cancel') {
                                        echo '<span style="color: red; font-weight: bold; font-size: 18px;">' . __('cancel') . '</span>';
                                    }
                                ?>
                            </td>
                            <td><?=$order['created_order']?></td>
                            <td><?=$order['updated_order']?></td>
                            <td>
                                <?php if (strtolower($order['order_status']) === 'cancel' || strtolower($order['order_status']) === 'shipping' || strtolower($order['order_status']) === 'complete') { ?>
                                    <button class="btn-disabled" disabled><?php echo __('ship'); ?></button>
                                <?php } else { ?>
                                    <form method="POST" onsubmit="return promptTrackingNumber(this);">
                                        <input type="hidden" name="ship_order_id" value="<?=$order['order_id']?>">
                                        <button type="submit" class="ship-button"><i class="fa-solid fa-truck-fast fa-beat"></i> <?php echo __('ship'); ?></button>
                                    </form>
                                <?php } ?>
                            </td>
                        </tr>
                <?php }} else { ?>
                    <tr>
                        <td colspan="7" style="color: red;"><i class="fa-solid fa-face-frown-open fa-bounce"></i> <?php echo __('no_data') ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
</main>
<?php include './layout/footer.php'; ?>
<script>
    function promptTrackingNumber(form) {
        var trackingNumber = prompt("Please enter the tracking number:");
        if (trackingNumber != null && trackingNumber != "") {
            form.appendChild(createHiddenInput("tracking_number", trackingNumber));
            return true;
        } else {
            alert("Tracking number is required.");
            return false;
        }
    }

    function createHiddenInput(name, value) {
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = name;
        input.value = value;
        return input;
    }
</script>
</body>
</html>
