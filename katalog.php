<?php
session_start();
require "config/db.php";

// validasi kategori
$category = $_GET['category'] ?? null;
$allowed  = ["Fashion", "Food", "Aksesoris", "Other"];

if (!in_array($category, $allowed)) {
  die("<h2 class='text-center mt-32 text-xl'>Kategori tidak ditemukan</h2>");
}

// ambil data produk
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
</head>

<body class="bg-gray-50 text-gray-800">

<!-- NAVBAR GLOBAL -->
<?php include 'partials/navbar.php'; ?>

<!-- MAIN CONTENT -->
<main class="pt-32">

<section class="max-w-7xl mx-auto px-4 pb-20">

  <!-- JUDUL -->
  <div class="text-center mb-12">
    <h1 class="text-3xl font-bold text-gray-800">
      Katalog <?= htmlspecialchars($category) ?>
    </h1>
    <p class="text-gray-600 mt-2">
      Temukan produk terbaik pilihan Spirit Guide
    </p>
  </div>

  <!-- GRID PRODUK -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

    <?php while($p = $result->fetch_assoc()): ?>
    <div
      class="bg-white rounded-xl shadow hover:shadow-xl
             hover:-translate-y-1 transition cursor-pointer
             product-card"
      data-title="<?= htmlspecialchars($p['title']) ?>"
      data-price="<?= number_format($p['price'], 0, ',', '.') ?>"
      data-img="<?= htmlspecialchars($p['image']) ?>"
    >

      <img src="<?= htmlspecialchars($p['image']) ?>"
           class="w-full h-56 object-cover rounded-t-xl">

      <div class="p-5">
        <h3 class="text-lg font-bold text-yellow-600">
          <?= htmlspecialchars($p['title']) ?>
        </h3>

        <p class="text-gray-600 text-sm mt-2 line-clamp-2">
          <?= htmlspecialchars($p['description']) ?>
        </p>

        <p class="mt-4 text-lg font-bold text-gray-900">
          Rp <?= number_format($p['price'], 0, ',', '.') ?>
        </p>

        <button
          class="mt-4 w-full bg-yellow-600 text-white py-2 rounded-full
                 hover:bg-yellow-700 transition">
          Pesan Sekarang
        </button>
      </div>
    </div>
    <?php endwhile; ?>

  </div>

</section>
</main>

<!-- MODAL ORDER -->
<div id="orderModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-[9999]">

  <div class="bg-white rounded-xl p-6 w-[90%] max-w-sm text-center animate-scale">

    <img id="modalImg"
         class="w-36 h-36 mx-auto object-cover rounded-lg mb-4">

    <h2 id="modalTitle"
        class="text-xl font-bold mb-2"></h2>

    <p class="text-lg mb-4">
      Harga: <b id="modalPrice"></b>
    </p>

    <a id="waLink"
       target="_blank"
       class="block bg-green-600 text-white py-2 rounded-full
              hover:bg-green-700 transition mb-3">
      Pesan via WhatsApp
    </a>

    <button onclick="closeModal()"
            class="bg-gray-200 px-4 py-2 rounded-full hover:bg-gray-300">
      Tutup
    </button>

  </div>
</div>

<!-- FOOTER GLOBAL -->
<?php include 'partials/footer.php'; ?>

<!-- SCRIPT -->
<script>
document.querySelectorAll('.product-card').forEach(card => {
  card.addEventListener('click', () => {
    document.getElementById('modalImg').src   = card.dataset.img;
    document.getElementById('modalTitle').textContent = card.dataset.title;
    document.getElementById('modalPrice').textContent = 'Rp ' + card.dataset.price;

    const text = encodeURIComponent(
      "Halo, saya ingin memesan:\n\n" +
      "Produk: " + card.dataset.title + "\n" +
      "Harga: Rp " + card.dataset.price
    );

    document.getElementById('waLink').href =
      "https://wa.me/6280000000000?text=" + text;

    document.getElementById('orderModal').classList.remove('hidden');
    document.getElementById('orderModal').classList.add('flex');
  });
});

function closeModal() {
  document.getElementById('orderModal').classList.add('hidden');
  document.getElementById('orderModal').classList.remove('flex');
}
</script>

</body>
</html>
