<?php
include 'config/db.php'; 
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Ambil & Bersihkan Data Input
    $name    = isset($_POST['nama']) ? mysqli_real_escape_string($conn, $_POST['nama']) : '';
    $phone   = isset($_POST['whatsapp']) ? mysqli_real_escape_string($conn, $_POST['whatsapp']) : ''; 
    $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';
    $payment = isset($_POST['payment']) ? mysqli_real_escape_string($conn, $_POST['payment']) : '';
    $cart_json = isset($_POST['cart_data']) ? $_POST['cart_data'] : '[]';
    $cart = json_decode($cart_json, true);

    if (empty($cart)) {
        die(json_encode(['status' => 'error', 'message' => 'Keranjang kosong']));
    }

    // 2. CEK STATUS MEMBER (Server-Side Logic)
    $isLoggedIn = isset($_SESSION['user']['id']);
    $total_final = 0;
    
    // Array untuk menyimpan data item yang sudah divalidasi harganya
    $validatedItems = [];

    // 3. VALIDASI HARGA & STOK (Looping ke Database)
    foreach ($cart as $item) {
        $id = (int)$item['id'];
        $qty = (int)$item['qty'];

        // Ambil harga ASLI dari database (Jangan percaya harga dari JS)
        $query = $conn->query("SELECT id, price, member_price, stock, title FROM products WHERE id = $id");
        
        if ($query->num_rows > 0) {
            $product = $query->fetch_assoc();
            
            // Cek Stok
            if ($product['stock'] < $qty) {
                die(json_encode(['status' => 'error', 'message' => "Stok {$product['title']} tidak cukup!"]));
            }

            // Tentukan Harga: Kalau login & ada harga member => Pakai Harga Member
            $realPrice = $product['price']; // Default harga normal
            if ($isLoggedIn && $product['member_price'] > 0 && $product['member_price'] < $product['price']) {
                $realPrice = $product['member_price'];
            }

            // Hitung subtotal valid
            $total_final += ($realPrice * $qty);

            // Simpan data valid untuk insert nanti
            $validatedItems[] = [
                'id' => $product['id'],
                'qty' => $qty,
                'price' => $realPrice // Harga yang sudah disahkan
            ];
        }
    }

    // 4. INSERT KE TABEL ORDERS
    $current_user_id = $isLoggedIn ? $_SESSION['user']['id'] : "NULL";
    
    $sql = "INSERT INTO orders (user_id, name, phone, address, total, payment_method, status, proof_image, created_at) 
            VALUES ($current_user_id, '$name', '$phone', '$address', '$total_final', '$payment', 'pending', '', NOW())";

    if (mysqli_query($conn, $sql)) {
        $order_id = mysqli_insert_id($conn);

        // 5. INSERT KE ORDER_ITEMS & UPDATE STOK
        foreach ($validatedItems as $item) {
            $p_id = $item['id'];
            $qty  = $item['qty'];
            $prc  = $item['price']; // Harga valid dari server
            
            // Simpan detail item
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, qty, price) VALUES ('$order_id', '$p_id', '$qty', '$prc')");
            
            // Kurangi stok
            mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE id = '$p_id'");
        }

        // Kembalikan total yang benar ke JS untuk ditampilkan di WA/Success Page
        echo json_encode(['status' => 'success', 'order_id' => $order_id, 'total' => $total_final]);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
}
?>