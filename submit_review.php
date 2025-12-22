<?php
include 'config/db.php';
session_start();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $p_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $user = $_SESSION['username'] ?? 'Guest';
    $u_id = $_SESSION['user_id'] ?? 'NULL';

    mysqli_query($conn, "INSERT INTO product_reviews (product_id, user_id, username, rating, comment) 
                         VALUES ('$p_id', $u_id, '$user', '$rating', '$comment')");
    echo json_encode(['status' => 'success']);
}