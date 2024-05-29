<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .sidebar-nav-item {
            list-style: none;
            margin: 0;
            padding: 0;
            font-weight: normal;
        }

        .sidebar-nav-item a {
            text-decoration: none;
            display: block;
            padding: 10px;
            font-size: 20px;
            border: 1px solid transparent;
            transition: border-radius 0.3s, box-shadow 0.3s;
            font-weight: normal;
        }

        .sidebar-nav-item a:hover {
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            font-size: 20px;
            background-color: lightgray;
        }

        .sidebar-nav-item.active a {
            font-weight: bold !important;
            border: 1px solid lightgray !important;
            border-radius: 8px !important;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3) !important;
            font-size: 20px !important;
            background-color: lightgray !important;
            color: black;
        }
    </style>
</head>
<body>

<aside>
    <nav>
        <ul>
            <li class="sidebar-nav-item product">
                <a href="../enterprise/product-listing.php" id="nav-product" onclick="setActive(event, 'nav-product', '../enterprise/product-listing.php')"><?php echo __('product')?></a>
            </li>
            <li class="sidebar-nav-item category">
                <a href="../enterprise/category-listing.php" id="nav-category" onclick="setActive(event, 'nav-category', '../enterprise/category-listing.php')"><?php echo __('category')?></a>
            </li>
            <li class="sidebar-nav-item order">
                <a href="../enterprise/order-listing.php" id="nav-order" onclick="setActive(event, 'nav-order', '../enterprise/order-listing.php')"><?php echo __('order')?></a>
            </li>
            <li class="sidebar-nav-item feedback">
                <a href="../enterprise/feedback-listing.php" id="nav-feedback" onclick="setActive(event, 'nav-feedback', '../enterprise/feedback-listing.php')"><?php echo __('feedback')?></a>
            </li>
            <li class="sidebar-nav-item payment">
                <a href="../enterprise/payment-listing.php" id="nav-payment" onclick="setActive(event, 'nav-payment', '../enterprise/payment-listing.php')"><?php echo __('payment')?></a>
            </li>
            <li class="sidebar-nav-item report">
                <a href="../enterprise/report-listing.php" id="nav-report" onclick="setActive(event, 'nav-report', '../enterprise/report-listing.php')"><?php echo __('report')?></a>
            </li>
        </ul>
    </nav>
</aside>

<script>
    function setActive(event, linkId, url) {
        event.preventDefault();
        const navItems = document.querySelectorAll('.sidebar-nav-item');

        navItems.forEach(item => {
            const anchor = item.querySelector('a');
            if (anchor.id === linkId) {
                item.classList.add('active');
                localStorage.setItem('activeNavItem', linkId);
            } else {
                item.classList.remove('active');
            }
        });

        // Redirect to the clicked link's href
        window.location.href = url;
    }

    function loadActiveNavItem() {
        const activeLinkId = localStorage.getItem('activeNavItem');
        if (activeLinkId) {
            const activeLink = document.getElementById(activeLinkId);
            if (activeLink) {
                const parentItem = activeLink.parentElement;
                parentItem.classList.add('active');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', loadActiveNavItem);
</script>



</body>
</html>
