<?php
include 'config/db.php'; 
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = isset($_POST['nama']) ? mysqli_real_escape_string($conn, $_POST['nama']) : '';
    $phone   = isset($_POST['whatsapp']) ? mysqli_real_escape_string($conn, $_POST['whatsapp']) : ''; 
    $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';
    $payment = isset($_POST['payment']) ? mysqli_real_escape_string($conn, $_POST['payment']) : '';
    $cart_json = isset($_POST['cart_data']) ? $_POST['cart_data'] : '[]';
    $cart = json_decode($cart_json, true);

    if (empty($cart)) {
        die(json_encode(['status' => 'error', 'message' => 'Keranjang kosong']));
    }

    $isLoggedIn = isset($_SESSION['user']['id']);
    $total_final = 0;
    $validatedItems = [];

    // HITUNG ULANG HARGA & STOK (SERVER SIDE VALIDATION)
    foreach ($cart as $item) {
        $id = (int)$item['id'];
        $qty = (int)$item['qty'];

        $query = $conn->query("SELECT id, price, member_price, stock, title FROM products WHERE id = $id");
        if ($query->num_rows > 0) {
            $product = $query->fetch_assoc();
            if ($product['stock'] < $qty) {
                die(json_encode(['status' => 'error', 'message' => "Stok {$product['title']} tidak cukup!"]));
            }
            $realPrice = $product['price'];
            if ($isLoggedIn && $product['member_price'] > 0 && $product['member_price'] < $product['price']) {
                $realPrice = $product['member_price'];
            }
            $total_final += ($realPrice * $qty);
            $validatedItems[] = ['id' => $product['id'], 'qty' => $qty, 'price' => $realPrice];
        }
    }

    // MEMBUAT ORDER ID UNIK (Contoh: ORD-20240108-123)
    $order_id_unik = "ORD-" . date('Ymd') . "-" . rand(100, 999);
    $current_user_id = $isLoggedIn ? $_SESSION['user']['id'] : "NULL";
    
    // UPDATE: Kolom 'total' diganti menjadi 'total_price' dan 'order_id' dimasukkan
    $sql = "INSERT INTO orders (order_id, user_id, name, phone, address, total_price, payment_method, status, proof_image, created_at) 
            VALUES ('$order_id_unik', $current_user_id, '$name', '$phone', '$address', '$total_final', '$payment', 'Pending', '', NOW())";

    if (mysqli_query($conn, $sql)) {
        $last_id = mysqli_insert_id($conn);
        foreach ($validatedItems as $item) {
            $p_id = $item['id'];
            $qty  = $item['qty'];
            $prc  = $item['price'];
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, qty, price) VALUES ('$last_id', '$p_id', '$qty', '$prc')");
            mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE id = '$p_id'");
        }
        echo json_encode(['status' => 'success', 'order_id' => $order_id_unik, 'total' => $total_final]);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
}
?>