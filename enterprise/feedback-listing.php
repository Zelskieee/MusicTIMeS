<?php
include './layout/main.php';

// Get enterprise_id from the session (adjust the variable name if needed)
$enterprise_id = isset($_SESSION['enterprise_id']) ? $_SESSION['enterprise_id'] : null;

// Validate and sanitize the enterprise_id to prevent SQL injection
$enterprise_id = intval($enterprise_id);

// Get filter, sort, and search parameters from GET request
$rating_filter = isset($_GET['rating']) ? intval($_GET['rating']) : '';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'latest';
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Prepare the base SQL query
$query = "
    SELECT f.feedback_id, p.product_name, c.customer_name, f.feedback_desc, f.feedback_media, f.rating
    FROM feedback f
    JOIN product p ON f.product_id = p.product_id
    JOIN customers c ON f.customer_id = c.customer_id
    WHERE p.enterprise_id = ? AND f.is_deleted = 0
";

// Add filter for rating if selected
if ($rating_filter) {
    $query .= " AND f.rating = ?";
}

// Add search functionality
if ($search_query) {
    $query .= " AND (c.customer_name LIKE ? OR p.product_name LIKE ?)";
}

// Add sorting functionality
switch ($sort_order) {
    case 'latest':
        $query .= " ORDER BY f.feedback_id DESC";
        break;
    case 'oldest':
        $query .= " ORDER BY f.feedback_id ASC";
        break;
    case 'alphabetical':
    default:
        $query .= " ORDER BY c.customer_name ASC";
        break;
}

$query_stmt = $conn->prepare($query);

// Prepare parameters for binding
$query_params = [];
$query_params[] = &$enterprise_id;
$param_types = 'i';

if ($rating_filter) {
    $query_params[] = &$rating_filter;
    $param_types .= 'i';
}

if ($search_query) {
    $like_query = '%' . $search_query . '%';
    $query_params[] = &$like_query;
    $query_params[] = &$like_query;
    $param_types .= 'ss';
}

$query_stmt->bind_param($param_types, ...$query_params);
$query_stmt->execute();
$result = $query_stmt->get_result();

// Check for errors in the query execution
if (!$result) {
    die("Query failed: " . $conn->error);
}
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

        .table img {
            object-fit: cover;
            width: 200px;
            height: 100px;
            transition: transform 0.3s ease;
        }
        
        .table img:hover {
            transform: scale(1.1);
        }

        .star {
            font-size: 24px;
        }
    </style>
</head>
<main>
    <?php include './layout/menu.php'; ?>

    <section>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h1 style="text-align: center; font-weight: bold;"><?php echo __('feedback_list')?></h1>
            <div>
                <form method="GET" style="display: flex; gap: 10px;">
                    <select name="rating" style="border-radius: 8px; border: solid 2px black; padding: 2px;">
                        <option value=""><?php echo __('select_rating') ?></option>
                        <option value="5" <?php if ($rating_filter == 5) echo 'selected'; ?>>5 <?php echo __('star') ?></option>
                        <option value="4" <?php if ($rating_filter == 4) echo 'selected'; ?>>4 <?php echo __('star') ?></option>
                        <option value="3" <?php if ($rating_filter == 3) echo 'selected'; ?>>3 <?php echo __('star') ?></option>
                        <option value="2" <?php if ($rating_filter == 2) echo 'selected'; ?>>2 <?php echo __('star') ?></option>
                        <option value="1" <?php if ($rating_filter == 1) echo 'selected'; ?>>1 <?php echo __('star') ?></option>
                    </select>
                    <select name="sort_order" style="border-radius: 8px; border: solid 2px black; padding: 2px;">
                        <option value="latest" <?php if ($sort_order == 'latest') echo 'selected'; ?>><?php echo __('latest') ?></option>
                        <option value="oldest" <?php if ($sort_order == 'oldest') echo 'selected'; ?>><?php echo __('oldest') ?></option>
                        <option value="alphabetical" <?php if ($sort_order == 'alphabetical') echo 'selected'; ?>><?php echo __('alphabetical') ?></option>
                    </select>
                    <input type="text" name="search_query" autocomplete="off" style="border-radius: 8px;" placeholder="<?php echo __('search') ?>" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" style="border-radius: 8px; transition: background-color 0.5s, color 0.5s; background-color: white; color: black;" onmouseover="this.style.backgroundColor='black'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='black';"><i class="fa-solid fa-magnifying-glass fa-beat-fade"></i> <?php echo __('search'); ?></button>
                </form>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?php echo __('feedback_id')?></th>
                    <th><?php echo __('product_name')?></th>
                    <th><?php echo __('customer_name')?></th>
                    <th><?php echo __('description')?></th>
                    <th><?php echo __('media')?></th>
                    <th><?php echo __('feedback_rating')?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if ($result->num_rows > 0) {
                        while ($feedback = $result->fetch_assoc()) { ?>
                            <tr id="feedbackRow<?=$feedback['feedback_id']?>">
                                <td><?=$feedback['feedback_id']?></td>
                                <td><?=$feedback['product_name']?></td>
                                <td><?=$feedback['customer_name']?></td>
                                <td><?=$feedback['feedback_desc']?></td>
                                <td>
                                    <?php if ($feedback['feedback_media']) { ?>
                                        <img src="<?=$feedback['feedback_media']?>" alt="Feedback Media">
                                    <?php } else { ?>
                                        N/A
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php
                                    if ($feedback['rating']) {
                                        $rating = $feedback['rating'];
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<span class="star" style="color: #ffbf03de;">&#9733;</span>';
                                            } else {
                                                echo '<span class="star" style="color: gray;">&#9733;</span>';
                                            }
                                        }
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="6" style="color: red;"><i class="fa-solid fa-face-frown-open fa-bounce"></i> <?php echo __('no_data') ?></td>
                        </tr>
                    <?php } ?>
            </tbody>
        </table>
    </section>
</main>

<?php include './layout/footer.php'; ?>
</body>
</html>
