<?php
session_start();
require "config/db.php";
require 'cart_helper.php';

$cart = getCart();
$cartCount = array_sum(array_column($cart, 'qty'));

// Cek kategori dari URL
$category = isset($_GET['category']) ? $_GET['category'] : null;

$allowed = ["Fashion", "Food", "Aksesoris"];
if (!in_array($category, $allowed)) {
    die("<h2 style='text-align:center; margin-top:50px;'>Kategori tidak ditemukan.</h2>");
}

// Ambil produk sesuai kategori
$stmt = $conn->prepare("SELECT * FROM products WHERE category = ? ORDER BY id DESC");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog <?= htmlspecialchars($category) ?> | Spirit Guide</title>

    <link rel="stylesheet" href="src/css/style.css">
    <!-- Modal CSS -->
    <style>
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .modal-box {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            animation: pop 0.25s ease-out;
        }
        @keyframes pop {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
    <style>
  /* FIX FINAL — override semua CSS lain */
  .product-card {
    height: 320px;
  }

  .product-card img {
    width: 100% !important;
    height: 220px !important;
    object-fit: cover !important;
  }
</style>

</head>

<body class="bg-gray-50 text-gray-800">

<!-- NAVBAR SAMA PERSIS DARI INDEX -->
<header class="fixed top-0 left-0 w-full bg-white/90 backdrop-blur-lg shadow-md z-50">
    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center py-3">

        <div class="flex items-center space-x-2">
            <img src="src/img/SpiritGuide.jpg" class="w-10 h-10 rounded-full">
            <h1 class="text-xl font-bold text-yellow-600"><a href="index.php">Spirit Guide</a></h1>
        </div>

        <nav class="hidden md:flex space-x-10 font-medium text-gray-700">
            <a href="index.php" class="hover:text-yellow-600 transition">Home</a>
            <a href="index.php#catalog" class="hover:text-yellow-600 transition">Catalog</a>
            <a href="index.php#about" class="hover:text-yellow-600 transition">About</a>
        </nav>

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
                <span class="mr-3">Halo, <?= htmlspecialchars($_SESSION['user']['username']); ?></span>
                <a href="logout.php" class="px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-700">Logout</a>
            <?php else: ?>
                <a href="login.php" class="px-4 py-2 bg-yellow-600 text-white rounded-full hover:bg-yellow-700">Login</a>
            <?php endif; ?>
        </div>
        
        <button id="menu-btn" class="md:hidden flex flex-col justify-between w-6 h-5">
            <span class="block w-full h-0.5 bg-gray-700"></span>
            <span class="block w-full h-0.5 bg-gray-700"></span>
            <span class="block w-full h-0.5 bg-gray-700"></span>
        </button>

    </div>
</header>

<!-- CONTENT -->
<section class="max-w-7xl mx-auto px-6 pt-32 pb-16">

  <h2 class="text-3xl font-bold text-center mb-10">
    Katalog <?= htmlspecialchars($category) ?>
  </h2>

  <!-- GRID WAJIB -->
  <div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8">

    <?php while ($p = $result->fetch_assoc()): ?>

      <!-- CARD -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition
                  product-card cursor-pointer"
           data-id="<?= $p['id'] ?>"
           data-title="<?= htmlspecialchars($p['title']) ?>"
           data-price="<?= $p['price'] ?>"
           data-image="<?= $p['image'] ?>">


        <img src="<?= htmlspecialchars($p['image']) ?>"
     class="w-full object-cover">


        <div class="p-4">
          <h3 class="font-semibold text-lg">
            <?= htmlspecialchars($p['title']) ?>
          </h3>

          <p class="text-yellow-600 font-bold mt-2">
            Rp <?= number_format($p['price'],0,',','.') ?>
          </p>
        </div>
      </div>

    <?php endwhile; ?>

  </div>
</section>


<div id="productModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

  <div class="bg-white rounded-xl max-w-md w-full p-6 relative">

    <button id="closeModal"
      class="absolute top-3 right-4 text-2xl text-gray-500">&times;</button>

    <img id="modalImage"
         class="w-full h-60 object-cover rounded-lg mb-4">

    <h2 id="modalTitle"
        class="text-xl font-bold mb-2"></h2>

    <p id="modalPrice"
       class="text-yellow-600 text-lg font-semibold mb-4"></p>

    <form action="cart_add.php" method="POST">
      <input type="hidden" name="product_id" id="modalProductId">

      <button type="submit"
        class="w-full bg-yellow-600 text-white py-3 rounded-full hover:bg-yellow-700 transition">
        + Tambah ke Keranjang
      </button>
    </form>

  </div>
</div>


<footer class="bg-gray-900 text-white text-center py-6 mt-10">
    <p>© 2025 Spirit Guide. All Rights Reserved.</p>
</footer>

<script>
  const cards = document.querySelectorAll('.product-card');
  const modal = document.getElementById('productModal');

  const modalImage = document.getElementById('modalImage');
  const modalTitle = document.getElementById('modalTitle');
  const modalPrice = document.getElementById('modalPrice');
  const modalProductId = document.getElementById('modalProductId');

  const closeModal = document.getElementById('closeModal');

  cards.forEach(card => {
    card.addEventListener('click', () => {
      modalImage.src = card.dataset.image;
      modalTitle.textContent = card.dataset.title;
      modalPrice.textContent =
        'Rp ' + Number(card.dataset.price).toLocaleString('id-ID');

      modalProductId.value = card.dataset.id;

      modal.classList.remove('hidden');
      modal.classList.add('flex');
    });
  });

  closeModal.addEventListener('click', () => {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  });

  modal.addEventListener('click', e => {
    if (e.target === modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  });
</script>
</div>
</body>
</html>
