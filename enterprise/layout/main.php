<?php 

session_start(); 
include '../db.php';

if (!isset($_SESSION['enterprise_id'])) {
    header('Location: ../login_enterprise.php');
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
    <title><?php echo __('tab_title')?></title>
    <link rel="icon" href="/musictimes/image/logo.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style/sidebar_enterprise.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <style>
        .profile-details {
            position: relative;
            display: inline-block;
        }

        .enterprise-link {
            color: initial;
            text-decoration: none;
        }

        .enterprise-link:hover {
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
    <h1>MusicTIMeS <span><?php echo __('title')?></span></h1>
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

        <script>
                function toggleLanguage() {
                    var checkbox = document.getElementById('language_mode');
                    var language = document.getElementsByName('lang')[0].value;
                    let _language = ""
                    if (checkbox.checked && language != 'my') {
                        _language = "my"
                    } else {
                        _language = 'en'
                    }
                    const formData = new FormData();
                    formData.append('lang', _language);

                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        window.location.reload();
                        return response.text();
                    })
                }
            </script>

        <div class="profile-option" style="background-color: #F8F6F0;">
            <?php 
                // Assuming you have access to the enterprise data
                $enterprise_username = $_SESSION['enterprise_username'];
                $query = "SELECT enterprise_name, enterprise_image FROM enterprise WHERE enterprise_username='$enterprise_username'";
                $result = $conn->query($query);
                $enterprise = $result->fetch_assoc();
            ?>
            <div class="profile-info">
            <div class="profile-image">
                <?php 
                $image_path = "/musictimes/image/enterprise/";
                $default_image = "/musictimes/image/default-profile-image.png";

                if (!empty($enterprise['enterprise_image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path . $enterprise['enterprise_image'])) {
                    $image_path .= $enterprise['enterprise_image'];
                } else {
                    $image_path = $default_image;
                }

                echo '<img src="' . htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8') . '" alt="Enterprise Profile Image" style="width: 50%; height: 50%; max-width: 100px; max-height: 100px; border-radius: 50%;">';
                ?>
            </div>

            <div class="profile-details">
                <a href="./enterprise-profile.php" class="enterprise-link" style="color: initial; text-decoration: none;" onmouseover="this.style.color='grey'" onmouseout="this.style.color='initial'">
                    <p class="enterprise-name"><?php echo $enterprise['enterprise_name']; ?></p>
                </a>
                <div class="dropdown-content">
                    <a href="./enterprise-profile.php"><i class="fa-solid fa-shop fa-beat"></i> <?php echo __('enterprise_profile'); ?></a>
                </div>
            </div>
            </div>
            </div>
        <div>
        <a class="logout-button" href="#" onclick="confirmLogout()">
        <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
        <div class="text"><?php echo __('logout')?></div></a>
            </div>
    </div>
    
    <script>
    function confirmLogout() {
    var confirmLogout = confirm("Are you sure you want to logout?");
    if (confirmLogout) {
        // Destroy PHP session
        fetch('controller/logout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'logout=true',
        })
        .then(response => {
            if (response.ok) {
                // Redirect to logout page
                window.location.href = "../login_enterprise.php";
            } else {
                console.error('Failed to logout');
            }
        })
        .catch(error => {
            console.error('Error occurred while trying to logout:', error);
        });
    }
}

</script>
</header>