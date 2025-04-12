<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Update session if lang is passed in URL
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] === 'hi' ? 'hi' : 'en';
    $url = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: $url");
    exit;
}

// Set default language
$lang = $_SESSION['lang'] ?? 'en';

// Load language array
$langFile = __DIR__ . "/lang/{$lang}.php";
if (file_exists($langFile)) {
    $langData = include($langFile);
} else {
    $langData = include(__DIR__ . "/lang/en.php");
}
