/**
 * SPIRIT GUIDE - FINAL INTEGRATED CHECKOUT (FIXED)
 */

function loadCheckout() {
    const cartKey = getCartKey(); // Ambil kunci dari cart.js
    const cart = JSON.parse(localStorage.getItem(cartKey) || '[]'); // Integrasi kunci dinamis
    const container = document.getElementById('checkout-items');
    const subtotalEl = document.getElementById('subtotal');
    const totalEl = document.getElementById('total-price');

    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = `
            <div class="text-center py-20 border-2 border-dashed border-gray-100 rounded-lg">
                <p class="text-gray-400 text-sm italic mb-4">Keranjang anda kosong.</p>
                <a href="index.php" class="inline-block bg-black text-white px-6 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition">Kembali Belanja</a>
            </div>`;
        if (subtotalEl) subtotalEl.innerText = "Rp 0";
        if (totalEl) totalEl.innerText = "Rp 0";
        return;
    }

    let total = 0;
    let htmlContent = '';

    cart.forEach((item, index) => {
        // Membersihkan harga dari karakter non-angka jika ada (seperti "Rp" atau titik)
        let price = item.price;
        if (typeof price === 'string') {
            price = parseInt(price.replace(/[^0-9]/g, '')) || 0;
        }
        
        const itemTotal = price * item.qty;
        total += itemTotal;

        htmlContent += `
            <div class="flex items-center border-b border-gray-50 pb-6 mb-6">
                <div class="w-20 h-20 bg-gray-50 p-2 mr-4">
                    <img src="${item.img}" class="w-full h-full object-contain" onerror="this.src='assets/placeholder.jpg'">
                </div>
                <div class="flex-1">
                    <h4 class="text-xs font-bold uppercase tracking-widest">${item.title}</h4>
                    <p class="text-[10px] text-gray-400 mt-1">Rp ${price.toLocaleString('id-ID')}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <button onclick="updateQtyCheckout(${index}, -1)" class="w-6 h-6 border border-gray-300 flex items-center justify-center hover:bg-black hover:text-white transition">-</button>
                        <span class="text-xs w-4 text-center">${item.qty}</span>
                        <button onclick="updateQtyCheckout(${index}, 1)" class="w-6 h-6 border border-gray-300 flex items-center justify-center hover:bg-black hover:text-white transition">+</button>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold">Rp ${itemTotal.toLocaleString('id-ID')}</p>
                    <button onclick="removeItemCheckout(${index})" class="text-[9px] text-red-500 mt-1 uppercase hover:underline">Hapus</button>
                </div>
            </div>`;
    });

    container.innerHTML = htmlContent;
    if (subtotalEl) subtotalEl.innerText = `Rp ${total.toLocaleString('id-ID')}`;
    if (totalEl) totalEl.innerText = `Rp ${total.toLocaleString('id-ID')}`;
}

// Update Quantity
window.updateQtyCheckout = function(index, change) {
    const cartKey = getCartKey(); // Tambahkan ini agar sinkron
    let cart = JSON.parse(localStorage.getItem(cartKey) || '[]'); // Ganti 'sg_cart' jadi cartKey
    cart[index].qty += change;
    
    if (cart[index].qty < 1) {
        if(confirm("Hapus barang dari keranjang?")) {
            cart.splice(index, 1);
        } else {
            cart[index].qty = 1;
        }
    }
    
    localStorage.setItem(cartKey, JSON.stringify(cart)); // Ganti 'sg_cart' jadi cartKey
    loadCheckout(); 
    if (typeof updateCartUI === 'function') updateCartUI();
};

// Remove Item
window.removeItemCheckout = function(index) {
    const cartKey = getCartKey(); // Tambahkan ini
    let cart = JSON.parse(localStorage.getItem(cartKey) || '[]'); // Ganti 'sg_cart' jadi cartKey
    cart.splice(index, 1);
    localStorage.setItem(cartKey, JSON.stringify(cart)); // Ganti 'sg_cart' jadi cartKey
    loadCheckout(); 
    if (typeof updateCartUI === 'function') updateCartUI();
};

// Form Submission
const checkoutForm = document.getElementById('checkoutForm');
if (checkoutForm) {
    checkoutForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const cart = JSON.parse(localStorage.getItem('sg_cart') || '[]');
        if (cart.length === 0) return alert("Keranjang masih kosong!");

        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerText = "MEMPROSES...";

        const formData = new FormData(this);
        formData.append('cart_data', JSON.stringify(cart));

        try {
            const response = await fetch('proses_pesanan.php', { method: 'POST', body: formData });
            const result = await response.json();

            if (result.status === 'success') {
                const nama = document.getElementById('nama').value;
                const waUser = document.getElementById('whatsapp').value;
                const alamat = document.getElementById('alamat').value;
                
                let listBarang = "";
                cart.forEach((item, i) => {
                    let p = typeof item.price === 'string' ? parseInt(item.price.replace(/[^0-9]/g, '')) : item.price;
                    listBarang += `${i+1}. ${item.title} (x${item.qty}) - Rp ${(p * item.qty).toLocaleString('id-ID')}\n`;
                });

                const teks = `*PESANAN BARU - ID #${result.order_id}*\n` +
                             `--------------------------------\n` +
                             `*Nama:* ${nama}\n` +
                             `*WhatsApp:* ${waUser}\n` +
                             `*Alamat:* ${alamat}\n\n` +
                             `*Barang:*\n${listBarang}\n` +
                             `*TOTAL BAYAR: Rp ${result.total.toLocaleString('id-ID')}*\n` +
                             `--------------------------------\n` +
                             `Silahkan kirim pesan ini untuk konfirmasi pesanan saya.`;

                const whatsappUrl = `https://wa.me/628971566371?text=${encodeURIComponent(teks)}`;
                localStorage.removeItem('sg_cart');
                window.location.href = whatsappUrl;
            } else {
                alert("Gagal: " + result.message);
                submitBtn.disabled = false;
                submitBtn.innerText = "Sahkan Pesanan via WhatsApp";
            }
        } catch (err) {
            console.error(err);
            alert("Terjadi kesalahan koneksi ke server.");
            submitBtn.disabled = false;
            submitBtn.innerText = "Sahkan Pesanan via WhatsApp";
        }
    });
}

document.addEventListener('DOMContentLoaded', loadCheckout);