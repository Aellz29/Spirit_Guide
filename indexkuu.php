<?php
session_start();
require 'cart_helper.php';

$cart = getCart();
$cartCount = array_sum(array_column($cart, 'qty'));

?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Spirit Guide | Fashion • Food • Aksesoris</title>
    <link href="./src/css/style.css" rel="stylesheet" />
  </head>
  <body class="bg-white text-gray-800">
    <!-- 🔹 NAVBAR -->
    <header class="fixed top-0 left-0 w-full bg-white/90 backdrop-blur-lg shadow-md z-50">
      <div class="max-w-7xl mx-auto px-4 flex justify-between items-center py-3">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
          <img src="./src/img/SpiritGuide.jpg" alt="Spirit Guide" class="w-10 h-10 rounded-full" />
          <h1 class="text-xl font-bold text-yellow-600 tracking-wide"><a href="index.php">Spirit Guide</a></h1>
        </div>

        <!-- Menu Tengah -->
        <nav class="desktop-menu space-x-10 font-medium text-gray-700">
          <a href="index.php#home" class="hover:text-yellow-600 transition">Home</a>
<a href="index.php#catalog" class="hover:text-yellow-600 transition">Catalog</a>
<a href="index.php#about" class="hover:text-yellow-600 transition">About</a>
        </nav>

        <!-- Login Button (Desktop) -->
         <div class="hidden md:flex items-center space-x-4">

  <!-- CART -->
  <a href="cart.php" class="relative text-xl">
    🛒
    <?php if ($cartCount > 0): ?>
      <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs px-2 rounded-full">
        <?= $cartCount ?>
      </span>
    <?php endif; ?>
  </a>

        <div class="hidden md:block">
          <?php if (!empty($_SESSION['user'])): ?>
            <!-- tampilkan nama + logout -->
            <span class="mr-3 font-medium text-gray-700">Halo, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
            <a href="logout.php"
   class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700 transition"
   style="background-color:#dc2626 !important;">
   Logout
</a>

          <?php else: ?>
            <a href="login.php"
              class="bg-yellow-600 text-white px-4 py-2 rounded-full hover:bg-yellow-700 transition">
              Login
            </a>
          <?php endif; ?>
        </div>

        <!-- Hamburger -->
        <button id="menu-btn" class="hamburger-btn flex flex-col justify-between w-6 h-5">
  <span class="block w-full h-0.5 bg-gray-700 rounded"></span>
  <span class="block w-full h-0.5 bg-gray-700 rounded"></span>
  <span class="block w-full h-0.5 bg-gray-700 rounded"></span>
</button>
      </div>

      <!-- Menu Mobile -->
      <div id="mobile-menu" class="mobile-menu hidden flex-col bg-white shadow-md">
  <a href="index.php" class="hover:text-yellow-600">Home</a>
  <a href="index.php#catalog" class="hover:text-yellow-600">Catalog</a>
  <a href="index.php#about" class="hover:text-yellow-600">About</a>

  <a href="cart.php" class="text-lg">
    🛒 Keranjang (<?= $cartCount ?>)
  </a>

  <?php if (!empty($_SESSION['user'])): ?>
    <p class="font-medium text-gray-700">
      Halo, <?= htmlspecialchars($_SESSION['user']['username']); ?>
    </p>
    <a href="logout.php"
       class="bg-red-600 text-white w-1/2 mx-auto px-4 py-2 rounded-full">
       Logout
    </a>
  <?php else: ?>
    <a href="login.php"
       class="bg-yellow-600 text-white w-1/2 mx-auto px-4 py-2 rounded-full">
       Login
    </a>
  <?php endif; ?>
</div>


    </header>

    <!-- 🔹 HERO SECTION -->
    <section id="home" class="relative flex flex-col items-center justify-center text-center pt-32 pb-20 bg-gray-50 reveal">
      <img src="./src/img/SpiritGuide.jpg" alt="Spirit Guide Hero"
        class="w-64 h-64 object-cover rounded-full shadow-lg mb-6 border-4 border-yellow-500" />
      <h2 class="text-4xl font-bold text-gray-900 mb-3">Welcome to <span class="text-yellow-600">Spirit Guide</span></h2>

      <!-- sapaan jika user login -->
      <?php if (!empty($_SESSION['user'])): ?>
        <p class="text-gray-700 mb-4">Selamat datang, <span class="text-yellow-600 font-semibold"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>!</p>
      <?php endif; ?>

      <p class="text-gray-600 max-w-lg mx-auto">
        Temukan gaya hidupmu lewat <span class="text-yellow-600 font-medium">fashion</span>,
        <span class="text-yellow-600 font-medium">makanan</span>, dan
        <span class="text-yellow-600 font-medium">aksesoris</span> pilihan kami.
      </p>
      <a href="#catalog"
        class="mt-6 bg-yellow-600 text-white px-6 py-3 rounded-full hover:bg-yellow-700 transition shadow-md">
        Jelajahi Sekarang
      </a>
    </section>

<!-- 🔹 CATALOG SECTION -->
<section id="catalog" class="max-w-6xl mx-auto px-4 py-16 reveal">
  <h3 class="text-3xl font-bold text-center text-gray-800 mb-10">Katalog Produk</h3>

  <div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8">

    <!-- FASHION -->
    <div class="relative shadow-md rounded-xl overflow-hidden hover:shadow-xl transition group">
      <a href="katalog.php?category=Fashion" class="block">
        <img src="./src/img/bajuF.jpg" alt="Fashion" class="w-full h-64 object-cover transition duration-500 group-hover:scale-105" />
      </a>
      
      <!-- Overlay dengan tombol selalu terlihat -->
      <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-100 transition duration-300">
        <a href="katalog.php?category=Fashion"
           class="bg-yellow-600 text-white px-4 py-2 rounded-full font-semibold shadow-md hover:bg-yellow-700 transition">
          Lihat Fashion
        </a>
      </div>
      
      <div class="p-4 text-center">
        <h4 class="text-xl font-semibold text-yellow-600">Fashion</h4>
        <p class="text-gray-600 text-sm mt-2">Tren terkini yang memadukan gaya dan kenyamanan.</p>
      </div>
    </div>

    <!-- FOOD -->
    <div class="relative shadow-md rounded-xl overflow-hidden hover:shadow-xl transition group">
      <a href="katalog.php?category=Food" class="block">
        <img src="./src/img/saladbuah.jpg" alt="Food" class="w-full h-64 object-cover transition duration-500 group-hover:scale-105" />
      </a>
      
      <!-- Overlay dengan tombol selalu terlihat -->
      <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-100 transition duration-300">
        <a href="katalog.php?category=Food"
           class="bg-yellow-600 text-white px-4 py-2 rounded-full font-semibold shadow-md hover:bg-yellow-700 transition">
          Lihat Food
        </a>
      </div>

      <div class="p-4 text-center">
        <h4 class="text-xl font-semibold text-yellow-600">Food</h4>
        <p class="text-gray-600 text-sm mt-2">Nikmati cita rasa modern khas Spirit Guide.</p>
      </div>
    </div>

    <!-- AKSESORIS -->
    <div class="relative shadow-md rounded-xl overflow-hidden hover:shadow-xl transition group">
      <a href="katalog.php?category=Aksesoris" class="block">
        <img src="./src/img/accesories.jpg" alt="Aksesoris" class="w-full h-64 object-cover transition duration-500 group-hover:scale-105" />
      </a>
      
      <!-- Overlay dengan tombol selalu terlihat -->
      <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-100 transition duration-300">
        <a href="katalog.php?category=Aksesoris"
           class="bg-yellow-600 text-white px-4 py-2 rounded-full font-semibold shadow-md hover:bg-yellow-700 transition">
          Lihat Aksesoris
        </a>
      </div>

      <div class="p-4 text-center">
        <h4 class="text-xl font-semibold text-yellow-600">Aksesoris</h4>
        <p class="text-gray-600 text-sm mt-2">Detail kecil yang melengkapi tampilanmu.</p>
      </div>
    </div>

  </div>
</section>


    <!-- About Section -->
<section id="about" class="bg-white py-16">
  <div class="container mx-auto px-6 text-center">
    <h2 class="text-3xl font-bold text-yellow-600 mb-6">About Spirit Guide</h2>
    <p class="text-gray-700 max-w-3xl mx-auto mb-10">
      Spirit Guide hadir sebagai destinasi gaya hidup yang menginspirasi, 
      menghadirkan koleksi <span class="font-semibold text-yellow-600">fashion</span>, 
      <span class="font-semibold text-yellow-600">food</span>, dan 
      <span class="font-semibold text-yellow-600">aksesoris</span> 
      dengan sentuhan elegan dan modern.  
      Kami percaya bahwa setiap orang memiliki gaya uniknya masing-masing, 
      dan Spirit Guide siap menjadi panduanmu untuk mengekspresikan diri dengan penuh makna.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
      <div class="p-6 shadow-lg rounded-xl hover:shadow-2xl transition duration-300 bg-yellow-50">
        <img src="./src/img/Fjaket.jpeg" alt="Fashion" class="w-full h-48 object-cover rounded-lg mb-4">
        <h3 class="text-xl font-semibold text-yellow-700 mb-2">Fashion</h3>
        <p class="text-gray-600">Koleksi yang memadukan tren modern dan sentuhan klasik untuk gaya yang tak lekang oleh waktu.</p>
      </div>
      <div class="p-6 shadow-lg rounded-xl hover:shadow-2xl transition duration-300 bg-yellow-50">
        <img src="./src/img/bolu.jpg" alt="Food" class="w-full h-48 object-cover rounded-lg mb-4">
        <h3 class="text-xl font-semibold text-yellow-700 mb-2">Food</h3>
        <p class="text-gray-600">Nikmati pilihan kuliner yang menggugah selera dengan cita rasa dan tampilan istimewa.</p>
      </div>
      <div class="p-6 shadow-lg rounded-xl hover:shadow-2xl transition duration-300 bg-yellow-50">
        <img src="./src/img/topi.jpeg" alt="Accessories" class="w-full h-48 object-cover rounded-lg mb-4">
        <h3 class="text-xl font-semibold text-yellow-700 mb-2">Accessories</h3>
        <p class="text-gray-600">Detail kecil yang memberikan sentuhan besar pada gaya hidupmu setiap hari.</p>
      </div>
    </div>
  </div>
</section>


<footer class="bg-gray-900 text-white pt-12 pb-6 mt-10">

  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-10 text-center md:text-left">

    <!-- BRAND -->
    <div>
      <h2 class="text-2xl font-bold text-yellow-500 mb-3">Spirit Guide</h2>
      <p class="text-gray-400 text-sm">
        Fashion • Food • Accessories  
        Temukan gaya hidup terbaikmu hanya di Spirit Guide.
      </p>
    </div>

    <!-- CONTACT -->
    <div>
      <h3 class="text-xl font-semibold text-yellow-500 mb-3">Temui Kami</h3>

      <p class="text-gray-300 text-sm mb-2">
        📍 Alamat:  Kampus 1, Jl. Cibogo No.Indah 3, Mekarjaya, Kec. Rancasari, Kota Bandung, Jawa Barat 40000
      </p>

      <p class="text-gray-300 text-sm mb-2">
        📞 WhatsApp:
        <a href="https://wa.me/628971566371" target="_blank" class="text-yellow-400 hover:underline">
          +62 897-1566-371
        </a>
      </p>

      <p class="text-gray-300 text-sm">
        📧 Email:
        <a href="mailto:spiritguide@egmail.com" class="text-yellow-400 hover:underline">
          spiritguide@gmail.com
        </a>
      </p>
    </div>

    <!-- SOCIAL IMAGES -->
    <div>
      <h3 class="text-xl font-semibold text-yellow-500 mb-3">Ikuti Kami</h3>

      <div class="flex items-center justify-center md:justify-start space-x-10">

  <!-- Instagram -->
  <a href="https://www.instagram.com/spiritguide17?utm_source=qr&igsh=MzNmY3V3dHFsMG11" target="_blank">
    <img src="https://cdn-icons-png.flaticon.com/512/1384/1384063.png" class="w-6 h-6" />
  </a>

  <!-- WhatsApp -->
  <a href="https://wa.me/628971566371" target="_blank">
    <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" class="w-6 h-6" />
  </a>

  <!-- TikTok -->
  <a href="https://tiktok.com" target="_blank">
    <img src="https://cdn-icons-png.flaticon.com/512/3046/3046125.png" class="w-6 h-6" />
  </a>

</div>

<script src="src/js/navbar.js"></script>
</body>

</footer>
