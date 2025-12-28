<?php
session_start();
include 'config/db.php';

// Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message = '';
// Hapus User Logic
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Proteksi: jangan hapus akun sendiri (opsional)
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) { $message = "Pengguna berhasil dihapus."; }
}

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <title>Kelola Pengguna | Spirit Guide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0a0a0a; color: #e0e0e0; }
        .glass-card { background: rgba(255, 255, 255, 0.02); backdrop-filter: blur(10px); border: 1px solid rgba(255, 215, 0, 0.1); }
    </style>
</head>
<body class="p-4 md:p-10">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-2xl font-bold uppercase tracking-tighter italic text-amber-400">User <span class="text-white">Database</span></h1>
                <p class="text-gray-500 text-xs mt-1">Manajemen akses dan data pengguna terdaftar.</p>
            </div>
            <a href="dashboard_admin.php" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-white transition">← Kembali ke Dashboard</a>
        </div>

        <?php if($message): ?>
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-500 text-xs font-bold uppercase rounded-xl">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-white/5 text-[10px] font-bold uppercase tracking-widest text-amber-400">
                        <tr>
                            <th class="p-5">ID</th>
                            <th class="p-5">Username</th>
                            <th class="p-5">Email</th>
                            <th class="p-5">Role</th>
                            <th class="p-5">Dibuat Pada</th>
                            <th class="p-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-white/5">
                        <?php while ($u = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="p-5 text-gray-500 italic">#<?= $u['id']; ?></td>
                            <td class="p-5 font-bold uppercase tracking-wide text-white"><?= htmlspecialchars($u['username']); ?></td>
                            <td class="p-5 text-gray-400"><?= htmlspecialchars($u['email']); ?></td>
                            <td class="p-5">
                                <span class="px-2 py-1 rounded text-[9px] font-bold uppercase <?= $u['role'] == 'admin' ? 'bg-amber-500 text-black' : 'bg-white/10 text-gray-400 border border-white/10' ?>">
                                    <?= $u['role']; ?>
                                </span>
                            </td>
                            <td class="p-5 text-gray-500 text-xs"><?= date('d/m/Y', strtotime($u['created_at'])); ?></td>
                            <td class="p-5 text-right">
                                <?php if ($u['role'] !== 'admin'): ?>
                                    <a href="?delete=<?= $u['id']; ?>" onclick="return confirm('Hapus user ini?')" class="text-red-500 text-[10px] font-bold uppercase hover:underline">Hapus</a>
                                <?php else: ?>
                                    <span class="text-gray-600 text-[9px] uppercase italic">Protected</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>