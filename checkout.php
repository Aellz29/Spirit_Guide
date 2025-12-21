<?php
session_start();
require "config/db.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // ambil body (cart dikirim dari client)
  $cartJson = $_POST['cart'] ?? '[]';
  $name = trim($_POST['name']);
  $phone = trim($_POST['phone']);
  $address = trim($_POST['address']);
  $cart = json_decode($cartJson, true);
  if(empty($cart) || !$name || !$phone || !$address){
    $err = "Data tidak lengkap.";
  } else {
    // hitung total
    $total = 0;
    foreach($cart as $it) $total += $it['price'] * $it['qty'];

    // simpan order
    $uid = $_SESSION['user']['id'] ?? null;
    $ins = $conn->prepare("INSERT INTO orders (user_id,name,phone,address,total) VALUES (?,?,?,?,?)");
    $ins->bind_param("isssd",$uid,$name,$phone,$address,$total);
    if($ins->execute()){
      $order_id = $ins->insert_id;
      // simpan item
      $stmt = $conn->prepare("INSERT INTO order_items (order_id,product_id,qty,price) VALUES (?,?,?,?)");
      foreach($cart as $it){
        $stmt->bind_param("iiid",$order_id,$it['id'],$it['qty'],$it['price']);
        $stmt->execute();
        // kurangi stok
        $conn->query("UPDATE products SET stock = stock - ".intval($it['qty'])." WHERE id=".intval($it['id']));
      }
      header("Location: order_success.php?id=".$order_id);
      exit;
    } else $err = "Gagal menyimpan order.";
  }
}
?>
<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Checkout</title><link rel="stylesheet" href="src/css/style.css"></head>
<body class="bg-gray-50">
<?php include 'partials/navbar.php'; ?>
<section class="max-w-3xl mx-auto px-6 pt-32 pb-16">
  <h2 class="text-2xl font-bold mb-4">Checkout</h2>
  <?php if(!empty($err)): ?><div class="p-3 bg-red-200 text-red-800 mb-3"><?=$err?></div><?php endif; ?>

  <form id="checkoutForm" method="POST">
    <input type="hidden" name="cart" id="cartInput">
    <label>Nama</label>
    <input type="text" name="name" required>
    <label>Phone / WA</label>
    <input type="text" name="phone" required>
    <label>Alamat</label>
    <textarea name="address" rows="4" required></textarea>
    <button class="mt-3 px-4 py-2 bg-green-600 text-white rounded">Bayar & Pesan</button>
  </form>
</section>
<?php include 'partials/footer.php'; ?> 
<script src="src/js/cart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', ()=>{
  const cart = SGCart.getCart();
  if(cart.length===0){
    alert('Keranjang kosong.');
    location.href='cart.php';
    return;
  }
  document.getElementById('cartInput').value = JSON.stringify(cart);
});
</script>
</body>
</html>
