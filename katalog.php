<?php
session_start();
// UPDATE: Mengarah ke folder config
require "config/db.php";

// 1. Validasi kategori dari URL
$category = $_GET['category'] ?? null;
$allowed  = ["Fashion", "Food", "Aksesoris", "Other"];

if (!in_array($category, $allowed)) {
  die("<h2 class='text-center mt-32 text-xl font-sans'>Kategori tidak ditemukan</h2>");
}

// 2. Ambil data produk berdasarkan kategori
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
    <script src="src/js/cart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .product-card:hover img { transform: scale(1.05); }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    </style>
</head>
<body class="bg-white text-gray-900 font-sans">

<?php include 'partials/navbar.php'; ?>

<main class="pt-28 lg:pt-36">
    <section class="max-w-[1400px] mx-auto px-4 pb-20">
        <div class="mb-10 border-b border-gray-100 pb-6">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 uppercase"><?= htmlspecialchars($category) ?></h1>
            <p class="text-sm text-gray-500 mt-1 border-b pb-4">Menampilkan koleksi terbaik untuk gaya hidup Anda.</p>

            <div class="mt-6 max-w-md relative">
                <input type="text" id="productSearch" placeholder="CARI PRODUK..." 
                    class="w-full border-b border-gray-300 py-2 text-[10px] font-bold tracking-widest uppercase focus:border-black outline-none transition-all bg-transparent">
                <div class="absolute right-0 top-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div id="productGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-x-4 gap-y-8">
            <?php while($p = $result->fetch_assoc()): ?>
            <div class="group product-card transition-all flex flex-col h-full bg-white border border-gray-100 p-2">
                <div class="cursor-pointer flex-1" onclick="openModal('<?= htmlspecialchars($p['image']) ?>', '<?= htmlspecialchars(addslashes($p['title'])) ?>', '<?= number_format($p['price'], 0, ',', '.') ?>', '<?= htmlspecialchars(addslashes($p['description'])) ?>', <?= (int)$p['stock'] ?>, <?= $p['id'] ?>, <?= $p['price'] ?>)">
                    <div class="aspect-square w-full overflow-hidden bg-gray-100 mb-3">
                        <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-contain transition-transform duration-500">
                    </div>
                    <div class="space-y-1">
                        <h3 class="product-title text-[12px] font-bold uppercase tracking-tight line-clamp-2"><?= htmlspecialchars($p['title']) ?></h3>
                        <p class="text-sm font-medium text-gray-600">Rp<?= number_format($p['price'], 0, ',', '.') ?></p>
                    </div>
                </div>
                <button type="button" onclick="event.stopPropagation(); window.addToCart({id: '<?= $p['id'] ?>', title: '<?= addslashes($p['title']) ?>', price: '<?= $p['price'] ?>', img: '<?= $p['image'] ?>'})" class="mt-3 w-full border border-gray-900 py-2 text-[10px] font-bold tracking-widest uppercase hover:bg-black hover:text-white transition-all">
                    ADD TO CART
                </button>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>

<div id="productModal" class="fixed inset-0 bg-black/80 hidden flex items-center justify-center z-[999] p-4 backdrop-blur-md">
    <div class="bg-white w-full max-w-4xl overflow-hidden relative shadow-2xl rounded-sm flex flex-col md:flex-row max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-900 z-10 bg-white/50 rounded-full p-1 hover:rotate-90 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="w-full md:w-1/2 bg-gray-50 flex items-center justify-center p-8 border-r border-gray-100">
            <img id="modalImg" class="max-w-full max-h-[400px] object-contain drop-shadow-xl">
        </div>
        <div class="w-full md:w-1/2 p-8 md:p-12">
            <nav class="text-[10px] text-gray-400 uppercase tracking-widest mb-2">Spirit Guide</nav>
            <h3 id="modalTitle" class="text-2xl font-bold uppercase tracking-tighter text-gray-900"></h3>
            <p id="modalPrice" class="mt-2 text-xl font-medium text-amber-800 border-b pb-4"></p>
            <div class="mt-4 text-[11px] text-gray-600">
                <p id="modalDesc"></p>
                <p class="mt-2 font-bold uppercase">Status: <span id="statusStock"></span></p>
            </div>
            <div class="mt-8 border-t pt-6">
                <h3 class="text-[11px] font-bold uppercase mb-4 tracking-widest">Ulasan Pelanggan</h3>
                <div id="review-list" class="space-y-4 max-h-40 overflow-y-auto mb-4 pr-2 custom-scrollbar"></div>
                <form id="reviewForm" class="bg-gray-50 p-4 rounded-sm">
                    <input type="hidden" name="product_id" id="review_product_id">
                    <select name="rating" class="text-[10px] border-b w-full bg-transparent mb-2 outline-none py-1">
                        <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                        <option value="4">⭐⭐⭐⭐ (4)</option>
                        <option value="3">⭐⭐⭐ (3)</option>
                        <option value="2">⭐⭐ (2)</option>
                        <option value="1">⭐ (1)</option>
                    </select>
                    <textarea name="comment" required placeholder="Tulis ulasan Anda..." class="w-full text-[11px] border-b bg-transparent outline-none h-12 resize-none focus:border-black transition-all"></textarea>
                    <button type="submit" class="w-full bg-black text-white py-2 text-[9px] font-bold uppercase mt-2 hover:bg-gray-800 transition">Kirim Review</button>
                </form>
            </div>
            <div class="mt-8 flex gap-2">
                <button id="modalAddToCartBtn" class="flex-1 border border-gray-900 py-3 text-[10px] font-bold uppercase hover:bg-black hover:text-white transition">ADD TO CART</button>
                <button onclick="window.location.href='checkout.php'" class="flex-1 bg-black text-white py-3 text-[10px] font-bold uppercase hover:bg-gray-800 transition">PESAN SEKARANG</button>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>

<script src="src/js/katalog.js"></script>
</body>
</html>