<?php
include 'config/db.php';
$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT * FROM product_reviews WHERE product_id = '$id' ORDER BY id DESC";
$res = mysqli_query($conn, $query);
$data = [];
while($row = mysqli_fetch_assoc($res)) { $data[] = $row; }
echo json_encode($data);