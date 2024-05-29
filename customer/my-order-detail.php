<?php include './layout/main.php'; ?>

<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<head>
    <title><?php echo __('order_detail')?></title>
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
            width: 100px;
            height: 100px;
            transition: transform 0.3s ease;
        }
        
        .table img:hover {
            transform: scale(1.1);
        }

        .btn-secondary {
            border: 1px solid black;
            background-color: black;
            color: white;
            width: 22%;
            margin-right: 20px;
            font-weight: bold;
            box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }

        .btn-secondary a {
            color: white; /* Ensure the link is white initially */
            font-size: 20px;
            text-decoration: none; /* Remove underline */
        }

        .btn-secondary:hover {
            background-color: white;
            color: black;
            border: solid 1px black;
        }

        .btn-secondary:hover a {
            color: black;
        }

        .btn-default {
            border: 1px solid black;
            background-color: white;
            color: black;
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
<main>
<?php
    $orderId = $_GET['order_id'];
    $query = "SELECT oi.*, p.product_name, p.product_price, p.product_image, e.enterprise_name, o.order_status 
              FROM `order_item` oi 
              JOIN product p ON oi.product_id = p.product_id 
              JOIN enterprise e ON p.enterprise_id = e.enterprise_id 
              JOIN `order` o ON oi.order_id = o.order_id
              WHERE oi.order_id = $orderId";
    $result = $conn->query($query);
?>

<section style="border:1px solid lightgray; margin:20px; border-radius:8px; box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);">
    <div style="display:flex; align-items:center; width:100%; justify-content: space-between; margin-bottom:20px;">
        <h1 style="font-weight: bold; font-size:30px; margin-right:30px"><?php echo __('order_detail')?></h1>
        <div style="display:flex;">
            <button class="btn btn-default" type="button" style='border:1px solid black;'><a href="my-order.php?order_id=<?php echo $orderId ?>" style="color:black"><i class="fa-solid fa-arrow-left fa-beat-fade"></i> <?php echo __('back')?></a></button>
        </div>
    </div>

    <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div style="font-size: 20px; font-weight: bold; color: white;"><?php echo __('order_detail')?></div>
        <button class="btn btn-secondary" type="button"><a href="download-invoice.php?order_id=<?php echo $orderId ?>"><i class="fa-regular fa-file-pdf fa-bounce"></i> <?php echo __('invoice') ?></a></button>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?php echo __('image')?></th>
                <th><?php echo __('product_name')?></th>
                <th><?php echo __('quantity')?></th>
                <th><?php echo __('price')?> (RM)</th>
                <th><?php echo __('enterprise_name')?></th>
                <th><?php echo __('status')?></th>
            </tr>
        </thead>
        <tbody>
        <?php while ($data = $result->fetch_assoc()) { ?>
            <tr>
                <td><img src="../image/product/<?php echo $data['product_image']; ?>" alt="<?php echo $data['product_name']; ?>"></td>
                <td><?php echo $data['product_name']; ?></td>
                <td><?php echo $data['order_quantity']; ?></td>
                <td><?php echo number_format($data['order_quantity'] * $data['product_price'], 2); ?></td>
                <td><?php echo $data['enterprise_name']; ?></td>
                <td style="color: <?php echo ($data['order_status'] == 'Complete') ? 'green' : 'inherit'; ?>; font-weight: <?php echo ($data['order_status'] == 'Complete') ? 'bold' : 'normal'; ?>; font-size: <?php echo ($data['order_status'] == 'Complete') ? '18px' : 'inherit'; ?>;">
                <?php echo __('complete'); ?>
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
