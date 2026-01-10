<?php
session_start();
include 'config/db.php';
date_default_timezone_set('Asia/Jakarta'); 

$message = "";
$resetLink = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
    
    if ($check->num_rows > 0) {
        $token = bin2hex(random_bytes(32)); 
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $conn->query("UPDATE users SET reset_token = '$token', reset_expiry = '$expiry' WHERE email = '$email'");

        // Auto-detect Link
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $path = dirname($_SERVER['PHP_SELF']);
        $resetLink = $protocol . "://" . $host . $path . "/reset_password.php?token=" . $token;
        
        // Pesan Sukses (Green Box) dengan Style
        $message = "<div class='msg-box success'>
            <strong>Link Reset Terkirim!</strong><br>
            <a href='$resetLink' class='break-all underline text-white hover:text-yellow-300'>Klik Link Ini Untuk Reset</a>
        </div>";
        
    } else {
        // Pesan Error (Red Box)
        $message = "<div class='msg-box error'>Email tidak ditemukan!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Spirit Guide</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #000;
            color: white;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        img.bg-image {
            position: fixed; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.5; z-index: 0;
        }

        .bg-overlay {
            position: fixed; inset: 0; background: rgba(0, 0, 0, 0.6); z-index: 1;
        }

        .form-container {
            position: relative; z-index: 2;
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px);
            border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2.5rem; width: 90%; max-width: 400px;
            box-shadow: 0 10px 40px rgba(255, 215, 0, 0.3); text-align: center;
            transition: 0.3s ease;
        }
        .form-container:hover { transform: scale(1.02); box-shadow: 0 15px 50px rgba(255, 215, 0, 0.4); }

        h2 { font-size: 1.5rem; font-weight: 700; color: #FFD700; margin-bottom: 0.5rem; }
        p.subtitle { font-size: 0.8rem; color: #ddd; margin-bottom: 1.5rem; }

        .input-field {
            width: 100%; padding: 12px 14px; border: none; border-radius: 10px;
            background: rgba(255, 255, 255, 0.9); color: #000; font-size: 1rem;
            margin-bottom: 15px; outline: none; transition: 0.2s; box-sizing: border-box;
        }
        .input-field:focus { box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.5); }

        .btn-submit {
            width: 100%; padding: 12px; border: none; border-radius: 10px;
            background: linear-gradient(90deg, #FFD700, #FFA500); font-weight: bold;
            color: #000; font-size: 1rem; cursor: pointer; transition: 0.3s;
        }
        .btn-submit:hover {
            background: linear-gradient(90deg, #FFB700, #FF8C00);
            transform: translateY(-2px); box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }

        .msg-box { padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 0.9rem; }
        .msg-box.error { background-color: rgba(239, 68, 68, 0.8); color: white; }
        .msg-box.success { background-color: rgba(34, 197, 94, 0.8); color: white; }

        .footer-link { margin-top: 1.5rem; display: block; font-size: 0.9rem; color: #FFD700; text-decoration: none; font-weight: 600; }
        .footer-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <img src="./src/img/SpiritGuide.jpg" class="bg-image">
    <div class="bg-overlay"></div>

    <div class="form-container">
        <h2>Lupa Password?</h2>
        <p class="subtitle">Masukkan email terdaftar, kami akan kirimkan link reset.</p>

        <?= $message ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email Kamu..." required class="input-field">
            <button type="submit" name="submit" class="btn-submit">Kirim Link Reset</button>
        </form>

        <a href="login.php" class="footer-link">Kembali ke Login</a>
    </div>
</body>
</html>