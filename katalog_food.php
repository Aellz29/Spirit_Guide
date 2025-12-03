<?php
session_start();
include './config/db.php';

// Ambil data katalog kategori food
$query = "SELECT * FROM katalog WHERE kategori = 'food' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Food | Spirit Guide</title>
    <link rel="stylesheet" href="./src/css/style.css">
</head>
<!-- Tailwind CSS -->
    <link rel="stylesheet" href="src/css/style.css">
</head>

<body class="bg-gray-50 text-gray-800">

<!-- 🔹 NAVBAR (SAMA DENGAN INDEX) -->
<header class="fixed top-0 left-0 w-full bg-white/90 backdrop-blur-lg shadow-md z-50">
  <div class="max-w-7xl mx-auto px-6 flex justify-between items-center py-3">

    <!-- Logo -->
    <div class="flex items-center space-x-2">
      <img src="src/img/SpiritGuide.jpg" class="w-10 h-10 rounded-full">
      <h1 class="text-xl font-bold text-yellow-600 tracking-wide">
        <a href="index.php">Spirit Guide</a>
      </h1>
    </div>

    <!-- Menu -->
    <nav class="hidden md:flex space-x-10 font-medium text-gray-700">
      <a href="index.php" class="hover:text-yellow-600 transition">Home</a>
      <a href="index.php#catalog" class="hover:text-yellow-600 transition">Catalog</a>
      <a href="index.php#about" class="hover:text-yellow-600 transition">About</a>
    </nav>

    <!-- Login / Logout -->
    <div class="hidden md:block">
      <?php if(isset($_SESSION["username"])): ?>
        <a href="logout.php"
          class="px-4 py-2 rounded-full text-white transition bg-red-600 hover:bg-red-700"
          style="background-color:#dc2626 !important;">
          Logout
        </a>
      <?php else: ?>
        <a href="login.php"
          class="px-4 py-2 rounded-full text-white bg-yellow-600 hover:bg-yellow-700 transition">
          Login
        </a>
      <?php endif; ?>
    </div>

    <!-- Hamburger -->
    <button id="menu-btn" class="md:hidden flex flex-col justify-between w-6 h-5">
      <span class="block w-full h-0.5 bg-gray-700"></span>
      <span class="block w-full h-0.5 bg-gray-700"></span>
      <span class="block w-full h-0.5 bg-gray-700"></span>
    </button>

  </div>

  <!-- Menu Mobile -->
  <div id="mobile-menu" class="hidden flex-col bg-white shadow-md md:hidden text-center space-y-4 py-4">
    <a href="index.php" class="hover:text-yellow-600 transition">Home</a>
    <a href="index.php#catalog" class="hover:text-yellow-600 transition">Catalog</a>
    <a href="index.php#about" class="hover:text-yellow-600 transition">About</a>

    <?php if(isset($_SESSION["username"])): ?>
      <a href="logout.php"
        class="bg-red-600 text-white w-1/2 mx-auto px-4 py-2 rounded-full hover:bg-red-700 transition">
        Logout
      </a>
    <?php else: ?>
      <a href="login.php"
        class="bg-yellow-600 text-white w-1/2 mx-auto px-4 py-2 rounded-full hover:bg-yellow-700 transition">
        Login
      </a>
    <?php endif; ?>
  </div>
</header>


<!-- 🔹 KONTEN -->
<section class="pt-32 pb-16 max-w-6xl mx-auto px-4">
    <h2 class="text-3xl font-bold text-yellow-600 text-center mb-10">Katalog Food</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">

        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-2xl transition">
            <img src="./uploads/<?php echo $row['gambar']; ?>" 
                 class="w-full h-56 object-cover">

            <div class="p-4">
                <h3 class="text-xl font-semibold text-yellow-600">
                    <?php echo $row['nama_katalog']; ?>
                </h3>
                <p class="text-gray-600 mt-2 text-sm">
                    <?php echo $row['deskripsi']; ?>
                </p>
            </div>
        </div>
        <?php endwhile; ?>

    </div>
</section>

<!-- 🔹 FOOTER (SAMA SEPERTI INDEX) -->
<footer class="bg-gray-900 text-white text-center py-6">
  <p>© 2025 Spirit Guide. All Rights Reserved.</p>
</footer>

<script src="./src/js/navbar.js"></script>
</body>
</html>
