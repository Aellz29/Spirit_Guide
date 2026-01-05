<?php
session_start();
require "config/db.php";

$isLoggedIn = isset($_SESSION['user']);
// AMBIL ID USER UNTUK JS AGAR KERANJANG SINKRON
$userID = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'guest';

$userData = [
    'name' => $isLoggedIn ? $_SESSION['user']['username'] : '',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout | Spirit Guide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F9FA; color: #111; }
        
        /* Smooth Scroll & Transitions */
        html { scroll-behavior: smooth; }
        .transition-all { transition-property: all; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 300ms; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        /* Animation untuk Hemat */
        @keyframes pulse-green { 0% { opacity: 0.6; } 50% { opacity: 1; } 100% { opacity: 0.6; } }
        .animate-pulse-green { animation: pulse-green 2s infinite; }
        
        /* Radio Button Styling */
        input[type="radio"]:checked + div { border-color: #f59e0b; background-color: #fffbeb; }
        input[type="radio"]:checked + div .check-icon { opacity: 1; transform: scale(1); }
    </style>
</head>
<body class="antialiased">

    <?php include 'partials/navbar.php'; ?>

    <main class="pt-32 pb-24 px-6 min-h-screen">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-gray-200 pb-6">
                <div class="relative pl-6 border-l-4 border-amber-500">
                    <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter text-gray-900 leading-none mb-2">
                        Checkout
                    </h1>
                    <p class="text-sm text-gray-500 font-medium tracking-widest uppercase">Selesaikan pesanan Anda</p>
                </div>
                
                <?php if($isLoggedIn): ?>
                    <div class="mt-4 md:mt-0 flex items-center gap-3 bg-white px-5 py-3 rounded-full shadow-sm border border-gray-100">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-bold uppercase tracking-widest text-gray-600">
                            Member Price <span class="text-amber-500">Active</span>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex flex-col lg:flex-row gap-12 relative">
                
                <div class="flex-1 space-y-10">
                    
                    <div class="bg-white p-8 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                        <div class="flex items-center gap-3 mb-8">
                            <span class="bg-black text-white w-8 h-8 flex items-center justify-center rounded-full text-xs font-bold">1</span>
                            <h2 class="text-lg font-bold uppercase tracking-widest text-gray-900">Keranjang Belanja</h2>
                        </div>
                        
                        <div id="checkout-items" class="space-y-6">
                            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                                <i class="fa fa-spinner fa-spin text-2xl mb-3"></i>
                                <p class="text-xs font-bold uppercase tracking-widest">Memuat keranjang...</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                        <div class="flex items-center gap-3 mb-8">
                            <span class="bg-black text-white w-8 h-8 flex items-center justify-center rounded-full text-xs font-bold">2</span>
                            <h2 class="text-lg font-bold uppercase tracking-widest text-gray-900">Metode Pembayaran</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="cursor-pointer relative group">
                                <input type="radio" name="payment_type" value="QRIS" class="peer sr-only" checked>
                                <div class="p-6 rounded-2xl border-2 border-gray-100 hover:border-amber-500/50 transition-all bg-white h-full flex flex-col justify-between group-hover:shadow-lg">
                                    <div class="flex justify-between items-start mb-4">
                                        <i class="fa fa-qrcode text-3xl text-gray-300 peer-checked:text-amber-500 transition-colors"></i>
                                        <div class="check-icon opacity-0 transition-all text-amber-500"><i class="fa fa-check-circle text-xl"></i></div>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 uppercase tracking-wide">QRIS Instant</h3>
                                        <p class="text-[10px] text-gray-500 mt-1">Gopay, OVO, Dana, ShopeePay</p>
                                    </div>
                                </div>
                            </label>

                            <label class="cursor-pointer relative group">
                                <input type="radio" name="payment_type" value="Bank Transfer" class="peer sr-only">
                                <div class="p-6 rounded-2xl border-2 border-gray-100 hover:border-amber-500/50 transition-all bg-white h-full flex flex-col justify-between group-hover:shadow-lg">
                                    <div class="flex justify-between items-start mb-4">
                                        <i class="fa fa-building-columns text-3xl text-gray-300 peer-checked:text-amber-500 transition-colors"></i>
                                        <div class="check-icon opacity-0 transition-all text-amber-500"><i class="fa fa-check-circle text-xl"></i></div>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 uppercase tracking-wide">Transfer Bank</h3>
                                        
                                        <div class="mt-2 relative">
                                            <select id="bank_list" onclick="document.querySelector('input[value=\'Bank Transfer\']').checked = true" 
                                                class="w-full bg-transparent border-b border-gray-300 py-1 pr-6 text-[11px] font-bold uppercase outline-none focus:border-amber-500 cursor-pointer appearance-none">
                                                <option value="BCA - 123456789">BCA - Spirit Guide</option>
                                                <option value="MANDIRI - 987654321">MANDIRI - Spirit Guide</option>
                                                <option value="DANA - 08971566371">DANA - Admin</option>
                                            </select>
                                            <i class="fa fa-chevron-down absolute right-0 top-1 text-[10px] text-gray-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-[420px] relative">
                    <div class="sticky top-32 bg-white p-8 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100">
                        <div class="flex items-center gap-3 mb-8">
                            <span class="bg-black text-white w-8 h-8 flex items-center justify-center rounded-full text-xs font-bold">3</span>
                            <h2 class="text-lg font-bold uppercase tracking-widest text-gray-900">Data Penerima</h2>
                        </div>
                        
                        <form id="checkoutForm" class="space-y-6">
                            <div class="group">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 group-focus-within:text-amber-500 transition-colors">Nama Lengkap</label>
                                <input type="text" name="nama" value="<?= htmlspecialchars($userData['name']) ?>" required 
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 outline-none focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all placeholder-gray-300"
                                    placeholder="Nama Penerima">
                            </div>

                            <div class="group">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 group-focus-within:text-amber-500 transition-colors">WhatsApp</label>
                                <input type="tel" name="whatsapp" placeholder="08..." required 
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 outline-none focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all placeholder-gray-300">
                            </div>

                            <div class="group">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 group-focus-within:text-amber-500 transition-colors">Alamat Pengiriman</label>
                                <textarea name="address" required rows="3" 
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 outline-none focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all resize-none placeholder-gray-300"
                                    placeholder="Jalan, No Rumah, Kecamatan..."></textarea>
                            </div>

                            <div id="summary-area" class="mt-8 pt-6 border-t-2 border-dashed border-gray-100">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Subtotal</span>
                                    <span id="subtotal" class="text-sm font-bold text-gray-900">Rp 0</span>
                                </div>
                                
                                <div id="total-row" class="flex justify-between items-end mt-4 pt-4 border-t border-gray-100">
                                    <span class="text-sm font-bold text-gray-900">Total Bayar</span>
                                    <span id="total-price" class="text-2xl font-black text-amber-500 tracking-tight">Rp 0</span>
                                </div>
                                
                                <div class="mt-2 flex justify-end">
                                    <span class="text-[10px] text-gray-400 bg-gray-50 px-2 py-1 rounded">Termasuk PPN & Diskon</span>
                                </div>
                            </div>

                            <button type="submit" id="submit-btn" class="w-full bg-gray-900 text-white py-4 rounded-xl text-xs font-black uppercase tracking-[0.2em] hover:bg-amber-500 hover:text-black hover:shadow-xl hover:shadow-amber-500/20 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 group">
                                <span>Buat Pesanan</span>
                                <i class="fa fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <div id="qris-modal" class="fixed inset-0 z-[999] hidden flex items-center justify-center px-4 bg-black/80 backdrop-blur-sm transition-all">
        <div class="bg-white w-full max-w-sm rounded-3xl p-8 text-center shadow-2xl relative animate-[fadeIn_0.3s_ease-out]">
            <button onclick="closeModal('qris-modal')" class="absolute top-4 right-4 text-gray-300 hover:text-black transition"><i class="fa fa-times text-xl"></i></button>
            <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl"><i class="fa fa-qrcode"></i></div>
            <h3 class="text-xl font-black uppercase tracking-tight mb-2">Scan QRIS</h3>
            <p class="text-xs text-gray-500 mb-6">Selesaikan pembayaran melalui e-wallet favoritmu.</p>
            
            <div class="bg-gray-50 p-4 rounded-2xl mb-6 border-2 border-dashed border-gray-200">
                <img src="./assets/images/Qris-Spiritguide.jpeg" class="w-full h-auto rounded-lg mix-blend-multiply opacity-90">
            </div>
            
            <div class="text-left mb-4">
                <label class="block text-[10px] font-bold uppercase mb-2 text-gray-400">Upload Bukti</label>
                <input type="file" id="proof_qris" accept="image/*" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-amber-50 file:text-amber-600 hover:file:bg-amber-100 cursor-pointer">
            </div>
            <button onclick="finalSubmitWhatsApp('proof_qris')" class="w-full bg-black text-white py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-amber-500 hover:text-black transition">Konfirmasi WA</button>
        </div>
    </div>

    <div id="bank-modal" class="fixed inset-0 z-[999] hidden flex items-center justify-center px-4 bg-black/80 backdrop-blur-sm transition-all">
        <div class="bg-white w-full max-w-sm rounded-3xl p-8 text-center shadow-2xl relative animate-[fadeIn_0.3s_ease-out]">
            <button onclick="closeModal('bank-modal')" class="absolute top-4 right-4 text-gray-300 hover:text-black transition"><i class="fa fa-times text-xl"></i></button>
            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl"><i class="fa fa-building-columns"></i></div>
            <h3 class="text-xl font-black uppercase tracking-tight mb-2">Transfer Manual</h3>
            
            <div class="bg-gray-50 p-5 rounded-2xl mb-6 border border-gray-100">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Silakan transfer ke:</p>
                <p id="modal-bank-detail" class="text-sm font-bold text-gray-900"></p>
            </div>

            <div class="text-left mb-4">
                <label class="block text-[10px] font-bold uppercase mb-2 text-gray-400">Upload Bukti</label>
                <input type="file" id="proof_bank" accept="image/*" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-black file:text-white hover:file:bg-gray-800 cursor-pointer">
            </div>
            <button onclick="finalSubmitWhatsApp('proof_bank')" class="w-full bg-black text-white py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-amber-500 hover:text-black transition">Konfirmasi WA</button>
        </div>
    </div>

    <script>window.USER_ID = "<?= $userID ?>";</script>
    <script src="src/js/cart.js?v=<?= time() ?>"></script>
    <script src="src/js/checkout.js?v=<?= time() ?>"></script>
    <?php include 'partials/footer.php'; ?>
</body>
</html>