<?php
$host = 'localhost';
$dbname = 'sabkasahayak';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set Error Mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optional: Message for testing only
    // echo "✅ Database connected successfully.";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
