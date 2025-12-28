let pendingOrderData = null;

function getCartKey() {
    const currentUID = window.USER_ID || 'guest';
    return 'sg_cart_' + currentUID;
}

function loadCheckout() {
    const cart = JSON.parse(localStorage.getItem(getCartKey()) || '[]');
    const container = document.getElementById('checkout-items');
    let total = 0;
    let html = '';

    if (!container) return;
    if (cart.length === 0) {
        container.innerHTML = '<p class="text-center py-10 uppercase text-[10px] tracking-widest">Keranjang Kosong</p>';
        return;
    }

    cart.forEach((item, index) => {
        let price = typeof item.price === 'string' ? parseInt(item.price.replace(/[^0-9]/g, '')) : item.price;
        total += (price * item.qty);
        html += `
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div class="flex items-center gap-4">
                    <img src="${item.img}" class="w-16 h-20 object-cover rounded">
                    <div>
                        <h4 class="text-[10px] font-bold uppercase tracking-widest">${item.title}</h4>
                        <div class="flex items-center gap-2 mt-2">
                            <button onclick="changeQty(${index}, -1)" class="w-6 h-6 border flex items-center justify-center hover:bg-black hover:text-white">-</button>
                            <span class="text-[10px] font-bold">${item.qty}</span>
                            <button onclick="changeQty(${index}, 1)" class="w-6 h-6 border flex items-center justify-center hover:bg-black hover:text-white">+</button>
                        </div>
                    </div>
                </div>
                <p class="text-[11px] font-bold tracking-widest">Rp ${(price * item.qty).toLocaleString('id-ID')}</p>
            </div>`;
    });
    container.innerHTML = html;
    document.getElementById('total-price').innerText = `Rp ${total.toLocaleString('id-ID')}`;
    document.getElementById('subtotal').innerText = `Rp ${total.toLocaleString('id-ID')}`;
}

// HANDLER SUBMIT UTAMA
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault(); // MENCEGAH REFRESH HALAMAN
    
    const cartData = localStorage.getItem(getCartKey());
    if (!cartData || JSON.parse(cartData).length === 0) {
        alert("Keranjang kosong!");
        return false;
    }

    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = true;
    submitBtn.innerText = "SEDANG MEMPROSES...";

    const paymentType = document.querySelector('input[name="payment_type"]:checked').value;
    const bankDetail = document.getElementById('bank_list').value;
    let finalPayment = paymentType === 'QRIS' ? 'QRIS' : `Transfer ${bankDetail}`;

    const formData = new FormData(this);
    formData.append('cart_data', cartData);
    formData.append('payment', finalPayment);

    // KIRIM DATA KE PHP TANPA REFRESH
    fetch('proses_pesanan.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            pendingOrderData = { result, cart: JSON.parse(cartData), finalPayment };
            
            // Buka Modal (Tanpa pindah halaman)
            if (paymentType === 'QRIS') {
                document.getElementById('qris-modal').classList.remove('hidden');
            } else {
                document.getElementById('modal-bank-detail').innerText = bankDetail;
                document.getElementById('bank-modal').classList.remove('hidden');
            }
        } else {
            alert("Error: " + result.message);
            submitBtn.disabled = false;
            submitBtn.innerText = "Sahkan Pesanan";
        }
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi kesalahan jaringan.");
        submitBtn.disabled = false;
    });
});

// UPLOAD BUKTI & WA
async function finalSubmitWhatsApp(inputId) {
    const fileInput = document.getElementById(inputId);
    if (!fileInput.files[0]) return alert("Harap upload bukti!");

    const { result, cart, finalPayment } = pendingOrderData;
    const formData = new FormData();
    formData.append('order_id', result.order_id);
    formData.append('proof_image', fileInput.files[0]);

    try {
        const upload = await fetch('upload_bukti.php', { method: 'POST', body: formData });
        const uploadRes = await upload.json();

        if (uploadRes.status === 'success') {
            let listBarang = cart.map(item => `- ${item.title} (x${item.qty})`).join('\n');
            const teks = `*ORDER #${result.order_id}*\n\n${listBarang}\n\n*Total:* Rp ${result.total.toLocaleString('id-ID')}\n*Metode:* ${finalPayment}\n\nBukti sudah diupload.`;
            
            localStorage.removeItem(getCartKey()); // Bersihin keranjang di akhir
            window.location.href = `https://wa.me/628971566371?text=${encodeURIComponent(teks)}`;
        } else {
            alert("Gagal upload bukti.");
        }
    } catch (err) {
        alert("Error sistem.");
    }
}

window.changeQty = function(index, delta) {
    let cart = JSON.parse(localStorage.getItem(getCartKey()) || '[]');
    cart[index].qty += delta;
    if (cart[index].qty <= 0) cart.splice(index, 1);
    localStorage.setItem(getCartKey(), JSON.stringify(cart));
    loadCheckout();
}

window.closeModal = function(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById('submit-btn').disabled = false;
    document.getElementById('submit-btn').innerText = "Sahkan Pesanan";
}

document.addEventListener('DOMContentLoaded', loadCheckout);