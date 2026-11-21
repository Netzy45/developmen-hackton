<?php
session_start();
require_once 'koneksi.php';

// Tolak akses selain POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id        = $_POST['id'] ?? '';
$nama_menu = trim($_POST['nama_menu'] ?? '');
$kategori  = trim($_POST['kategori'] ?? '');
$harga     = $_POST['harga'] ?? '';

// 1. Cek ID dengan ctype_digit sebelum query
if ($id === '' || !ctype_digit((string)$id)) {
    $_SESSION['error'] = 'ID menu tidak valid!';
    header('Location: index.php');
    exit;
}

// 2. Validasi Server: Field wajib diisi & harga > 0
if ($nama_menu === '' || $kategori === '' || $harga === '') {
    $_SESSION['error'] = 'Semua field wajib diisi!';
    header("Location: edit.php?id={$id}");
    exit;
}

if (!is_numeric($harga) || (float)$harga <= 0) {
    $_SESSION['error'] = 'Harga harus berupa angka lebih dari 0!';
    header("Location: edit.php?id={$id}");
    exit;
}

// 3. Eksekusi UPDATE dengan PDO Prepared Statement
try {
    $sql = "UPDATE menu SET nama_menu = :nama_menu, kategori = :kategori, harga = :harga WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':nama_menu' => $nama_menu,
        ':kategori'  => $kategori,
        ':harga'     => (int)$harga,
        ':id'        => (int)$id
    ]);

    $_SESSION['success'] = "Menu '{$nama_menu}' berhasil diperbarui!";
    header('Location: index.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = 'Gagal memperbarui data: ' . $e->getMessage();
    header("Location: edit.php?id={$id}");
    exit;
}