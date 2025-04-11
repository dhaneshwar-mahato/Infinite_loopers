<?php
require 'includes/db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $age = (int)$_POST["age"];
    $income = (float)$_POST["income"];
    $occupation = trim($_POST["occupation"]);
    $location = trim($_POST["location"]);
    $dob = $_POST["dob"]; // Expected format: YYYY-MM-DD

    // Handle image upload
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0755, true);

        $filename = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            $error = "Image upload failed.";
        }
    }

    if (!$error) {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, age, income, occupation, location, dob, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $password, $age, $income, $occupation, $location, $dob, $imagePath])) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script>
  document.getElementById('dob').addEventListener('change', function () {
    const dob = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const m = today.getMonth() - dob.getMonth();

    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
      age--;
    }

    document.getElementById('age').value = age > 0 ? age : '';
  });
</script>

<body class="bg-gray-100">
  <div class="flex justify-center items-center min-h-screen p-4">
    <div class="bg-white shadow-lg rounded-xl p-8 max-w-lg w-full animate-slide-up">
      <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">Create your account</h2>

      <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?= $error ?></div>
      <?php elseif ($success): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?= $success ?></div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1">Full Name</label>
          <input type="text" name="name" required class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input type="email" name="email" required class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Password</label>
          <input type="password" name="password" required class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
        <label class="block text-sm font-medium mb-1">Date of Birth</label>
        <input type="date" name="dob" id="dob" required class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
        <label class="block text-sm font-medium mb-1">Age</label>
        <input type="number" name="age" id="age" min="10" max="120" readonly class="w-full px-4 py-2 border rounded bg-gray-100 text-gray-600">
        </div>


        <div>
          <label class="block text-sm font-medium mb-1">Monthly Income (₹)</label>
          <input type="number" name="income" step="0.01" value="0.00" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Occupation</label>
          <input type="text" name="occupation" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Profile Photo</label>
          <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border rounded bg-white focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Location (Auto)</label>
          <input type="text" id="location" name="location" readonly class="w-full px-4 py-2 border rounded bg-gray-100 text-gray-600">
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition">Register</button>
      </form>

      <div class="text-center mt-4 text-sm text-gray-600">
        Already have an account? <a href="login.php" class="text-blue-600 font-semibold hover:underline">Login here</a>
      </div>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>

  <script>
    // Auto-detect location
    window.onload = function () {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(async function(position) {
          const lat = position.coords.latitude;
          const lon = position.coords.longitude;

          try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`);
            const data = await res.json();
            document.getElementById('location').value = data.address.city || data.address.town || data.address.village || data.address.state || "Location Unavailable";
          } catch (e) {
            document.getElementById('location').value = "Could not fetch location.";
          }
        }, function() {
          document.getElementById('location').value = "Permission denied.";
        });
      } else {
        document.getElementById('location').value = "Geolocation not supported.";
      }
    };
  </script>
  <script>
  document.getElementById('dob').addEventListener('change', function () {
    const dob = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const m = today.getMonth() - dob.getMonth();

    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
      age--;
    }

    document.getElementById('age').value = age > 0 ? age : '';
  });
</script>

  <style>
    @keyframes slide-up {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up {
      animation: slide-up 0.6s ease-out;
    }
  </style>
</body>
</html>
