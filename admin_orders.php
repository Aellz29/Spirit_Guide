<?php
session_start();
include 'config/db.php';

// Proteksi Admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil data terbaru
$query = "SELECT * FROM orders ORDER BY id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <title>Admin Management | Spirit Guide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #0a0a0a; color: #e0e0e0; font-family: 'Plus Jakarta Sans', sans-serif; }
        .status-badge { padding: 4px 12px; border-radius: 99px; font-size: 10px; font-weight: bold; text-transform: uppercase; border: 1px solid; display: inline-block; }
    </style>
</head>
<body class="p-4 md:p-10">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-2xl font-bold uppercase tracking-tighter italic text-amber-400">Orders<span class="text-white">Database</span></h1>
                <p class="text-gray-500 text-xs mt-1">Orders Management & Update Orders.</p>
            </div>
            <a href="dashboard_admin.php" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-white transition">← Kembali ke Dashboard</a>
        </div>
        
        <div class="overflow-x-auto bg-white/5 rounded-3xl border border-white/10">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] uppercase tracking-wider text-gray-500 border-b border-white/10">
                        <th class="p-5">ID</th>
                        <th class="p-5">Pelanggan</th>
                        <th class="p-5">Bukti</th>
                        <th class="p-5 text-center">Status</th>
                        <th class="p-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr class="border-b border-white/5 hover:bg-white/5 transition">
                        <td class="p-5 font-mono text-amber-500">#<?= $row['id'] ?></td>
                        <td class="p-5">
                            <div class="font-bold text-white"><?= htmlspecialchars($row['name']); ?></div>
                            <div class="text-[10px] text-gray-400 italic"><?= $row['payment_method']; ?></div>
                        </td>
                        
                        <td class="p-5">
                            <?php if(!empty($row['proof_image'])): ?>
                                <a href="assets/uploads/proofs/<?= $row['proof_image'] ?>" target="_blank" class="text-[10px] text-amber-500 hover:underline font-bold uppercase">Lihat Bukti</a>
                            <?php else: ?>
                                <span class="text-[10px] text-gray-600 italic">No Upload</span>
                            <?php endif; ?>
                        </td>

                        <td class="p-5 text-center">
                            <?php 
                            $s = $row['status'];
                            if($s == 'pending' || empty($s)) {
                                echo '<span class="status-badge bg-gray-500/10 text-gray-400 border-gray-500/20">⏳ Baru</span>';
                            } elseif($s == 'verifying') {
                                echo '<span class="status-badge bg-amber-500/10 text-amber-500 border-amber-500/20">🔍 Verifikasi</span>';
                            } elseif($s == 'shipping') {
                                echo '<span class="status-badge bg-blue-500/10 text-blue-500 border-blue-500/20">🚚 Dikirim</span>';
                            } elseif($s == 'success') {
                                echo '<span class="status-badge bg-green-500/10 text-green-500 border-green-500/20">✅ Selesai</span>';
                            } elseif($s == 'canceled') {
                                echo '<span class="status-badge bg-red-500/10 text-red-500 border-red-500/20">❌ Batal</span>';
                            }
                            ?>
                        </td>

                        <td class="p-5 text-right">
                            <div class="flex justify-end gap-2">
                                
                                <?php if($s == 'pending' || empty($s)): ?>
                                    <a href="update_status.php?id=<?= $row['id'] ?>&to=verifying" class="bg-amber-600 text-white px-3 py-2 rounded-lg text-[10px] font-bold uppercase hover:bg-amber-700 transition">Verifikasi</a>
                                <?php endif; ?>

                                <?php if($s == 'verifying'): ?>
                                    <a href="update_status.php?id=<?= $row['id'] ?>&to=shipping" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-[10px] font-bold uppercase hover:bg-blue-700 transition">Kirim Barang</a>
                                <?php endif; ?>

                                <?php if($s == 'shipping'): ?>
                                    <a href="update_status.php?id=<?= $row['id'] ?>&to=success" class="bg-green-600 text-white px-3 py-2 rounded-lg text-[10px] font-bold uppercase hover:bg-green-700 transition">Selesaikan</a>
                                <?php endif; ?>

                                <?php if($s != 'success' && $s != 'canceled'): ?>
                                    <a href="update_status.php?id=<?= $row['id'] ?>&to=canceled" onclick="return confirm('Batalkan pesanan ini?')" class="border border-red-500/50 text-red-500 px-3 py-2 rounded-lg text-[10px] font-bold uppercase hover:bg-red-500 hover:text-white transition">Batal</a>
                                <?php endif; ?>

                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $row['phone']) ?>" target="_blank" class="bg-white/10 text-white px-3 py-2 rounded-lg text-[10px] font-bold uppercase hover:bg-white/20">WA</a>

                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>