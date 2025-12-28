<?php
include 'config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $file = $_FILES['proof_image'];
    
    // Pastikan path assets benar (pakai path relatif dari file ini)
    $target_dir = "assets/uploads/proofs/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $file_name = "proof_" . $order_id . "_" . time() . "." . $ext;
    
    if (move_uploaded_file($file['tmp_name'], $target_dir . $file_name)) {
        // UPDATE kolom proof_image berdasarkan ID yang baru dibuat
        $update = mysqli_query($conn, "UPDATE orders SET proof_image = '$file_name', status = 'verifying' WHERE id = '$order_id'");
        
        if($update) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal update database: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal upload file ke folder']);
    }
}
?>