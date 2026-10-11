<?php
session_start();
require_once __DIR__ . '/../api/db-pesanan.php';

if (!empty($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if (hash_equals(ADMIN_USER, $user) && hash_equals(ADMIN_PASS, $pass)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit;
    }
    $error = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Kost An-nur</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3efe8; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .box { background: #fff; padding: 32px; border-radius: 12px; width: 100%; max-width: 340px; box-shadow: 0 6px 24px rgba(0,0,0,.1); }
        h1 { margin: 0 0 20px; font-size: 22px; }
        label { display: block; font-size: 14px; margin: 12px 0 6px; }
        input { width: 100%; padding: 10px 12px; border: 1px solid #c9c9c9; border-radius: 8px; box-sizing: border-box; font-size: 15px; }
        button { width: 100%; margin-top: 20px; padding: 12px; border: 0; border-radius: 8px; background: #b08d57; color: #fff; font-size: 15px; cursor: pointer; }
        .err { background: #fde8e8; color: #a12; padding: 10px; border-radius: 8px; font-size: 14px; margin-bottom: 8px; }
    </style>
</head>
<body>
    <form class="box" method="post">
        <h1>Login Admin Kost An-nur</h1>
        <?php if ($error): ?>
            <div class="err"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Masuk</button>
    </form>
</body>
</html>