# 🚀 Starter Pack Mini Hackathon

**SMKN 3 Metro x CV. Woka Project Mandiri** · Sesi 2 (10.15 – 12.00 WIB)

Isi paket ini:

| File | Fungsi |
|---|---|
| `database.sql` | 3 skema tabel + data dummy (kasir, inventaris, perpustakaan) |
| `koneksi.php` | Koneksi PDO ke MySQL XAMPP (sudah aman, try-catch) |
| `index.php` | Contoh halaman Read dengan Tailwind CDN — titik awal untuk dikembangkan |
| `README.md` | Panduan ini |

---

## Langkah 1 — Nyalakan XAMPP

1. Buka **XAMPP Control Panel**.
2. Klik **Start** pada modul **Apache** dan **MySQL** (status harus hijau).
3. Buka browser, ketik `http://localhost/phpmyadmin`.

> Kalau MySQL merah / gagal start, cek apakah port 3306 dipakai aplikasi lain, lalu restart XAMPP.

---

## Langkah 2 — Buat Database

1. Di phpMyAdmin, klik tab **SQL**.
2. Buka file `database.sql` dengan Notepad / VS Code, **copy seluruh isinya**.
3. Paste ke kolom SQL, lalu klik **Go / Kirim**.
4. Akan muncul database `db_hackathon` berisi tabel `menu`, `barang`, dan `buku` lengkap dengan 5 data dummy masing-masing.

> Tidak perlu pilih satu kasus dulu — ketiga tabel boleh ada, nanti gunakan yang sesuai tim kamu.

---

## Langkah 3 — Taruh Folder di htdocs

1. Ekstrak `starter-pack.zip`.
2. Copy folder `starter-pack` ke `C:\xampp\htdocs\` (atau `D:\xampp\htdocs\`).
3. Buka `http://localhost/starter-pack/koneksi.php`.
4. **Halaman putih bersih = koneksi berhasil.** Kalau muncul kotak merah, baca pesan errornya — biasanya MySQL belum start atau nama database salah.

---

## Langkah 4 — Buka index.php

Buka `http://localhost/starter-pack/index.php`.

Kamu akan melihat tabel berisi data dummy. Di `index.php` baris ke-9 ada:

```php
$tabel = "menu"; // ganti ke 'barang' atau 'buku' sesuai kasusmu
```

Ganti sesuai studi kasus tim kamu. Perhatikan bahwa kolom yang ditampilkan juga menyesuaikan (`nama_menu`, `nama_barang`, atau `judul`).

> **Penting:** `index.php` ini sudah berisi halaman Read yang lengkap — tugasmu **bukan membuat ulang**, melainkan menyesuaikannya dengan kasus tim. Tombol **Edit** dan **Hapus** di dalam tabel masih berupa tampilan saja (belum ada aksinya); akan kamu sambungkan di langkah berikutnya lewat AI.

---

## Langkah 5 — Lanjutkan dengan AI

Gunakan prompt di halaman Sesi 2 (atau di `../01-prompting-crud/cheatsheet.html`). Urutan yang disarankan:

1. Tambah form `tambah.php` + proses simpan PDO.
2. Tambah `edit.php` (form terisi otomatis).
3. Tambah `hapus.php` (wajib konfirmasi + POST).
4. Tambah search bar.
5. Rapikan UI & badge status.

**Kalimat kunci:** “Perbaiki hanya bagian X, jangan ubah bagian lain.”

---

## Prompt Cepat (Copy ke AI)

```
Bertindak sebagai Senior PHP Developer.
Saya punya $pdo dari koneksi.php dan tabel `menu`
(id, nama_menu, kategori, harga, stok).

Buatkan tambah.php + proses_simpan.php:
- Form dengan validasi server (wajib diisi, harga angka > 0)
- INSERT dengan PDO prepared statement (:nama_menu, :kategori, :harga, :stok)
- htmlspecialchars() untuk semua output
- Tailwind CSS CDN
- Redirect ke index.php setelah sukses
- Komentar singkat di tiap bagian penting
```

---

## Troubleshooting Cepat

| Gejala | Penyebab | Solusi |
|---|---|---|
| Kotak merah "Koneksi Database Gagal" | MySQL belum start / db belum dibuat | Start MySQL, import ulang `database.sql` |
| "Table 'db_hackathon.menu' doesn't exist" | SQL belum di-import | Buka phpMyAdmin → tab SQL → paste `database.sql` → Go |
| Halaman putih total | Ada `Parse error` / salah titik koma | Lihat menu **View → Source** di browser atau aktifkan `display_errors` di php.ini |
| Data tidak muncul padahal tabel ada | `$tabel` belum diganti | Sesuaikan `$tabel` di baris 9 `index.php` |
| Tampilan tidak cantik | Tailwind CDN tidak termuat | Cek koneksi internet (CDN butuh online) |

---

## Checklist Sebelum Dikumpulkan

- [ ] CRUD (Create, Read, Update, Delete) benar-benar berjalan
- [ ] Semua query pakai PDO prepared statement
- [ ] Tombol hapus ada konfirmasi
- [ ] Tampilan rapi & responsif
- [ ] Setiap anggota tim bisa menjelaskan kodenya
- [ ] Nama tim & kasus ditulis di README/project note
