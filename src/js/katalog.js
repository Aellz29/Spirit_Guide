// File: src/js/katalog.js

// 1. FUNGSI BUKA MODAL
function openModal(img, title, priceFormatted, originalPriceFormatted, desc, stock, id, rawPrice, isFlash, isMember) {
    const modal = document.getElementById('productModal');
    if (!modal) return;

    document.getElementById('modalImg').src = img;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalPrice').textContent = 'Rp ' + priceFormatted;
    document.getElementById('modalDesc').textContent = desc || "-";

    // Harga Coret
    const originalEl = document.getElementById('modalOriginalPrice');
    if (originalEl) {
        if (originalPriceFormatted && originalPriceFormatted !== '' && originalPriceFormatted !== '0') {
            originalEl.textContent = 'Rp ' + originalPriceFormatted;
            originalEl.classList.remove('hidden');
        } else {
            originalEl.classList.add('hidden');
        }
    }

    // Badges
    const badgeContainer = document.getElementById('modalBadges');
    if (badgeContainer) {
        badgeContainer.innerHTML = '';
        if (isFlash) badgeContainer.innerHTML += `<span class="bg-red-600 text-white text-[9px] px-2 py-1 rounded font-bold mr-1">FLASH SALE</span>`;
        if (isMember) badgeContainer.innerHTML += `<span class="bg-blue-600 text-white text-[9px] px-2 py-1 rounded font-bold">MEMBER</span>`;
    }

    // Stok Status
    const statusEl = document.getElementById('statusStock');
    if (statusEl) {
        const isReady = parseInt(stock) > 0;
        statusEl.innerText = isReady ? "Ready Stock" : "Habis";
        statusEl.className = isReady ? "text-green-600 font-bold ml-1" : "text-red-600 font-bold ml-1";
    }

    // Update Tombol Add to Cart
    const modalBtn = document.getElementById('modalAddToCartBtn');
    if (modalBtn) {
        const newBtn = modalBtn.cloneNode(true);
        modalBtn.parentNode.replaceChild(newBtn, modalBtn);
        
        newBtn.onclick = function() {
            let rawOriginal = 0;
            if (originalPriceFormatted) {
                rawOriginal = parseFloat(originalPriceFormatted.replace(/\./g, '').replace(/,/g, ''));
            }
            window.addToCart({
                id: id, 
                title: title, 
                price: rawPrice, 
                originalPrice: rawOriginal, 
                img: img
            });
        };
    }

    // Load Review
    fetchReviews(id);

    // Tampilkan
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// 2. FUNGSI LOAD REVIEW
async function fetchReviews(productId) {
    const container = document.getElementById('review-list');
    const idInput = document.getElementById('review_product_id');
    
    // Set ID Produk ke form input hidden biar pas submit ID-nya kebawa
    if (idInput) idInput.value = productId;
    
    if (!container) return;
    container.innerHTML = '<p class="text-[10px] text-gray-400 italic">Memuat ulasan...</p>';

    try {
        const response = await fetch(`get_reviews.php?id=${productId}`);
        const reviews = await response.json();

        if (reviews.length === 0) {
            container.innerHTML = '<p class="text-[10px] text-gray-400 italic">Belum ada ulasan.</p>';
            return;
        }

        container.innerHTML = reviews.map(r => `
            <div class="border-b border-gray-100 pb-3 mb-3 last:border-0">
                <div class="flex justify-between items-center mb-1">
                    <span class="font-bold text-xs uppercase text-gray-900">${r.username}</span>
                    <span class="text-amber-500 text-[10px] font-bold">★ ${r.rating}</span>
                </div>
                <p class="text-[11px] text-gray-600 leading-snug">${r.comment}</p>
                <p class="text-[9px] text-gray-300 mt-1">${r.created_at}</p>
            </div>
        `).join('');
    } catch (err) {
        console.error("Gagal load review", err);
        container.innerHTML = '<p class="text-[10px] text-red-400">Gagal memuat ulasan.</p>';
    }
}

// 3. FUNGSI KIRIM REVIEW
document.getElementById('reviewForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const submitBtn = this.querySelector('button[type="submit"]');
    
    // Cek apakah user sudah login (biasanya form gak muncul kalau belum login, tapi buat jaga-jaga)
    const productId = document.getElementById('review_product_id').value;
    if(!productId) return alert("Terjadi kesalahan sistem (ID Produk hilang).");

    submitBtn.disabled = true;
    
    try {
        const formData = new FormData(this);
        const res = await fetch('submit_review.php', { method: 'POST', body: formData });
        const result = await res.json();
        
        if (result.status === 'success') {
            // FIX: Gunakan [name="comment"] agar cocok untuk input maupun textarea
            const inputKomen = this.querySelector('[name="comment"]');
            if(inputKomen) inputKomen.value = ''; 
            
            // Refresh list review
            fetchReviews(productId);
        } else {
            alert('Gagal: ' + (result.message || 'Terjadi kesalahan'));
        }
    } catch (error) {
        alert('Error koneksi ke server.');
    } finally {
        submitBtn.disabled = false;
    }
});

function closeModal() {
    const modal = document.getElementById('productModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target == modal) closeModal();
}