<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logging Out - Sabka Sahayak</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- TailwindCSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <!-- Custom animation -->
  <style>
    @keyframes fadeOutUp {
      from {
        opacity: 1;
        transform: translateY(0);
      }
      to {
        opacity: 0;
        transform: translateY(-20px);
      }
    }

    .animate-fade-out-up {
      animation: fadeOutUp 1.2s ease-in forwards;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

  <?php include 'includes/header.php'; ?>

  <!-- 🔒 Logout Message -->
  <main class="flex-grow flex items-center justify-center px-4">
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full text-center animate-fade-in">
      <h1 class="text-2xl font-bold text-blue-600 mb-2">Logging you out...</h1>
      <p class="text-gray-600 mb-4">Thanks for using <span class="font-semibold text-blue-500">Sabka Sahayak</span>! You'll be redirected to the homepage shortly.</p>
      <div class="w-16 h-16 mx-auto my-6 border-4 border-blue-300 border-dashed rounded-full animate-spin"></div>
      <a href="index.php" class="inline-block mt-4 text-blue-600 hover:underline text-sm">Click here if not redirected</a>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>

  <!-- Auto redirect after 3 seconds -->
  <script>
    setTimeout(function () {
      window.location.href = "index.php";
    }, 3000);
  </script>
</body>
</html>
