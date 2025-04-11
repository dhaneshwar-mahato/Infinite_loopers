<?php
session_start();
require '../includes/db.php';

// Redirect if admin is not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Handle Add/Edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $min_age = (int)$_POST['min_age'];
    $max_age = (int)$_POST['max_age'];
    $income_range = trim($_POST['income_range']);
    $occupation = trim($_POST['occupation']);
    $location = trim($_POST['location']);

    if (isset($_POST['scheme_id']) && $_POST['scheme_id'] !== '') {
        $id = (int)$_POST['scheme_id'];
        $stmt = $conn->prepare("UPDATE schemes SET title=?, description=?, min_age=?, max_age=?, income_limit=?, occupation=?, location=? WHERE id=?");
        $stmt->execute([$title, $description, $min_age, $max_age, $income_range, $occupation, $location, $id]);
        $_SESSION['success'] = "Scheme updated successfully!";
    } else {
        $stmt = $conn->prepare("INSERT INTO schemes (title, description, min_age, max_age, income_limit, occupation, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $min_age, $max_age, $income_range, $occupation, $location]);
        $_SESSION['success'] = "New scheme added successfully!";
    }

    header("Location: schemes.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->prepare("DELETE FROM schemes WHERE id=?")->execute([$id]);
    $_SESSION['success'] = "Scheme deleted successfully!";
    header("Location: schemes.php");
    exit;
}

// Fetch data for editing
$edit_scheme = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM schemes WHERE id=?");
    $stmt->execute([$edit_id]);
    $edit_scheme = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch all schemes
$schemes = $conn->query("SELECT * FROM schemes ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<script src="https://cdn.tailwindcss.com"></script>

<div class="flex min-h-screen bg-gray-100">
  <!-- <?php include 'sidebar.php'; ?> -->
  <div class="p-6 w-full">
    <h2 class="text-3xl font-bold text-blue-700 mb-6">🎯 Manage Welfare Schemes</h2>

    <!-- SUCCESS MESSAGE -->
    <?php if (isset($_SESSION['success'])): ?>
      <div class="mb-6 px-4 py-3 bg-green-100 text-green-800 border border-green-300 rounded-md animate-fade-in">
        <?= $_SESSION['success'] ?>
        <?php unset($_SESSION['success']); ?>
      </div>
    <?php endif; ?>

    <!-- FORM -->
    <form method="POST" class="grid md:grid-cols-3 gap-4 bg-white p-6 rounded-lg shadow-md mb-10 animate-fade-in border border-blue-200">
      <input type="hidden" name="scheme_id" value="<?= $edit_scheme ? htmlspecialchars($edit_scheme['id']) : '' ?>">

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Title</label>
        <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
               value="<?= $edit_scheme ? htmlspecialchars($edit_scheme['title']) : '' ?>">
      </div>

      <div class="md:col-span-3">
        <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
        <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"><?= $edit_scheme ? htmlspecialchars($edit_scheme['description']) : '' ?></textarea>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Min Age</label>
        <input type="number" name="min_age" class="w-full px-4 py-2 border border-gray-300 rounded"
               value="<?= $edit_scheme ? htmlspecialchars($edit_scheme['min_age']) : '' ?>">
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Max Age</label>
        <input type="number" name="max_age" class="w-full px-4 py-2 border border-gray-300 rounded"
               value="<?= $edit_scheme ? htmlspecialchars($edit_scheme['max_age']) : '' ?>">
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Income Range</label>
        <input type="text" name="income_range" placeholder="e.g. 0-10000" class="w-full px-4 py-2 border border-gray-300 rounded"
               value="<?= $edit_scheme ? htmlspecialchars($edit_scheme['income_limit']) : '' ?>">
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Occupation</label>
        <input type="text" name="occupation" class="w-full px-4 py-2 border border-gray-300 rounded"
               value="<?= $edit_scheme ? htmlspecialchars($edit_scheme['occupation']) : '' ?>">
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Location</label>
        <input type="text" name="location" class="w-full px-4 py-2 border border-gray-300 rounded"
               value="<?= $edit_scheme ? htmlspecialchars($edit_scheme['location']) : '' ?>">
      </div>

      <div class="md:col-span-3 text-right">
        <button type="submit" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow-sm transition">
          <?= $edit_scheme ? '✏️ Update Scheme' : '💾 Save Scheme' ?>
        </button>
      </div>
    </form>

    <!-- TABLE -->
    <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200 animate-fade-in">
      <h3 class="text-xl font-semibold text-gray-700 mb-4">📋 All Schemes</h3>
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead class="bg-blue-100 text-blue-800">
            <tr>
              <th class="px-4 py-2">Title</th>
              <th class="px-4 py-2">Age</th>
              <th class="px-4 py-2">Income</th>
              <th class="px-4 py-2">Occupation</th>
              <th class="px-4 py-2">Location</th>
              <th class="px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($schemes as $scheme): ?>
              <tr class="border-b hover:bg-gray-50 transition">
                <td class="px-4 py-2 font-medium text-gray-800"><?= htmlspecialchars($scheme['title']) ?></td>
                <td class="px-4 py-2 text-gray-600"><?= $scheme['min_age'] ?> - <?= $scheme['max_age'] ?></td>
                <td class="px-4 py-2 text-gray-600"><?= htmlspecialchars($scheme['income_limit']) ?></td>
                <td class="px-4 py-2 text-gray-600"><?= htmlspecialchars($scheme['occupation']) ?></td>
                <td class="px-4 py-2 text-gray-600"><?= htmlspecialchars($scheme['location']) ?></td>
                <td class="px-4 py-2 space-x-2">
                  <a href="schemes.php?edit=<?= $scheme['id'] ?>" class="text-indigo-600 hover:underline">Edit</a>
                  <a href="schemes.php?delete=<?= $scheme['id'] ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fade-in 0.5s ease-out;
}
</style>

<?php include '../includes/footer.php'; ?>
