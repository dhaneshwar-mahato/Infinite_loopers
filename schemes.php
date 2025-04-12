<?php
require 'includes/db.php';
session_start();

$filters = ['age' => '', 'income' => '', 'location' => '', 'occupation' => ''];
$user = null;

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

$filtered = false;
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    foreach ($filters as $key => $value) {
        if (!empty($_GET[$key])) {
            $filters[$key] = trim($_GET[$key]);
            $filtered = true;
        }
    }
}

$sql = "SELECT * FROM schemes WHERE 1=1";
$params = [];

if ($filtered) {
    if ($filters['age']) {
        $sql .= " AND min_age <= ? AND max_age >= ?";
        $params[] = $filters['age'];
        $params[] = $filters['age'];
    }
    if ($filters['income']) {
        $sql .= " AND income_limit >= ?";
        $params[] = $filters['income'];
    }
    if ($filters['location']) {
        $sql .= " AND (location = ? OR location IS NULL OR location = '')";
        $params[] = $filters['location'];
    }
    if ($filters['occupation']) {
        $sql .= " AND (occupation = ? OR occupation IS NULL OR occupation = '')";
        $params[] = $filters['occupation'];
    }
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$schemes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Government Schemes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    @keyframes fadeSlideUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .fade-slide-up {
      animation: fadeSlideUp 0.5s ease-out forwards;
    }

    .scheme-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .scheme-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">

<?php include 'includes/header.php'; ?>

<div class="max-w-6xl mx-auto px-4 py-10 fade-slide-up">
  <h1 class="text-3xl md:text-4xl font-bold text-blue-700 mb-6 text-center">Find Government Schemes</h1>
  
  <!-- Filter Form -->
  <form method="GET" class="grid md:grid-cols-4 gap-4 bg-white p-6 rounded-lg shadow mb-8">
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
      <input type="number" name="age" value="<?= htmlspecialchars($filters['age']) ?>" placeholder="Enter Age" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Income</label>
      <input type="number" name="income" step="0.01" value="<?= htmlspecialchars($filters['income']) ?>" placeholder="Enter Income" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
      <input type="text" name="location" value="<?= htmlspecialchars($filters['location']) ?>" placeholder="Enter Location" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
      <input type="text" name="occupation" value="<?= htmlspecialchars($filters['occupation']) ?>" placeholder="Enter Occupation" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="md:col-span-4 text-right">
      <button type="submit" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Apply Filters</button>
    </div>
  </form>

  <!-- Schemes Display -->
  <div class="grid md:grid-cols-2 gap-6">
    <?php if (count($schemes) > 0): ?>
      <?php foreach ($schemes as $scheme): ?>
        <div class="scheme-card bg-white border rounded-lg p-6 shadow fade-slide-up">
          <h3 class="text-xl font-semibold text-blue-700"><?= htmlspecialchars($scheme['title']) ?></h3>
          <p class="mt-2 text-gray-700 text-sm"><?= nl2br(htmlspecialchars($scheme['description'])) ?></p>
          <ul class="mt-4 text-sm text-gray-600 space-y-1">
            <li><strong>Age Range:</strong> <?= $scheme['min_age'] ?> - <?= $scheme['max_age'] ?></li>
            <li><strong>Income Limit:</strong> ₹<?= number_format($scheme['income_limit']) ?></li>
            <li><strong>Location:</strong> <?= $scheme['location'] ?: 'Any' ?></li>
            <!-- <li><strong>Occupation:</strong> <?= $scheme['occupation'] ?: 'Any' ?></li> -->
          </ul>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-span-2 text-center text-gray-500">No matching schemes found.</div>
    <?php endif; ?>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
