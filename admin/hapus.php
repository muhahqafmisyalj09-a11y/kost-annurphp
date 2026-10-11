<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/db-pesanan.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

cek_csrf();

$id = (int) ($_POST['id'] ?? 0);

$stmt = $conn->prepare("DELETE FROM pesanan WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>
        alert('Pesanan berhasil dihapus.');
        window.location.href = 'index.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus pesanan.');
        window.location.href = 'index.php';
    </script>";
}