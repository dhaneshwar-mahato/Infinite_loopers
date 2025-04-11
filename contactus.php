<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $success = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - Sabka Sahayak</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #f0f4f8, #e2e8f0);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center py-10 px-4">


  <div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-6xl w-full grid grid-cols-1 md:grid-cols-2 animate-fadeIn">
    
    <!-- Left Side (Form) -->
    <div class="p-8">
      <?php if (isset($success) && $success): ?>
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">Thank you for contacting us! We’ll get back to you soon.</div>
      <?php endif; ?>

      <h2 class="text-3xl font-bold mb-6 text-blue-700">Contact Us</h2>
      <form action="" method="POST" class="space-y-6 animate-slideIn">
        <div>
          <label class="block mb-1 font-semibold text-gray-700">Your Name</label>
          <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </div>
        <div>
          <label class="block mb-1 font-semibold text-gray-700">Email Address</label>
          <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </div>
        <div>
          <label class="block mb-1 font-semibold text-gray-700">Your Message</label>
          <textarea name="message" rows="5" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"></textarea>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition transform hover:scale-105">Send Message</button>
      </form>
    </div>

    <!-- Right Side (Map & Image) -->
    <div class="bg-blue-600 text-white p-6 flex flex-col justify-center space-y-4">
      <h3 class="text-xl font-semibold">Find Us</h3>
      <div class="rounded-lg overflow-hidden shadow-lg border-4 border-white animate-slideInUp">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3676.8942084897785!2d86.09989157535239!3d22.843403179299706!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f5e5f1b496777f%3A0x1d506033b3ed835d!2sARKA%20JAIN%20University!5e0!3m2!1sen!2sin!4v1744383698668!5m2!1sen!2sin"
          width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
      <img src="https://illustrations.popsy.co/white/contact.svg" alt="Contact" class="w-3/4 mx-auto mt-4 animate-slideInUp">
    </div>
  </div>

  <!-- Animations -->
  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInUp {
      from { transform: translateY(50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .animate-fadeIn {
      animation: fadeIn 0.8s ease-out;
    }

    .animate-slideIn {
      animation: fadeIn 1s ease-out;
    }

    .animate-slideInUp {
      animation: slideInUp 1s ease-out;
    }
  </style>

</body>
</html>
