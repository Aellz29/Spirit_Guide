<?php
include 'config/db.php'; 
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = mysqli_real_escape_string($conn, $_POST['nama']);
    $phone   = mysqli_real_escape_string($conn, $_POST['whatsapp']); 
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $cart_data = json_decode($_POST['cart_data'], true);

    $items_processed = []; 
    $total_final = 0;

    // Filter barang yang Ready Stock
    foreach ($cart_data as $item) {
        $p_id = mysqli_real_escape_string($conn, $item['id']);
        $res = mysqli_query($conn, "SELECT stock, title FROM products WHERE id = '$p_id'");
        $prod = mysqli_fetch_assoc($res);

        if ($prod && $prod['stock'] >= $item['qty']) {
            $items_processed[] = $item;
            $total_final += ($item['price'] * $item['qty']);
        }
    }

    if (empty($items_processed)) {
        echo json_encode(['status' => 'error', 'message' => 'Stok habis, tidak ada barang yang bisa dipesan.']);
        exit;
    }

    $user_id = $_SESSION['user_id'] ?? 'NULL';
    $query_order = "INSERT INTO orders (user_id, name, phone, address, total, status) 
                    VALUES ($user_id, '$name', '$phone', '$address', '$total_final', 'pending')";

    if (mysqli_query($conn, $query_order)) {
        $order_id = mysqli_insert_id($conn);
        foreach ($items_processed as $item) {
            $p_id = $item['id'];
            $qty = $item['qty'];
            $price = $item['price'];

            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, qty, price) VALUES ('$order_id', '$p_id', '$qty', '$price')");
            mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE id = '$p_id'");
        }

        // Respon sukses dengan data untuk WhatsApp
        echo json_encode([
            'status' => 'success', 
            'order_id' => $order_id, 
            'processed_items' => $items_processed, 
            'total' => $total_final
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
}
?>