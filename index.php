<?php
session_start();
include './config/db.php'; // Pastikan koneksi database jalan

// CEK STATUS LOGIN UNTUK HARGA MEMBER
$isLoggedIn = isset($_SESSION['user']);
$username = $_SESSION['user']['username'] ?? 'Guest';

// AMBIL PRODUK DARI DATABASE (Prioritas Flash Sale & Terbaru)
// Kita ambil 4 produk teratas buat dipajang di halaman depan
$query = "SELECT * FROM products ORDER BY is_flash_sale DESC, id DESC LIMIT 4";
$res = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Spirit Guide | Fashion • Food • Aksesoris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="./src/css/style.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        /* Animasi biar menarik */
        .flash-badge { animation: pulseRed 2s infinite; }
        @keyframes pulseRed { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.8; transform: scale(1.05); } 100% { opacity: 1; transform: scale(1); } }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    </style>
  </head>
  <body class="bg-white text-gray-800 font-sans">
    
    <?php include 'partials/navbar.php'; ?>

    <section id="home" class="relative flex flex-col items-center justify-center text-center pt-32 pb-20 reveal min-h-screen overflow-hidden">
      <div class="absolute inset-0 z-0">
        <video autoplay muted loop playsinline class="w-full h-full object-cover">
          <source src="./assets/video/onlineShopping.mp4" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-t from-transparent via-black/10 to-black/30"></div> 
      </div>

      <div class="relative z-10 flex flex-col items-center px-4">
        <img src="./src/img/SpiritGuide.jpg" alt="Spirit Guide Hero"
          class="w-64 h-64 object-cover rounded-full shadow-2xl mb-6 border-4 border-yellow-500" />
        
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-3 drop-shadow-lg">
            Welcome to <span class="text-yellow-500">Spirit Guide</span>
        </h2>

        <?php if ($isLoggedIn): ?>
          <p class="text-gray-100 mb-4 text-lg bg-black/30 px-4 py-1 rounded-full backdrop-blur-sm">
            Halo, <span class="text-yellow-400 font-semibold"><?php echo htmlspecialchars($username); ?></span>! 
            <span class="text-xs text-blue-300 ml-1">(Member Price Active)</span>
          </p>
        <?php endif; ?>

        <p class="text-gray-200 max-w-lg mx-auto text-lg leading-relaxed drop-shadow-md">
          Temukan gaya hidupmu lewat <span class="text-yellow-400 font-medium italic">fashion</span>,
          <span class="text-yellow-400 font-medium italic">makanan</span>, dan
          <span class="text-yellow-400 font-medium italic">aksesoris</span> pilihan kami.
        </p>
        
        <a href="#featured"
          class="mt-8 bg-yellow-600 text-white px-8 py-3 rounded-full hover:bg-yellow-500 transition-all duration-300 shadow-xl hover:scale-105 font-semibold">
          Lihat Promo
        </a>
      </div>
    </section>

    <section id="featured" class="max-w-7xl mx-auto px-4 py-20">
        <div class="text-center mb-12">
            <h3 class="text-3xl font-black text-gray-900 mb-2 uppercase tracking-tighter">New Arrivals</h3>
            <p class="text-gray-500 text-sm uppercase tracking-widest">Koleksi Terbaru & Terlaris</p>
        </div>

        <?php if($res->num_rows > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php while($p = $res->fetch_assoc()): 
                // Logic Harga
                $showMemberPrice = $isLoggedIn && !empty($p['member_price']) && $p['member_price'] < $p['price'];
                $finalPrice = $showMemberPrice ? $p['member_price'] : $p['price'];
                
                // Logic Harga Coret
                $hargaCoret = 0;
                if ($p['original_price'] > $p['price']) {
                    $hargaCoret = $p['original_price'];
                } elseif ($showMemberPrice) {
                    $hargaCoret = $p['price'];
                }
            ?>
            
            <div class="product-card group bg-white rounded-2xl overflow-hidden relative flex flex-col">
                
                <div class="absolute top-3 left-3 z-20 flex flex-col gap-2 items-start">
                    <?php if($p['is_flash_sale']): ?>
                        <span class="flash-badge bg-red-600 text-white text-[10px] font-black px-2 py-1 rounded shadow-lg uppercase tracking-wider">Flash Sale</span>
                    <?php endif; ?>
                    <?php if($showMemberPrice): ?>
                        <span class="bg-blue-600 text-white text-[10px] font-black px-2 py-1 rounded shadow-lg uppercase tracking-wider">Member</span>
                    <?php endif; ?>
                </div>

                <div class="relative h-72 overflow-hidden bg-gray-100">
                    <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <a href="katalog.php?category=<?= $p['category'] ?>" class="bg-white text-black px-5 py-2 rounded-full font-bold text-xs uppercase hover:bg-yellow-500 transition">Lihat Detail</a>
                    </div>
                </div>

                <div class="p-5 text-center flex-1 flex flex-col justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1"><?= $p['category'] ?></p>
                        <h4 class="text-lg font-bold text-gray-900 mb-2 truncate"><?= htmlspecialchars($p['title']) ?></h4>
                        
                        <div class="flex flex-col items-center gap-1 justify-center min-h-[50px]">
                            <?php if($hargaCoret > 0): ?>
                                <span class="text-xs text-gray-400 line-through font-medium">Rp <?= number_format($hargaCoret, 0, ',', '.') ?></span>
                            <?php endif; ?>
                            <span class="text-xl font-black <?= $showMemberPrice ? 'text-blue-600' : ($p['is_flash_sale'] ? 'text-red-600' : 'text-gray-900') ?>">
                                Rp <?= number_format($finalPrice, 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>

                    <button type="button" onclick="event.stopPropagation(); window.addToCart({
                        id: '<?= $p['id'] ?>', 
                        title: '<?= addslashes($p['title']) ?>', 
                        price: '<?= $finalPrice ?>', 
                        originalPrice: '<?= $hargaCoret ?>', 
                        img: '<?= $p['image'] ?>'
                    })" class="w-full mt-4 bg-black text-white py-3 text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-yellow-500 hover:text-black transition-all rounded-xl shadow-lg">
                        ADD TO CART +
                    </button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
            <div class="text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                <p class="text-gray-400 uppercase tracking-widest text-xs font-bold">Belum ada produk yang ditampilkan.</p>
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-12">
             <a href="#catalog" class="text-sm text-gray-500 hover:text-yellow-600 font-bold uppercase tracking-widest border-b-2 border-transparent hover:border-yellow-600 pb-1 transition">Lihat Semua Kategori ↓</a>
        </div>
    </section>

    <section id="catalog" class="max-w-6xl mx-auto px-4 py-16 reveal min-h-screen">
      <h3 class="text-3xl font-bold text-center text-gray-800 mb-10">Kategori Produk</h3>

      <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-8">
        <div class="relative shadow-md rounded-xl overflow-hidden hover:shadow-xl transition group">
          <a href="katalog.php?category=Fashion" class="block">
            <img src="./src/img/bajuF.jpg" alt="Fashion" class="w-full h-64 object-cover transition duration-500 group-hover:scale-105" />
          </a>
          <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
            <a href="katalog.php?category=Fashion" class="bg-yellow-600 text-white px-4 py-2 rounded-full font-semibold shadow-md hover:bg-yellow-700 transition">
              Lihat Fashion
            </a>
          </div>
          <div class="p-4 text-center">
            <h4 class="text-xl font-semibold text-yellow-600">Fashion</h4>
            <p class="text-gray-600 text-sm mt-2">Tren terkini yang memadukan gaya dan kenyamanan.</p>
          </div>
        </div>

        <div class="relative shadow-md rounded-xl overflow-hidden hover:shadow-xl transition group">
          <a href="katalog.php?category=Food" class="block">
            <img src="./src/img/saladbuah.jpg" alt="Food" class="w-full h-64 object-cover transition duration-500 group-hover:scale-105" />
          </a>
          <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
            <a href="katalog.php?category=Food" class="bg-yellow-600 text-white px-4 py-2 rounded-full font-semibold shadow-md hover:bg-yellow-700 transition">
              Lihat Food
            </a>
          </div>
          <div class="p-4 text-center">
            <h4 class="text-xl font-semibold text-yellow-600">Food</h4>
            <p class="text-gray-600 text-sm mt-2">Nikmati cita rasa modern khas Spirit Guide.</p>
          </div>
        </div>

        <div class="relative shadow-md rounded-xl overflow-hidden hover:shadow-xl transition group">
          <a href="katalog.php?category=Aksesoris" class="block">
            <img src="./src/img/accesories.jpg" alt="Aksesoris" class="w-full h-64 object-cover transition duration-500 group-hover:scale-105" />
          </a>
          <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
            <a href="katalog.php?category=Aksesoris" class="bg-yellow-600 text-white px-4 py-2 rounded-full font-semibold shadow-md hover:bg-yellow-700 transition">
              Lihat Aksesoris
            </a>
          </div>
          <div class="p-4 text-center">
            <h4 class="text-xl font-semibold text-yellow-600">Aksesoris</h4>
            <p class="text-gray-600 text-sm mt-2">Detail kecil yang melengkapi tampilanmu.</p>
          </div>
        </div>

        <div class="relative shadow-md rounded-xl overflow-hidden hover:shadow-xl transition group">
          <a href="katalog.php?category=Other" class="block">
            <img src="./src/img/other.jpg" alt="Other" class="w-full h-64 object-cover transition duration-500 group-hover:scale-105" />
          </a>
          <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
            <a href="katalog.php?category=Other" class="bg-yellow-600 text-white px-4 py-2 rounded-full font-semibold shadow-md hover:bg-yellow-700 transition">
              Lihat Lainnya
            </a>
          </div>
          <div class="p-4 text-center">
            <h4 class="text-xl font-semibold text-yellow-600">Other</h4>
            <p class="text-gray-600 text-sm mt-2">Produk kategori lain yang tersedia.</p>
          </div>
        </div>
      </div>
    </section>

    <section id="about" class="bg-white py-16 pt-24 border-t border-gray-100">
      <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold text-yellow-600 mb-6">About Spirit Guide</h2>
        <p class="text-gray-700 max-w-3xl mx-auto mb-10">
          Spirit Guide hadir sebagai destinasi gaya hidup yang menginspirasi, 
          menghadirkan koleksi <span class="font-semibold text-yellow-600">fashion</span>, 
          <span class="font-semibold text-yellow-600">food</span>, dan 
          <span class="font-semibold text-yellow-600">aksesoris</span> 
          dengan sentuhan elegan dan modern.
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

    <?php include 'partials/footer.php'; ?>
  </body>
</html>
