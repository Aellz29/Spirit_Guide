/**
 * SPIRIT GUIDE - FINAL INTEGRATED CHECKOUT
 */

function loadCheckout() {
    const cart = JSON.parse(localStorage.getItem('sg_cart') || '[]');
    const container = document.getElementById('checkout-items');
    const subtotalEl = document.getElementById('subtotal');
    const totalEl = document.getElementById('total-price');

    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = `<div class="text-center py-20 border-2 border-dashed border-gray-100 rounded-lg">
            <p class="text-gray-400 text-sm italic mb-4">Keranjang anda kosong.</p>
            <a href="index.php" class="inline-block bg-black text-white px-6 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-yellow-600 transition">Kembali Belanja</a>
        </div>`;
        return;
    }

    let total = 0;
    container.innerHTML = '';
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.qty;
        total += itemTotal;
        container.innerHTML += `
            <div class="flex items-center border-b border-gray-50 pb-6 mb-6">
                <div class="w-20 h-20 bg-gray-50 p-2 mr-4"><img src="${item.img}" class="w-full h-full object-contain"></div>
                <div class="flex-1">
                    <h4 class="text-xs font-bold uppercase tracking-widest">${item.title}</h4>
                    <p class="text-[10px] text-gray-400 mt-1">Rp ${item.price.toLocaleString('id-ID')}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <button onclick="changeQty(${index}, -1)" class="w-6 h-6 border border-gray-300">-</button>
                        <span class="text-xs">${item.qty}</span>
                        <button onclick="changeQty(${index}, 1)" class="w-6 h-6 border border-gray-300">+</button>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold">Rp ${itemTotal.toLocaleString('id-ID')}</p>
                    <button onclick="removeFromCart(${index})" class="text-[9px] text-red-500 mt-1 uppercase">Hapus</button>
                </div>
            </div>`;
    });

    if (subtotalEl) subtotalEl.innerText = `Rp ${total.toLocaleString('id-ID')}`;
    if (totalEl) totalEl.innerText = `Rp ${total.toLocaleString('id-ID')}`;
}

const checkoutForm = document.getElementById('checkoutForm');
if (checkoutForm) {
    checkoutForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    const cart = JSON.parse(localStorage.getItem('sg_cart') || '[]');
    const formData = new FormData(this);
    formData.append('cart_data', JSON.stringify(cart));

    try {
        const response = await fetch('proses_pesanan.php', { method: 'POST', body: formData });
        const result = await response.json();

        if (result.status === 'success') {
    const nama = document.getElementById('nama').value;
    const waUser = document.getElementById('whatsapp').value;
    const alamat = document.getElementById('alamat').value;
    
    // 1. Susun daftar barang HANYA dari yang berhasil diproses server
    let listBarang = "";
    result.processed_items.forEach((item, i) => {
        const sub = item.price * item.qty;
        listBarang += `${i+1}. ${item.title} (x${item.qty}) - Rp ${sub.toLocaleString('id-ID')}\n`;
    });

    // 2. Susun template pesan (Gunakan backtick ` bukan kutip satu ')
    const teks = `*PESANAN BARU - ID #${result.order_id}*\n` +
                 `--------------------------------\n` +
                 `*Nama:* ${nama}\n` +
                 `*WhatsApp:* ${waUser}\n` +
                 `*Alamat:* ${alamat}\n\n` +
                 `*Barang (Ready Stock):*\n${listBarang}\n` +
                 `*TOTAL BAYAR: Rp ${result.total.toLocaleString('id-ID')}*\n` +
                 `--------------------------------\n` +
                 `Barang yang habis otomatis tidak disertakan dalam pesanan ini.`;

    // 3. Eksekusi Redirect ke WhatsApp
    const whatsappUrl = `https://wa.me/628971566371?text=${encodeURIComponent(teks)}`;
    
    // Hapus keranjang dan pindah ke WA
    localStorage.removeItem('sg_cart');
    window.location.href = whatsappUrl;

} else {
    // Jika ada error dari server (misal stok benar-benar habis semua)
    alert("Pemberitahuan: " + result.message);
}
    } catch (err) {
        console.error(err);
        alert("Terjadi kesalahan sistem.");
    }
});
}

document.addEventListener('DOMContentLoaded', loadCheckout);