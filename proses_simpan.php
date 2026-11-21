<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nama_menu = trim($_POST['nama_menu'] ?? '');
$kategori_select = trim($_POST['kategori_select'] ?? '');
$kategori_baru = trim($_POST['kategori_baru'] ?? '');
$harga = $_POST['harga'] ?? '';

$kategori_final = $kategori_select === 'ADD_NEW'
    ? $kategori_baru
    : $kategori_select;

if ($nama_menu === '' || $kategori_final === '' || $harga === '') {
    $_SESSION['error'] = 'Semua field wajib diisi (termasuk kategori baru)!';
    header('Location: tambah.php');
    exit;
}

if (!is_numeric($harga) || (float) $harga <= 0) {
    $_SESSION['error'] = 'Harga harus berupa angka dan lebih besar dari 0!';
    header('Location: tambah.php');
    exit;
}

try {
    $pdo->beginTransaction();

    if ($kategori_select === 'ADD_NEW') {
        $stmt = $pdo->prepare(
            'SELECT nama_kategori
             FROM kategori
             WHERE LOWER(nama_kategori) = LOWER(:nama)
             LIMIT 1'
        );
        $stmt->execute([':nama' => $kategori_final]);
        $kategoriTersimpan = $stmt->fetchColumn();

        if ($kategoriTersimpan !== false) {
            $kategori_final = $kategoriTersimpan;
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO kategori (nama_kategori) VALUES (:nama)'
            );
            $stmt->execute([':nama' => $kategori_final]);
        }
    } else {
        $stmt = $pdo->prepare(
            'SELECT nama_kategori FROM kategori WHERE nama_kategori = :nama LIMIT 1'
        );
        $stmt->execute([':nama' => $kategori_select]);
        $kategoriTersimpan = $stmt->fetchColumn();

        if ($kategoriTersimpan === false) {
            $pdo->rollBack();
            $_SESSION['error'] = 'Kategori yang dipilih tidak ditemukan.';
            header('Location: tambah.php');
            exit;
        }

        $kategori_final = $kategoriTersimpan;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO menu (nama_menu, kategori, harga)
         VALUES (:nama_menu, :kategori, :harga)'
    );
    $stmt->execute([
        ':nama_menu' => $nama_menu,
        ':kategori' => $kategori_final,
        ':harga' => (int) $harga,
    ]);

    $pdo->commit();

    $_SESSION['success'] = 'Data menu dan kategori berhasil ditambahkan!';
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['error'] = 'Gagal menyimpan data: ' . $e->getMessage();
    header('Location: tambah.php');
    exit;
}
