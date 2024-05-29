<?php include './layout/main.php'; ?>

<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$customerId = $_SESSION['customer_id']; // Assuming customer ID is stored in session
?>
<head>
    <title><?php echo __('feedback')?></title>
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

        .btn.btn-primary:hover a {
            color: black; /* Change link color to black on button hover */
        }

        .btn.btn-primary a {
            color: white; /* Ensure the link is white initially */
            text-decoration: none; /* Remove underline */
        }

        .btn.btn-primary:hover a {
            color: black; /* Ensure the link color changes to black on hover */
        }

        .btn.btn-disabled {
            background-color: lightgray;
            color: darkgray;
            border: solid 1px gray;
            cursor: not-allowed;
        }
    </style>
</head>
<main>
<?php
// Get filter and sort parameters from GET request
$rating_filter = isset($_GET['rating']) ? $_GET['rating'] : '';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'latest';
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Prepare the base SQL query
$query = "SELECT o.order_id, o.order_status, p.product_name, oi.order_quantity, f.rating, f.feedback_media, f.feedback_desc, p.product_id, e.enterprise_name
          FROM `order` o
          JOIN `order_item` oi ON o.order_id = oi.order_id
          JOIN `product` p ON oi.product_id = p.product_id
          JOIN `enterprise` e ON p.enterprise_id = e.enterprise_id
          LEFT JOIN `feedback` f ON f.product_id = p.product_id AND f.customer_id = o.customer_id AND f.order_id = o.order_id
          WHERE o.customer_id = $customerId AND o.order_status = 'Complete'";

// Add search functionality
if ($search_query) {
    $query .= " AND p.product_name LIKE '%$search_query%'";
}

// Add rating filter
if ($rating_filter) {
    $query .= " AND f.rating = $rating_filter";
}

// Add sorting functionality
switch ($sort_order) {
    case 'latest':
        $query .= " ORDER BY o.order_id DESC";
        break;
    case 'oldest':
        $query .= " ORDER BY o.order_id ASC";
        break;
    case 'alphabetical':
        $query .= " ORDER BY p.product_name ASC";
        break;
    case 'rating':
        $query .= " ORDER BY f.rating DESC";
        break;
    default:
        $query .= " ORDER BY o.order_id DESC";
        break;
}

$result = $conn->query($query);
?>

<section style="border:1px solid lightgray; margin:20px; border-radius:8px; box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);">
    <div style="display:flex; align-items:center; width:80%; justify-content: space-between; margin-bottom:20px;">
        <h1 style="font-weight: bold; font-size:30px; margin-right:30px; width: 100%;"><?php echo __('feedback')?></h1>
        
        <button class="btn btn-default" type="button">
            <a href="./my-order.php"><?php echo __('my_order')?></a></button>
        <button class="btn btn-default" type="button">
            <a href="./cancel-order.php"><?php echo __('cancel_order')?></a></button>
        <button class="btn btn-default" type="button">
            <a href="./track-order.php"><?php echo __('track_order')?></a></button>
        <button class="btn btn-secondary" type="button">
            <a href="./customer-feedback.php"><?php echo __('feedback')?></a>
        </button>
    </div>

    <form id="filter-form" method="GET" style="display: flex; align-items: center; gap: 10px; margin-left: 64%; margin-bottom: 20px;">
            <select name="rating" style="border-radius: 8px; border: solid 2px black; padding: 2px;">
                <option value=""><?php echo __('select_rating'); ?></option>
                <option value="5" <?php if ($rating_filter == '5') echo 'selected'; ?>>5 <?php echo __('star') ?></i></option>
                <option value="4" <?php if ($rating_filter == '4') echo 'selected'; ?>>4 <?php echo __('star') ?></i></option>
                <option value="3" <?php if ($rating_filter == '3') echo 'selected'; ?>>3 <?php echo __('star') ?></i></option>
                <option value="2" <?php if ($rating_filter == '2') echo 'selected'; ?>>2 <?php echo __('star') ?></i></option>
                <option value="1" <?php if ($rating_filter == '1') echo 'selected'; ?>>1 <?php echo __('star') ?></i></option>
            </select>
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
                <th><?php echo __('enterprise_name')?></th>
                <th><?php echo __('product_name_quantity')?></th>
                <th><?php echo __('feedback_rating')?></th>
                <th><?php echo __('feedback_media')?></th>
                <th><?php echo __('feedback_description')?></th>
                <th><?php echo __('action')?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td class="text-center"><?php echo $row['enterprise_name']; ?></td>
                    <td class="text-center"><?php echo $row['product_name'] . ' (' . $row['order_quantity'] . ')'; ?></td>
                    <td class="text-center">
                        <?php
                        if ($row['rating']) {
                            $rating = $row['rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $rating) {
                                    echo '<span class="star" style="color: #ffbf03de;">&#9733;</span>';
                                } else {
                                    echo '<span class="star" style="color: gray;">&#9733;</span>';
                                }
                            }
                        } else {
                            echo '<i class="fa-solid fa-face-frown fa-beat" style="color: red;"></i> &nbsp;<span style="color: red; font-size: 18px;">' . __('not_rated') . '</span>';
                        }
                        ?>
                    </td>
                    <td class="text-center">
                        <?php 
                        if ($row['feedback_media']) {
                            echo "<img src='{$row['feedback_media']}' width='100px' />";
                        } else {
                            echo '<i class="fa-solid fa-face-frown fa-beat" style="color: red;"></i> &nbsp;<span style="color: red; font-size: 18px;">' . __('not_rated') . '</span>';
                        }
                        ?>
                    </td>
                    <td class="text-center">
                        <?php echo $row['feedback_desc'] ?: '<i class="fa-solid fa-face-frown fa-beat" &nbsp; style="color: red;"></i>' . '<span style="color: red; font-size: 18px;">' . __('not_rated') . '</span>'; ?>
                    </td>
                    <td class="text-center">
                        <?php if ($row['rating']) { ?>
                            <button class="btn btn-disabled" disabled><i class="fa-solid fa-face-smile-wink fa-beat" style="cursor: not-allowed;"></i> <?php echo __('rated') ?></button>
                        <?php } else { ?>
                            <button class="btn btn-primary">
                                <a href="create-feedback.php?product_id=<?php echo $row['product_id']; ?>&order_id=<?php echo $row['order_id']; ?>">
                                    <i class="fa-solid fa-star fa-bounce"></i> <?php echo __('rate'); ?>
                                </a>
                            </button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="6" class="text-center" style="color: red;"><i class="fa-solid fa-face-frown-open fa-beat-fade"></i>&nbsp<?php echo __('no_data') ?></td></tr>
        <?php } ?>
        </tbody>
    </table>
</section>
</main>
<?php include './layout/footer.php'; ?>
</body>
</html>
