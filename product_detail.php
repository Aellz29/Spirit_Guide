<?php
session_start();
include 'partials/navbar.php';

$products = [
  1 => [
    'name' => 'Spirit Hoodie',
    'price' => 299000,
    'image' => 'assets/products/hoodie.jpg',
    'desc' => 'Hoodie premium dengan bahan lembut dan nyaman.'
  ],
  2 => [
    'name' => 'Spirit T-Shirt',
    'price' => 149000,
    'image' => 'assets/products/tshirt.jpg',
    'desc' => 'Kaos casual cocok untuk daily outfit.'
  ],
  3 => [
    'name' => 'Spirit Cap',
    'price' => 99000,
    'image' => 'assets/products/cap.jpg',
    'desc' => 'Topi stylish untuk gaya santai.'
  ],
];

$id = $_GET['id'] ?? null;

if (!isset($products[$id])) {
  echo "<p class='text-center pt-40'>Produk tidak ditemukan</p>";
  exit;
}

$product = $products[$id];
?>

<div class="max-w-6xl mx-auto px-4 pt-32 grid md:grid-cols-2 gap-10">

  <img src="<?= $product['image']; ?>"
       class="w-full rounded-xl shadow">

  <div>
    <h1 class="text-3xl font-bold text-gray-800 mb-4">
      <?= $product['name']; ?>
    </h1>

    <p class="text-yellow-600 text-2xl font-bold mb-4">
      Rp <?= number_format($product['price'],0,',','.'); ?>
    </p>

    <p class="text-gray-600 mb-6 leading-relaxed">
      <?= $product['desc']; ?>
    </p>

    <form action="add_to_cart.php" method="POST">
      <input type="hidden" name="id" value="<?= $id; ?>">
      <input type="hidden" name="name" value="<?= $product['name']; ?>">
      <input type="hidden" name="price" value="<?= $product['price']; ?>">
      <input type="hidden" name="image" value="<?= $product['image']; ?>">

      <button
        class="bg-yellow-600 hover:bg-yellow-700 text-white
               px-6 py-3 rounded-full font-semibold transition">
        Tambah ke Keranjang
      </button>
    </form>
  </div>

</div>

<?php include 'partials/footer.php'; ?>
