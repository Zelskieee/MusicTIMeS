<?php include './layout/main.php';?>

<head>
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
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

        .filter-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-form {
            display: flex;
            gap: 10px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<main>
    <?php include './layout/menu.php';?>

    <?php 
        $enterprise_id = $_SESSION['enterprise_id'];

        // Get filter and sort parameters from GET request
        $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'latest';
        $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

        // Prepare the base SQL query
        $query = "SELECT * FROM category WHERE enterprise_id = ?";

        // Add search functionality
        if ($search_query) {
            $query .= " AND category_name LIKE ?";
            $search_query = '%' . $search_query . '%';
        }

        // Add sorting functionality
        switch ($sort_order) {
            case 'latest':
                $query .= " ORDER BY category_id DESC";
                break;
            case 'oldest':
                $query .= " ORDER BY category_id ASC";
                break;
        }

        $stmt = $conn->prepare($query);
        if ($search_query) {
            $stmt->bind_param("is", $enterprise_id, $search_query);
        } else {
            $stmt->bind_param("i", $enterprise_id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
    ?>
    <section>
        <div class="header-container">
            <h1 style="font-weight: bold;"><?php echo __('category_list'); ?></h1>
            <a href="/musictimes/enterprise/category-add.php" class="btn btn-secondary"><i class="fa-solid fa-plus fa-beat-fade"></i> <?php echo __('add_category'); ?></a>
        </div>
        <div class="filter-container">
            <form method="GET" class="filter-form">
                <select name="sort_order" style="border-radius: 8px; border: solid 2px black; padding: 2px;">
                    <option value="latest" <?php if ($sort_order == 'latest') echo 'selected'; ?>><?php echo __('latest') ?></option>
                    <option value="oldest" <?php if ($sort_order == 'oldest') echo 'selected'; ?>><?php echo __('oldest') ?></option>
                </select>
                <input type="text" name="search_query" style="border-radius: 8px;" autocomplete="off" placeholder="<?php echo __('search') ?>" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" style="border-radius: 8px; transition: background-color 0.5s, color 0.5s; background-color: white; color: black;" onmouseover="this.style.backgroundColor='black'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='black';"><i class="fa-solid fa-magnifying-glass fa-beat-fade"></i> <?php echo __('search'); ?></button>
            </form>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?php echo __('category_name'); ?></th>
                    <th><?php echo __('action'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result->num_rows > 0) {
                    while ($category = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                            <td>
                                <a href="/musictimes/enterprise/category-edit.php?id=<?php echo $category['category_id']; ?>" class="btn btn-secondary btn-sm" style="font-weight: bold;">
                                    <i class="fa-solid fa-file-pen fa-beat-fade"></i> <?php echo __('edit'); ?>
                                </a>
                                <a href="/musictimes/enterprise/controller/category-delete.php?id=<?php echo $category['category_id']; ?>" class="btn btn-danger btn-sm" style="font-weight: bold;">
                                    <i class="fa-regular fa-trash-can fa-beat-fade"></i> <?php echo __('delete'); ?>
                                </a>
                            </td>
                        </tr>
                <?php }} else { ?>
                    <tr>
                        <td colspan="2" style="color: red;"><i class="fa-solid fa-face-frown-open fa-bounce"></i> <?php echo __('no_category_table'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
</main>
<?php include './layout/footer.php';?>
</body>
</html>
