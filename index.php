<?php
session_start();
require_once 'lang.php'; // Include the language array

// Language switch handling
if (isset($_POST['lang_switch'])) {
    $_SESSION['lang'] = ($_SESSION['lang'] ?? 'en') === 'en' ? 'hi' : 'en'; // Toggle between 'en' and 'hi'
}

$lang = $_SESSION['lang'] ?? 'en'; // Default to English if no language is set
$isLoggedIn = isset($_SESSION['user_id']);
$redirectLink = $isLoggedIn ? 'index.php' : 'login.php';
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
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
        <img src="assets/images/sliders/slider1.jpeg" class="w-full h-full object-cover" alt="Banner 1">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center text-white text-center">
          <!-- <h2 class="text-3xl md:text-5xl font-bold mb-4">Empowering Citizens</h2>
          <p class="text-lg md:text-xl mb-6">Explore benefits made for you.</p> -->
          <!-- <a href="<?= $redirectLink ?>" onclick="event.stopPropagation()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition">Get Started</a> -->
        </div>
      </div>
      <div class="swiper-slide relative">
        <img src="assets/images/sliders/slider2.jpeg" class="w-full h-full object-cover" alt="Banner 2">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center text-white text-center">
          <!-- <h2 class="text-3xl md:text-5xl font-bold mb-4">Know Your Rights</h2>
          <p class="text-lg md:text-xl mb-6">Find and apply for government schemes easily.</p> -->
          <!-- <a href="<?= $redirectLink ?>" onclick="event.stopPropagation()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition">Get Started</a> -->
        </div>
      </div>
      <div class="swiper-slide relative">
        <img src="assets/images/sliders/slider3.jpeg" class="w-full h-full object-cover" alt="Banner 3">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center text-white text-center">
          <!-- <h2 class="text-3xl md:text-5xl font-bold mb-4">Know Your Rights</h2>
          <p class="text-lg md:text-xl mb-6">Find and apply for government schemes easily.</p> -->
          <!-- <a href="<?= $redirectLink ?>" onclick="event.stopPropagation()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition">Get Started</a> -->
        </div>
      </div>
      <div class="swiper-slide relative">
        <img src="assets/images/sliders/slider4.jpeg" class="w-full h-full object-cover" alt="Banner 4">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center text-white text-center">
          <!-- <h2 class="text-3xl md:text-5xl font-bold mb-4">Know Your Rights</h2>
          <p class="text-lg md:text-xl mb-6">Find and apply for government schemes easily.</p> -->
          <!-- <a href="<?= $redirectLink ?>" onclick="event.stopPropagation()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition">Get Started</a> -->
        </div>
      </div>
    </div>

    <!-- Navigation buttons (optional, you can keep these) -->
    <div class="swiper-button-next text-white"></div>
    <div class="swiper-button-prev text-white"></div>
    
    <!-- Removed: <div class="swiper-pagination"></div> -->
  </div>
</section>


<!-- 🎞️ Featured Schemes Carousel -->
<section class="w-full px-4 md:px-8 py-10 bg-white mt-10">
  <div class="w-full rounded-md shadow-md p-6 bg-white">
  <h2 class="text-2xl font-semibold mb-4"><?= $langData['featured_schemes'] ?></h2>
    
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
  </div>
</section>


<!-- 📄 More Schemes -->
<section class="w-full p-6 mt-10">
<h2 class="text-2xl font-semibold mb-4"><?= $langData['explore_more_schemes'] ?></h2>
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
<!-- 👥 Our Team Section -->
<section class="w-full bg-white py-12 mt-10 px-4 md:px-8">
  <div class="flex flex-col md:flex-row items-center justify-center w-full gap-8">
    
    <!-- Left: Image -->
    <div class="w-full md:w-1/2 h-64 md:h-96 relative overflow-hidden rounded-lg shadow-lg">
      <img src="assets/images/team.jpg" alt="Our Team" class="absolute inset-0 w-full h-full object-cover" />
    </div>

    <!-- Right: Text -->
    <div class="w-full md:w-1/2">
    <h3 class="text-3xl font-bold text-blue-700 mb-4"><?= $langData['our_team'] ?></h3>
      <p class="text-gray-700 leading-relaxed mb-4">
        <span class="ml-4">Our "one team" attitude breaks down silos and helps us engage equally effectively from the C-suite to the front line...</span>
      </p>
      <p class="text-gray-700 leading-relaxed mb-4">
        <span class="ml-4">We have a passion for our clients' true results and a pragmatic drive for action...</span>
      </p>
      <p class="text-gray-700 leading-relaxed">
        <span class="ml-4">And we never go it alone. We support and are supported to develop our own personal results stories...</span>
      </p>
    </div>
  </div>
</section>

<!-- 📚 Programs, News, Testimonials -->
<section class="w-full px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-10 bg-white mt-10 rounded-lg shadow">
  
  <!-- Programmes Column -->
  <div>
  <h3 class="text-2xl font-semibold text-blue-700 mb-4"><?= $langData['programmes'] ?></h3>
    <p class="text-gray-700 text-sm leading-relaxed">
      Sed ut perspiciaatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur.
      <br><br>
      Sed ut perspiciaatis iste natus error sit voluptatem probably haven't heard of them accusamus.
    </p>
  </div>

  <!-- Latest News (Accordion) Column -->
  <div>
  <h3 class="text-2xl font-semibold text-blue-700 mb-4"><?= $langData['testimonials'] ?></h3>
    <div class="space-y-4">
      <details class="group border border-gray-200 rounded-lg p-4">
        <summary class="font-medium cursor-pointer text-blue-600 group-open:text-blue-800">Accordion Heading Text Item #1</summary>
        <p class="mt-2 text-sm text-gray-600">
          Sed ut perspiciaatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium...
        </p>
      </details>
      <details class="group border border-gray-200 rounded-lg p-4">
        <summary class="font-medium cursor-pointer text-blue-600 group-open:text-blue-800">Accordion Heading Text Item #2</summary>
        <p class="mt-2 text-sm text-gray-600">
          Sed ut perspiciaatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium...
        </p>
      </details>
      <details class="group border border-gray-200 rounded-lg p-4">
        <summary class="font-medium cursor-pointer text-blue-600 group-open:text-blue-800">Accordion Heading Text Item #3</summary>
        <p class="mt-2 text-sm text-gray-600">
          Sed ut perspiciaatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium...
        </p>
      </details>
      <details class="group border border-gray-200 rounded-lg p-4">
        <summary class="font-medium cursor-pointer text-blue-600 group-open:text-blue-800">Accordion Heading Text Item #4</summary>
        <p class="mt-2 text-sm text-gray-600">
          Sed ut perspiciaatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium...
        </p>
      </details>
    </div>
  </div>

  <!-- Testimonials Column -->
  <div>
  <h3 class="text-2xl font-semibold text-blue-700 mb-4"><?= $langData['testimonials'] ?></h3>
    <div class="bg-gray-100 p-6 rounded-lg shadow text-sm">
    <p class="italic text-gray-700"><?= $langData['testimonial_quote'] ?></p>
      <div class="flex items-center mt-4">
        <img src="plugins/home-plugins/img/team4.jpg" alt="Dhaneshwar Mahato" class="w-12 h-12 rounded-full border-2 border-blue-500">
        <div class="ml-4">
          <p class="font-semibold text-blue-700">Dhaneshwar Mahato</p>
          <p class="text-gray-500 text-xs">Technical Director</p>
        </div>
      </div>
    </div>
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
