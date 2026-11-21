-- ========================================================
-- STARTER PACK SQL: 3 PILIHAN SKEMA DATABASE HACKATHON
-- SMKN 3 Metro x CV. Woka Project Mandiri
-- ========================================================

-- BUAT DATABASE (Jalankan ini dulu di phpMyAdmin SQL tab)
CREATE DATABASE IF NOT EXISTS `db_hackathon` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_hackathon`;

-- --------------------------------------------------------
-- PILIHAN A: SISTEM KASIR KANTIN SEKOLAH
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `menu` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_menu` VARCHAR(100) NOT NULL,
  `kategori` ENUM('Makanan', 'Minuman', 'Snack') NOT NULL DEFAULT 'Makanan',
  `harga` INT NOT NULL DEFAULT 0,
  `stok` INT NOT NULL DEFAULT 10,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Dummy Pilihan A
INSERT INTO `menu` (`nama_menu`, `kategori`, `harga`, `stok`) VALUES
('Nasi Goreng Spesial', 'Makanan', 12000, 20),
('Mie Ayam Bakso', 'Makanan', 10000, 15),
('Es Teh Manis', 'Minuman', 3000, 50),
('Jus Jeruk Segar', 'Minuman', 5000, 25),
('Risol Mayo Crispy', 'Snack', 2500, 30);


-- --------------------------------------------------------
-- PILIHAN B: SISTEM INVENTARIS LAB KOMPUTER
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `barang` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kode_barang` VARCHAR(20) NOT NULL UNIQUE,
  `nama_barang` VARCHAR(100) NOT NULL,
  `kategori` VARCHAR(50) NOT NULL,
  `lokasi_lab` VARCHAR(50) NOT NULL DEFAULT 'Lab RPL 1',
  `kondisi` ENUM('Baik', 'Rusak Ringan', 'Rusak Berat') NOT NULL DEFAULT 'Baik',
  `jumlah` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Dummy Pilihan B
INSERT INTO `barang` (`kode_barang`, `nama_barang`, `kategori`, `lokasi_lab`, `kondisi`, `jumlah`) VALUES
('PC-01', 'PC All-in-One Core i5', 'Hardware', 'Lab RPL 1', 'Baik', 1),
('KB-05', 'Keyboard Mechanical RGB', 'Periferal', 'Lab RPL 1', 'Baik', 10),
('MS-02', 'Mouse Optical USB', 'Periferal', 'Lab RPL 2', 'Rusak Ringan', 5),
('SW-01', 'Switch TP-Link 24 Port', 'Jaringan', 'Server Lab', 'Baik', 2),
('PRJ-01', 'Proyektor Epson EB-X400', 'Multimedia', 'Lab RPL 1', 'Baik', 1);


-- --------------------------------------------------------
-- PILIHAN C: SISTEM PERPUSTAKAAN SEKOLAH
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `buku` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `isbn` VARCHAR(30) NULL,
  `judul` VARCHAR(150) NOT NULL,
  `penulis` VARCHAR(100) NOT NULL,
  `kategori` VARCHAR(50) NOT NULL,
  `stok` INT NOT NULL DEFAULT 5,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Dummy Pilihan C
INSERT INTO `buku` (`isbn`, `judul`, `penulis`, `kategori`, `stok`) VALUES
('978-602-04-1', 'Pemrograman Web PHP & MySQL Modern', 'Budi Santoso', 'Komputer', 7),
('978-602-04-2', 'Dasar-Dasar Rekayasa Perangkat Lunak', 'Ahmad Dani', 'Pendidikan', 10),
('978-602-04-3', 'Belajar Tailwind CSS dari Nol', 'Rizky Pratama', 'Komputer', 5),
('978-602-04-4', 'Struktur Data & Algoritma Dasar', 'Siti Rahma', 'Informatika', 4),
('978-602-04-5', 'Laskar Pelangi', 'Andrea Hirata', 'Novel', 3);
