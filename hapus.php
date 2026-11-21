<?php
session_start();
require_once 'koneksi.php';

// 1. Wajib kirim lewat POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// 2. Cek ID dengan ctype_digit sebelum query
$id = $_POST['id'] ?? '';
if ($id === '' || !ctype_digit((string)$id)) {
    $_SESSION['error'] = 'ID menu tidak valid!';
    header('Location: index.php');
    exit;
}

// 3. Eksekusi DELETE dengan PDO Prepared Statement
try {
    $stmt = $pdo->prepare("DELETE FROM menu WHERE id = :id");
    $stmt->execute([':id' => (int)$id]);

    $_SESSION['success'] = 'Data menu berhasil dihapus!';
    header('Location: index.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = 'Gagal menghapus data: ' . $e->getMessage();
    header('Location: index.php');
    exit;
}