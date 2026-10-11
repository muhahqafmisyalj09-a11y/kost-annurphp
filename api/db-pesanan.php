<?php
// Konfigurasi database (sesuaikan dengan milik Anda)
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "kost_annur";   // GANTI

// Akun admin (GANTI password sebelum online)
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', '060907');

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    die("Koneksi database gagal.");
}

$conn->set_charset("utf8mb4");