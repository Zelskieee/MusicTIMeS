<?php include './layout/main.php'; ?>

<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
<title><?php echo __('customer_profile')?></title>
<script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <style>
        * { 
            font-family: "Freeman", sans-serif;
        }
        .strength-meter {
            margin-top: 5px;
            height: 10px;
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
        }
        .strength-meter div {
            height: 100%;
            transition: width 0.3s ease-in-out;
        }
        #strength-text {
            display: block;
            margin-top: 5px;
            font-weight: bold;
        }
        .strength-weak {
            width: 20%;
            background-color: red;
            color: red;
        }
        .strength-moderate {
            width: 60%;
            background-color: orange;
            color: orange;
        }
        .strength-strong {
            width: 100%;
            background-color: green;
            color: green;
        }
    </style>
</head>

<main>
    
        <?php 
            $customer_username = $_SESSION['customer_username'];
            $query = "SELECT * FROM customers WHERE customer_username='$customer_username'";
            $result = $conn->query($query);
            $customer = $result->fetch_assoc();
        ?>
        
        <section style="margin-left: 300px;">
        <h1 style="text-align: center; font-weight: bold; margin-bottom: 20px;"><?php echo __('customer_profile')?></h1>
        <form action="./controller/update-profile.php?id=<?=$customer['customer_id']?>" method="post" enctype="multipart/form-data">
            <div class="mb-2">
                <label class="form-label" style="font-weight: bold;"><?php echo __('customer_image')?></label><br>
                <?php
                    $customerImagePath = '/musictimes/image/customer/' . $customer['customer_image'];
                    $defaultImagePath = '/musictimes/image/default-customer.png';

                    // Construct the full server paths for checking file existence
                    $fullCustomerImagePath = $_SERVER['DOCUMENT_ROOT'] . $customerImagePath;
                    $fullDefaultImagePath = $_SERVER['DOCUMENT_ROOT'] . $defaultImagePath;

                    // Check if the customer image file exists
                    if (file_exists($fullCustomerImagePath) && !empty($customer['customer_image'])) {
                        $imageSrc = $customerImagePath;
                    } else {
                        $imageSrc = $defaultImagePath;
                    }
                ?>
                <img style="width: 200px; height: 200px; margin-bottom: 10px;" src="<?= $imageSrc ?>" alt="Customer image">
                <input type="file" name="image" accept="image/*" class="form-control">
            </div>
            <div class="mb-2">
                <label class="form-label" style="font-weight: bold;"><?php echo __('customer_username'); ?></label>
                <input type="text" name="customer_username" class="form-control" style="cursor:not-allowed;" value="<?= htmlspecialchars($customer['customer_username']); ?>" readonly>
            </div>
            <div class="mb-2">
                <label class="form-label" style="font-weight: bold;"><?php echo __('customer_name')?></label>
                <input type="text" name="customer_name" class="form-control" value="<?=$customer['customer_name']?>">
            </div>
            <div class="mb-2">
                <label class="form-label" style="font-weight: bold;"><?php echo __('customer_email')?></label>
                <input type="email" name="customer_email"  class="form-control" style="cursor:not-allowed;" value="<?= htmlspecialchars($customer['customer_email']);?>" readonly>
            </div>
            <div class="mb-2">
                <label class="form-label" style="font-weight: bold;"><?php echo __('customer_phone')?></label>
                <input type="tel" name="customer_phone" class="form-control" value="<?= htmlspecialchars($customer['customer_phone']) ?>" placeholder="Enter Phone Number (e.g. 6011111111)" pattern="\d{10,15}" title="Please enter a valid phone number with only numeric characters (10-15 digits)" oninput="this.value = this.value.replace(/[^0-9]/g, '');">

            </div>
            <div class="mb-2">
                <label class="form-label" style="font-weight: bold;"><?php echo __('customer_address')?><span style="color: red;">*</span></label>
                <textarea name="customer_address" cols="30" rows="10"  class="form-control" placeholder="Enter Address" required><?=$customer['customer_address']?></textarea>
            </div>
            <div class="mb-2">
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-circle-check fa-beat-fade"></i><strong> <?php echo __('submit')?></strong></button>
            </div>
        </form>
</section>
    
<section>
    <div class="new_password-form">
        <form action="./controller/change-password.php" method="post">
            <h1 style="text-align: center; font-weight: bold; margin-bottom: 20px"><?php echo __('change_password')?></h1>

            <div class="mb-2">
                <label class="form-label" style="font-weight: bold;"><?php echo __('old_password')?></label>
                <input type="password" name="old_password" class="form-control" required>
            </div>

            <div class="mb-2">
                <label class="form-label" style="font-weight: bold;"><?php echo __('new_password')?></label>
                <input type="password" name="new_password" id="new_password" class="form-control" oninput="checkPasswordStrength()" required>
                <div id="password-strength" class="strength-meter">
                    <div id="strength-bar"></div>
                </div>
                <span id="strength-text"></span>
            </div>

            <div class="mb-2">
                <label class="form-label" style="font-weight: bold;"><?php echo __('confirm_password')?></label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>

            <?php
            if (isset($_GET['error'])) {
                $error_message = htmlspecialchars($_GET['error']);
                echo '<p style="color: red;">' . $error_message . '</p>';
            }
            if (isset($_GET['success'])) {
                $success_message = htmlspecialchars($_GET['success']);
                echo '<p style="color: green;">' . $success_message . '</p>';
            }
            ?>

            <div class="mb-2">
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-lock fa-beat-fade"></i><strong> <?php echo __('change')?></strong></button>
            </div>

        </form>
    </div>
</section>

    <div style="margin-top: 10px; margin-right: 70px;">
        <a href="./product-listing.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left fa-beat-fade"></i><strong> <?php echo __('back')?></strong></a>
        </div>
</div>
</main>
<?php include './layout/footer.php'; ?>

<script>
    function checkPasswordStrength() {
    var strengthBar = document.getElementById('strength-bar');
    var strengthText = document.getElementById('strength-text');
    var password = document.getElementById('new_password').value;
    var strength = 0;

    if (password.length >= 8) strength += 1;
    if (password.match(/[a-z]+/)) strength += 1;
    if (password.match(/[A-Z]+/)) strength += 1;
    if (password.match(/[0-9]+/)) strength += 1;
    if (password.match(/[\W]+/)) strength += 1;

    strengthBar.className = ''; // Reset the class
    strengthText.className = ''; // Reset the class

    switch (strength) {
        case 0:
        case 1:
        case 2:
            strengthBar.classList.add('strength-weak');
            strengthText.innerText = 'Weak';
            strengthText.style.color = 'red';
            break;
        case 3:
        case 4:
            strengthBar.classList.add('strength-moderate');
            strengthText.innerText = 'Moderate';
            strengthText.style.color = 'orange';
            break;
        case 5:
            strengthBar.classList.add('strength-strong');
            strengthText.innerText = 'Strong';
            strengthText.style.color = 'green';
            break;
        default:
            strengthText.innerText = '';
    }
}
</script>
</body>
</html>