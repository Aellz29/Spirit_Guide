<?php
session_start();
include './config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); exit;
}

$id = intval($_GET['id'] ?? 0);
$res = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $res->fetch_assoc();

if (!$product) die("Produk tidak ditemukan.");

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    
    // LOGIC BARU: Member Price, Original Price, Flash Sale
    $member_price = !empty($_POST['member_price']) ? floatval($_POST['member_price']) : NULL;
    $original_price = !empty($_POST['original_price']) ? floatval($_POST['original_price']) : NULL;
    $is_flash_sale = isset($_POST['is_flash_sale']) ? 1 : 0;

    $imgPath = $product['image'];

    if (!empty($_FILES['image']['name'])) {
        $name = 'prod_'.time().'.'.pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/src/img/products/' . $name)) {
            $imgPath = 'src/img/products/' . $name;
        }
    }

    // UPDATE QUERY (Diselaraskan dengan kolom baru)
    $upd = $conn->prepare("UPDATE products SET title=?, category=?, description=?, price=?, member_price=?, original_price=?, stock=?, is_flash_sale=?, image=? WHERE id=?");
    // Types: s=string, d=double, i=integer
    // Urutan: title(s), cat(s), desc(s), price(d), member(d), original(d), stock(i), flash(i), img(s), id(i)
    $upd->bind_param("sssddiiisi", $title, $category, $description, $price, $member_price, $original_price, $stock, $is_flash_sale, $imgPath, $id);
    
    if ($upd->execute()) {
        $message = "Perubahan Berhasil Disimpan!";
        // Refresh data agar form terupdate
        $res = $conn->query("SELECT * FROM products WHERE id=$id");
        $product = $res->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Produk | Spirit Guide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #0a0a0a; color: white; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        input, select, textarea { background: rgba(255,255,255,0.05) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: white !important; transition: 0.3s; }
        input:focus, select:focus, textarea:focus { border-color: #fbbf24 !important; background: rgba(255,255,255,0.08) !important; }
        select { color-scheme: dark; }
        select option { background-color: #0a0a0a !important; color: white !important; }
        .helper-text { font-size: 10px; font-weight: bold; letter-spacing: 0.05em; margin-top: 4px; display: block; }
    </style>
</head>
<body class="p-5 md:p-10">
    <div class="max-w-5xl mx-auto">
        
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-black uppercase italic tracking-tighter text-white">
                Edit <span class="text-amber-500">Inventory</span>
            </h1>
            <a href="products.php" class="text-[10px] font-bold text-gray-500 hover:text-white transition tracking-widest uppercase border border-white/10 px-4 py-2 rounded-lg hover:bg-white/5">
                <i class="fa fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <?php if($message): ?>
            <div class="mb-8 p-4 bg-green-500/10 border border-green-500/20 text-green-500 rounded-2xl text-xs flex items-center gap-2 uppercase tracking-widest font-bold">
                <i class="fa fa-check-circle"></i> <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="glass p-8 md:p-10 rounded-[40px] relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 to-transparent"></div>

            <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Nama Produk</label>
                        <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" class="w-full p-3 rounded-xl outline-none text-sm">
                    </div>
                    
                    <div>
                        <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Kategori</label>
                        <select name="category" class="w-full p-3 rounded-xl outline-none text-sm cursor-pointer">
                            <option value="Fashion" <?= $product['category'] == 'Fashion' ? 'selected' : '' ?>>Fashion</option>
                            <option value="Food" <?= $product['category'] == 'Food' ? 'selected' : '' ?>>Food</option>
                            <option value="Aksesoris" <?= $product['category'] == 'Aksesoris' ? 'selected' : '' ?>>Aksesoris</option>
                            <option value="Other" <?= $product['category'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/5 border border-white/5 space-y-4 relative group hover:border-amber-500/30 transition">
                        <div class="absolute top-3 right-3 text-amber-500 opacity-20 group-hover:opacity-100 transition">
                            <i class="fa fa-calculator"></i>
                        </div>
                        <p class="text-[10px] uppercase text-amber-500 font-black tracking-widest border-b border-white/10 pb-2">Strategi Harga</p>

                        <div>
                            <label class="text-[10px] uppercase text-gray-300 font-bold mb-1 block">Harga Jual Normal</label>
                            <input type="number" id="inp_price" name="price" value="<?= $product['price'] ?>" class="w-full p-2 rounded-lg text-sm bg-black/20">
                        </div>

                        <div>
                            <label class="text-[10px] uppercase text-blue-400 font-bold mb-1 block">Harga Khusus Member</label>
                            <input type="number" id="inp_member" name="member_price" value="<?= $product['member_price'] ?>" placeholder="Opsional" class="w-full p-2 rounded-lg text-sm border-blue-500/30 focus:border-blue-500 bg-black/20">
                            <span id="calc_member" class="helper-text text-blue-400/70"></span>
                        </div>

                        <div>
                            <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Harga Coret (Asli)</label>
                            <input type="number" id="inp_original" name="original_price" value="<?= $product['original_price'] ?>" placeholder="Opsional" class="w-full p-2 rounded-lg text-sm text-gray-400 bg-black/20">
                            <span id="calc_original" class="helper-text text-gray-500/70"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 items-center">
                        <div>
                            <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Stok</label>
                            <input type="number" name="stock" value="<?= $product['stock'] ?>" class="w-full p-3 rounded-xl outline-none text-center font-bold">
                        </div>
                        <div class="h-full flex items-end">
                            <label class="flex items-center gap-3 p-3 w-full bg-red-500/10 border border-red-500/20 rounded-xl cursor-pointer hover:bg-red-500/20 transition">
                                <input type="checkbox" name="is_flash_sale" value="1" class="w-4 h-4 accent-red-500" <?= $product['is_flash_sale'] ? 'checked' : '' ?>>
                                <span class="text-[10px] uppercase text-red-400 font-black tracking-widest">Flash Sale</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-black/20 p-4 rounded-3xl border border-white/5">
                        <label class="text-[10px] uppercase text-gray-500 font-bold mb-2 block">Foto Produk Saat Ini</label>
                        <div class="aspect-square rounded-2xl overflow-hidden mb-4 border border-white/10 bg-[#050505]">
                            <img src="<?= $product['image'] ?>" class="w-full h-full object-contain">
                        </div>
                        <input type="file" name="image" class="block w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-white/10 file:text-white hover:file:bg-amber-500 hover:file:text-black transition">
                    </div>

                    <div>
                        <label class="text-[10px] uppercase text-gray-500 font-bold mb-1 block">Deskripsi Detail</label>
                        <textarea name="description" rows="5" class="w-full p-4 rounded-2xl outline-none text-sm leading-relaxed"><?= htmlspecialchars($product['description']) ?></textarea>
                    </div>

                    <button type="submit" class="w-full bg-amber-500 text-black font-black py-4 rounded-2xl hover:bg-amber-400 transition uppercase text-xs tracking-[0.2em] shadow-lg shadow-amber-500/20 mt-4">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
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

        // Jalankan saat loading agar data lama langsung terhitung
        calculate();
    </script>
</body>
</html>