<?php
session_start();
include './config/db.php';

// Cek akses admin
if (!isset($_SESSION['user']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['user']['username'] ?? 'Admin';

// --- LOGIC PHP PENGAMBILAN DATA ---

// 1. Statistik Utama
$totalProducts = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$totalUsers    = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$totalReviews  = $conn->query("SELECT COUNT(*) as total FROM product_reviews")->fetch_assoc()['total']; // Data Review

// Cek tabel orders (antisipasi error jika tabel belum ada)
$totalOrders = 0; 
$checkOrders = $conn->query("SHOW TABLES LIKE 'orders'");
if($checkOrders->num_rows > 0) {
    $totalOrders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
}

// 2. Data Grafik Stok Kategori
$catData = $conn->query("SELECT category, SUM(stock) as total_stock FROM products GROUP BY category");
$categories = []; $stocks = [];
while($row = $catData->fetch_assoc()) {
    $categories[] = $row['category'];
    $stocks[] = $row['total_stock'];
}

// 3. Ambil 5 Review Terbaru (JOIN ke tabel products biar tau review buat barang apa)
$reviewQuery = "SELECT r.*, p.title as product_name 
                FROM product_reviews r 
                LEFT JOIN products p ON r.product_id = p.id 
                ORDER BY r.created_at DESC LIMIT 5";
$reviewsRes = $conn->query($reviewQuery);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Spirit Guide</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0a0a0a; color: #e5e5e5; }
        
        /* Background Glow Effect */
        .bg-glow {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1;
            background: radial-gradient(circle at 15% 50%, rgba(251, 191, 36, 0.08), transparent 25%), 
                        radial-gradient(circle at 85% 30%, rgba(59, 130, 246, 0.08), transparent 25%);
        }

        /* Glassmorphism Card */
        .glass { 
            background: rgba(20, 20, 20, 0.6); 
            backdrop-filter: blur(16px); 
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08); 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        /* Sidebar Transition */
        #sidebar { transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 1024px) { .sidebar-closed { transform: translateX(-100%); } }
        
        /* Stat Cards Hover */
        .stat-card { transition: all 0.3s ease; position: relative; overflow: hidden; }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.03), transparent);
            transform: translateX(-100%); transition: 0.5s;
        }
        .stat-card:hover::before { transform: translateX(100%); }
        .stat-card:hover { transform: translateY(-5px); border-color: rgba(251,191,36, 0.5); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        ::-webkit-scrollbar-track { background: transparent; }
    </style>
</head>
<body class="flex min-h-screen overflow-x-hidden selection:bg-amber-500 selection:text-black">

    <div class="bg-glow"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 glass border-r border-white/5 lg:translate-x-0 sidebar-closed flex flex-col">
        <div class="p-8">
            <div class="flex items-center gap-4 mb-12">
                <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center text-black font-black text-xl shadow-[0_0_20px_rgba(245,158,11,0.4)]">S</div>
                <div>
                    <h2 class="font-bold text-lg tracking-tight text-white leading-none">SPIRIT GUIDE</h2>
                    <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] mt-1">Admin Panel</p>
                </div>
            </div>

            <p class="text-xs font-bold text-gray-600 uppercase tracking-widest mb-4 px-4">Menu Utama</p>
            <nav class="space-y-2">
                <a href="dashboard_admin.php" class="flex items-center gap-4 p-4 rounded-xl text-amber-400 bg-amber-500/10 border border-amber-500/20 font-bold text-sm transition-all shadow-lg shadow-amber-900/10">
                    <i class="fa fa-gauge w-5 text-center"></i> Dashboard
                </a>
                <a href="products.php" class="flex items-center gap-4 p-4 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition text-sm">
                    <i class="fa fa-box w-5 text-center"></i> Produk
                </a>
                <a href="admin_users.php" class="flex items-center gap-4 p-4 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition text-sm">
                    <i class="fa fa-users w-5 text-center"></i> Pengguna
                </a>
                <a href="admin_orders.php" class="flex items-center gap-4 p-4 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition text-sm">
                    <i class="fa fa-cart-shopping w-5 text-center"></i> Pesanan
                </a>
            </nav>
        </div>
        
        <div class="mt-auto p-6 border-t border-white/5">
            <a href="logout.php" class="flex items-center gap-3 p-4 rounded-xl text-red-400 bg-red-500/5 hover:bg-red-500/10 border border-red-500/10 transition text-sm font-bold justify-center group">
                <i class="fa fa-power-off group-hover:scale-110 transition"></i> Logout
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col lg:ml-72 w-full transition-all">
        
        <header class="lg:hidden p-5 glass flex justify-between items-center sticky top-0 z-40 border-b border-white/5">
            <span class="font-bold text-amber-500 uppercase tracking-widest text-sm">Admin Panel</span>
            <button onclick="document.getElementById('sidebar').classList.toggle('sidebar-closed')" class="text-2xl text-white"><i class="fa fa-bars-staggered"></i></button>
        </header>

        <main class="p-6 md:p-10 max-w-7xl mx-auto w-full">
            
            <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Overview</p>
                    <h1 class="text-3xl md:text-4xl font-black text-white">Dashboard <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">Kinerja</span></h1>
                </div>
                <div class="flex items-center gap-3 glass px-5 py-2.5 rounded-full border border-white/10">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-bold text-gray-300">Halo, <span class="text-white"><?= htmlspecialchars($username) ?></span></span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div onclick="location.href='products.php'" class="stat-card glass p-6 rounded-[2rem] cursor-pointer group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-full bg-amber-500/10 text-amber-500"><i class="fa fa-box text-xl"></i></div>
                        <span class="text-[10px] bg-white/5 px-2 py-1 rounded text-gray-400 font-bold">+2 New</span>
                    </div>
                    <h3 class="text-3xl font-black text-white mb-1"><?= $totalProducts ?></h3>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Produk</p>
                </div>

                <div onclick="location.href='admin_users.php'" class="stat-card glass p-6 rounded-[2rem] cursor-pointer group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-full bg-blue-500/10 text-blue-500"><i class="fa fa-users text-xl"></i></div>
                    </div>
                    <h3 class="text-3xl font-black text-white mb-1"><?= $totalUsers ?></h3>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total User</p>
                </div>

                <div onclick="location.href='admin_orders.php'" class="stat-card glass p-6 rounded-[2rem] cursor-pointer group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-full bg-green-500/10 text-green-500"><i class="fa fa-cart-shopping text-xl"></i></div>
                    </div>
                    <h3 class="text-3xl font-black text-white mb-1"><?= $totalOrders ?></h3>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Pesanan</p>
                </div>

                <div class="stat-card glass p-6 rounded-[2rem] cursor-pointer group border-purple-500/20 hover:border-purple-500">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-full bg-purple-500/10 text-purple-400"><i class="fa fa-star text-xl"></i></div>
                    </div>
                    <h3 class="text-3xl font-black text-white mb-1"><?= $totalReviews ?></h3>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Ulasan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
                <div class="glass p-8 rounded-[2rem] lg:col-span-2 border border-white/5">
                    <h4 class="text-sm font-bold text-gray-300 uppercase mb-6 flex items-center gap-2">
                        <i class="fa fa-chart-simple text-amber-500"></i> Analisis Stok Kategori
                    </h4>
                    <div class="relative h-64 w-full">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
                <div class="glass p-8 rounded-[2rem] border border-white/5">
                    <h4 class="text-sm font-bold text-gray-300 uppercase mb-6 flex items-center gap-2">
                        <i class="fa fa-pie-chart text-blue-500"></i> Distribusi Data
                    </h4>
                    <div class="relative h-64 w-full flex items-center justify-center">
                        <canvas id="ratioChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="glass rounded-[2rem] overflow-hidden mb-10 border border-white/5">
                <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/0">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-purple-400 flex items-center gap-2">
                        <i class="fa fa-comments"></i> Ulasan Terbaru
                    </h3>
                    <span class="text-[10px] text-gray-500 font-mono">Real-time Data</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm min-w-[600px]">
                        <thead class="text-gray-500 font-bold uppercase text-[10px] tracking-wider bg-white/5">
                            <tr>
                                <th class="p-5">User</th>
                                <th class="p-5">Produk</th>
                                <th class="p-5">Rating</th>
                                <th class="p-5">Komentar</th>
                                <th class="p-5 text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php if ($reviewsRes->num_rows > 0): ?>
                                <?php while ($r = $reviewsRes->fetch_assoc()): ?>
                                <tr class="hover:bg-white/5 transition group">
                                    <td class="p-5 font-bold text-white flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-xs font-bold text-gray-300 border border-white/10">
                                            <?= strtoupper(substr($r['username'], 0, 1)) ?>
                                        </div>
                                        <?= htmlspecialchars($r['username']) ?>
                                    </td>
                                    <td class="p-5 text-gray-400 text-xs uppercase tracking-wide font-bold">
                                        <?= htmlspecialchars($r['product_name'] ?? 'Produk Dihapus') ?>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex text-amber-400 text-xs">
                                            <?php for($i=0; $i<$r['rating']; $i++) echo '<i class="fa fa-star"></i>'; ?>
                                            <?php for($i=$r['rating']; $i<5; $i++) echo '<i class="fa fa-star text-gray-700"></i>'; ?>
                                        </div>
                                    </td>
                                    <td class="p-5 text-gray-300 italic text-xs max-w-xs truncate">
                                        "<?= htmlspecialchars($r['comment']) ?>"
                                    </td>
                                    <td class="p-5 text-right text-gray-500 text-xs font-mono">
                                        <?= date('d M Y', strtotime($r['created_at'])) ?>
                                        <br>
                                        <span class="text-[10px] opacity-50"><?= date('H:i', strtotime($r['created_at'])) ?></span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-500 text-xs uppercase tracking-widest">Belum ada ulasan masuk.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-center md:text-right border-t border-white/5 pt-6">
                <p class="text-[10px] text-gray-600 uppercase tracking-widest font-bold">
                    Spirit Guide Admin System <span class="text-amber-500 mx-2">•</span> Version 2.0
                </p>
            </div>

        </main>
    </div>

    <script>
        // Data Stok
        const categories = <?php echo json_encode($categories); ?>;
        const stockData = <?php echo json_encode($stocks); ?>;

        // Chart Stok
        new Chart(document.getElementById('stockChart'), {
            type: 'bar',
            data: {
                labels: categories,
                datasets: [{ 
                    label: 'Stok Barang', 
                    data: stockData, 
                    backgroundColor: '#fbbf24', 
                    borderRadius: 6,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(255,255,255,0.05)' }, 
                        ticks: { color: '#888', font: {size: 10, family: 'Outfit'} },
                        border: { display: false }
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { color: '#888', font: {size: 10, family: 'Outfit'} },
                        border: { display: false }
                    }
                }
            }
        });

        // Chart Ratio
        new Chart(document.getElementById('ratioChart'), {
            type: 'doughnut',
            data: {
                labels: ['Produk', 'User', 'Review'],
                datasets: [{ 
                    data: [<?= $totalProducts ?>, <?= $totalUsers ?>, <?= $totalReviews ?>], 
                    backgroundColor: ['#fbbf24', '#3b82f6', '#a855f7'], 
                    borderColor: '#141414',
                    borderWidth: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { 
                    legend: { 
                        position: 'bottom', 
                        labels: { color: '#aaa', font: { size: 11, family: 'Outfit' }, padding: 20, usePointStyle: true } 
                    } 
                }
            }
        });
    </script>
</body>
</html>
