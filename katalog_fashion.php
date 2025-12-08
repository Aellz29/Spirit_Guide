<?php
session_start();
require "config/db.php";

// Ambil data hanya kategori Fashion
$query = $conn->query("SELECT * FROM products WHERE category = 'Fashion' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Fashion | Spirit Guide</title>

    <link rel="stylesheet" href="src/css/style.css">
</head>

<body class="bg-gray-50 text-gray-800">

<!-- NAVBAR (sama seperti index.php) -->
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
      <?php if (!empty($_SESSION['user'])): ?>
        <span class="mr-3 font-medium text-gray-700">
          Halo, <?= htmlspecialchars($_SESSION['user']['username']); ?>
        </span>
        <a href="logout.php"
           class="px-4 py-2 rounded-full text-white transition"
           style="background-color:#dc2626 !important; display:inline-block;"
           onmouseover="this.style.backgroundColor='#b91c1c'"
           onmouseout="this.style.backgroundColor='#dc2626'">
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

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden flex-col bg-white shadow-md md:hidden text-center space-y-4 py-4">
    <a href="index.php" class="hover:text-yellow-600 transition">Home</a>
    <a href="index.php#catalog" class="hover:text-yellow-600 transition">Catalog</a>
    <a href="index.php#about" class="hover:text-yellow-600 transition">About</a>

    <?php if (!empty($_SESSION['user'])): ?>
      <p class="font-medium text-gray-700">Halo, <?= htmlspecialchars($_SESSION['user']['username']); ?></p>

      <a href="logout.php"
           class="px-4 py-2 rounded-full text-white transition"
           style="background-color:#dc2626 !important; display:inline-block;"
           onmouseover="this.style.backgroundColor='#b91c1c'"
           onmouseout="this.style.backgroundColor='#dc2626'">
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



<!-- CONTENT -->
<section class="max-w-7xl mx-auto px-6 pt-32 pb-16">

  <h2 class="text-3xl font-bold text-center text-gray-800 mb-10">
    Katalog Fashion
  </h2>

  <div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8">

    <?php if ($query->num_rows == 0): ?>

      <p class="text-center text-gray-600 col-span-3">Belum ada produk fashion.</p>

    <?php else: ?>
      <?php while($row = $query->fetch_assoc()): ?>

      <!-- CARD PRODUK + POPUP TRIGGER -->
      <div onclick="openPopup('<?= htmlspecialchars($row['title']); ?>')"
           class="shadow-lg bg-white rounded-xl overflow-hidden hover:shadow-2xl transition cursor-pointer">

        <img src="<?= htmlspecialchars($row['image']); ?>" 
             class="w-full h-64 object-cover">

        <div class="p-5">
          <h3 class="text-xl font-bold text-yellow-600"><?= htmlspecialchars($row['title']); ?></h3>
          <p class="text-gray-700 text-sm mt-2"><?= htmlspecialchars($row['description']); ?></p>

          <p class="mt-3 text-lg font-bold text-gray-900">
            Rp <?= number_format($row['price'], 0, ',', '.'); ?>
          </p>
        </div>

      </div>

      <?php endwhile; ?>
    <?php endif; ?>

  </div>

</section>


<!-- POPUP PEMESANAN -->
<div id="orderPopup" 
     class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">

    <div class="bg-white w-80 p-6 rounded-xl shadow-xl text-center relative">

        <!-- Tombol Close -->
        <button onclick="closePopup()" 
                class="absolute top-3 right-3 text-gray-600 text-xl font-bold">
            ×
        </button>

        <h2 class="text-xl font-bold text-yellow-600 mb-3">Pesan Produk</h2>

        <p class="text-gray-700 mb-4">
            Anda akan memesan:<br>
            <span id="popupProductName" class="font-semibold text-yellow-600"></span>
        </p>

        <a id="popupOrderBtn" target="_blank"
           class="bg-green-600 text-white w-full block py-2 rounded-lg hover:bg-green-700 transition">
           Pesan via WhatsApp
        </a>
    </div>
</div>



<footer class="bg-gray-900 text-white text-center py-6 mt-10">
  <p>© 2025 Spirit Guide. All Rights Reserved.</p>
</footer>

<script src="src/js/navbar.js"></script>


<!-- SCRIPT POPUP -->
<script>
function openPopup(productName) {
    document.getElementById("orderPopup").classList.remove("hidden");
    document.getElementById("popupProductName").innerText = productName;

    let nomorWA = "628971566371"; // Ganti dengan nomor asli
    let pesan = encodeURIComponent("Halo, saya ingin memesan produk: " + productName);
    document.getElementById("popupOrderBtn").href = "https://wa.me/628971566371" + nomorWA + "?text=" + pesan;
}

function closePopup() {
    document.getElementById("orderPopup").classList.add("hidden");
}
</script>

</body>
</html>
