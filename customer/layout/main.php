<?php 

session_start(); 
include '../db.php'; 

if (!isset($_SESSION['customer_id'])) {
    header('Location: ../index.php');
    exit;
}


?>

<?php

$language = 'en';

if (isset($_SESSION['lang'])) {
    $language = $_SESSION['lang'];
}

if (isset($_POST['lang'])) {
    $language = $_POST['lang'];
    $_SESSION['lang'] = $language;
}

$langFile = "../languages/{$language}.php";

if (file_exists($langFile)) {
    $translations = include $langFile;
} else {
    $translations = [];
}

function __($key) {
    global $translations;
    return $translations[$key] ?? $key;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/logo.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="../style/sidebar_enterprise.css">
    <link rel="stylesheet" href="../style/customer_main.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="../assets/customer_main.js"></script>
    <style>
        * { 
            font-family: "Freeman", sans-serif;
        }
        
        .profile-details {
            position: relative;
            display: inline-block;
        }

        .customer-link {
            color: initial;
            text-decoration: none;
        }

        .customer-link:hover {
            color: grey;
        }

        .dropdown-content {
            text-align: left;
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 180px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 10px;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            border-radius: 10px;
        }

        .profile-details:hover .dropdown-content {
            display: block;
        }
    </style>
</head>

<body>
<header>
    <img src="../image/logo.png" alt="MusicTIMeS Logo" id="logo">
    <h1 style="font-weight: bold;">MusicTIMeS</h1>
    <nav id="navbar">
        <a href="./homepage.php" id="nav-home" onclick="setActive(event, 'nav-home', './homepage.php')"><?php echo __('home'); ?></a>
        <a href="./product-listing.php" id="nav-product" onclick="setActive(event, 'nav-product', './product-listing.php')"><?php echo __('product'); ?></a>
        <a href="./enterprise-listing.php" id="nav-enterprise" onclick="setActive(event, 'nav-enterprise', './enterprise-listing.php')"><?php echo __('enterprise'); ?></a>
    </nav>

    <div class="header-options">
        <div class="btn-container">
            <form method="post" id="languageForm">
                <label class="switch btn-language-mode-switch">
                    <input name="lang" type="hidden" value="<?php echo $language; ?>">
                    <input value="1" id="language_mode" name="language_mode" type="checkbox" <?php if ($language == 'my') echo 'checked'; ?> onchange="toggleLanguage()">
                    <label class="btn-language-mode-switch-inner" data-off="en" data-on="my" for="language_mode" style="text-transform: uppercase; display:flex;align-items:center; font-size:12px">
                        <img src="../image/england.png" alt="EN Flag" style="width: 20px; height: 20px; margin-left:3px; z-index:1000" /></img>
                        <img src="../image/malaysia.png" alt="MY Flag" style="width: 20px; height: 20px; margin-left:47px; z-index:1000" /></img>
                    </label>
                </label>
            </form>
        </div>

        <div class="profile-option" style="background-color: #F8F6F0;">
            <?php 
                $customer_username = $_SESSION['customer_username'];
                $query = "SELECT customer_name, customer_image FROM customers WHERE customer_username='$customer_username'";
                $result = $conn->query($query);
                $customer = $result->fetch_assoc();
            ?>
            <div class="profile-info">
                <div class="profile-image">
                <?php
                $image_path = "/musictimes/image/customer/";
                $default_image = "/musictimes/image/default-customer.png";

                if (!empty($customer['customer_image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path . $customer['customer_image'])) {
                    $image_path .= $customer['customer_image'];
                } else {
                    $image_path = $default_image;
                }

                echo '<img src="' . htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8') . '" alt="Customer Profile Image" style="width: 50%; height: 50%; max-width: 100px; max-height: 100px; border-radius: 50%;">';
                ?>

                </div>
                <div class="profile-details">
                <a href="./customer-profile.php" class="customer-link" style="color: initial; text-decoration: none;" onmouseover="this.style.color='grey'" onmouseout="this.style.color='initial'">
                    <p class="customer-name"><?php echo htmlspecialchars($customer['customer_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                </a>
                <div class="dropdown-content">
                    <a href="./customer-profile.php"><i class="fa-regular fa-address-card fa-beat"></i> <?php echo __('my_profile'); ?></a>
                    <a href="./my-order.php"><i class="fa-solid fa-list-ul fa-beat"></i> <?php echo __('my_order'); ?></a>
                </div>
                </div>
            </div>
        </div>
        
        <?php
        $customer_id = $_SESSION['customer_id']; // Example of getting customer ID from session

        $totalQuantity = 0;

        // SQL query to select data from the cart table for the specific customer
        $sql = "SELECT c.cart_id, c.updated_cart, c.customer_id, c.product_id, c.cart_quantity, c.created_cart, 
                        p.category_id, p.enterprise_id, p.product_name, p.product_desc, p.product_quantity, 
                        p.product_tag, p.product_price, p.product_image
                FROM cart c
                JOIN product p ON c.product_id = p.product_id
                WHERE c.customer_id = $customer_id"; // Filter by customer ID

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                $totalQuantity += $row['cart_quantity'];
            }
        }
        ?>

        <button id="cart_quantity" data-quantity="<?php echo $totalQuantity; ?>" class="btn-cart" id="cart-toggle" onclick="handleCart()">
            <svg class="icon-cart" viewBox="0 0 24.38 30.52" height="30.52" width="24.38" xmlns="http://www.w3.org/2000/svg">
                <title><?php echo __('cart'); ?></title>
                <path transform="translate(-3.62 -0.85)" d="M28,27.3,26.24,7.51a.75.75,0,0,0-.76-.69h-3.7a6,6,0,0,0-12,0H6.13a.76.76,0,0,0-.76.69L3.62,27.3v.07a4.29,4.29,0,0,0,4.52,4H23.48a4.29,4.29,0,0,0,4.52-4ZM15.81,2.37a4.47,4.47,0,0,1,4.46,4.45H11.35a4.47,4.47,0,0,1,4.46-4.45Zm7.67,27.48H8.13a2.79,2.79,0,0,1-3-2.45L6.83,8.34h3V11a.76.76,0,0,0,1.52,0V8.34h8.92V11a.76.76,0,0,0,1.52,0V8.34h3L26.48,27.4a2.79,2.79,0,0,1-3,2.44Zm0,0"></path>
            </svg>
            <span class="quantity"><?php echo $totalQuantity; ?></span>
        </button>

        <div class="cart-sidebar" style="padding:20px">
    <div>
        <button onclick="handleCart()" style="background-color: black; color:#fff; display:flex; justify-content: center; align-items: center; border:none; border-radius: 5px; padding: 2px 10px; font-weight: bold;">></button>
        <div style="font-weight: bold; font-size:30px"><i class="fa-solid fa-basket-shopping fa-bounce"></i> <?php echo __('cart'); ?></div>
    </div>
    <div style="overflow-y: auto; height: 80vh;">
      
      <?php 
          $customer_id = $_SESSION['customer_id'];
          $totalQuantity = 0;
          $totalPrice = 0.00;

          $sql = "SELECT c.cart_id, c.updated_cart, c.customer_id, c.product_id, c.cart_quantity, c.created_cart, 
                          p.category_id, p.enterprise_id, p.product_name, p.product_desc, p.product_quantity, 
                          p.product_tag, p.product_price, p.product_image
                  FROM cart c
                  JOIN product p ON c.product_id = p.product_id
                  WHERE c.customer_id = $customer_id";

          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  $totalQuantity += $row['cart_quantity'];
                  $itemTotalPrice = number_format($row['product_price'] * $row['cart_quantity'], 2, '.', '');
                  $totalPrice += $row['product_price'] * $row['cart_quantity'];
                  echo " <div class='cart-item' style='border-bottom: 1px solid black; padding:10px 0px'>
                            <div style='display: flex; align-items:center'>
                                <div>
                                    <img style='width:30px; height:auto' src='../image/product/".$row['product_image']."' />
                                </div>
                                <div style='text-align:left; margin-left:20px'>
                                    <div style='font-weight:bold'>".$row['product_name']."</div>
                                    <div>".$row['product_desc']."</div>
                                    <div class='item-total-price' data-item-price='".$row['product_price']."'>RM ".$itemTotalPrice."</div>
                                </div>
                            </div>
                            <div style='display: flex; align-items:center'>
                                <div style='width:30px; height:auto'></div>
                                <div style='margin-left:20px;margin-top:10px'>
                                    <button class='minus-quantity btn btn-primary btn-sm' style='background-color: #000; color: #fff; font-size: 16px; font-weight: bold; border: 1px solid #fff;' onclick='updateCartQuantity(".$row['cart_id'].", \"minus\", $customer_id)'>-</button>
                                    <span id='quantity_".$row['cart_id']."' class='px-2' style='font-weight: bold; font-size: 16px;' data-quantity='".$row['cart_quantity']."'>".$row['cart_quantity']."</span>
                                    <button class='add-quantity btn btn-primary btn-sm' style='background-color: #000; color: #fff; font-size: 16px; font-weight: bold; border: 1px solid #fff;' onclick='updateCartQuantity(".$row['cart_id'].", \"add\", $customer_id)'>+</button>
                                    <button class='btn btn-danger' type='button' style='font-size:11px' onclick='deleteCartItem(".$row['cart_id'].", $customer_id)'><i class='fas fa-trash'></i></button>
                                </div>
                            </div>
                        </div>";
              }
          } else {
                echo "<div style='background:lightgrey; padding:8px 0px; border-radius:8px'>" . __('item') . "</div>";
            }
      ?>
    </div>
            <div style="display:flex; flex-direction: column; align-items: center;">
                <div style="font-weight: bold; font-size: 20px; margin-top: -30px;background-color: white; width: 290px;">
                <div class="total-price" data-total-price="<?php echo number_format($totalPrice, 2); ?>">
                    <i class="fa-solid fa-money-bill-1-wave fa-beat-fade"></i> <?php echo __('total_price') . number_format($totalPrice, 2); ?>
                </div>
                </div>
                <div style="display: flex; width: 290px; margin-top: 10px;">
                    <button class="btn btn-secondary" style="width:50%; margin-right: 5px; font-weight: bold;"><a href="./my-order.php" style="color: white; text-decoration: none;"><i class="fa-solid fa-list-ul fa-bounce"></i> <?php echo __('view')?></a></button>
                    <a href="https://buy.stripe.com/test_7sIg14guXg11dna5kk" id="checkoutLink" style="width:50%; margin-left: 5px;">
                        <button 
                            id="checkoutButton" 
                            class="btn btn-primary" 
                            style="width:100%; font-weight: bold; background-color: black; border: solid 1px black; color: white;"
                            onmouseover="this.style.backgroundColor='white'; this.style.color='black'; this.style.border='solid 1px black';"
                            onmouseout="this.style.backgroundColor='black'; this.style.color='white'; this.style.border='solid 1px black';"
                        >
                            <i class="fa-regular fa-credit-card fa-bounce"></i> <?php echo __('checkout'); ?>
                        </button>
                    </a>

                </div>
            </div>
            <div style="margin-top: 20px;"></div>
            
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var checkoutButton = document.getElementById('checkoutButton');
            var checkoutLink = document.getElementById('checkoutLink');
            var totalQuantity = <?php echo $totalQuantity; ?>;
            if (totalQuantity === 0) {
                checkoutButton.disabled = true;
                checkoutButton.style.backgroundColor = 'grey';
                checkoutButton.style.border = 'solid 1px grey';
                checkoutLink.style.pointerEvents = 'none';
                checkoutLink.style.type = 'hidden';
            }
        });
        </script>

        <script>
            function deleteCartItem(cart_id) {
                // Show confirmation dialog
                var confirmation = confirm("Are you sure you want to delete this item from your cart?");
                
                // If user confirms, proceed with deletion
                if (confirmation) {
                    // Construct the delete URL
                    var deleteUrl = 'controller/delete-from-cart.php?id=' + cart_id;

                    // Navigate to the delete URL
                    window.location.href = deleteUrl;

                }
            }
        </script>

        <a class="logout-button" onclick="confirmLogout()">
        <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
        <div class="text"><?php echo __('logout'); ?></div></a>
    </div>
    <script>
    function updateCartQuantity(cartId, action, customerId) {
        $.ajax({
            type: 'POST',
            url: './controller/update-quantity.php',
            data: { cart_id: cartId, action: action, customer_id: customerId },
            success: function(response) {
                // Update the quantity displayed in the cart sidebar
                var quantityElement = document.getElementById('quantity_' + cartId);
                var currentQuantity = parseInt(quantityElement.getAttribute('data-quantity'));
                var ori = currentQuantity;

                if (action === 'add') {
                    currentQuantity++;
                } else if (action === 'minus' && currentQuantity > 1) {
                    currentQuantity--;
                }

                // Update the data-quantity attribute and the displayed quantity
                quantityElement.setAttribute('data-quantity', currentQuantity);
                quantityElement.textContent = currentQuantity;

                // Update the total quantity in the cart button
                var cartQuantityElement = document.getElementById('cart_quantity');
                var totalQuantity = parseInt(cartQuantityElement.getAttribute('data-quantity'));
                var newTotalQuantity = totalQuantity - ori + currentQuantity;
                cartQuantityElement.setAttribute('data-quantity', newTotalQuantity);
                cartQuantityElement.querySelector('.quantity').textContent = newTotalQuantity;

                // Update the total price for the specific item
                var priceElement = quantityElement.closest('.cart-item').querySelector('.item-total-price');
                var itemPrice = parseFloat(priceElement.getAttribute('data-item-price'));
                var newItemTotalPrice = itemPrice * currentQuantity;
                priceElement.innerHTML = 'RM ' + newItemTotalPrice.toFixed(2);

                // Update the total price for the cart
                var totalPriceElement = document.querySelector('.total-price');
                var oldTotalPrice = parseFloat(totalPriceElement.getAttribute('data-total-price'));
                var newTotalPrice = oldTotalPrice - (itemPrice * ori) + newItemTotalPrice;
                totalPriceElement.setAttribute('data-total-price', newTotalPrice);
                totalPriceElement.innerHTML = '<i class="fa-solid fa-money-bill-1-wave fa-beat-fade"></i> <?php echo __('total_price') ?>' + ' ' + newTotalPrice.toFixed(2);
            }
        });
    }
</script>

<script>
    // Function to set the active class on the clicked link and store it in localStorage
    function setActive(event, linkId, url) {
        event.preventDefault();
        const navLinks = document.querySelectorAll('nav a');

        navLinks.forEach(link => {
            if (link.id === linkId) {
                link.classList.add('active');
                localStorage.setItem('activeNavLink', linkId);
                applyActiveStyles(link);
            } else {
                link.classList.remove('active');
                removeActiveStyles(link);
            }
        });

        // Redirect to the clicked link's href
        window.location.href = url;
    }

    // Function to apply active styles
    function applyActiveStyles(link) {
        link.style.backgroundColor = 'lightgrey';
        link.style.borderRadius = '15px';
        link.style.fontWeight = 'bold';
    }

    // Function to remove active styles
    function removeActiveStyles(link) {
        link.style.backgroundColor = '';
        link.style.borderRadius = '';
        link.style.fontWeight = '';
    }

    // Function to load the active link from localStorage
    function loadActiveLink() {
        const activeLinkId = localStorage.getItem('activeNavLink');
        console.log('Loaded active link:', activeLinkId); // Debugging log
        if (activeLinkId) {
            const activeLink = document.getElementById(activeLinkId);
            if (activeLink) {
                activeLink.classList.add('active');
                applyActiveStyles(activeLink);
            }
        }
    }

    // Load the active link when the page loads
    document.addEventListener('DOMContentLoaded', loadActiveLink);
</script>

</header>