<?php
session_start();
require "config/db.php";
?>
<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Keranjang</title><link rel="stylesheet" href="src/css/style.css"></head>
<body class="bg-gray-50">
<?php include 'partials/navbar.php'; ?>

<section class="max-w-4xl mx-auto px-6 pt-32 pb-16">
  <h2 class="text-2xl font-bold mb-6">Keranjang Anda</h2>

  <div id="cartContainer"></div>

  <div class="mt-6">
    <a href="checkout.php" id="goCheckout" class="px-4 py-2 bg-green-600 text-white rounded" style="display:none;">Checkout</a>
    <a href="index.php" class="px-4 py-2 border rounded">Lanjut Belanja</a>
  </div>
</section>

<?php include 'partials/footer.php'; ?>

<script src="src/js/cart.js"></script>
<script>
function renderCart(){
  const cart = SGCart.getCart();
  const container = document.getElementById('cartContainer');
  if(cart.length===0){
    container.innerHTML = '<p>Keranjang kosong.</p>';
    document.getElementById('goCheckout').style.display='none';
    return;
  }
  let html = '<table style="width:100%;border-collapse:collapse"><thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead><tbody>';
  let total=0;
  cart.forEach(it=>{
    const sub = it.price * it.qty;
    total += sub;
    html += `<tr><td style="padding:8px"><img src="${it.img}" style="width:64px;height:64px;object-fit:cover;margin-right:8px;vertical-align:middle"> ${it.title}</td><td>Rp ${it.price.toLocaleString('id-ID')}</td><td>${it.qty}</td><td>Rp ${sub.toLocaleString('id-ID')}</td></tr>`;
  });
  html += `</tbody></table><p class="mt-4 font-bold">Total: Rp ${total.toLocaleString('id-ID')}</p>`;
  container.innerHTML = html;
  document.getElementById('goCheckout').style.display='inline-block';
}
document.addEventListener('DOMContentLoaded', renderCart);
</script>
</body>
</html>
