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
        .modal-active { overflow: hidden; }
    </style>
</head>
<body class="bg-white text-gray-900">

    <?php include 'partials/navbar.php'; ?>
    <main class="pt-32 pb-20 px-4">
        <div class="max-w-5xl mx-auto flex flex-col lg:flex-row gap-12">
            <div class="flex-1">
                <h2 class="text-xl font-bold uppercase tracking-[0.2em] mb-8 border-b pb-4">Ringkasan Pesanan</h2>
                <div id="checkout-items" class="space-y-6"></div>
                
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <div class="flex justify-between text-sm uppercase tracking-widest text-gray-500">
                        <span>Subtotal</span>
                        <span id="subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold uppercase tracking-widest mt-4">
                        <span>Total Keseluruhan</span>
                        <span id="total-price" class="text-yellow-600">Rp 0</span>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-4">Pilih Metode Pembayaran</label>
                        <div class="space-y-4">
                            <label class="flex items-center p-3 border border-gray-200 rounded-sm cursor-pointer hover:border-black transition-all">
                                <input type="radio" name="payment_type" value="QRIS" class="w-4 h-4 text-black focus:ring-black" checked>
                                <div class="ml-3">
                                    <span class="block text-[10px] font-bold uppercase tracking-tighter text-gray-800">QRIS</span>
                                    <span class="block text-[8px] text-gray-400 uppercase">Scan Otomatis</span>
                                </div>
                            </label>

                            <div class="p-3 border border-gray-200 rounded-sm">
                                <div class="flex items-center mb-3">
                                    <input type="radio" name="payment_type" value="Bank Transfer" class="w-4 h-4 text-black focus:ring-black">
                                    <span class="ml-3 text-[10px] font-bold uppercase tracking-tighter text-gray-800">Transfer Bank / E-Wallet</span>
                                </div>
                                <select id="bank_list" class="w-full bg-transparent border-b border-gray-300 py-1 text-[10px] font-bold uppercase tracking-widest focus:border-black outline-none cursor-pointer">
                                    <option value="BCA - 123456789 (A/N Spirit Guide)">BCA - 123456789</option>
                                    <option value="BNI - 987654321 (A/N Spirit Guide)">BNI - 987654321</option>
                                    <option value="DANA - 08123456789">DANA - 08123456789</option>
                                    <option value="OVO - 08123456789">OVO - 08123456789</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-[400px] bg-gray-50 p-8 rounded-sm">
                <h2 class="text-sm font-bold uppercase tracking-[0.2em] mb-6">Maklumat Penghantaran</h2>
                <form id="checkoutForm" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Nama Penuh</label>
                        <input type="text" id="nama" name="nama" required class="w-full border-b border-gray-300 bg-transparent py-2 focus:border-black outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Nomor WhatsApp</label>
                        <input type="tel" id="whatsapp" name="whatsapp" placeholder="0812..." required class="w-full border-b border-gray-300 bg-transparent py-2 focus:border-black outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Alamat Lengkap</label>
                        <textarea name="address" id="alamat" required rows="3" class="w-full border-b border-gray-300 bg-transparent py-2 focus:border-black outline-none text-sm resize-none"></textarea>
                    </div>
                    <button type="submit" id="submit-btn" class="w-full bg-black text-white py-4 mt-6 text-[11px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition-all">
                        Sahkan Pesanan
                    </button>
                </form>
            </div>
        </div>
    </main>

    <div id="qris-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm" onclick="closeModal('qris-modal')">
    <div class="bg-white w-full max-w-md rounded-2xl p-8 text-center shadow-2xl" onclick="event.stopPropagation()">
        <h3 class="text-lg font-bold uppercase tracking-widest mb-2">Scan QRIS</h3>
        <p class="text-[10px] text-gray-400 uppercase mb-6">Silakan Scan dan Selesaikan Pembayaran</p>
        
        <img src="./assets/images/Qris-Spiritguide.jpeg" class="w-64 h-64 mx-auto mb-6 border p-2 rounded-lg">
        
        <div class="space-y-3">
            <button onclick="finalSubmitWhatsApp()" class="w-full bg-black text-white py-4 text-[11px] font-bold uppercase tracking-[0.3em]">
                Saya Sudah Bayar
            </button>
            <button onclick="closeModal('qris-modal')" class="w-full bg-transparent text-gray-400 py-2 text-[10px] font-bold uppercase tracking-widest hover:text-black transition-all">
                KEMBALI
            </button>
        </div>
    </div>
</div>

<div id="bank-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm" onclick="closeModal('bank-modal')">
    <div class="bg-white w-full max-w-md rounded-2xl p-8 text-center shadow-2xl" onclick="event.stopPropagation()">
        <h3 class="text-lg font-bold uppercase tracking-widest mb-2">Transfer Bank</h3>
        <p class="text-[10px] text-gray-400 uppercase mb-6">Silakan Transfer ke Rekening Berikut</p>
        
        <div class="bg-gray-50 p-6 rounded-lg mb-6 border border-gray-100">
            <p id="modal-bank-detail" class="text-sm font-bold tracking-widest text-gray-800"></p>
            <p class="text-[9px] text-gray-400 mt-3 italic uppercase">*Pastikan nominal sesuai dengan total order</p>
        </div>
        
        <div class="space-y-3">
            <button onclick="finalSubmitWhatsApp()" class="w-full bg-black text-white py-4 text-[11px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition-all">
                Saya Sudah Transfer
            </button>
            <button onclick="closeModal('bank-modal')" class="w-full bg-transparent text-gray-400 py-2 text-[10px] font-bold uppercase tracking-widest hover:text-black transition-all">
                KEMBALI
            </button>
        </div>
    </div>
</div>

<script src="src/js/cart.js"></script>
<script src="src/js/checkout.js?v=1.3"></script>
</body>
</html>