<?php
require_once './lang.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch user image if logged in
$userImage = 'assets/images/default-profile.png'; // default image
if (isset($_SESSION['user_id'])) {
    $conn = new mysqli("localhost", "root", "", "sabkasahayak"); // UPDATE your DB credentials

    if (!$conn->connect_error) {
        $stmt = $conn->prepare("SELECT image FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($image);
        if ($stmt->fetch() && !empty($image)) {
            $userImage = $image;
        }
        $stmt->close();
        $conn->close();
    }
}
?>

<header class="bg-gray-700 shadow-md p-4 flex justify-between items-center">
  <a href="index.php" class="text-xl font-bold text-blue-600"><?= $langData['site_title'] ?></a>

  <nav class="flex items-center space-x-6">
    <a href="schemes.php" class="text-white hover:text-blue-300 font-medium"><?= $langData['nav_schemes'] ?></a>
    <a href="contactus.php" class="text-white hover:text-blue-300 font-medium"><?= $langData['nav_contact'] ?></a>

    <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="login.php" class="text-blue-400 hover:text-blue-200 font-medium"><?= $langData['nav_login'] ?></a>
      <a href="register.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded transition"><?= $langData['nav_register'] ?></a>
    <?php else: ?>
      <a href="dashboard.php" title="<?= $langData['nav_dashboard'] ?>">
        <img src="<?= htmlspecialchars($userImage) ?>" alt="Profile" class="w-10 h-10 rounded-full border-2 border-blue-400 hover:border-blue-600 transition object-cover">
      </a>
    <?php endif; ?>

    <a href="?lang=<?= $lang === 'en' ? 'hi' : 'en' ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm">
      <?= $langData['switch_lang'] ?>
    </a>
  </nav>
</header>
