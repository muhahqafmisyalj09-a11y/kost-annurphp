<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/db-pesanan.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

cek_csrf();

$id     = (int) ($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';

if (!in_array($status, ['Baru', 'Dikonfirmasi', 'Ditolak', 'Selesai'], true)) {
    die('Status tidak valid. <a href="index.php">Kembali</a>');
}

$stmt = $conn->prepare("UPDATE pesanan SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    echo "<script>
        alert('Status pesanan diubah menjadi $status.');
        window.location.href = 'index.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal mengubah status.');
        window.location.href = 'index.php';
    </script>";
}