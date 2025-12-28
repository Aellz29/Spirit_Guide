<?php
session_start();
include 'config/db.php'; // Pastikan path ini benar sesuai folder kamu

// 1. Ambil data dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
$to = isset($_GET['to']) ? $_GET['to'] : null;

if ($id && $to) {
    // 2. Update status di database (Gunakan tanda petik pada variabel string $to)
    $sql = "UPDATE orders SET status = '$to' WHERE id = '$id'";
    
    if ($conn->query($sql)) {
        // 3. Jika berhasil, balikkan ke halaman admin_orders
        header("Location: admin_orders.php?status=updated");
    } else {
        // Jika error, tampilkan pesan errornya
        echo "Error: " . $conn->error;
    }
} else {
    echo "Data ID atau Status tidak ditemukan!";
}
exit;
?>