function openModal(img, title, priceFormatted, desc, stock, id, rawPrice) {
    const modal = document.getElementById('productModal');
    if(!modal) return;

    // Load Data Produk ke Modal
    document.getElementById('modalImg').src = img;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalPrice').textContent = 'Rp ' + priceFormatted;
    document.getElementById('modalDesc').textContent = desc || "Koleksi eksklusif Spirit Guide.";

    // Update Status Stok
    const statusEl = document.getElementById('statusStock');
    const isReady = parseInt(stock) > 0;
    statusEl.innerText = isReady ? "In Stock" : "Out of Stock";
    statusEl.className = isReady ? "text-green-600 font-bold text-[10px]" : "text-red-600 font-bold text-[10px]";

    // Simpan ID Produk untuk Form Review
    const idInput = document.getElementById('review_product_id');
    if(idInput) idInput.value = id;

    // Event Tombol Add to Cart
    const modalBtn = document.getElementById('modalAddToCartBtn');
    if (modalBtn) {
        modalBtn.onclick = function() {
            window.addToCart({id: id, title: title, price: rawPrice, img: img});
        };
    }

    // Tampilkan Modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden'; 

    // Ambil Review dari Database
    fetchReviews(id);
}

function closeModal() {
    const modal = document.getElementById('productModal');
    if(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

async function fetchReviews(productId) {
    const container = document.getElementById('review-list');
    if(!container) return;

    try {
        const response = await fetch(`get_reviews.php?id=${productId}`);
        const reviews = await response.json();

        if (reviews.length === 0) {
            container.innerHTML = '<p class="text-[10px] text-gray-400 italic">Belum ada ulasan.</p>';
            return;
        }

        container.innerHTML = reviews.map(r => `
            <div class="border-b border-gray-100 pb-2 mb-2">
                <div class="flex justify-between items-center text-[10px]">
                    <span class="font-bold uppercase">${r.username}</span>
                    <span class="text-amber-500">★ ${r.rating}/5</span>
                </div>
                <p class="text-[11px] text-gray-500 mt-1">${r.comment}</p>
            </div>
        `).join('');
    } catch (err) {
        console.error("Gagal memuat review", err);
    }
}

// Handler Submit Form Review
document.addEventListener('DOMContentLoaded', () => {
    const rForm = document.getElementById('reviewForm');
    if(rForm) {
        rForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const res = await fetch('submit_review.php', { method: 'POST', body: formData });
            const result = await res.json();
            
            if (result.status === 'success') {
                this.reset();
                fetchReviews(formData.get('product_id'));
            }
        });
    }
});

// Close Modal saat klik area luar
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target == modal) closeModal();
}
// Fungsi Pencarian Real-time (Tambahkan di katalog.js)
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('productSearch');
    const productCards = document.querySelectorAll('.product-card');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const filter = this.value.toUpperCase();
            
            productCards.forEach(card => {
                // Mencari teks di dalam tag h3 (Judul Produk)
                const title = card.querySelector('h3').textContent || card.querySelector('h3').innerText;
                
                if (title.toUpperCase().indexOf(filter) > -1) {
                    card.style.display = ""; // Tampilkan jika cocok
                } else {
                    card.style.display = "none"; // Sembunyikan jika tidak cocok
                }
            });
        });
    }
});