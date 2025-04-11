<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="bg-gray-700 shadow-md p-4 flex justify-between items-center">
  <a href="index.php" class="text-xl font-bold text-blue-600">Sabka Sahayak</a>

  <nav class="flex items-center space-x-6">
    <!-- Always show this -->
    <a href="schemes.php" class="text-white hover:text-blue-300 font-medium">Schemes</a>

    <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="login.php" class="text-blue-400 hover:text-blue-200 font-medium">Login</a>
      <a href="register.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded transition">Register</a>
    <?php else: ?>
      <a href="dashboard.php" title="Dashboard">
        <img src="assets/images/user-icon.png" alt="Profile" class="w-10 h-10 rounded-full border-2 border-blue-400 hover:border-blue-600 transition">
      </a>
    <?php endif; ?>
  </nav>
</header>
