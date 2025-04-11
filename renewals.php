<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

// Fetch renewals for the logged-in user
$stmt = $conn->prepare("SELECT * FROM renewals WHERE user_id = ? ORDER BY renewal_date ASC");
$stmt->execute([$_SESSION['user_id']]);
$renewals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<body class="bg-gray-100">
  <div class="max-w-5xl mx-auto px-4 py-10">
    <div class="bg-white p-6 rounded-lg shadow-lg animate-fade-in">
      <h2 class="text-2xl font-semibold text-blue-700 mb-4">Upcoming Renewals</h2>

      <?php if (count($renewals) > 0): ?>
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-100 text-gray-600 text-sm uppercase">
              <tr>
                <th class="py-3 px-4 text-left">Title</th>
                <th class="py-3 px-4 text-left">Renewal Date</th>
                <th class="py-3 px-4 text-left">Status</th>
              </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
              <?php foreach ($renewals as $r): ?>
                <?php
                  $date = new DateTime($r['renewal_date']);
                  $now = new DateTime();
                  $daysLeft = (int)$now->diff($date)->format('%r%a');
                  if ($daysLeft < 0) {
                    $status = "<span class='text-red-600 font-semibold'>Overdue</span>";
                  } elseif ($daysLeft <= 7) {
                    $status = "<span class='text-yellow-600 font-semibold'>Due Soon</span>";
                  } else {
                    $status = "<span class='text-green-600 font-semibold'>Upcoming</span>";
                  }
                ?>
                <tr class="hover:bg-gray-50 transition">
                  <td class="py-3 px-4 border-t"><?= htmlspecialchars($r['title']) ?></td>
                  <td class="py-3 px-4 border-t"><?= date('d M Y', strtotime($r['renewal_date'])) ?></td>
                  <td class="py-3 px-4 border-t"><?= $status ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-gray-600">No upcoming renewals found.</p>
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
