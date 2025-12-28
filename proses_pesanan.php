<?php
include 'config/db.php'; 
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan database terkoneksi
    if (!$conn) {
        echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal']);
        exit;
    }

    // Ambil data dari POST (pastikan nama field sesuai dengan di checkout.php)
    $name    = mysqli_real_escape_string($conn, $_POST['nama']);
    $phone   = mysqli_real_escape_string($conn, $_POST['whatsapp']); 
    $address = mysqli_real_escape_string($conn, $_POST['address']); // Sesuaikan dengan name="address"
    
    // Default payment jika inputnya tidak ada di HTML
    $payment = isset($_POST['payment']) ? mysqli_real_escape_string($conn, $_POST['payment']) : 'WhatsApp Transfer';
    
    $cart_data = json_decode($_POST['cart_data'], true);

    if (empty($cart_data)) {
        echo json_encode(['status' => 'error', 'message' => 'Data keranjang tidak terbaca']);
        exit;
    }

    $total_final = 0;
    foreach ($cart_data as $item) {
        // Membersihkan harga jika masih dalam bentuk string dengan titik/Rp
        $price = is_string($item['price']) ? (int)preg_replace('/[^0-9]/', '', $item['price']) : $item['price'];
        $total_final += ($price * $item['qty']);
    }

    $user_id = $_SESSION['user']['id'] ?? "NULL";

    $query_order = "INSERT INTO orders (user_id, name, phone, address, total, payment_method, status, created_at) 
                    VALUES ($user_id, '$name', '$phone', '$address', '$total_final', '$payment', 'verifying', NOW())";

    if (mysqli_query($conn, $query_order)) {
        $order_id = mysqli_insert_id($conn);
        foreach ($cart_data as $item) {
            $p_id = $item['id'];
            $qty = $item['qty'];
            $price = is_string($item['price']) ? (int)preg_replace('/[^0-9]/', '', $item['price']) : $item['price'];
            
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, qty, price) VALUES ('$order_id', '$p_id', '$qty', '$price')");
            mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE id = '$p_id'");
        }
        
        // SANGAT PENTING: Kirim kembali total_final agar JavaScript tidak error
        echo json_encode([
            'status' => 'success', 
            'order_id' => $order_id,
            'total' => $total_final
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
}
?>