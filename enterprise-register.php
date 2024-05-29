<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Enterprise</title>
    <link rel="icon" href="./image/logo.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/register.css">
    <style>
        * { 
            font-family: "Freeman", sans-serif;
        }
        body {
            background-image: url('image/batik.png');
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

        /* Define the styles for hover effect */
        input[type="submit"]:hover {
            letter-spacing: 3px;
            background-color: #dedfdb;
            color: black;
            box-shadow: rgb(208, 206, 213) 0px 7px 29px 0px;
        }

        /* Define the styles for active effect */
        input[type="submit"]:active {
            letter-spacing: 3px;
            background-color: #dedfdb;
            color: hsl(0, 0%, 100%);
            box-shadow: rgb(208, 206, 213) 0px 0px 0px 0px;
            transform: translateY(10px);
            transition: 100ms;
        }
    </style>
    <script>
        function showMessage(message, redirectUrl) {
            alert(message);
            window.location.href = redirectUrl;
        }
    </script>
</head>
<body>

<form action="insert_enterprise.php" method="post" enctype="multipart/form-data" class="registration-form">

    <div class="image-container">
        <img src="./image/logo.png" alt="Registration Image" width="300px" height="100px">
    </div>

    <h1>Enterprise Register</h1>

    <label for="enterprise_name">Enterprise Name <span style="color: red;">*</span></label>
    <input type="text" name="enterprise_name" id="enterprise_name" required placeholder="Enter your enterprise name">

    <label for="enterprise_username">Enterprise Username <span style="color: red;">*</span></label>
    <input type="text" name="enterprise_username" id="enterprise_username" required placeholder="Choose an enterprise username">

    <label for="enterprise_email">Enterprise Email <span style="color: red;">*</span></label>
    <input type="text" name="enterprise_email" id="enterprise_email" required placeholder="Enter your enterprise email" >

    <label for="enterprise_certificate">SSM Certificate <span style="color: red;">*</span></label>
    <input type="file" name="ssm_certificate" id="ssm_certificate" accept=".pdf" required>

    <label for="enterprise_password">Password <span style="color: red;">*</span></label>
    <input type="password" name="enterprise_password" id="enterprise_password" required placeholder="Enter your password" minlength="8" oninput="checkPasswordStrength()" >
    <div id="password-strength" style="margin-bottom: 10px";></div>

    <label for="confirm_password">Confirm Password <span style="color: red;">*</span></label>
    <input type="password" name="confirm_password" id="confirm_password" required placeholder="Confirm your password" minlength="8" >


    <input type="submit" name="submit" value="Sign Up" style="margin-top: 10px;">
    
<?php
    if (isset($_GET['error'])) {
        $error_message = htmlspecialchars($_GET['error']);
        echo '<p style="color: red;">' . $error_message . '</p>';
    }
?>
    <p>Already an Enterprise? <a href="login_enterprise.php" class="a">Login here</a>.</p>

</form>
<script>
    function checkPasswordStrength() {
        var password = document.getElementById("enterprise_password").value;
        var strengthMeter = document.getElementById("password-strength");

        // You can implement your own logic for password strength
        // For simplicity, let's check if the password length is at least 8 characters
        if (password.length >= 8) {
            strengthMeter.innerHTML = "Password strength: <strong style='color: green;'>Strong</strong>";
        } else {
            strengthMeter.innerHTML = "Password strength: <strong style='color: yellow;'>Weak</strong>";
        }
    }
</script>


</body>
</html>