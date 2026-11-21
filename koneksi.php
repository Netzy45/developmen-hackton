<?php
/**
 * ========================================================
 * STARTER PACK: KONEKSI DATABASE PDO STANDAR INDUSTRI
 * Workshop Guru Tamu: SMKN 3 Metro x CV. Woka Project Mandiri
 * ========================================================
 */

$host     = "localhost";
$dbname   = "db_hackathon"; // GANTI dengan nama databasemu yang dibuat di phpMyAdmin
$username = "root";
$password = "";             // Default XAMPP kosong

try {
    // Membuat koneksi menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Konfigurasi Error Mode ke Exception (Wajib untuk standar industri)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode ke associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Tangani jika terjadi error koneksi
    die("
    <div style='font-family: sans-serif; padding: 20px; background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px; max-width: 600px; margin: 40px auto;'>
        <h3 style='color: #b91c1c; margin-top: 0;'>⚠️ Koneksi Database Gagal!</h3>
        <p style='color: #451a03; font-size: 14px;'>Pesan Error: <code>" . htmlspecialchars($e->getMessage()) . "</code></p>
        <hr style='border: 0; border-top: 1px solid #fca5a5;'>
        <p style='color: #374151; font-size: 13px;'>
            <strong>Langkah Perbaikan:</strong><br>
            1. Pastikan MySQL di XAMPP Control Panel sudah berstatus <strong>Running (Hijau)</strong>.<br>
            2. Cek apakah database <code>$dbname</code> sudah kamu buat di <a href='http://localhost/phpmyadmin' target='_blank'>phpMyAdmin</a>.<br>
            3. Pastikan username dan password koneksi sudah benar.
        </p>
    </div>
    ");
}
