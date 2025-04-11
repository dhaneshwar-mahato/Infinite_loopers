<?php
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sabka Sahayak | Welcome</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body class="bg-gray-100 text-gray-800">

<?php include 'includes/header.php'; ?>
<?php $isLoggedIn = isset($_SESSION['user_id']); 
$redirectLink = isset($_SESSION['user_id']) ? 'index.php' : 'login.php';?>

<!-- Hero Section / Banner -->
<section id="banner" class="relative overflow-hidden cursor-pointer" onclick="window.location.href='<?= $redirectLink ?>'">
  <div class="swiper bannerSwiper h-[60vh] md:h-[75vh]">
    <div class="swiper-wrapper">
      <div class="swiper-slide relative">
        <img src="assets/images/1.jpg" class="w-full h-full object-cover" alt="Banner 1">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center text-white text-center">
          <h2 class="text-3xl md:text-5xl font-bold mb-4">Empowering Citizens</h2>
          <p class="text-lg md:text-xl mb-6">Explore benefits made for you.</p>
          <a href="<?= $redirectLink ?>" onclick="event.stopPropagation()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition">Get Started</a>
        </div>
      </div>
      <div class="swiper-slide relative">
        <img src="assets/images/2.jpg" class="w-full h-full object-cover" alt="Banner 2">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center text-white text-center">
          <h2 class="text-3xl md:text-5xl font-bold mb-4">Know Your Rights</h2>
          <p class="text-lg md:text-xl mb-6">Find and apply for government schemes easily.</p>
          <a href="<?= $redirectLink ?>" onclick="event.stopPropagation()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition">Get Started</a>
        </div>
      </div>
    </div>

    <!-- Navigation buttons -->
    <div class="swiper-button-next text-white"></div>
    <div class="swiper-button-prev text-white"></div>
    <div class="swiper-pagination"></div>
  </div>
</section>



<!-- 🎞️ Featured Schemes Carousel -->
<section class="p-6 bg-white shadow-md mt-10 rounded-md max-w-6xl mx-auto">
  <h2 class="text-2xl font-semibold mb-4">Featured Schemes</h2>
  <div class="swiper mySwiper rounded overflow-hidden">
    <div class="swiper-wrapper">
      <?php
      $schemes = [
        "PM Scholarship Scheme",
        "Ayushman Bharat Yojana",
        "PM Awas Yojana",
        "National Means-Cum-Merit",
        "Kisan Samman Nidhi"
      ];
      foreach ($schemes as $title) {
        echo '<div class="swiper-slide bg-blue-100 p-6 rounded text-center text-lg cursor-pointer transition hover:scale-105"
              onclick="location.href=\'' . ($isLoggedIn ? 'schemes.php' : 'login.php') . '\'">' . $title . '</div>';
      }
      ?>
    </div>
    <!-- Pagination & Navigation -->
    <div class="swiper-pagination mt-4"></div>
  </div>
</section>

<!-- 📄 More Schemes -->
<section class="max-w-6xl mx-auto p-6 mt-10">
  <h2 class="text-2xl font-semibold mb-4">Explore More Schemes</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <?php
    $otherSchemes = [
      "Digital India Internship",
      "Mid-Day Meal Scheme",
      "Beti Bachao Beti Padhao",
      "Ujjwala Yojana",
      "Startup India"
    ];
    foreach ($otherSchemes as $title) {
      echo '<div onclick="location.href=\'' . ($isLoggedIn ? 'schemes.php' : 'login.php') . '\'"
              class="bg-white shadow p-6 rounded-lg cursor-pointer hover:bg-blue-50 transition">
              <h3 class="text-lg font-medium mb-2">' . $title . '</h3>
              <p class="text-sm text-gray-600">Click to know more</p>
            </div>';
    }
    ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<!-- Swiper Init Scripts -->
<script>
  // Banner Swiper
  const bannerSwiper = new Swiper(".bannerSwiper", {
    loop: true,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev"
    },
  });

  // Schemes Swiper
  const swiper = new Swiper(".mySwiper", {
    slidesPerView: 1.2,
    spaceBetween: 20,
    breakpoints: {
      640: { slidesPerView: 2 },
      1024: { slidesPerView: 3 },
    },
    loop: true,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true
    }
  });
</script>

</body>
