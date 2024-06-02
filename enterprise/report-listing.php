<?php
include './layout/main.php';

error_reporting(E_ALL); 
ini_set('display_errors', 1);

include '../db.php';

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get enterprise_id from the session (adjust the variable name if needed)
$enterprise_id = isset($_SESSION['enterprise_id']) ? $_SESSION['enterprise_id'] : null;

// Validate and sanitize the enterprise_id to prevent SQL injection
$enterprise_id = intval($enterprise_id);

// Check if the date range is provided in the request
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Validate and sanitize date inputs to prevent SQL injection
$start_date = mysqli_real_escape_string($conn, $start_date);
$end_date = mysqli_real_escape_string($conn, $end_date);

$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'most';

// Prepare and execute the SQL query to fetch sales data within the specified date range
$sort_column = $sort_order === 'alphabetical' ? 'p.product_name' : 'total_quantity';
$sort_direction = $sort_order === 'alphabetical' ? 'ASC' : ($sort_order === 'least' ? 'ASC' : 'DESC');

$query = "SELECT p.product_name, p.product_image, p.product_price, 
                 IFNULL(SUM(oi.order_quantity), 0) AS total_quantity, 
                 IFNULL(SUM(oi.order_quantity * p.product_price), 0) AS total_sales
          FROM product p
          LEFT JOIN order_item oi ON oi.product_id = p.product_id 
          LEFT JOIN `order` o ON oi.order_id = o.order_id 
          WHERE p.enterprise_id = ? AND o.order_status = 'Complete'
          AND o.created_order BETWEEN ? AND ?
          GROUP BY p.product_name, p.product_image, p.product_price
          ORDER BY $sort_column $sort_direction";

$stmt = $conn->prepare($query);
$stmt->bind_param("iss", $enterprise_id, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

// Debug: Output query for troubleshooting
// echo $stmt->error;
// var_dump($stmt->get_result()->fetch_all());

$salesData = $result->fetch_all(MYSQLI_ASSOC);

// Query to get the counts for ongoing, canceled, and completed orders, and the total sales correctly for the enterprise
$status_query = "SELECT 
                   IFNULL(SUM(CASE WHEN o.order_status IN ('Shipping', 'Preparing') THEN 1 ELSE 0 END), 0) AS ongoing_orders,
                   IFNULL(SUM(CASE WHEN o.order_status = 'Cancel' THEN 1 ELSE 0 END), 0) AS canceled_orders,
                   IFNULL(COUNT(DISTINCT CASE WHEN o.order_status = 'Complete' THEN o.order_id ELSE NULL END), 0) AS completed_orders,
                   IFNULL(SUM(CASE WHEN o.order_status = 'Complete' AND p.enterprise_id = ? THEN oi.order_quantity * p.product_price ELSE 0 END), 0) AS total_sales_complete,
                   IFNULL(COUNT(DISTINCT p.product_id), 0) AS total_products,
                   IFNULL(COUNT(DISTINCT f.feedback_id), 0) AS total_feedback
                FROM `order` o
                LEFT JOIN order_item oi ON o.order_id = oi.order_id
                LEFT JOIN product p ON oi.product_id = p.product_id
                LEFT JOIN feedback f ON o.order_id = f.order_id
                WHERE p.enterprise_id = ? AND o.created_order BETWEEN ? AND ?";

$status_stmt = $conn->prepare($status_query);
$status_stmt->bind_param("iiss", $enterprise_id, $enterprise_id, $start_date, $end_date);
$status_stmt->execute();
$status_result = $status_stmt->get_result();
$status_data = $status_result->fetch_assoc();

// Debug: Output query for troubleshooting
// echo $status_stmt->error;
// var_dump($status_stmt->get_result()->fetch_assoc());
?>

<head>
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: "Freeman", sans-serif;
        }

        button {
            font-weight: bold;
            background-color: white;
            border: solid 1px black;
            color: black;
            border-radius: 10px;
            transition: all 0.3s ease;
            padding: 10px;
        }

        button:hover {
            font-weight: bold;
            background-color: black;
            color: white;
            border-radius: 10px;
        }

        .no-data-found {
            width: 100%;
            height: 50px;
            color: red;
            display: flex;
            align-items: center;
            padding-left: 20px;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .flex-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        canvas {
            width: 100% !important;
            height: auto !important;
        }

        .report-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px; /* Adds space between cards */
            margin-top: 20px;
            padding: 20px;
        }

        .report-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 300px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .report-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .report-name {
            font-size: 1.5em;
            margin-top: 10px;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #dee2e6;
            width: 100%;
        }

        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
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
            border-top-right-radius: 0px.
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
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const endDateInput = document.querySelector('input[name="end_date"]');
            const startDateInput = document.querySelector('input[name="start_date"]');
            const today = new Date().toISOString().split('T')[0];

            // Set the max attribute of the end date input to today's date
            endDateInput.setAttribute('max', today);
            // Set the max attribute of the start date input to today's date
            startDateInput.setAttribute('max', today);

            // Add event listener to start date input to validate date range
            startDateInput.addEventListener('change', function () {
                if (startDateInput.value > endDateInput.value) {
                    startDateInput.setCustomValidity('Start date cannot be after the end date.');
                } else {
                    startDateInput.setCustomValidity('');
                }
            });

            // Add event listener to end date input to validate date range
            endDateInput.addEventListener('change', function () {
                if (startDateInput.value > endDateInput.value) {
                    endDateInput.setCustomValidity('End date cannot be before the start date.');
                } else {
                    endDateInput.setCustomValidity('');
                }
            });
        });
    </script>
</head>
<main>
    <?php include './layout/menu.php'; ?>

    <section>
        <h1 style="text-align: center; font-weight: bold; margin-bottom: 10px;"><?php echo __('report')?></h1>

        <div id="summary" class="report-container">
            <div class="report-card flex-item">
                <h1 style="font-weight: bold; margin-bottom: 10px; margin-top: 10px;"><i class="fa-regular fa-circle-check fa-bounce"></i> &nbsp <?php echo __('completed_orders')?></h1>
                <div style='width:100%; padding: 20px;'>
                    <p style="font-size: 40px; font-weight: bold;"><?php echo $status_data['completed_orders'] ?: 0; ?></p>
                </div>
            </div>
            
            <div class="report-card flex-item">
                <h1 style="font-weight: bold; margin-bottom: 10px; margin-top: 10px;"><i class="fa-solid fa-spinner fa-bounce"></i> &nbsp <?php echo __('ongoing_orders')?></h1>
                <div style='width:100%; padding: 20px;'>
                    <p style="font-size: 40px; font-weight: bold;"><?php echo $status_data['ongoing_orders'] ?: 0; ?></p>
                </div>
            </div>
            
            <div class="report-card flex-item">
                <h1 style="font-weight: bold; margin-bottom: 10px; margin-top: 10px;"><i class="fa-solid fa-xmark fa-bounce"></i> &nbsp <?php echo __('canceled_orders')?></h1>
                <div style='width:100%; padding: 20px;'>
                    <p style="font-size: 40px; font-weight: bold;"><?php echo $status_data['canceled_orders'] ?: 0; ?></p>
                </div>
            </div>

            <div class="report-card flex-item">
                <h1 style="font-weight: bold; margin-bottom: 10px; margin-top: 10px;"><i class="fa-solid fa-money-bill-wave fa-bounce"></i> &nbsp <?php echo __('total_sales')?></h1>
                <div style='width:100%; padding: 20px;'>
                    <p style="font-size: 40px; font-weight: bold;">RM <?php echo number_format($status_data['total_sales_complete'] ?: 0, 2); ?></p>
                </div>
            </div>

            <div class="report-card flex-item">
                <h1 style="font-weight: bold; margin-bottom: 10px; margin-top: 10px;"><i class="fa-solid fa-box-open fa-bounce"></i> &nbsp <?php echo __('total_products')?></h1>
                <div style='width:100%; padding: 20px;'>
                    <p style="font-size: 40px; font-weight: bold;"><?php echo $status_data['total_products'] ?: 0; ?></p>
                </div>
            </div>

            <div class="report-card flex-item">
                <h1 style="font-weight: bold; margin-bottom: 10px; margin-top: 10px;"><i class="fa-regular fa-comment fa-bounce"></i> &nbsp <?php echo __('total_feedback')?></h1>
                <div style='width:100%; padding: 20px;'>
                    <p style="font-size: 40px; font-weight: bold;"><?php echo $status_data['total_feedback'] ?: 0; ?></p>
                </div>
            </div>
        </div>

        <h1 style="text-align: center; font-weight: bold; margin-bottom: 10px; margin-top: 10px;"><?php echo __('table_report')?></h1>
        <div style="display:flex; justify-content:space-between">
            <form action="report-listing.php" method="get">
                <label for="start_date" style="font-weight: bold;"><?php echo __('start_date')?>:</label>
                <input type="date" style="height:90%; width: 150px; border-radius: 8px;" name="start_date" value="<?= $start_date ?>" max="<?= date('Y-m-d') ?>">

                <label for="end_date" style="font-weight: bold;"><?php echo __('end_date')?>:</label>
                <input type="date" style="height:90%; width: 150px; border-radius: 8px;" name="end_date" value="<?= $end_date ?>" max="<?= date('Y-m-d') ?>">

                <label for="sort_order" style="font-weight: bold;"><?php echo __('sort')?>:</label>
                <select id="sort_order" name="sort_order" style="border: solid 1px black;">
                    <option value="most" <?= isset($_GET['sort_order']) && $_GET['sort_order'] === 'most' ? 'selected' : '' ?>><?php echo __('most')?></option>
                    <option value="least" <?= isset($_GET['sort_order']) && $_GET['sort_order'] === 'least' ? 'selected' : '' ?>><?php echo __('least')?></option>
                    <option value="alphabetical" <?= isset($_GET['sort_order']) && $_GET['sort_order'] === 'alphabetical' ? 'selected' : '' ?>><?php echo __('alphabet')?></option>
                </select>

                <button type="submit"><i class="fa-solid fa-filter fa-bounce"></i> <?php echo __('filter')?></button>
            </form>

            <div style="display:flex">
            <form action="./controller/export_report.php" method="get">
                <input type="hidden" name="enterprise_id" value="<?= $enterprise_id ?>">
                <input type="hidden" name="start_date" value="<?= $start_date ?>">
                <input type="hidden" name="end_date" value="<?= $end_date ?>">
                <button type="submit" name="export_csv"><i class="fa-solid fa-file-csv fa-bounce"></i> <?php echo __('csv')?></button>
            </form>
            </div>
        </div>

        <div class="flex-container">
            <!-- Sales Graph -->
            <div id="salesGraphContainer" class="flex-item">
                <?php
                    if ($result->num_rows > 0) {
                        $result->data_seek(0); // Reset pointer to the beginning of the result set
                        $salesData = $result->fetch_all(MYSQLI_ASSOC);
                    } else {
                        $salesData = [];
                    }
                ?>
                <canvas id="salesGraph"></canvas>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    var salesData = <?php echo json_encode($salesData); ?>;
                    if (salesData.length > 0) {
                        var ctx = document.getElementById('salesGraph').getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: salesData.map(item => item.product_name),
                                datasets: [{
                                    label: '<?php echo __('sales')?>',
                                    data: salesData.map(item => item.total_quantity),
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1,
                                    borderRadius: 10,
                                    borderSkipped: false,
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    } else {
                        document.getElementById('salesGraphContainer').innerHTML = '<i class="fa-solid fa-face-sad-tear fa-beat-fade" style="color: red;"></i> <span style="color: red; font-size: 16px;"><?php echo __('graph') ?></span>';
                    }
                </script>
            </div>

            <div id="topSalesList" class="flex-item" style="margin-top: 30px;">
                <?php
                if ($result->num_rows > 0) {
                    echo "<table class='table'>";
                    echo "<thead><tr><th>Product Image</th><th>Product Name</th><th>Total Quantity</th><th>Price Per Unit</th><th>Total Sales</th></tr></thead>";
                    echo "<tbody>";
                    foreach ($salesData as $row) {
                        $imagePath = "image/product/{$row['product_image']}";
                        $imageUrl = "http://localhost/musictimes/{$imagePath}";
                        echo "<tr>";
                        echo "<td><img src='{$imageUrl}' alt='{$row['product_name']}'></td>";
                        echo "<td>{$row['product_name']}</td>";
                        echo "<td>{$row['total_quantity']}</td>";
                        echo "<td>RM " . number_format($row['product_price'], 2) . "</td>";
                        echo "<td>RM " . number_format($row['total_sales'], 2) . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<div class='no-data-found'><i class='fa-solid fa-face-sad-tear fa-beat-fade'></i> " . " &nbsp " . __('no_data') . "</div>";
                }
                ?>
            </div>
        </div>
    </section>
</main>

<?php include './layout/footer.php'; ?>
</body>
</html>
