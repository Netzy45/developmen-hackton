<?php
session_start();
require_once 'koneksi.php';

// 1. Validasi parameter ID harus digit angka
$id = $_GET['id'] ?? '';
if ($id === '' || !ctype_digit((string)$id)) {
    $_SESSION['error'] = 'ID menu tidak valid!';
    header('Location: index.php');
    exit;
}

// 2. Ambil data lama dari database berdasarkan ID
try {
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id = :id");
    $stmt->execute([':id' => (int)$id]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmtKategori = $pdo->query(
        'SELECT nama_kategori FROM kategori ORDER BY nama_kategori ASC'
    );
    $kategoriList = $stmtKategori->fetchAll(PDO::FETCH_COLUMN);

    // Pastikan kategori yang sedang dipakai tetap tersedia di dropdown.
    if (!in_array($menu['kategori'], $kategoriList, true)) {
        array_unshift($kategoriList, $menu['kategori']);
    }


    if (!$menu) {
        $_SESSION['error'] = 'Data menu tidak ditemukan!';
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Gagal mengambil data: ' . $e->getMessage();
    header('Location: index.php');
    exit;
}

// Ambil pesan error jika ada redirect gagal validasi dari proses_edit.php
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu - Kasir</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <div class="max-w-lg mx-auto mt-12 px-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

            <!-- Header Form -->
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                <h1 class="text-xl font-bold text-slate-800">Edit Data Menu</h1>
                <a href="index.php" class="text-sm text-slate-500 hover:text-slate-700">&larr; Kembali</a>
            </div>

            <!-- Alert Error Validasi -->
            <?php if ($error): ?>
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-600">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Form Edit -->
            <form action="proses_edit.php" method="POST" class="space-y-4">
                <input type="hidden" name="id" value="<?= htmlspecialchars($menu['id']) ?>">

                <!-- Input Nama Menu -->
                <div>
                    <label for="nama_menu" class="block text-sm font-medium text-slate-700 mb-1">Nama Menu <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_menu" name="nama_menu" required
                        value="<?= htmlspecialchars($menu['nama_menu'] ?? '') ?>"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Input Kategori -->
                <div>
                    <label for="kategori" class="block text-sm font-medium text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select id="kategori" name="kategori" required
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <?php foreach ($kategoriList as $kategori): ?>
                            <option value="<?= htmlspecialchars($kategori) ?>"
                                <?= $menu['kategori'] === $kategori ? 'selected' : '' ?>>
                                <?= htmlspecialchars($kategori) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Input Harga -->
                <div>
                    <label for="harga" class="block text-sm font-medium text-slate-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" id="harga" name="harga" min="1" required
                        value="<?= htmlspecialchars($menu['harga'] ?? '') ?>"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2 flex justify-end gap-2">
                    <a href="index.php" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

</body>

</html>