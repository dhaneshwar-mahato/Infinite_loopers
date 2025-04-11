<?php
session_start();
require 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Set user image
$userImage = (!empty($user['image'])) ? $user['image'] : 'assets/images/default-profile.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Sabka Sahayak</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">

  <?php include 'includes/header.php'; ?>

  <!-- 🧑‍💼 User Info -->
  <section class="max-w-4xl mx-auto mt-10 bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
      <div class="flex items-center space-x-4">
        <img src="<?= htmlspecialchars($userImage) ?>" alt="User Profile" class="w-16 h-16 rounded-full border-2 border-blue-400 object-cover">
        <h1 class="text-2xl font-bold">Welcome, <?= htmlspecialchars($user['name']) ?> 👋</h1>
      </div>
      <a href="logout.php" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-md shadow transition-all duration-300">
        Logout
        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
        </svg>
      </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
      <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
      <p><strong>Age:</strong> <?= htmlspecialchars($user['age']) ?></p>
      <p><strong>Income:</strong> ₹<?= number_format($user['income'], 2) ?></p>
      <p><strong>Occupation:</strong> <?= htmlspecialchars($user['occupation'] ?? 'N/A') ?></p>
      <p><strong>Location:</strong> <?= htmlspecialchars($user['location'] ?? 'Unknown') ?></p>
      <p><strong>Joined:</strong> <?= date("d M Y", strtotime($user['created_at'])) ?></p>
    </div>
  </section>

  <!-- 🚀 Navigation Buttons -->
  <section class="max-w-4xl mx-auto mt-8 p-6">
    <h2 class="text-xl font-semibold mb-4">Navigate</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 md:gap-6">
      <?php
      $navItems = [
        ['Schemes', 'schemes.php', '📜'],
        ['Eligibility', 'eligibility-checker.php', '✅'],
        ['Documents', 'documents.php', '📁'],
        ['Chatbot', 'chatbot.php', '🤖'],
        ['Renewals', 'renewals.php', '🔁'],
        ['Profile', 'profile.php', '👤']
      ];

      foreach ($navItems as $item) {
        echo '
        <a href="' . $item[1] . '" class="border border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-100 text-gray-800 p-4 rounded-lg shadow-sm flex flex-col items-center transition transform hover:scale-105 duration-300">
          <div class="text-3xl mb-2">' . $item[2] . '</div>
          <span class="text-base font-medium text-center">' . $item[0] . '</span>
        </a>';
      }
      ?>
    </div>
  </section>

  <?php include 'includes/footer.php'; ?>

</body>
</html>
