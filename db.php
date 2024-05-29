<?php
$host = "localhost";
$db_name = "musictimes";
$username = "root";
$password = "";

$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>

<?php
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_PORT', 587); // Or the port your SMTP server uses
define('SMTP_SECURE', 'tls'); // Or 'ssl' if your SMTP server uses SSL
define('SMTP_USERNAME', 'chojjaarif2002@gmail.com');
define('SMTP_PASSWORD', 'adelsembe2@');
define('FROM_EMAIL', 'noreply@musictimes.com');
define('FROM_NAME', 'MusicTIMeS');
?>
