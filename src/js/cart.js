/**
 * SISTEM KERANJANG PRIVATE - SPIRIT GUIDE
 * Fungsi: Memastikan setiap user memiliki wadah penyimpanan masing-masing.
 */

// 1. FUNGSI PENENTU WADAH (STORAGE KEY)
// Fungsi ini membuat nama kunci unik, contoh: 'sg_cart_ail' atau 'sg_cart_guest'
// Di file src/js/cart.js
function getCartKey() {
    // Jika login 'ail', return 'sg_cart_ail'
    // Jika tidak login, return 'sg_cart_guest'
    const currentUID = window.USER_ID || 'guest';
    return 'sg_cart_' + currentUID;
}
// 2. FUNGSI TAMBAH KE KERANJANG
function addToCart(item) {
    const cartKey = getCartKey(); // Ambil kunci unik user aktif
    try {
        // Ambil data lama dari wadah user tersebut, jika belum ada buat array kosong []
        let cart = JSON.parse(localStorage.getItem(cartKey) || '[]');
        
        // Cek apakah produk yang diklik sudah ada di keranjang user ini
        const idx = cart.findIndex(c => c.id == item.id);
        
        if (idx > -1) {
            cart[idx].qty += 1; // Jika sudah ada, cukup tambah jumlahnya
        } else {
            // Jika produk baru, masukkan object produk ke dalam array
            cart.push({
                id: item.id,
                title: item.title,
                price: parseFloat(item.price) || 0,
                img: item.img,
                qty: 1
            });
        }

        // Simpan kembali ke LocalStorage sesuai kunci unik user
        localStorage.setItem(cartKey, JSON.stringify(cart));
        
        // Update angka merah (badge) di icon keranjang secara real-time
        if (window.updateCartBadge) window.updateCartBadge();
        
        alert(item.title + " berhasil masuk ke keranjang pribadi Anda!");
    } catch (e) {
        console.error("Gagal simpan keranjang:", e);
    }
}

// 3. FUNGSI UPDATE ANGKA (BADGE) NAVBAR
window.updateCartBadge = function() {
    const cartKey = getCartKey(); // Pastikan mengambil data dari wadah user yang benar
    const cart = JSON.parse(localStorage.getItem(cartKey) || '[]');
    
    // Menjumlahkan seluruh qty yang ada di dalam keranjang user
    const totalQty = cart.reduce((total, item) => total + (item.qty || 0), 0);
    
    const badgeDesktop = document.getElementById('cart-badge-desktop');
    const badgeMobile = document.getElementById('cart-badge-mobile');

    const render = (el) => {
        if (el) {
            if (totalQty > 0) {
                el.textContent = totalQty; // Tampilkan angka jika > 0
                el.classList.remove('hidden');
                el.classList.add('animate-bounce-cart'); // Animasi membal saat bertambah
            } else {
                el.classList.add('hidden'); // Sembunyikan jika kosong
            }
        }
    };

    render(badgeDesktop);
    render(badgeMobile);
};

// Pastikan angka keranjang langsung muncul saat halaman pertama kali dibuka
document.addEventListener('DOMContentLoaded', window.updateCartBadge);