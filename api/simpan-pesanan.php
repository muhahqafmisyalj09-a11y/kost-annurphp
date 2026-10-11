<?php
require_once __DIR__ . '/db-pesanan.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'pesan' => 'Metode tidak diizinkan.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'pesan' => 'Data tidak valid.']);
    exit;
}

// Harga ditentukan di server agar tidak bisa dimanipulasi dari browser
$kamar = [
    'reguler' => ['nama' => 'Kamar Kipas Reguler (Kecil)', 'harga' => 500000],
    'luas'    => ['nama' => 'Kamar Kipas Luas (Besar)',    'harga' => 800000],
    'ac'      => ['nama' => 'Kamar AC',                    'harga' => 1200000],
];

$kode    = $data['tipe'] ?? '';
$nama    = trim($data['nama'] ?? '');
$hp      = trim($data['hp'] ?? '');
$status  = trim($data['status'] ?? '');
$tanggal = trim($data['tanggal'] ?? '');
$durasi  = (int) ($data['durasi'] ?? 0);
$catatan = trim($data['catatan'] ?? '');

if (!isset($kamar[$kode]) || $nama === '' || $hp === '' || $status === '' || $tanggal === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'pesan' => 'Lengkapi semua data wajib.']);
    exit;
}

if (!preg_match('/^[0-9+\s-]{9,16}$/', $hp)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'pesan' => 'Nomor WhatsApp tidak valid.']);
    exit;
}

if (!in_array($durasi, [1, 3, 6, 12], true)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'pesan' => 'Lama sewa tidak valid.']);
    exit;
}

$d = DateTime::createFromFormat('Y-m-d', $tanggal);
if (!$d || $d->format('Y-m-d') !== $tanggal) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'pesan' => 'Tanggal tidak valid.']);
    exit;
}

$tipeNama = $kamar[$kode]['nama'];
$total    = $kamar[$kode]['harga'] * $durasi;

$stmt = $conn->prepare(
    "INSERT INTO pesanan (tipe_kamar, nama, hp, status_penghuni, tanggal_mulai, durasi, catatan, total)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("sssssisi", $tipeNama, $nama, $hp, $status, $tanggal, $durasi, $catatan, $total);

if ($stmt->execute()) {
    echo json_encode(['ok' => true, 'pesan' => 'Pesanan tersimpan.']);
} else {
    http_response_code(500);
    echo json_encode(['ok' => false, 'pesan' => 'Gagal menyimpan pesanan.']);
}