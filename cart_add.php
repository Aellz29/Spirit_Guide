<?php
require 'cart_helper.php';
require 'config/db.php';

if (!isset($_POST['product_id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_POST['product_id']);

// ambil data produk
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: index.php");
    exit;
}

$cart = getCart();

// jika produk sudah ada → tambah qty
if (isset($cart[$id])) {
    $cart[$id]['qty'] += 1;
} else {
    $cart[$id] = [
        'id'    => $product['id'],
        'title' => $product['title'],
        'price' => $product['price'],
        'image' => $product['image'],
        'qty'   => 1
    ];
}

saveCart($cart);

// balik ke halaman sebelumnya
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
