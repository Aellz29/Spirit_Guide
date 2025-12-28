<?php
include 'config/db.php'; 
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Ambil & Bersihkan Data
    $name    = isset($_POST['nama']) ? mysqli_real_escape_string($conn, $_POST['nama']) : '';
    $phone   = isset($_POST['whatsapp']) ? mysqli_real_escape_string($conn, $_POST['whatsapp']) : ''; 
    $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';
    $payment = isset($_POST['payment']) ? mysqli_real_escape_string($conn, $_POST['payment']) : '';
    $cart_json = isset($_POST['cart_data']) ? $_POST['cart_data'] : '[]';
    $cart = json_decode($cart_json, true);

    if (empty($cart)) {
        die(json_encode(['status' => 'error', 'message' => 'Keranjang kosong']));
    }

    // 2. Hitung Total
    $total_final = 0;
    foreach ($cart as $item) {
        $price = is_string($item['price']) ? (int)preg_replace('/[^0-9]/', '', $item['price']) : $item['price'];
        $total_final += ($price * $item['qty']);
    }

    // 3. User ID
    $current_user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
    $db_user_id = ($current_user_id) ? $current_user_id : "NULL";

    // 4. QUERY INSERT (Urutan Kolom Sesuai Screenshot Lu)
    $sql = "INSERT INTO orders (user_id, name, phone, address, total, payment_method, status, proof_image, created_at) 
            VALUES ($db_user_id, '$name', '$phone', '$address', '$total_final', '$payment', 'pending', '', NOW())";

    if (mysqli_query($conn, $sql)) {
        $order_id = mysqli_insert_id($conn);

        // 5. Simpan Item & Update Stok (Kalo ini udah jalan, kita keep)
        foreach ($cart as $item) {
            $p_id = mysqli_real_escape_string($conn, $item['id']);
            $qty  = (int)$item['qty'];
            $prc  = is_string($item['price']) ? (int)preg_replace('/[^0-9]/', '', $item['price']) : $item['price'];
            
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, qty, price) VALUES ('$order_id', '$p_id', '$qty', '$prc')");
            mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE id = '$p_id'");
        }

        echo json_encode(['status' => 'success', 'order_id' => $order_id, 'total' => $total_final]);
    } else {
        // JIKA GAGAL, KITA TAMPILKAN ERROR MYSQL-NYA
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
}
?>