<?php
session_start();
require "config/db.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Spirit Guide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-900">

    <?php include 'partials/navbar.php'; ?>
    <main class="pt-32 pb-20 px-4">
        <div class="max-w-5xl mx-auto flex flex-col lg:flex-row gap-12">
            <div class="flex-1">
                <h2 class="text-xl font-bold uppercase tracking-[0.2em] mb-8 border-b pb-4">Ringkasan Pesanan</h2>
                <div id="checkout-items" class="space-y-6">
                    </div>
                
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <div class="flex justify-between text-sm uppercase tracking-widest text-gray-500">
                        <span>Subtotal</span>
                        <span id="subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold uppercase tracking-widest mt-4">
                        <span>Total Keseluruhan</span>
                        <span id="total-price">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-[400px] bg-gray-50 p-8 rounded-sm">
                <h2 class="text-sm font-bold uppercase tracking-[0.2em] mb-6">Maklumat Penghantaran</h2>
                
                <form id="checkoutForm" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Nama Penuh</label>
                        <input type="text" id="nama" name="nama" required class="w-full border-b border-gray-300 bg-transparent py-2 focus:border-black outline-none transition-colors text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Nombor WhatsApp</label>
                        <input type="tel" id="whatsapp" name="whatsapp" placeholder="Contoh: 08123456789" required class="w-full border-b border-gray-300 bg-transparent py-2 focus:border-black outline-none transition-colors text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Alamat Lengkap</label>
                        <textarea name="address" id="alamat" required rows="3" class="w-full border-b border-gray-300 bg-transparent py-2 focus:border-black outline-none transition-colors text-sm resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Nota (Opsional)</label>
                        <input type="text" id="nota" placeholder="Warna, saiz, dsb." class="w-full border-b border-gray-300 bg-transparent py-2 focus:border-black outline-none transition-colors text-sm">
                    </div>

                    <button type="submit" class="w-full bg-black text-white py-4 mt-6 text-[11px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition-all">
                        Sahkan Pesanan via WhatsApp
                    </button>
                    
                    <a href="index.php" class="block text-center mt-4 text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-black">
                        Batal & Kembali Belanja
                    </a>
                </form>
            </div>

        </div>
    </main>

<script src="src/js/cart.js"></script>
<script src="src/js/katalog.js"></script>
<script src="src/js/checkout.js?v=1.2"></script>
<?php include 'partials/footer.php'; ?>
</body>
</html>