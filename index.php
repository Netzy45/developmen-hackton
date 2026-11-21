<?php
// Hubungkan ke file koneksi
require_once 'koneksi.php';

// CONTOH QUERY READ: Ambil semua data (Silakan sesuaikan nama tabelmu)
// Contoh jika memilih Kasir Kantin: tabel 'menu'
// Contoh jika memilih Inventaris Lab: tabel 'barang'
// Contoh jika memilih Perpustakaan: tabel 'buku'
$tabel = "menu"; // <--- Ganti sesuai pilihan studi kasusmu

$keyword = trim($_GET['keyword'] ?? '');

try {
  if ($keyword !== '') {
    // Query dengan pencarian menggunakan prepared statement LIKE
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE nama_menu LIKE :keyword OR kategori LIKE :keyword ORDER BY id DESC");
    $stmt->execute([':keyword' => "%$keyword%"]);
  } else {
    // Query standar jika tidak ada keyword pencarian
    $stmt = $pdo->query("SELECT * FROM menu ORDER BY id DESC");
  }
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $rows = [];
  $error = $e->getMessage();
}

try {
  $kategoriList = $pdo->query("SELECT * FROM kategori ORDER BY nama_kategori ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $kategoriList = [];
}

$keyword = trim($_GET['keyword'] ?? '');
$selectedKategori = trim($_GET['kategori_filter'] ?? '');

try {
  $sql = "SELECT * FROM menu WHERE 1=1";
  $params = [];

  if ($keyword !== '') {
    $sql .= " AND (nama_menu LIKE :keyword OR kategori LIKE :keyword)";
    $params[':keyword'] = "%$keyword%";
  }

  if ($selectedKategori !== '') {
    $sql .= " AND kategori = :kategori";
    $params[':kategori'] = $selectedKategori;
  }

  $sql .= " ORDER BY id DESC";
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $rows = [];
  $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aplikasi Mini Hackathon | SMKN 3 Metro</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 text-slate-800 min-h-screen">

  <!-- Navbar -->
  <nav class="bg-indigo-600 text-white shadow-md">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
      <div class="font-bold text-lg">🚀 Proyek Hackathon: SMKN 3 Metro</div>
      <div class="text-xs bg-indigo-700 px-3 py-1 rounded-full">PHP PDO + Tailwind CDN</div>
    </div>
  </nav>

  <!-- Container -->
  <main class="max-w-6xl mx-auto px-4 py-8 space-y-6">

    <!-- Header Section -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-2xl font-extrabold text-slate-900">Menu-menu Makanan Kantin</h1>
        <p class="text-sm text-slate-500">Lihatlah menu-menu makanan yang tersedia di kantin sekolah dengan fitur-fitur menarik lainnya </p>
      </div>

      <!-- Tombol Trigger Form / Modal -->
      <a href="tambah.php" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm shadow transition">
        + Tambah Data Baru
      </a>
    </div>

    <!-- Search & Filter Bar Section (Gabungan 1 Baris) -->
    <div class="mb-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-col lg:flex-row justify-between items-center gap-3">
      <form action="index.php" method="GET" class="flex flex-wrap items-center gap-2 w-full lg:w-auto">

        <!-- 1. Input masukan nama -->
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Cari nama menu..."
          class="px-3.5 py-2 border border-slate-300 rounded-lg text-sm w-full sm:w-52 focus:outline-none focus:ring-2 focus:ring-indigo-500">

        <!-- 2. Tombol Cari -->
        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
          Cari
        </button>

        <!-- 3. Tombol Reset (Hanya muncul jika sedang mencari/memfilter) -->
        <?php if (!empty($keyword) || !empty($selectedKategori)): ?>
          <a href="index.php" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-medium rounded-lg transition-colors flex items-center justify-center">
            Reset
          </a>
        <?php endif; ?>

        <!-- 4. Dropdown Kategori -->
        <select name="kategori_filter" class="px-3.5 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
          <option value="">Semua Kategori</option>
          <?php foreach ($kategoriList as $kat): ?>
            <option value="<?= htmlspecialchars($kat['nama_kategori']) ?>" <?= ($selectedKategori ?? '') === $kat['nama_kategori'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($kat['nama_kategori']) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <!-- 5. Tombol Filter -->
        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
          Filter
        </button>
      </form>

      <!-- Total Item di sebelah kanan -->
    </div>


    <!-- Error Alert jika tabel belum dibuat -->
    <?php if (isset($error)): ?>
      <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm">
        <strong>Pemberitahuan:</strong> Tabel <code><?= htmlspecialchars($tabel) ?></code> belum ditemukan di databasemu. Silakan import file <code>database.sql</code> terlebih dahulu di phpMyAdmin!
      </div>
    <?php endif; ?>

    <!-- Tabel Data (READ) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
      <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
        <h2 class="font-bold text-slate-700 text-sm">Daftar Data</h2>
        <span class="text-xs text-slate-500"> Total: <?= count($rows) ?> item </span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-slate-50 text-slate-600 font-semibold text-xs uppercase tracking-wider border-b">
            <tr>
              <th class="p-3.5">No</th>
              <th class="p-3.5">Nama Item</th>
              <th class="p-3.5">Kategori</th>
              <th class="p-3.5">Detail / Nilai</th>
              <th class="p-3.5 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <?php if (empty($rows)): ?>
              <tr>
                <td colspan="5" class="px-6 py-6 text-center text-slate-500 italic bg-white">
                  <?= !empty($keyword) ? 'Data tidak ditemukan untuk pencarian "' . htmlspecialchars($keyword) . '".' : 'Belum ada data menu yang tersedia.' ?>
                </td>
              </tr>
            <?php else: ?>
              <?php $no = 1; ?>
              <?php foreach ($rows as $row): ?>
                <tr class="border-b hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-4 text-sm text-gray-700"><?= $no++ ?></td>
                  <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($row['nama_menu'] ?? '-') ?></td>
                  <td class="px-6 py-4 text-sm">
                    <span class="px-2.5 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded-full">
                      <?= htmlspecialchars($row['kategori'] ?? 'Umum') ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-700">
                    Rp <?= number_format($row['harga'] ?? 0, 0, ',', '.') ?>
                  </td>
                  <td class="px-6 py-4 text-sm flex items-center gap-2">
                    <!-- Tombol Edit -->
                    <a href="edit.php?id=<?= htmlspecialchars($row['id']) ?>"
                      class="px-2.5 py-1 text-xs font-medium text-amber-700 bg-amber-100 hover:bg-amber-200 rounded transition-colors">
                      Edit
                    </a>

                    <!-- Form Hapus via POST + confirm('Yakin?') -->
                    <form action="hapus.php" method="POST" onsubmit="return confirm('Yakin?');" class="inline">
                      <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                      <button type="submit"
                        class="px-2.5 py-1 text-xs font-medium text-red-700 bg-red-100 hover:bg-red-200 rounded transition-colors">
                        Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>

</body>

</html>