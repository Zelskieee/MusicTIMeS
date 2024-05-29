<?php
// Set the default language to English
$language = 'en';

// Check if the user's language preference is set
if (isset($_SESSION['lang'])) {
    $language = $_SESSION['lang'];
}

// Load the language file
$langFile = "lang/{$language}.php";
if (file_exists($langFile)) {
    $translations = include $langFile;
} else {
    $translations = [];
}

// Function to get translated text
function __($key) {
    global $translations;
    return $translations[$key] ?? $key;
}
?>