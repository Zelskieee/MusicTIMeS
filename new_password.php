<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" href="./image/logo.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/new_password.css">

    <style>
        * { 
            font-family: "Freeman", sans-serif;
        }

        body {
            background-image: url('image/bg.png');
            background-size: cover;
            background-position: center;
        }

        input[type="submit"] {
            padding: 17px 40px;
            border-radius: 50px;
            cursor: pointer;
            border: 0;
            background-color: white;
            color: black;
            box-shadow: rgb(0 0 0 / 5%) 0 0 8px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-size: 15px;
            transition: all 0.5s ease;
            border-color: #dedfdb;
        }

        input[type="submit"]:hover {
            letter-spacing: 3px;
            background-color: #dedfdb;
            color: black;
            box-shadow: rgb(208, 206, 213) 0px 7px 29px 0px;
        }

        input[type="submit"]:active {
            letter-spacing: 3px;
            background-color: #dedfdb;
            color: hsl(0, 0%, 100%);
            box-shadow: rgb(208, 206, 213) 0px 0px 0px 0px;
            transform: translateY(10px);
            transition: 100ms;
        }
    </style>
</head>
<body>

<div class="new_password-form">
    <form action="change_new_password.php" method="post">

        <div class="image-container">
            <img src="./image/logo.png" alt="Change New Password Image" width="300px" height="100px">
        </div>

        <h1>Forgot Password</h1>

        <label for="customer_email">Email:</label>
        <?php
        session_start();
        if (isset($_SESSION['customer_email'])) {
            $customer_email = htmlspecialchars($_SESSION['customer_email']);
        } else {
            $customer_email = "";
            echo '<p style="color: red;">Error: Email not set. Please try again.</p>';
        }
        ?>
        <input type="email" id="customer_email" name="customer_email" value="<?php echo $customer_email; ?>" readonly required aria-label="Customer Email" style="cursor: not-allowed; background-color: #dedfdb;">

        <label for="customer_password">New Password</label>
        <input type="password" name="customer_password" id="customer_password" required aria-label="New Password" oninput="checkPasswordStrength()">
        <div id="password-strength" style="margin-bottom: 10px;"></div>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" required aria-label="Confirm Password">

        <input type="submit" name="submit" value="Submit" style="margin-top: 10px;">

        <?php
        if (isset($_GET['error'])) {
            $error_message = htmlspecialchars($_GET['error']);
            echo '<p style="color: red;">' . $error_message . '</p>';
        }
        ?>
    </form>

    <button id="back-button" onclick="window.location.href='forgot_password.php'">Back</button>
</div>

</body>
<script>
    function checkPasswordStrength() {
        var strengthBar = document.getElementById('password-strength');
        var password = document.getElementById('customer_password').value;
        var strength = 0;

        if (password.length >= 8) strength += 1;
        if (password.match(/[a-z]+/)) strength += 1;
        if (password.match(/[A-Z]+/)) strength += 1;
        if (password.match(/[0-9]+/)) strength += 1;
        if (password.match(/[\W]+/)) strength += 1;

        switch (strength) {
            case 0:
            case 1:
            case 2:
                strengthBar.style.color = 'red';
                strengthBar.innerHTML = 'Weak';
                break;
            case 3:
            case 4:
                strengthBar.style.color = 'orange';
                strengthBar.innerHTML = 'Moderate';
                break;
            case 5:
                strengthBar.style.color = 'green';
                strengthBar.innerHTML = 'Strong';
                break;
        }
    }

    document.querySelector('form').addEventListener('submit', function (e) {
        var password = document.getElementById('customer_password').value;
        var confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match');
        }
    });
</script>
</html>
