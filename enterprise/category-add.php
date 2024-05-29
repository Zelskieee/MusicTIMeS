<?php include './layout/main.php';?>

<head>
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Freeman", sans-serif;
        }
    </style>
</head>

<main>
    <?php include './layout/menu.php';?>
    <section>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0" style="font-weight: bold;"><?php echo __('add_category'); ?></h1>
            <a href="/musictimes/enterprise/category-listing.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left fa-beat-fade"></i><strong> <?php echo __('back'); ?></strong>
            </a>
        </div>
        <hr>
        <form action="/musictimes/enterprise/controller/category-add.php" method="post">
            <input type="hidden" name="enterprise_id" value="<?php echo $_SESSION['enterprise_id']; ?>">
            <div class="mb-2">
                <label class="form-label" style="font-weight: bold;"><?php echo __('category_name'); ?></label>
                <input type="text" name="category_name" class="form-control" autocomplete="off" placeholder="Enter Category Name" required>
            </div>
            <div class="mb-2">
                <button type="submit" class="btn btn-secondary">
                <i class="fa-solid fa-circle-check fa-beat-fade"></i><strong> <?php echo __('submit'); ?></strong>
                </button>
            </div>
        </form>
    </section>
</main>
<?php include './layout/footer.php';?>
</body>
</html>
