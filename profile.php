<?php
session_start();
include 'config/db.php';

// Cek Login
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); exit;
}

$id = $_SESSION['user']['id'];

// 1. UPDATE PROFIL
$msg = "";
if (isset($_POST['update_profile'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // Upload Foto
    $avatarQuery = "";
    if (!empty($_FILES['avatar']['name'])) {
        $uploadDir = __DIR__ . "/src/img/users/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES['avatar']['name']);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFilePath)) {
            $avatarQuery = ", avatar='" . $fileName . "'";
        } else {
            $msg = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4 text-sm font-bold'>Gagal upload foto.</div>";
        }
    }

    $sql = "UPDATE users SET username='$username', phone='$phone', address='$address' $avatarQuery WHERE id=$id";
    if ($conn->query($sql)) {
        $_SESSION['user']['username'] = $username;
        $msg = "<div class='bg-green-100 text-green-700 p-3 rounded mb-4 text-sm font-bold'>Profil berhasil diperbarui!</div>";
    } else {
        $msg = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4 text-sm font-bold'>Gagal update profil.</div>";
    }
}

// 2. AMBIL DATA USER
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

// 3. AMBIL RIWAYAT PESANAN
$orders = $conn->query("SELECT * FROM orders WHERE user_id=$id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | Spirit Guide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F9FA; }
        .glass-card { background: white; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body class="text-gray-800">

    <?php include 'partials/navbar.php'; ?>

    <main class="pt-32 pb-20 px-4 min-h-screen">
        <div class="max-w-5xl mx-auto">
            
            <div class="flex flex-col md:flex-row gap-8">
                
                <div class="w-full md:w-1/3">
                    <div class="glass-card rounded-3xl p-8 text-center sticky top-32">
                        <div class="relative w-28 h-28 mx-auto mb-4 group cursor-pointer" onclick="document.getElementById('avatarInput').click()">
                            <?php 
                                $avatar = !empty($user['avatar']) ? "src/img/users/".$user['avatar'] : "https://ui-avatars.com/api/?name=".$user['username']."&background=0D8ABC&color=fff";
                            ?>
                            <img src="<?= $avatar ?>" class="w-full h-full rounded-full object-cover border-4 border-white shadow-lg group-hover:opacity-75 transition">
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <i class="fa fa-camera text-white drop-shadow-md"></i>
                            </div>
                        </div>
                        
                        <h2 class="text-xl font-black uppercase tracking-tight"><?= htmlspecialchars($user['username']) ?></h2>
                        <p class="text-xs text-gray-400 font-bold mb-6"><?= htmlspecialchars($user['email']) ?></p>

                        <div class="flex justify-center gap-2 mb-6">
                            <?php if($user['role'] == 'admin'): ?>
                                <span class="bg-black text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">Admin</span>
                            <?php else: ?>
                                <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">Member</span>
                            <?php endif; ?>
                        </div>

                        <hr class="border-gray-100 mb-6">
                        
                        <div class="text-left space-y-3">
                            <button onclick="switchTab('settings')" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-amber-500">
                                <i class="fa fa-gear w-5"></i> Pengaturan Akun
                            </button>
                            <button onclick="switchTab('orders')" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-amber-500">
                                <i class="fa fa-box w-5"></i> Riwayat Belanja
                            </button>
                            <a href="logout.php" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-red-50 transition text-xs font-bold uppercase tracking-widest text-red-400 hover:text-red-600">
                                <i class="fa fa-power-off w-5"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-2/3">
                    <?= $msg ?>

                    <div id="tab-settings" class="glass-card rounded-3xl p-8">
                        <h3 class="text-lg font-black uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa fa-user-pen text-amber-500"></i> Edit Profil
                        </h3>
                        
                        <form method="POST" enctype="multipart/form-data" class="space-y-5">
                            <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/*">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Username</label>
                                    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="w-full bg-gray-50 border-0 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Email (Terkunci)</label>
                                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled class="w-full bg-gray-100 border-0 rounded-xl px-4 py-3 text-sm font-bold text-gray-400 cursor-not-allowed">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Nomor WhatsApp</label>
                                <input type="text" name="phone" placeholder="08..." value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full bg-gray-50 border-0 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500">
                                <p class="text-[10px] text-gray-400 mt-1 italic">*Digunakan untuk konfirmasi pesanan otomatis.</p>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Alamat Lengkap</label>
                                <textarea name="address" rows="3" placeholder="Jalan, No Rumah, Kecamatan..." class="w-full bg-gray-50 border-0 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500 resize-none"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                            </div>

                            <button type="submit" name="update_profile" class="bg-black text-white px-8 py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-amber-500 hover:text-black transition shadow-lg">
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>

                    <div id="tab-orders" class="glass-card rounded-3xl p-8 hidden">
                        <h3 class="text-lg font-black uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa fa-history text-blue-500"></i> Riwayat Pesanan
                        </h3>

                        <?php if($orders->num_rows > 0): ?>
                            <div class="space-y-4">
                                <?php while($o = $orders->fetch_assoc()): ?>
                                <div class="border border-gray-100 rounded-2xl p-5 hover:shadow-md transition bg-white flex flex-col md:flex-row justify-between items-center gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                            <i class="fa fa-bag-shopping"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold uppercase text-gray-400 tracking-widest">
                                                #<?= htmlspecialchars($o['order_id'] ?? $o['id']) ?>
                                            </p>
                                            <p class="text-sm font-black text-gray-900">
                                                Rp <?= number_format($o['total_price'], 0, ',', '.') ?>
                                            </p>
                                            <p class="text-[10px] text-gray-500">
                                                <?= date('d M Y • H:i', strtotime($o['created_at'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <?php 
                                            $status = $o['status'];
                                            $badgeColor = "bg-gray-100 text-gray-500";
                                            if($status == 'Lunas') $badgeColor = "bg-green-100 text-green-600";
                                            if($status == 'Dikirim') $badgeColor = "bg-blue-100 text-blue-600";
                                            if($status == 'Pending') $badgeColor = "bg-yellow-100 text-yellow-600";
                                        ?>
                                        <span class="<?= $badgeColor ?> px-4 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest">
                                            <?= $status ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Belum ada riwayat belanja.</p>
                                <a href="katalog.php?category=Fashion" class="mt-4 inline-block text-amber-500 font-bold text-xs underline">Mulai Belanja</a>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script>
        function switchTab(tabName) {
            document.getElementById('tab-settings').classList.add('hidden');
            document.getElementById('tab-orders').classList.add('hidden');
            document.getElementById('tab-' + tabName).classList.remove('hidden');
        }
    </script>
    <?php include 'partials/footer.php'; ?>
</body>
</html>