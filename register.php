<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" href="./image/logo.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/register.css">
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

        .otp-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #dedfdb;
            box-shadow: rgb(0 0 0 / 5%) 0 0 8px;
            z-index: 1000;
        }

        .otp-popup input {
            margin-top: 10px;
        }
    </style>
    <script>
        function showOTPForm() {
            document.getElementById('otp-popup').style.display = 'block';
        }

        function hideOTPForm() {
            document.getElementById('otp-popup').style.display = 'none';
        }

        function submitForm(event) {
            event.preventDefault();

            var formData = new FormData(document.querySelector('.registration-form'));

            document.getElementById('otp-customer_name').value = formData.get('customer_name');
            document.getElementById('otp-customer_username').value = formData.get('customer_username');
            document.getElementById('otp-customer_email').value = formData.get('customer_email');
            document.getElementById('otp-customer_password').value = formData.get('customer_password');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'send_otp.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    showOTPForm();
                } else {
                    alert('An error occurred. Please try again.');
                }
            };
            xhr.send(formData);
        }
    </script>
</head>
<body>

<form action="send_otp.php" method="post" class="registration-form" onsubmit="submitForm(event)">

    <div class="image-container">
        <img src="./image/logo.png" alt="Registration Image" width="300px" height="100px">
    </div>

    <h1>Register</h1>

    <label for="customer_name">Name <span style="color: red;">*</span></label>
    <input type="text" name="customer_name" id="customer_name" required placeholder="Enter your full name">

    <label for="customer_username">Username <span style="color: red;">*</span></label>
    <input type="text" name="customer_username" id="customer_username" required placeholder="Choose a username">

    <label for="customer_email">Email <span style="color: red;">*</span></label>
    <input type="text" name="customer_email" id="customer_email" required placeholder="Enter your email">

    <label for="customer_password">Password <span style="color: red;">*</span></label>
    <input type="password" name="customer_password" id="customer_password" required placeholder="Enter your password" minlength="8" oninput="checkPasswordStrength()">
    <div id="password-strength" style="margin-bottom: 10px;"></div>

    <label for="confirm_password">Confirm Password <span style="color: red;">*</span></label>
    <input type="password" name="confirm_password" id="confirm_password" required placeholder="Confirm your password" minlength="8">

    <input type="submit" name="submit" value="Sign Up" style="margin-top: 10px;">
    
    <?php
        if (isset($_GET['error'])) {
            $error_message = htmlspecialchars($_GET['error']);
            echo '<p style="color: red;">' . $error_message . '</p>';
        }
    ?>
    <p>Already have an account? <a href="index.php" class="a">Click here</a>.</p>

</form>

<div id="otp-popup" class="otp-popup">
    <form action="verify_otp.php" method="post">
        <h2>Enter OTP</h2>
        <label for="otp">OTP</label>
        <input type="text" name="otp" id="otp" required>
        <input type="hidden" name="customer_name" id="otp-customer_name">
        <input type="hidden" name="customer_username" id="otp-customer_username">
        <input type="hidden" name="customer_email" id="otp-customer_email">
        <input type="hidden" name="customer_password" id="otp-customer_password">
        <input type="submit" value="Verify OTP">
        <button type="button" onclick="hideOTPForm()">Cancel</button>
    </form>
</div>

<script>
    function checkPasswordStrength() {
        var password = document.getElementById("customer_password").value;
        var strengthMeter = document.getElementById("password-strength");

        if (password.length >= 8) {
            strengthMeter.innerHTML = "Password strength: <strong style='color: green;'>Strong</strong>";
        } else {
            strengthMeter.innerHTML = "Password strength: <strong style='color: yellow;'>Weak</strong>";
        }
    }
</script>

</body>
</html>
