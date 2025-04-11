<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['documents'])) {
  $doc_type = trim($_POST['doc_type']);
  $file = $_FILES['documents'];

  if ($file['error'] === 0) {
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    $fileName = uniqid() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
      $stmt = $conn->prepare("INSERT INTO documents (user_id, doc_type, filename) VALUES (?, ?, ?)");
      if ($stmt->execute([$_SESSION['user_id'], $doc_type, $fileName])) {
        $success = "Document uploaded successfully!";
      } else {
        $error = "Database error. Please try again.";
      }
    } else {
      $error = "Failed to move uploaded file.";
    }
  } else {
    $error = "Error during file upload.";
  }
}

// Fetch user's documents
$docs = $conn->prepare("SELECT * FROM documents WHERE user_id = ? ORDER BY uploaded_on DESC");
$docs->execute([$_SESSION['user_id']]);
$documents = $docs->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<body class="bg-gray-100">
  <div class="max-w-4xl mx-auto py-10 px-4">
    <div class="bg-white p-6 rounded-lg shadow-lg animate-fade-in">
      <h2 class="text-2xl font-semibold text-blue-700 mb-4">Upload Document</h2>

      <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?= $error ?></div>
      <?php elseif ($success): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?= $success ?></div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
          <input type="text" name="doc_type" required class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Upload File</label>
          <input type="file" name="document" required class="w-full px-4 py-2 border rounded bg-white focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="text-right">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Upload</button>
        </div>
      </form>
    </div>

    <div class="mt-10 bg-white p-6 rounded-lg shadow-lg animate-fade-in">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Uploaded Documents</h2>
      <?php if (count($documents) > 0): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-left border border-gray-300 rounded">
            <thead class="bg-gray-100">
              <tr>
                <th class="py-2 px-4 border-b">Document Type</th>
                <th class="py-2 px-4 border-b">File</th>
                <th class="py-2 px-4 border-b">Uploaded On</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($documents as $doc): ?>
                <tr class="hover:bg-gray-50 transition">
                  <td class="py-2 px-4 border-b"><?= htmlspecialchars($doc['doc_type']) ?></td>
                  <td class="py-2 px-4 border-b">
                    <a href="uploads/<?= $doc['filename'] ?>" target="_blank" class="text-blue-600 hover:underline"><?= $doc['filename'] ?></a>
                  </td>
                  <td class="py-2 px-4 border-b"><?= date('d M Y, h:i A', strtotime($doc['uploaded_on'])) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-gray-600">No documents uploaded yet.</p>
      <?php endif; ?>
    </div>
  </div>

  <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
      animation: fade-in 0.5s ease-out;
    }
  </style>
</body>
</html>
