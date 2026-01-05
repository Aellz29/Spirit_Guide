/**
 * CHECKOUT LOGIC - FINAL
 */

let pendingOrderData = null;

function getCartKey() {
    // Ambil ID dari PHP. Jika tidak ada, pakai 'guest'
    const uid = (typeof window.USER_ID !== 'undefined' && window.USER_ID !== '') ? window.USER_ID : 'guest';
    return 'sg_cart_' + uid;
}

function loadCheckout() {
    const cart = JSON.parse(localStorage.getItem(getCartKey()) || '[]');
    const container = document.getElementById('checkout-items');
    
    if (!container) return;
    
    if (cart.length === 0) {
        container.innerHTML = `
            <div class="text-center py-10 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                <i class="fa fa-shopping-basket text-4xl text-gray-300 mb-3"></i>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Keranjang Kosong</p>
                <a href="index.php" class="mt-4 inline-block text-[10px] font-bold text-amber-500 hover:underline">Belanja Dulu Yuk</a>
            </div>`;
        updateSummary(0, 0);
        return;
    }

    let totalBayar = 0;
    let totalAsli = 0;
    let html = '';

    cart.forEach((item, index) => {
        let price = parseFloat(item.price);
        // Kalau data lama gak punya originalPrice, samakan dengan price
        let original = parseFloat(item.originalPrice) || price;
        if (original < price) original = price;

        const subItem = price * item.qty;
        const subAsli = original * item.qty;
        
        totalBayar += subItem;
        totalAsli += subAsli;

        // LOGIKA TAMPILAN DISKON
        let displayHarga = '';
        let badge = '';

        if (original > price) {
            let hematPersen = Math.round(((original - price) / original) * 100);
            badge = `<span class="absolute top-0 left-0 bg-red-600 text-white text-[8px] font-black px-1.5 py-0.5 rounded-br-lg shadow-sm">-${hematPersen}%</span>`;
            
            displayHarga = `
                <div class="text-right">
                    <p class="text-[10px] text-gray-400 line-through">Rp ${subAsli.toLocaleString('id-ID')}</p>
                    <p class="text-[12px] font-bold text-red-600">Rp ${subItem.toLocaleString('id-ID')}</p>
                </div>`;
        } else {
            displayHarga = `<p class="text-[12px] font-bold text-gray-900">Rp ${subItem.toLocaleString('id-ID')}</p>`;
        }

        html += `
            <div class="flex items-start gap-4 border-b border-gray-100 pb-4 last:border-0 hover:bg-gray-50 p-2 rounded-lg transition">
                <div class="w-16 h-16 bg-white rounded-lg border border-gray-200 relative overflow-hidden shrink-0">
                    <img src="${item.img}" class="w-full h-full object-cover">
                    ${badge}
                </div>
                
                <div class="flex-1 min-w-0">
                    <h4 class="text-[11px] font-bold uppercase tracking-tight text-gray-800 line-clamp-1">${item.title}</h4>
                    <div class="flex items-center gap-2 mt-1 mb-2">
                        <span class="text-[10px] text-gray-500 bg-gray-100 px-2 py-0.5 rounded">@ Rp ${price.toLocaleString('id-ID')}</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <button onclick="changeQty(${index}, -1)" class="w-6 h-6 flex items-center justify-center bg-gray-100 rounded text-xs font-bold hover:bg-gray-200 transition">-</button>
                        <span class="text-[11px] font-bold w-4 text-center">${item.qty}</span>
                        <button onclick="changeQty(${index}, 1)" class="w-6 h-6 flex items-center justify-center bg-gray-100 rounded text-xs font-bold hover:bg-gray-200 transition">+</button>
                    </div>
                </div>
                ${displayHarga}
            </div>`;
    });

    container.innerHTML = html;
    updateSummary(totalBayar, totalAsli);
}

function updateSummary(totalBayar, totalAsli) {
    const hemat = totalAsli - totalBayar;
    
    document.getElementById('subtotal').innerText = `Rp ${totalAsli.toLocaleString('id-ID')}`;
    document.getElementById('total-price').innerText = `Rp ${totalBayar.toLocaleString('id-ID')}`;
    
    // Logic Baris "Kamu Hemat"
    let savingsRow = document.getElementById('savings-row');
    const summaryArea = document.getElementById('summary-area');
    
    if (hemat > 0) {
        if (!savingsRow && summaryArea) {
            const row = document.createElement('div');
            row.id = 'savings-row';
            row.className = 'flex justify-between text-xs uppercase tracking-widest text-green-600 font-bold mt-2 pb-2 border-b border-dashed border-green-200 animate-pulse';
            row.innerHTML = `<span><i class="fa fa-tag mr-1"></i> Kamu Hemat</span><span id="savings-val"></span>`;
            
            // Masukkan sebelum Total Bayar
            const totalRow = document.getElementById('total-row');
            summaryArea.insertBefore(row, totalRow);
        }
        if(document.getElementById('savings-val')) {
            document.getElementById('savings-val').innerText = `- Rp ${hemat.toLocaleString('id-ID')}`;
        }
    } else {
        if (savingsRow) savingsRow.remove();
    }
}

window.changeQty = function(index, delta) {
    let cart = JSON.parse(localStorage.getItem(getCartKey()) || '[]');
    cart[index].qty += delta;
    if (cart[index].qty <= 0) cart.splice(index, 1);
    localStorage.setItem(getCartKey(), JSON.stringify(cart));
    loadCheckout();
    if(window.updateCartBadge) window.updateCartBadge();
}

// Logic Submit
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const cartData = localStorage.getItem(getCartKey());
    if (!cartData || JSON.parse(cartData).length === 0) return alert("Keranjang kosong!");

    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = true;
    submitBtn.innerText = 'Memproses...';

    const paymentType = document.querySelector('input[name="payment_type"]:checked').value;
    const bankDetail = document.getElementById('bank_list').value;
    let finalPayment = paymentType === 'QRIS' ? 'QRIS' : `Transfer ${bankDetail}`;

    const formData = new FormData(this);
    formData.append('cart_data', cartData); 
    formData.append('payment', finalPayment);

    fetch('proses_pesanan.php', { method: 'POST', body: formData })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            pendingOrderData = { result, cart: JSON.parse(cartData), finalPayment };
            if (paymentType === 'QRIS') {
                document.getElementById('qris-modal').classList.remove('hidden');
                document.getElementById('qris-modal').classList.add('flex');
            } else {
                document.getElementById('modal-bank-detail').innerText = bankDetail;
                document.getElementById('bank-modal').classList.remove('hidden');
                document.getElementById('bank-modal').classList.add('flex');
            }
        } else {
            alert("Gagal: " + result.message);
        }
    })
    .catch(err => { alert("Error koneksi."); })
    .finally(() => { 
        submitBtn.disabled = false; 
        submitBtn.innerText = "Buat Pesanan";
    });
});

async function finalSubmitWhatsApp(inputId) {
    const fileInput = document.getElementById(inputId);
    if (!fileInput.files[0]) return alert("Harap upload bukti transfer!");
    
    event.target.disabled = true;
    event.target.innerText = "Mengirim...";

    const { result, cart, finalPayment } = pendingOrderData;
    const formData = new FormData();
    formData.append('order_id', result.order_id);
    formData.append('proof_image', fileInput.files[0]);

    try {
        const upload = await fetch('upload_bukti.php', { method: 'POST', body: formData });
        const uploadRes = await upload.json();

        if (uploadRes.status === 'success') {
            let listBarang = cart.map(item => `- ${item.title} (x${item.qty})`).join('\n');
            const teks = `*ORDER BARU #${result.order_id}*\n------------------\n${listBarang}\n------------------\n*Total Bayar: Rp ${parseInt(result.total).toLocaleString('id-ID')}*\n*Metode:* ${finalPayment}\n\n*Status:* Bukti bayar sudah diupload.\nMohon diproses, Terima kasih!`;
            localStorage.removeItem(getCartKey());
            if (window.updateCartBadge) window.updateCartBadge();
            window.location.href = `https://wa.me/628971566371?text=${encodeURIComponent(teks)}`;
        } else {
            alert("Gagal upload: " + uploadRes.message);
            event.target.disabled = false;
        }
    } catch (err) {
        alert("Error sistem.");
        event.target.disabled = false;
    }
}

window.closeModal = function(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}

document.addEventListener('DOMContentLoaded', loadCheckout);