// Variable untuk menyimpan data sementara
let pendingOrderData = null;

// Fungsi Load Checkout (Tetap sama dengan milikmu yang sudah fixed)
function loadCheckout() {
    const cartKey = getCartKey();
    const cart = JSON.parse(localStorage.getItem(cartKey) || '[]');
    const container = document.getElementById('checkout-items');
    const totalEl = document.getElementById('total-price');
    const subtotalEl = document.getElementById('subtotal');

    if (!container) return;
    if (cart.length === 0) {
        container.innerHTML = `<p class="text-center py-10 text-gray-400">Keranjang kosong.</p>`;
        return;
    }

    let total = 0;
    let html = '';
    cart.forEach((item, index) => {
        let price = typeof item.price === 'string' ? parseInt(item.price.replace(/[^0-9]/g, '')) : item.price;
        total += (price * item.qty);
        html += `
            <div class="flex items-center justify-between border-b pb-4">
                <div class="flex items-center gap-4">
                    <img src="${item.img}" class="w-16 h-16 object-cover">
                    <div>
                        <h4 class="text-xs font-bold uppercase">${item.title}</h4>
                        <p class="text-[10px] text-gray-400">x${item.qty}</p>
                    </div>
                </div>
                <p class="text-sm font-bold uppercase">Rp ${(price * item.qty).toLocaleString('id-ID')}</p>
            </div>`;
    });
    container.innerHTML = html;
    totalEl.innerText = `Rp ${total.toLocaleString('id-ID')}`;
    subtotalEl.innerText = `Rp ${total.toLocaleString('id-ID')}`;
}

// Logika Form Submission Bertahap
const checkoutForm = document.getElementById('checkoutForm');
if (checkoutForm) {
    checkoutForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const cartKey = getCartKey(); 
        const cart = JSON.parse(localStorage.getItem(cartKey) || '[]');
        if (cart.length === 0) return alert("Keranjang masih kosong!");

        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerText = "MENGUNCI PESANAN...";

        const paymentType = document.querySelector('input[name="payment_type"]:checked').value;
        const bankDetail = document.getElementById('bank_list').value;
        let finalPayment = paymentType === 'QRIS' ? 'QRIS' : `Transfer ${bankDetail}`;

        const formData = new FormData(this);
        formData.append('cart_data', JSON.stringify(cart));
        formData.append('payment', finalPayment);

        try {
            const response = await fetch('proses_pesanan.php', { method: 'POST', body: formData });
            const result = await response.json();

            if (result.status === 'success') {
                // Simpan data untuk dikirim ke WA nanti
                pendingOrderData = { result, cart, finalPayment };

                if (paymentType === 'QRIS') {
                    document.getElementById('qris-modal').classList.remove('hidden');
                } else {
                    const bankDisplay = document.getElementById('modal-bank-detail');
            if (bankDisplay) {
                bankDisplay.innerText = bankDetail;
            }
            document.getElementById('bank-modal').classList.remove('hidden');
        }
    } else {
        alert("Error: " + result.message);
        submitBtn.disabled = false;
        submitBtn.innerText = "Sahkan Pesanan";
    }
} catch (err) {
    console.error(err); // Untuk melihat error asli di console browser
    alert("Terjadi kesalahan teknis saat memproses pesanan.");
    submitBtn.disabled = false;
}
    });
}

// Fungsi Akhir ke WhatsApp
function finalSubmitWhatsApp() {
    if (!pendingOrderData) return;

    const { result, cart, finalPayment } = pendingOrderData;
    const nama = document.getElementById('nama').value;
    const waUser = document.getElementById('whatsapp').value;
    const alamat = document.getElementById('alamat').value;

    let listBarang = "";
    cart.forEach((item, i) => {
        let p = typeof item.price === 'string' ? parseInt(item.price.replace(/[^0-9]/g, '')) : item.price;
        listBarang += `${i+1}. ${item.title} (x${item.qty})\n`;
    });

    const teks = `*PESANAN BERHASIL - ID #${result.order_id}*\n` +
                 `--------------------------------\n` +
                 `*Nama:* ${nama}\n` +
                 `*Alamat:* ${alamat}\n` +
                 `*Metode:* ${finalPayment}\n` +
                 `*Status:* Menunggu Verifikasi Pembayaran\n\n` +
                 `*Barang:*\n${listBarang}\n` +
                 `*TOTAL: Rp ${result.total.toLocaleString('id-ID')}*\n` +
                 `--------------------------------\n` +
                 `Saya sudah melakukan pembayaran. Mohon divalidasi.`;

    const whatsappUrl = `https://wa.me/628971566371?text=${encodeURIComponent(teks)}`;
    localStorage.removeItem(getCartKey());
    window.location.href = whatsappUrl;
}

document.addEventListener('DOMContentLoaded', loadCheckout);

// Fungsi untuk menutup modal
window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        
        // Kembalikan tombol submit ke keadaan semula agar user bisa klik ulang
        const submitBtn = document.getElementById('submit-btn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerText = "Sahkan Pesanan";
        }
    }
}
window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        const submitBtn = document.getElementById('submit-btn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerText = "Sahkan Pesanan";
        }
    }
};