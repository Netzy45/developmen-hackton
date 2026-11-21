<?php
session_start();
require_once 'koneksi.php';

// Ambil daftar kategori dari database untuk pilihan dropdown
try {
    $kategoriList = $pdo->query("SELECT * FROM kategori ORDER BY nama_kategori ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $kategoriList = [];
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <div class="max-w-lg mx-auto mt-12 px-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

            <!-- Header Form -->
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                <h1 class="text-xl font-bold text-slate-800">Tambah Menu Baru</h1>
                <a href="index.php" class="text-sm text-slate-500 hover:text-slate-700">&larr; Kembali</a>
            </div>

            <!-- Tampilkan Pesan Error Validasi -->
            <?php if ($error): ?>
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-600">
                    <?= htmlspecialchars("Gagal menyimpan data. Silakan coba lagi.") ?>
                </div>
            <?php endif; ?>

            <!-- Form Input Data -->
            <form action="proses_simpan.php" method="POST" class="space-y-4">

                <!-- Input Nama Menu -->
                <div>
                    <label for="nama_menu" class="block text-sm font-medium text-slate-700 mb-1">Nama Menu <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_menu" name="nama_menu" required placeholder="Contoh: Nasi Goreng Spesial"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Dropdown Kategori -->
                <div>
                    <label for="kategori_select" class="block text-sm font-medium text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select id="kategori_select" name="kategori_select" onchange="toggleKategoriBaru(this)" required
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($kategoriList as $kat): ?>
                            <option value="<?= htmlspecialchars($kat['nama_kategori']) ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                        <option value="ADD_NEW" class="font-semibold text-indigo-600">+ Tambah Kategori Baru</option>
                    </select>
                </div>

                <!-- Input Tambahan Kategori Baru (Muncul jika opsi 'Tambah Kategori Baru' dipilih) -->
                <div id="wrapper_kategori_baru" class="hidden mt-3">
                    <label for="kategori_baru" class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori Baru <span class="text-red-500">*</span></label>
                    <input type="text" id="kategori_baru" name="kategori_baru" placeholder="Masukkan nama kategori baru"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Input Harga -->
                <div>
                    <label for="harga" class="block text-sm font-medium text-slate-700 mb-1">Harga <span class="text-red-500">*</span></label>
                    <input type="number" id="harga" name="harga" min="1" required placeholder="Contoh: 15000"
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-2 flex justify-end gap-2">
                    <a href="index.php" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Script untuk Toggle Input Kategori Baru -->
    <script>
        function toggleKategoriBaru(selectObj) {
            const wrapper = document.getElementById('wrapper_kategori_baru');
            const inputBaru = document.getElementById('kategori_baru');

            if (selectObj.value === 'ADD_NEW') {
                wrapper.classList.remove('hidden');
                inputBaru.setAttribute('required', 'required');
            } else {
                wrapper.classList.add('hidden');
                inputBaru.removeAttribute('required');
                inputBaru.value = '';
            }
        }
    </script>
</body>

</html>