// cart.js — simpan di src/js/cart.js
(function(){
  function getCart(){ return JSON.parse(localStorage.getItem('sg_cart') || '[]'); }
  function saveCart(cart){ localStorage.setItem('sg_cart', JSON.stringify(cart)); }

  function addToCart(item){
    const cart = getCart();
    const idx = cart.findIndex(c=>c.id==item.id);
    if(idx>-1){ cart[idx].qty += item.qty; }
    else { cart.push(item); }
    saveCart(cart);
    alert(item.title + " ditambahkan ke keranjang");
  }

  // event listeners for katalog cards
  document.addEventListener('click', function(e){
    if(e.target.classList.contains('btn-add-cart')){
      const el = e.target;
      const item = {
        id: el.dataset.id,
        title: el.dataset.title,
        price: parseFloat(el.dataset.price),
        img: el.dataset.img,
        qty: 1
      };
      addToCart(item);
    }
    if(e.target.id === 'addToCartBtn'){
      const el = e.target;
      const item = {
        id: el.dataset.id,
        title: el.dataset.title,
        price: parseFloat(el.dataset.price),
        img: el.dataset.img,
        qty: 1
      };
      addToCart(item);
    }
  });

  // Expose small API for cart page
  window.SGCart = {
    getCart,
    saveCart,
    addToCart
  };
})();
