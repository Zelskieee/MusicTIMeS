<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 10px;
        }

        h3 {
            margin-bottom: 20px;
            font-size: 16px;
            color: black;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .otp-input {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px;
        }

        .otp-input input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            border-radius: 10px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .otp-input input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
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

<form id="otp-form" action="verify_otp.php" method="post" autocomplete="off">
    <div class="image-container">
        <img src="./image/logo.png" alt="Login Image" width="300px" height="100px">
    </div>
    <h2>Account Verification</h2>
    <h3>OTP Code has been sent to <span style="color: grey; font-weight: bold; font-size: 18px;">@<?php echo htmlspecialchars($_GET['customer_email']); ?></span></h3>
    <label for="otp">Enter OTP Code <span style="color: red;">*</span></label>
    <div class="otp-input">
        <input type="text" autocomplete="one-time-code" name="otp1" maxlength="1" required>
        <input type="text" autocomplete="one-time-code" name="otp2" maxlength="1" required>
        <input type="text" autocomplete="one-time-code" name="otp3" maxlength="1" required>
        <input type="text" autocomplete="one-time-code" name="otp4" maxlength="1" required>
        <input type="text" autocomplete="one-time-code" name="otp5" maxlength="1" required>
        <input type="text" autocomplete="one-time-code" name="otp6" maxlength="1" required>
    </div>
    <input type="hidden" name="otp" id="otp">
    <input type="hidden" name="customer_email" value="<?php echo htmlspecialchars($_GET['customer_email']); ?>">
    <input type="submit" value="Verify OTP">
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = document.querySelectorAll(".otp-input input");
        const otpHiddenInput = document.getElementById("otp");
        const otpForm = document.getElementById("otp-form");

        inputs.forEach((input, index) => {
            input.addEventListener("input", (e) => {
                if (/[^0-9]/.test(e.target.value)) {
                    e.target.value = "";
                } else if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener("keydown", (e) => {
                if (e.key === "Backspace" && input.value === "" && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        otpForm.addEventListener("submit", (e) => {
            let otp = "";
            inputs.forEach(input => {
                otp += input.value;
            });
            otpHiddenInput.value = otp;
        });
    });
</script>

</body>
</html>
