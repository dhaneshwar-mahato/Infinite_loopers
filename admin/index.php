<?php
session_start();
require '../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch overview counts
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalSchemes = $conn->query("SELECT COUNT(*) FROM schemes")->fetchColumn();
$totalDocuments = $conn->query("SELECT COUNT(*) FROM documents")->fetchColumn();
$upcomingRenewals = $conn->query("SELECT COUNT(*) FROM renewals WHERE renewal_date >= CURDATE()")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeUp {
      animation: fadeUp 0.6s ease-out;
    }
  </style>
</head>
<body class="bg-gray-100 font-sans">

  <div class="min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-blue-800 text-white shadow-lg">
      <div class="p-6 text-center border-b border-blue-700">
        <h1 class="text-xl font-bold">Admin Panel</h1>
      </div>
      <nav class="flex flex-col gap-2 p-4">
        <a href="index.php" class="px-4 py-2 rounded hover:bg-blue-700 transition">Dashboard</a>
        <a href="schemes.php" class="px-4 py-2 rounded hover:bg-blue-700 transition">Manage Schemes</a>
        <a href="documents.php" class="px-4 py-2 rounded hover:bg-blue-700 transition">Documents</a>
        <a href="users.php" class="px-4 py-2 rounded hover:bg-blue-700 transition">Users</a>
        <a href="renewals.php" class="px-4 py-2 rounded hover:bg-blue-700 transition">Renewals</a>
        <a href="logout.php" class="mt-4 px-4 py-2 rounded bg-red-600 hover:bg-red-700 transition text-center">Logout</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
      <h2 class="text-3xl font-bold mb-6 text-gray-700">Overview</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fadeUp">
        <div class="bg-white rounded-lg shadow p-6 hover:scale-105 transform transition duration-300">
          <h3 class="text-lg font-semibold text-gray-600 mb-2">Total Users</h3>
          <p class="text-3xl font-bold text-blue-700"><?= $totalUsers ?></p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 hover:scale-105 transform transition duration-300">
          <h3 class="text-lg font-semibold text-gray-600 mb-2">Total Schemes</h3>
          <p class="text-3xl font-bold text-green-600"><?= $totalSchemes ?></p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 hover:scale-105 transform transition duration-300">
          <h3 class="text-lg font-semibold text-gray-600 mb-2">Documents Uploaded</h3>
          <p class="text-3xl font-bold text-yellow-600"><?= $totalDocuments ?></p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 hover:scale-105 transform transition duration-300">
          <h3 class="text-lg font-semibold text-gray-600 mb-2">Upcoming Renewals</h3>
          <p class="text-3xl font-bold text-red-600"><?= $upcomingRenewals ?></p>
        </div>
      </div>
    </main>
  </div>

</body>
</html>
