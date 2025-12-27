<?php
// Pastikan session_start() sudah dipanggil di file utama
// Ambil data ID unik dari database yang sudah ada di session
$userIdJS = 'guest';

if (isset($_SESSION['user']['username'])) {
    // Jika login sebagai 'ail', maka $userIdJS jadi 'ail'
    $userIdJS = $_SESSION['user']['username']; 
} elseif (isset($_SESSION['user_id'])) {
    $userIdJS = $_SESSION['user_id'];
}
?>

<script>
    // Variabel ini yang akan menentukan laci LocalStorage mana yang dibuka
    window.USER_ID = "<?php echo $userIdJS; ?>";
    console.log("Sistem Belanja Aktif Untuk User:", window.USER_ID);
</script>

<header id="navbar" class="fixed top-0 left-0 w-full bg-white shadow-md z-50 transition-all duration-300">
    <style>
        @keyframes bounce-cart {
            0% { transform: scale(1); }
            30% { transform: scale(1.6); }
            50% { transform: scale(0.9); }
            100% { transform: scale(1); }
        }
        .animate-bounce-cart {
            display: flex !important;
            animation: bounce-cart 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
    </style>
    <div class="max-w-7xl mx-auto px-4 flex justify-between items-center py-3">
        <div class="flex items-center space-x-2">
            <img src="./src/img/SpiritGuide.jpg" alt="Spirit Guide" class="w-10 h-10 rounded-full">
            <h1 class="text-xl font-bold text-yellow-600 tracking-wide">
                <a href="index.php">Spirit Guide</a>
            </h1>
        </div>

        <nav class="hidden md:flex space-x-10 font-medium text-gray-700">
            <a href="index.php#home" class="nav-link">Home</a>
            <a href="index.php#catalog" class="nav-link">Catalog</a>
            <a href="index.php#about" class="nav-link">About</a>
        </nav>

        <div class="hidden md:flex items-center space-x-5">
            <a href="checkout.php" class="relative mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700 hover:text-yellow-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.3 5.4A1 1 0 007.7 20h8.6a1 1 0 001-1.2L17 13M7 13h10" />
                </svg>
                <span id="cart-badge-desktop" class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full hidden">0</span>
            </a>

            <?php if (!empty($_SESSION['user'])): ?>
                <span class="text-gray-700 font-medium"><?= htmlspecialchars($_SESSION['user']['username']); ?></span>
                <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700 transition">Logout</a>
            <?php else: ?>
                <a href="login.php" class="bg-yellow-600 text-white px-4 py-2 rounded-full hover:bg-yellow-700 transition">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<script src="./src/js/cart.js"></script>