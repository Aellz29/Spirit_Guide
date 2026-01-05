<?php
session_start();
include './config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); exit;
}

$message = '';

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $price = floatval($_POST['price']); // Harga Normal
    $stock = intval($_POST['stock']);
    
    // Logic Harga
    $original_price = !empty($_POST['original_price']) ? floatval($_POST['original_price']) : NULL;
    $member_price = !empty($_POST['member_price']) ? floatval($_POST['member_price']) : NULL;
    $is_flash_sale = isset($_POST['is_flash_sale']) ? 1 : 0;

    $imgPath = null;
    if (!empty($_FILES['image']['name'])) {
        $name = 'prod_'.time().'.'.pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $target = __DIR__ . '/src/img/products/' . $name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $imgPath = 'src/img/products/' . $name;
        }
    }

    $stmt = $conn->prepare("INSERT INTO products (title, category, description, price, member_price, stock, image, original_price, is_flash_sale) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssddisdi", $title, $category, $description, $price, $member_price, $stock, $imgPath, $original_price, $is_flash_sale);
    
    if ($stmt->execute()) $message = "Produk Berhasil Disimpan!";
    $stmt->close();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: products.php"); exit;
}

$res = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kelola Produk | Spirit Guide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #050505; color: white; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        input, select, textarea { background: rgba(255,255,255,0.05) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: white !important; transition: 0.3s; }
        input:focus, select:focus, textarea:focus { border-color: #fbbf24 !important; background: rgba(255,255,255,0.08) !important; }
        select { color-scheme: dark; }
        select option { background-color: #0a0a0a !important; color: white !important; }
        
        /* Helper Text Style */
        .helper-text { font-size: 10px; font-weight: bold; letter-spacing: 0.05em; margin-top: 4px; display: block; }
    </style>
</head>
<body class="p-5 md:p-10">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-black italic tracking-tighter uppercase text-white">
                Input <span class="text-amber-500">Barang</span>
            </h1>
            <a href="dashboard_admin.php" class="text-[10px] font-bold text-gray-500 hover:text-white transition tracking-widest uppercase border border-white/10 px-4 py-2 rounded-lg hover:bg-white/5">
                <i class="fa fa-arrow-left mr-2"></i> Dashboard
            </a>
        </div>

        <?php if($message): ?>
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-500 rounded-xl text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                <i class="fa fa-check-circle"></i> <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <div class="glass p-8 rounded-[30px] h-fit relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 to-transparent"></div>
                <h2 class="text-xs font-black uppercase tracking-[0.2em] mb-6 text-gray-400">Formulir Produk Baru</h2>
                
                <form method="POST" enctype="multipart/form-data" class="space-y-5">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Nama Barang</label>
                            <input type="text" name="title" required placeholder="Contoh: Jaket Varsity Spirit" class="w-full p-3 rounded-xl outline-none text-sm">
                        </div>
                        <div>
                            <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Kategori</label>
                            <select name="category" class="w-full p-3 rounded-xl outline-none text-sm cursor-pointer">
                                <option value="Fashion">Fashion</option>
                                <option value="Food">Food</option>
                                <option value="Aksesoris">Aksesoris</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Deskripsi</label>
                        <textarea name="description" rows="3" placeholder="Jelaskan keunggulan produk..." class="w-full p-3 rounded-xl outline-none text-sm"></textarea>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/5 border border-white/5 space-y-4 relative group hover:border-amber-500/30 transition">
                        <div class="absolute top-3 right-3 text-amber-500 opacity-20 group-hover:opacity-100 transition">
                            <i class="fa fa-calculator"></i>
                        </div>
                        <p class="text-[10px] uppercase text-amber-500 font-black tracking-widest border-b border-white/10 pb-2">Strategi Harga</p>

                        <div>
                            <label class="text-[10px] uppercase text-gray-300 font-bold mb-1 block">Harga Jual Normal</label>
                            <input type="number" id="inp_price" name="price" required placeholder="200000" class="w-full p-2 rounded-lg text-sm bg-black/20">
                        </div>

                        <div>
                            <label class="text-[10px] uppercase text-blue-400 font-bold mb-1 block">Harga Khusus Member</label>
                            <input type="number" id="inp_member" name="member_price" placeholder="175000" class="w-full p-2 rounded-lg text-sm border-blue-500/30 focus:border-blue-500 bg-black/20">
                            <span id="calc_member" class="helper-text text-blue-400/70"></span>
                        </div>

                        <div>
                            <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Harga Coret (Asli/Mahal)</label>
                            <input type="number" id="inp_original" name="original_price" placeholder="350000" class="w-full p-2 rounded-lg text-sm text-gray-400 bg-black/20">
                            <span id="calc_original" class="helper-text text-gray-500/70"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 items-center">
                        <div>
                            <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Stok</label>
                            <input type="number" name="stock" required class="w-full p-3 rounded-xl outline-none text-sm text-center font-bold">
                        </div>
                        <div class="h-full flex items-end">
                            <label class="flex items-center gap-3 p-3 w-full bg-red-500/10 border border-red-500/20 rounded-xl cursor-pointer hover:bg-red-500/20 transition">
                                <input type="checkbox" name="is_flash_sale" value="1" class="w-4 h-4 accent-red-500">
                                <span class="text-[10px] uppercase text-red-400 font-black tracking-widest">Flash Sale</span>
                            </label>
                        </div>
                    </div>

                    <div class="relative">
                        <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Foto Produk</label>
                        <input type="file" name="image" class="block w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-white/10 file:text-white hover:file:bg-amber-500 hover:file:text-black transition">
                    </div>

                    <button type="submit" class="w-full bg-amber-500 text-black font-black py-4 rounded-2xl hover:bg-amber-400 transition uppercase text-xs tracking-[0.2em] shadow-lg shadow-amber-500/20 mt-4">
                        Upload Produk
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 glass p-8 rounded-[30px] overflow-hidden flex flex-col h-full">
                <h2 class="text-xs font-black uppercase tracking-[0.2em] mb-6 text-gray-400">Database Inventory</h2>
                <div class="overflow-x-auto flex-1 custom-scrollbar">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead>
                            <tr class="text-[10px] uppercase text-gray-500 border-b border-white/10 tracking-widest">
                                <th class="pb-4 font-black">Item Details</th>
                                <th class="pb-4 text-center font-black">Availability</th>
                                <th class="pb-4 text-center font-black">Pricing</th>
                                <th class="pb-4 text-right font-black">Manage</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php while($p = $res->fetch_assoc()): ?>
                            <tr class="border-b border-white/5 hover:bg-white/5 transition group">
                                <td class="py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden border border-white/10 group-hover:border-amber-500/50 transition">
                                            <img src="<?= $p['image'] ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500">
                                        </div>
                                        <div>
                                            <p class="font-bold text-white text-sm flex items-center gap-2">
                                                <?= $p['title'] ?>
                                                <?php if($p['is_flash_sale']): ?>
                                                    <i class="fa fa-bolt text-red-500 text-[10px] animate-pulse"></i>
                                                <?php endif; ?>
                                            </p>
                                            <p class="text-[10px] text-gray-500 uppercase tracking-wide"><?= $p['category'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="text-xs font-bold font-mono <?= $p['stock'] < 10 ? 'text-red-400' : 'text-green-400' ?>">
                                        <?= $p['stock'] ?>
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <?php if($p['original_price'] > $p['price']): ?>
                                            <span class="text-[10px] text-gray-500 line-through">Rp <?= number_format($p['original_price'],0,',','.') ?></span>
                                        <?php endif; ?>
                                        
                                        <span class="text-xs font-bold text-white">Rp <?= number_format($p['price'],0,',','.') ?></span>
                                        
                                        <?php if($p['member_price'] && $p['member_price'] < $p['price']): ?>
                                            <span class="text-[9px] text-blue-400 font-bold bg-blue-500/10 px-2 rounded">
                                                Member: Rp <?= number_format($p['member_price'],0,',','.') ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="products_edit.php?id=<?= $p['id'] ?>" class="w-8 h-8 flex items-center justify-center bg-white/5 rounded-lg text-gray-400 hover:bg-amber-500 hover:text-black transition">
                                            <i class="fa fa-pencil text-xs"></i>
                                        </a>
                                        <a href="products.php?delete=<?= $p['id'] ?>" onclick="return confirm('Hapus produk ini?')" class="w-8 h-8 flex items-center justify-center bg-red-500/10 rounded-lg text-red-500 hover:bg-red-500 hover:text-white transition">
                                            <i class="fa fa-trash text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const normalInp = document.getElementById('inp_price');
        const memberInp = document.getElementById('inp_member');
        const originalInp = document.getElementById('inp_original');
        const calcMember = document.getElementById('calc_member');
        const calcOriginal = document.getElementById('calc_original');

        function formatRupiah(num) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
        }

        function calculate() {
            const normal = parseFloat(normalInp.value) || 0;
            const member = parseFloat(memberInp.value) || 0;
            const original = parseFloat(originalInp.value) || 0;

            // Hitung Diskon Member
            if (normal > 0 && member > 0 && member < normal) {
                const diff = normal - member;
                const percent = Math.round((diff / normal) * 100);
                calcMember.innerHTML = `<span class="text-green-400">Hemat ${percent}% (${formatRupiah(diff)})</span>`;
            } else if (member >= normal) {
                calcMember.innerHTML = `<span class="text-red-400">Harga member harus lebih murah!</span>`;
            } else {
                calcMember.innerText = "";
            }

            // Hitung Diskon Gimmick (Coret)
            if (original > normal) {
                const diff = original - normal;
                const percent = Math.round((diff / original) * 100);
                calcOriginal.innerHTML = `<span class="text-amber-500">Kelihatan diskon ${percent}%</span>`;
            } else {
                calcOriginal.innerText = "";
            }
        }

        normalInp.addEventListener('input', calculate);
        memberInp.addEventListener('input', calculate);
        originalInp.addEventListener('input', calculate);
    </script>
</body>
</html>
