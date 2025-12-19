<?php
session_start();
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
    <?php include 'partials/navbar.php'; ?>

    <!-- 🔹 HERO SECTION -->
    <section id="home" class="relative flex flex-col items-center justify-center text-center pt-32 pb-20 bg-gray-50 reveal min-h-screen">
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
<section id="catalog" class="max-w-6xl mx-auto px-4 py-16 reveal pt-24 min-h-screen">
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
      
          <!-- OTHER -->
    <div class="relative shadow-md rounded-xl overflow-hidden hover:shadow-xl transition group">
      <a href="katalog.php?category=Other" class="block">
        <img src="./src/img/other.jpg" alt="Other" class="w-full h-64 object-cover transition duration-500 group-hover:scale-105" />
      </a>

      <!-- Overlay -->
      <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-100 transition duration-300">
        <a href="katalog.php?category=Other"
           class="bg-yellow-600 text-white px-4 py-2 rounded-full font-semibold shadow-md hover:bg-yellow-700 transition">
          Lihat Produk Lainnya
        </a>
      </div>

      <div class="p-4 text-center">
        <h4 class="text-xl font-semibold text-yellow-600">Other</h4>
        <p class="text-gray-600 text-sm mt-2">Produk kategori lain yang tersedia di Spirit Guide.</p>
      </div>
    </div>

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
<section id="about" class="bg-white py-16 pt-24">
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

    <!-- 🔹 FOOTER -->
    <?php include 'partials/footer.php'; ?>

</body>