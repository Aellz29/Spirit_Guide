// Pastikan ini baris paling atas di cart.js
console.log("Cart.js Terdeteksi!");

function addToCart(item) {
    console.log("Fungsi addToCart terpanggil!", item);
    try {
        let cart = JSON.parse(localStorage.getItem('sg_cart') || '[]');
        
        const idx = cart.findIndex(c => c.id == item.id);
        if (idx > -1) {
            cart[idx].qty += 1;
        } else {
            cart.push({
                id: item.id,
                title: item.title,
                price: parseFloat(item.price) || 0,
                img: item.img,
                qty: 1
            });
        }

        localStorage.setItem('sg_cart', JSON.stringify(cart));
        
        // Update badge jika ada
        if (window.updateCartBadge) window.updateCartBadge();
        
        alert(item.title + " ditambahkan!");
    } catch (e) {
        console.error("Gagal simpan keranjang:", e);
    }
}

function updateCartBadge() {
    const cart = JSON.parse(localStorage.getItem('sg_cart') || '[]');
    const total = cart.reduce((sum, i) => sum + (i.qty || 0), 0);
    
    const b1 = document.getElementById('cart-badge-desktop');
    const b2 = document.getElementById('cart-badge-mobile');

    [b1, b2].forEach(el => {
        if (el) {
            el.textContent = total;
            total > 0 ? el.classList.remove('hidden') : el.classList.add('hidden');
        }
    });
}

window.updateCartBadge = function() {
    const cart = JSON.parse(localStorage.getItem('sg_cart') || '[]');
    const totalQty = cart.reduce((total, item) => total + (item.qty || 0), 0);
    
    const badgeDesktop = document.getElementById('cart-badge-desktop');
    const badgeMobile = document.getElementById('cart-badge-mobile');

    const render = (el) => {
        if (el) {
            if (totalQty > 0) {
                el.textContent = totalQty;
                el.classList.remove('hidden');
                
                // --- LOGIKA BOUNCE ---
                el.classList.remove('animate-bounce-cart');
                void el.offsetWidth; // Trigger reflow untuk restart animasi
                el.classList.add('animate-bounce-cart');
                
            } else {
                el.classList.add('hidden');
                el.classList.remove('animate-bounce-cart');
            }
        }
    };

    render(badgeDesktop);
    render(badgeMobile);
};