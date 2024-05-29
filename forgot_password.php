<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" href="./image/logo.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/forgot_password.css">

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
</head>
<body>

<div class="forgot_email-form">
    <form action="forgot_email.php" method="post">

        <div class="image-container">
            <img src="./image/logo.png" alt="Forgot Image" width="300px" height="100px">
        </div>

        <h1>Forgot Password</h1>

        <label for="email">Email</label>
        <input type="text" name="customer_email" id="customer_email">

        <input type="submit" name="submit" value="Verify" style="margin-top: 10px;">

<?php
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']);
    echo '<p style="color: red;">' . $error_message . '</p>';
}
?>
    </form>

    <button id="back-button" onclick="window.location.href='index.php'">Back</button>
</div>

</body>
</html>