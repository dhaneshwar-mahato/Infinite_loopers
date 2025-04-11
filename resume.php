<?php
session_start();
require 'vendor/autoload.php'; // Load Dompdf

use Dompdf\Dompdf;
use Dompdf\Options;

include 'includes/db.php'; // Your PDO connection in $conn

// Check login
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die("Access denied.");
}

$user_id = $_SESSION['user_id'];

// Get user data
$stmt = $conn->prepare("SELECT name, email, phone, image FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404);
    die("User not found.");
}

// Load image as base64 if local
$imageData = '';
if (!empty($user['image']) && file_exists($user['image'])) {
    $imageType = pathinfo($user['image'], PATHINFO_EXTENSION);
    $imageData = 'data:image/' . $imageType . ';base64,' . base64_encode(file_get_contents($user['image']));
}

// HTML template
$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { border: 2px solid #444; padding: 20px; border-radius: 10px; }
        h1 { color: #2b6cb0; }
        .info { margin-bottom: 20px; }
        .image { float: right; margin-left: 20px; border-radius: 50%; width: 100px; height: 100px; object-fit: cover; border: 2px solid #2b6cb0; }
    </style>
</head>
<body>
    <div class="container">
        '. ($imageData ? '<img src="' . $imageData . '" class="image" />' : '') .'
        <h1>' . htmlspecialchars($user['name']) . '</h1>
        <div class="info">
            <strong>Email:</strong> ' . htmlspecialchars($user['email']) . '<br>
            <strong>Phone:</strong> ' . htmlspecialchars($user['phone']) . '<br>
        </div>
        <h3>Skills</h3>
        <p>' . nl2br(htmlspecialchars($user['skills'])) . '</p>
    </div>
</body>
</html>
';

// Generate PDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Force download
header("Content-Type: application/pdf");
$dompdf->stream("Resume_" . preg_replace("/[^a-zA-Z0-9]/", "_", $user['name']) . ".pdf", ["Attachment" => true]);
exit;
