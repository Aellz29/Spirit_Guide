// File: src/js/cart.js

function getCartKey() {
    const currentUID = window.USER_ID || 'guest';
    return 'sg_cart_' + currentUID;
}

function addToCart(item) {
    const cartKey = getCartKey();
    try {
        let cart = JSON.parse(localStorage.getItem(cartKey) || '[]');
        const idx = cart.findIndex(c => c.id == item.id);
        
        // 1. Pastikan harga berupa ANGKA
        let finalPrice = parseFloat(item.price);
        // 2. TANGKAP HARGA ASLI (Ini yang dulu hilang)
        let originalPrice = parseFloat(item.originalPrice) || 0; 

        if (idx > -1) {
            cart[idx].qty += 1;
            cart[idx].price = finalPrice;
            cart[idx].originalPrice = originalPrice; // Update harga jika berubah
        } else {
            cart.push({
                id: item.id,
                title: item.title,
                price: finalPrice,
                originalPrice: originalPrice, // SIMPAN KE MEMORI
                img: item.img,
                qty: 1
            });
        }

        localStorage.setItem(cartKey, JSON.stringify(cart));
        
        if (window.updateCartBadge) window.updateCartBadge();
        
        alert(`Berhasil! ${item.title} masuk keranjang.`);
    } catch (e) {
        console.error("Gagal simpan keranjang:", e);
    }
}

// Update Badge (Tetap sama)
window.updateCartBadge = function() {
    const cartKey = getCartKey(); 
    const cart = JSON.parse(localStorage.getItem(cartKey) || '[]');
    const totalQty = cart.reduce((total, item) => total + (item.qty || 0), 0);
    const badgeDesktop = document.getElementById('cart-badge-desktop');
    const badgeMobile = document.getElementById('cart-badge-mobile');
    const render = (el) => {
        if (el) {
            if (totalQty > 0) {
                el.textContent = totalQty;
                el.classList.remove('hidden');
                el.classList.add('animate-bounce-cart');
            } else {
                el.classList.add('hidden');
            }
        }
    };
    render(badgeDesktop);
    render(badgeMobile);
};
document.addEventListener('DOMContentLoaded', window.updateCartBadge);