<?php
// Ambil data ID unik dari database yang sudah ada di session
$userIdJS = 'guest';
if (isset($_SESSION['user']['username'])) {
    $userIdJS = $_SESSION['user']['username']; 
} elseif (isset($_SESSION['user_id'])) {
    $userIdJS = $_SESSION['user_id'];
}
?>

<script>
    window.USER_ID = "<?php echo $userIdJS; ?>";
</script>

<header id="navbar" class="fixed top-0 left-0 w-full bg-white shadow-md z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 flex justify-between items-center py-3">
        <div class="flex items-center space-x-2">
            <img src="./src/img/SpiritGuide.jpg" alt="Spirit Guide" class="w-10 h-10 rounded-full">
            <h1 class="text-xl font-bold text-yellow-600 tracking-wide">
                <a href="index.php">Spirit Guide</a>
            </h1>
        </div>

        <nav class="hidden md:flex space-x-10 font-medium text-gray-700">
            <a href="index.php#home" class="hover:text-yellow-600 transition">Home</a>
            <a href="index.php#catalog" class="hover:text-yellow-600 transition">Catalog</a>
            <a href="index.php#about" class="hover:text-yellow-600 transition">About</a>
            <?php if (isset($_SESSION['user']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin_orders.php" class="text-red-600 font-bold underline">Admin Panel</a>
            <?php endif; ?>
        </nav>

        <div class="hidden md:flex items-center space-x-5">
            <a href="checkout.php" class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700 hover:text-yellow-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.3 5.4A1 1 0 007.7 20h8.6a1 1 0 001-1.2L17 13M7 13h10" />
                </svg>
                <span id="cart-badge-desktop" class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full hidden">0</span>
            </a>

            <?php if (!empty($_SESSION['user'])): ?>
                <span class="text-gray-700 font-medium">Hi, <?= htmlspecialchars($_SESSION['user']['username']); ?></span>
                <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700 transition text-sm font-bold">Logout</a>
            <?php else: ?>
                <a href="login.php" class="bg-yellow-600 text-white px-4 py-2 rounded-full hover:bg-yellow-700 transition text-sm font-bold">Login</a>
            <?php endif; ?>
        </div>

        <button id="menu-btn" class="md:hidden flex flex-col space-y-1.5 focus:outline-none z-50">
            <span class="w-6 h-0.5 bg-gray-800 transition-all duration-300"></span>
            <span class="w-6 h-0.5 bg-gray-800 transition-all duration-300"></span>
            <span class="w-6 h-0.5 bg-gray-800 transition-all duration-300"></span>
        </button>
    </div>

    <div id="mobile-menu" class="fixed top-0 right-0 h-screen w-64 bg-white shadow-2xl translate-x-full transition-transform duration-300 md:hidden flex flex-col p-6 space-y-6">
        <div class="mt-16 flex flex-col space-y-4 font-medium text-gray-700">
            <a href="index.php#home" class="hover:text-yellow-600 py-2 border-b border-gray-100">Home</a>
            <a href="index.php#catalog" class="hover:text-yellow-600 py-2 border-b border-gray-100">Catalog</a>
            <a href="index.php#about" class="hover:text-yellow-600 py-2 border-b border-gray-100">About</a>
            
            <a href="checkout.php" class="flex items-center justify-between py-2 border-b border-gray-100">
                <span>Keranjang</span>
                <span id="cart-badge-mobile" class="bg-red-600 text-white text-[10px] px-2 py-0.5 rounded-full">0</span>
            </a>

            <?php if (!empty($_SESSION['user'])): ?>
                <div class="pt-4">
                    <p class="text-[10px] text-gray-400 uppercase">Logged in as</p>
                    <p class="font-bold text-gray-800"><?= htmlspecialchars($_SESSION['user']['username']); ?></p>
                </div>
                <a href="logout.php" class="bg-red-600 text-white text-center py-3 rounded-xl font-bold">Logout</a>
            <?php else: ?>
                <a href="login.php" class="bg-yellow-600 text-white text-center py-3 rounded-xl font-bold">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<div class="h-16"></div>

<script src="./src/js/navbar.js"></script>
<script src="./src/js/cart.js"></script>