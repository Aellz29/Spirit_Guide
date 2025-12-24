<?php
session_start();

// proteksi: harus login dan role = admin
if (!isset($_SESSION['user']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['user']['username'] ?? ($_SESSION['user'] ?? 'Tidak diketahui');
$email = $_SESSION['user']['email'] ?? ($_SESSION['email'] ?? 'Belum ada email');
$role = $_SESSION['role'] ?? 'guest';

if ($role !== 'admin') {
    header("Location: index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin | Spirit Guide</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<link rel="stylesheet" href="style_dashboard.css">
</head>
<body>

<div class="wrapper">

  <!-- SIDEBAR -->
  <div class="sidebar">
    <div class="logo-box">
      <img src="./src/img/SpiritGuide.jpg" alt="Spirit Guide Logo">
      <h2>Spirit Guide</h2>
    </div>
    <a href="#"><i class="fa fa-gauge"></i> Dashboard</a>
    <a href="products.php"><i class="fa fa-box"></i> Kelola Produk</a>
    <a href="admin_users.php"><i class="fa fa-users"></i> Kelola Pengguna</a>
    <a href="logout.php"><i class="fa fa-right-from-bracket"></i> Logout</a>
  </div>

  <!-- MAIN --> 
  <div class="main"> 
    
    <div class="header"> 
      <h1>Dashboard Admin</h1> 
    </div>

    <div class="cards">
      <div class="card">
        <h4>Username</h4>
        <p><?= htmlspecialchars($username); ?></p>
      </div>
      <div class="card">
        <h4>Email</h4>
        <p><?= htmlspecialchars($email); ?></p>
      </div>
      <div class="card">
        <h4>Role</h4>
        <p><?= htmlspecialchars(ucfirst($role)); ?></p>
      </div>
    </div>

    <!-- USER TABLE (ISI TETAP) -->
    <?php
    include './config/db.php';
    $res = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY id ASC");
    if ($res && $res->num_rows > 0) {
        echo "<div class='table-box'>";
        echo "<h3 style='color:#FFD700'>Daftar User</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th></tr>";
        while ($r = $res->fetch_assoc()) {
            echo "<tr>
                    <td>{$r['id']}</td>
                    <td>".htmlspecialchars($r['username'])."</td>
                    <td>".htmlspecialchars($r['email'])."</td>
                    <td>".htmlspecialchars($r['role'])."</td>
                    <td>".htmlspecialchars($r['created_at'])."</td>
                  </tr>";
        }
        echo "</table></div>";
    } else {
        echo "<p>Tidak ada user terdaftar.</p>";
    }
    $conn->close();
    ?>

    

  </div> </div>

</body>
</html>
