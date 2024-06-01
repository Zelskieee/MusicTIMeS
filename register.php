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
    </style>
<body>

<form action="insert.php" method="post" class="registration-form">

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
