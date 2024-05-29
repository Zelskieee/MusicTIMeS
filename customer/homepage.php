<?php include './layout/main.php'; 

include '../db.php';

// Initialize the $enterprises array
$enterprises = [];

// Fetch enterprises from the database
$query = "SELECT enterprise_name, enterprise_image FROM enterprise";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $enterprises[] = [
            'enterprise_name' => $row['enterprise_name'],
            'enterprise_image' => $row['enterprise_image'],
        ];
    }
} else {
    echo "No enterprises found.";
}

$conn->close();
?>
<head>
    <title><?php echo __('home'); ?></title>
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Freeman&family=Poppins:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/homepage.css">
</head>
<main>
    <section>
        <h2 style="text-align: center; font-weight: bold;"><?php echo __('home'); ?></h2>
        <hr>
        <div id="homepage-image">
            <img id="transitionImage" src="../image/homepage.png" alt="Homepage Image" />
        </div>

        <div id="enterprises" style="margin-top: 40px;">
            <h2 style="text-align: center; font-weight: bold;"><?php echo __('featured') ?></h2>
            <div class="enterprise-container">
                <?php 
                if (!empty($enterprises)) {
                    foreach ($enterprises as $enterprise) {
                        $image_path = $enterprise['enterprise_image'] ? "/musictimes/image/enterprise/" . $enterprise['enterprise_image'] : "/musictimes/image/default-profile-image.png";
                        echo '<div class="enterprise-card">';
                        echo '<img src="' . $image_path . '" alt="Enterprise Logo">';
                        echo '<div class="enterprise-name">' . $enterprise['enterprise_name'] . '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p style="text-align: center;">No enterprises available.</p>';
                }
                ?>
            </div>
        </div>

        <div id="video">
    <h2 style="text-align: center; font-weight: bold; margin-top: 40px;"><?php echo __('instrument') ?></h2>
    <br>
    <div class="container">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 10px; text-align: center;">
                    <iframe class="video" width="560" height="315" style="border-radius: 20px;" src="https://www.youtube.com/embed/2Lite8xZdiU?si=4wvSg8xhPdVObf6J" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </td>
                <td>
                    <p class="project" style="text-align: center; font-weight: bold; font-size: 50px;"><i class="fa-solid fa-music fa-bounce"></i> <?php echo __('kompang'); ?></p>
                    <p style="text-align: justify; padding-left: 30px;"><?php echo __('kompang_desc'); ?></p>
                    <div class="image-container">
                        <div class="image-wrapper" data-name="Kompang">
                            <img src="../image/kompang.png" style="width: 100%;"/>
                        </div>
                        <div class="image-wrapper" data-name="Gendang">
                            <img src="../image/gendang.png" style="width: 100%;"/>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="project" style="text-align: center; font-weight: bold; font-size: 50px;"><i class="fa-solid fa-music fa-bounce"></i> <?php echo __('gambus'); ?></p>
                    <p style="text-align: justify; padding-right: 30px;"><?php echo __('gambus_desc'); ?></p>
                    <div class="image-container">
                        <div class="image-wrapper" data-name="Gambus">
                            <img src="../image/gambus.png" style="width: 100%;"/>
                        </div>
                        <div class="image-wrapper" data-name="Marakas">
                            <img src="../image/marakas.png" style="width: 100%;"/>
                        </div>
                        <div class="image-wrapper" data-name="Tabla">
                            <img src="../image/tabla.png" style="width: 100%;"/>
                        </div>
                    </div>
                </td>
                <td style="padding: 10px; text-align: center;">
                    <iframe class="video" width="560" height="315" style="border-radius: 20px;" src="https://www.youtube.com/embed/vhnRyDBMsIc?si=sVzgWYiod8ZEm9rx" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px; text-align: center;">
                    <iframe class="video" width="560" height="315" style="border-radius: 20px;" src="https://www.youtube.com/embed/gVs_FDd2YHg?si=I-648jKCISW_AJXs" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </td>
                <td>
                    <p class="project" style="text-align: center; font-weight: bold; font-size: 50px;"><i class="fa-solid fa-music fa-bounce"></i> <?php echo __('rebana'); ?></p>
                    <p style="text-align: justify; padding-left: 30px;"><?php echo __('rebana_desc'); ?></p>
                    <div class="image-container">
                        <div class="image-wrapper" data-name="Rebana">
                            <img src="../image/rebana.png" style="width: 100%;"/>
                        </div>
                        <div class="image-wrapper" data-name="Marwas">
                            <img src="../image/marwas.png" style="width: 100%;"/>
                        </div>
                    </div>
                </td>

            </tr>
        </table>
    </div>
</div>
    </section>
</main>
<script>
        window.onload = function() {
    let images = ['../image/homepage.png', '../image/homepage1.png'];
    let currentIndex = 0;

    function rotateImage() {
        currentIndex = (currentIndex + 1) % images.length;
        changeImage(images[currentIndex]);
    }

    function changeImage(newSrc) {
        let img = document.getElementById('transitionImage');
        
        img.classList.add('fade-out');

        setTimeout(function() {
            img.src = newSrc;
            img.classList.remove('fade-out');
        }, 1000); // Match the timeout with the transition duration
    }

    // Rotate images every 10 seconds
    setInterval(rotateImage, 10000);
    
    // Start the first rotation after 10 seconds
    setTimeout(rotateImage, 10000);
};
    </script>
<?php include './layout/footer.php'; ?>
