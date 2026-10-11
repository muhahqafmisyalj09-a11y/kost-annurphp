<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/db-pesanan.php';

$daftarStatus = ['Baru', 'Dikonfirmasi', 'Ditolak', 'Selesai'];

$filter = $_GET['status'] ?? '';
if (in_array($filter, $daftarStatus, true)) {
    $stmt = $conn->prepare("SELECT * FROM pesanan WHERE status = ? ORDER BY id DESC");
    $stmt->bind_param("s", $filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $filter = '';
    $result = $conn->query("SELECT * FROM pesanan ORDER BY id DESC");
}

// Hitung jumlah per status
$hitung = array_fill_keys($daftarStatus, 0);
$rs = $conn->query("SELECT status, COUNT(*) AS jml FROM pesanan GROUP BY status");
while ($r = $rs->fetch_assoc()) {
    $hitung[$r['status']] = (int) $r['jml'];
}

function nomor_wa($hp) {
    $n = preg_replace('/\D/', '', $hp);
    if (substr($n, 0, 1) === '0') {
        $n = '62' . substr($n, 1);
    }
    return $n;
}

function rupiah($n) {
    return 'Rp' . number_format($n, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Pesanan Kamar</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f6f3ee; color: #222; }
        header { background: #2b2b2b; color: #fff; padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; }
        header a { color: #e6c88f; text-decoration: none; }
        main { padding: 24px; max-width: 1200px; margin: 0 auto; }
        .tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 18px; }
        .tabs a { padding: 8px 14px; border-radius: 20px; background: #fff; border: 1px solid #ddd; color: #222; text-decoration: none; font-size: 14px; }
        .tabs a.on { background: #b08d57; color: #fff; border-color: #b08d57; }
        .wrap { overflow-x: auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.06); }
        table { border-collapse: collapse; width: 100%; min-width: 900px; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #eee; text-align: left; font-size: 14px; vertical-align: top; }
        th { background: #faf7f2; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 12px; }
        .Baru { background: #fff2cc; } .Dikonfirmasi { background: #d8f0dc; }
        .Ditolak { background: #fbdcdc; } .Selesai { background: #dde6f5; }
        form.inline { display: inline; margin: 0; }
        select, button { font: inherit; font-size: 13px; padding: 5px 8px; }
        .wa { color: #1a8f4c; text-decoration: none; font-weight: 600; }
        .kosong { padding: 30px; text-align: center; color: #777; }
    </style>
</head>
<body>
<header>
    <strong>Admin Kost An-nur</strong>
    <a href="logout.php">Keluar</a>
</header>

<main>
    <h2>Pesanan Kamar</h2>

    <div class="tabs">
        <a href="index.php" class="<?= $filter === '' ? 'on' : '' ?>">Semua (<?= array_sum($hitung) ?>)</a>
        <?php foreach ($daftarStatus as $s): ?>
            <a href="index.php?status=<?= $s ?>" class="<?= $filter === $s ? 'on' : '' ?>"><?= $s ?> (<?= $hitung[$s] ?>)</a>
        <?php endforeach; ?>
    </div>

    <div class="wrap">
        <table>
            <tr>
                <th>No</th>
                <th>Masuk</th>
                <th>Pemesan</th>
                <th>Kamar</th>
                <th>Mulai</th>
                <th>Sewa</th>
                <th>Total</th>
                <th>Catatan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            <?php
            $no = 1;
            $ada = false;
            while ($row = $result->fetch_assoc()) :
                $ada = true;
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($row['dibuat_pada']))) ?></td>
                <td>
                    <strong><?= htmlspecialchars($row['nama']) ?></strong><br>
                    <?= htmlspecialchars($row['status_penghuni']) ?><br>
                    <a class="wa" target="_blank" rel="noopener"
                    href="https://wa.me/<?= nomor_wa($row['hp']) ?>">WA: <?= htmlspecialchars($row['hp']) ?></a>
                </td>
                <td><?= htmlspecialchars($row['tipe_kamar']) ?></td>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($row['tanggal_mulai']))) ?></td>
                <td><?= (int) $row['durasi'] ?> bln</td>
                <td><?= rupiah($row['total']) ?></td>
                <td><?= htmlspecialchars($row['catatan'] ?? '') ?></td>
                <td><span class="badge <?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                <td>
                    <form class="inline" action="ubah-status.php" method="post">
                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                        <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                        <select name="status">
                            <?php foreach ($daftarStatus as $s): ?>
                                <option value="<?= $s ?>" <?= $row['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Simpan</button>
                    </form>
                    <form class="inline" action="hapus.php" method="post"
                        onsubmit="return confirm('Yakin hapus pesanan ini?');">
                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                        <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <?php if (!$ada): ?>
            <div class="kosong">Belum ada pesanan.</div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>