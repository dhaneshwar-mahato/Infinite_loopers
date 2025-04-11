<?php
require 'includes/db.php';
session_start();

$filters = ['age' => '', 'income' => '', 'location' => '', 'occupation' => ''];
$user = null;

// Get user data if logged in
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $filters['age'] = $user['age'];
        $filters['income'] = $user['income'];
        $filters['location'] = $user['location'];
        $filters['occupation'] = $user['occupation'];
    }
}

// Handle scheme apply
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['apply_scheme']) && isset($_SESSION['user_id'])) {
    $scheme_id = (int)$_POST['scheme_id'];

    // Step 1: Check if the scheme exists
    $check_scheme = $conn->prepare("SELECT id FROM schemes WHERE id = ?");
    $check_scheme->execute([$scheme_id]);
    if ($check_scheme->fetch()) {

        // Step 2: Check if already applied
        $check = $conn->prepare("SELECT id FROM user_schemes WHERE user_id = ? AND scheme_id = ?");
        $check->execute([$_SESSION['user_id'], $scheme_id]);

        if (!$check->fetch()) {
            // Step 3: Insert into user_schemes
            $insert = $conn->prepare("INSERT INTO user_schemes (user_id, scheme_id) VALUES (?, ?)");
            $insert->execute([$_SESSION['user_id'], $scheme_id]);
        }
    }
}

// Handle filter form
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    foreach (['age', 'income', 'location', 'occupation'] as $key) {
        if (isset($_GET[$key]) && $_GET[$key] !== '') {
            $filters[$key] = trim($_GET[$key]);
        }
    }
}

// Build SQL query
$sql = "SELECT * FROM schemes WHERE 1=1";
$params = [];

if ($filters['age'] !== '') {
    $sql .= " AND min_age <= ? AND max_age >= ?";
    $params[] = $filters['age'];
    $params[] = $filters['age'];
}
if ($filters['income'] !== '') {
    $sql .= " AND income_limit >= ?";
    $params[] = $filters['income'];
}
if ($filters['location'] !== '') {
    $sql .= " AND (location = ? OR location IS NULL OR location = '')";
    $params[] = $filters['location'];
}
if ($filters['occupation'] !== '') {
    $sql .= " AND (occupation = ? OR occupation IS NULL OR occupation = '')";
    $params[] = $filters['occupation'];
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$schemes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Schemes - Sabka Sahayak</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeUp {
      animation: fadeUp 0.5s ease-out;
    }
  </style>
</head>
<body class="bg-gray-100 text-gray-800">

<?php include 'includes/header.php'; ?>

<section class="max-w-5xl mx-auto p-6 mt-8 bg-white shadow-lg rounded-lg animate-fadeUp">
  <h2 class="text-2xl font-bold text-blue-700 mb-4">Find Schemes for You</h2>
  <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <input type="number" name="age" value="<?= htmlspecialchars($filters['age']) ?>" placeholder="Age" class="p-2 border rounded focus:ring-2 focus:ring-blue-500">
    <input type="number" name="income" step="0.01" value="<?= htmlspecialchars($filters['income']) ?>" placeholder="Monthly Income" class="p-2 border rounded focus:ring-2 focus:ring-blue-500">
    <input type="text" name="location" value="<?= htmlspecialchars($filters['location']) ?>" placeholder="Location" class="p-2 border rounded focus:ring-2 focus:ring-blue-500">
    <input type="text" name="occupation" value="<?= htmlspecialchars($filters['occupation']) ?>" placeholder="Occupation" class="p-2 border rounded focus:ring-2 focus:ring-blue-500">
    <div class="col-span-1 md:col-span-2 text-right">
      <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Apply Filters</button>
    </div>
  </form>

  <div class="grid md:grid-cols-2 gap-6">
    <?php if (count($schemes) > 0): ?>
      <?php foreach ($schemes as $scheme): ?>
        <div class="bg-gray-50 p-4 rounded shadow hover:shadow-md transition duration-300 border border-gray-200 animate-fadeUp">
          <h3 class="text-lg font-semibold text-blue-700"><?= htmlspecialchars($scheme['title']) ?></h3>
          <p class="text-sm text-gray-600 mt-2"><?= htmlspecialchars($scheme['description']) ?></p>
          <p class="text-sm text-gray-500 mt-1"><strong>Eligibility:</strong> Age <?= $scheme['min_age'] ?>-<?= $scheme['max_age'] ?>, Income ≤ ₹<?= number_format($scheme['income_limit']) ?></p>
          <form method="POST" class="mt-4">
            <input type="hidden" name="scheme_id" value="<?= $scheme['id'] ?>">
            <button type="submit" name="apply_scheme" class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700 transition">Apply</button>
          </form>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-gray-600 col-span-2 text-center">No schemes found matching your filters.</p>
    <?php endif; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

</body>
</html>
