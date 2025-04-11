<?php
session_start(); // Make sure this is uncommented and at the very top
require 'includes/db.php';

$error = "";
$debug = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (!isset($_POST["email"], $_POST["password"])) {
            throw new Exception("Email or password not set in POST.");
        }

        $email = trim($_POST["email"]);
        $password = $_POST["password"];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("No user found with this email.");
        }

        if (!password_verify($password, $user['password'])) {
            throw new Exception("Password incorrect.");
        }

        $_SESSION["user_id"] = $user["id"];

        // Optional debugging
        $debug .= "User ID set: " . $_SESSION["user_id"] . "<br>";

        header("Location: index.php");
        exit;

    } catch (Exception $e) {
        $error = "Login failed: " . $e->getMessage();
        $debug .= "Error trace: " . $e->getFile() . ":" . $e->getLine();
    }
}
?>


<?php include 'includes/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<body class="bg-gray-100">
  <div class="flex justify-center items-center h-screen p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-8 animate-fade-in">
      <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">Login to Sabka Sahayak</h2>

      <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
          <?= $error ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input type="email" name="email" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Password</label>
          <input type="password" name="password" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition">Login</button>
      </form>

      <div class="text-center mt-4 text-sm text-gray-600">
        Don't have an account? <a href="register.php" class="text-blue-600 font-semibold hover:underline">Register here</a>
      </div>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>

  <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
      animation: fade-in 0.6s ease-out;
    }
  </style>
</body>
</html>
