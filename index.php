<?php 

    $lang = 'en';
    if (isset($_SESSION['lang'])) {
        $lang = $_SESSION['lang'];
    }
    $translations = include_once "languages/$lang.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="./image/logo.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/login.css">
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
</head>
<style>
        * { 
            font-family: "Freeman", sans-serif;
        }

        body {
            background-image: url('image/bg.png');
            background-size: cover;
            background-position: center;
        }
        #image-gallery {
            width: 50%;
            margin-left: 20px;
        }

        #image-gallery img {
            width: 50%;
            display: none; 
            transition: opacity 1.5s ease;
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

        nav ul {
            margin-top: -10px;
            margin-bottom: -5px;
            list-style: none;
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }

        nav li {
            margin: 0 15px;
            border-radius: 50px;
            padding: 15px;
        }

        nav li.active a,
        nav li a:hover {
            color: white;
            text-decoration: none;
            background-color: #c8c9c5;
            border-radius: 50px;
            padding: 15px;
            transition: all 0.3s;
        }
    </style>
<body>
<div id="image-gallery">
        <img src="./image/gambus.png" alt="Instrument 1" class="image">
        <img src="./image/tabla.png" alt="Instrument 2" class="image">
        <img src="./image/caklempong.png" alt="Instrument 3" class="image">
        <img src="./image/jidor.png" alt="Instrument 4" class="image">
        <img src="./image/kompang.png" alt="Instrument 5" class="image">
        <img src="./image/marakas.png" alt="Instrument 6" class="image">
        <img src="./image/angklung.png" alt="Instrument 7" class="image">
        <img src="./image/gong.png" alt="Instrument 8" class="image">
        <img src="./image/marwas.png" alt="Instrument 9" class="image">
        <img src="./image/rebana.png" alt="Instrument 10" class="image">
        <img src="./image/gendang.png" alt="Instrument 11" class="image">
</div>

<div class="login-form">
    <nav>
        <ul>
            <li class="active"><a href="index.php"><i class="fa-solid fa-user fa-fade" title="Customer Login"></i></a></li>
            <li><a href="login_enterprise.php"><i class="fa fa-store-alt" style="color: black;" title="Enterprise Login" onmouseover="this.style.color='white'" onmouseout="this.style.color='black'"></i></a></li>
        </ul>
    </nav>
    <form action="authenticate.php" method="post">

        <div class="image-container">
            <img src="./image/logo.png" alt="Login Image" width="300px" height="100px">
        </div>

        <h1>Login</h1>

        <label for="customer_username">Username <span style="color: red;">*</span></label>
        <input type="text" name="customer_username" id="customer_username" autocomplete="off">

        <label for="customer_password">Password <span style="color: red;">*</span></label>
        <input type="password" name="customer_password" id="customer_password" autocomplete="off">

        <input type="submit" name="submit" value="Login" style="margin-top: 10px;">

<?php
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']);
    echo '<p style="color: red;">' . $error_message . '</p>';
}
?>
        <a href="forgot_password.php" class="forgot">Forgot Password</a>

        <p>Don't have an account? <a href="register.php" class="a">Click here</a>.</p>

    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const images = document.querySelectorAll(".image");
        let currentIndex = 0;
        let intervalId; // variable to hold the interval ID

        function changeImage() {
            // Hide all images
            images.forEach(image => {
                image.style.display = "none";
            });

            // Show next image
            images[currentIndex].style.display = "block";

            // Update currentIndex for the next image
            currentIndex = (currentIndex + 1) % images.length;
        }

        function startImageInterval() {
            intervalId = setInterval(changeImage, 3000);
        }

        function stopImageInterval() {
            clearInterval(intervalId);
        }

        // Call changeImage initially to display the first image
        changeImage();

        // Start the image interval after 5 seconds
        setTimeout(startImageInterval, 3000);

        // Add event listener for form submission
        const loginForm = document.querySelector('.login-form form');
        loginForm.addEventListener('submit', function(event) {
            // Stop the image interval when form is submitted
            stopImageInterval();
        });
    });
</script>



</body>
</html>