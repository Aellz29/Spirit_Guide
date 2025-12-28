<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'spiritguide_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
// Definisikan BASE_URL agar link tidak pecah
// Sesuaikan dengan nama folder di htdocs lu
define('BASE_URL', 'http://localhost/Website Spirit Guide/');
?>
