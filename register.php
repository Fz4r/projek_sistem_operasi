<?php
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$nama = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = trim($_POST['nama'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if (!csrf_valid()) {
        $errors[] = 'Sesi tidak valid. Muat ulang halaman dan coba lagi.';
    }
    if ($nama === '' || mb_strlen($nama) > 100) {
        $errors[] = 'Nama wajib diisi (maksimal 100 karakter).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
        $errors[] = 'Format email tidak valid.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Kata sandi minimal 8 karakter.';
    }
    if ($password !== $konfirmasi) {
        $errors[] = 'Konfirmasi kata sandi tidak cocok.';
    }

    if (!$errors) {
        $cek = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $cek->execute([$email]);
        if ($cek->fetch()) {
            $errors[] = 'Email sudah terdaftar. Gunakan email lain atau masuk.';
        } else {
            $ins = $pdo->prepare('INSERT INTO users (nama, email, password) VALUES (?, ?, ?)');
            $ins->execute([$nama, $email, password_hash($password, PASSWORD_DEFAULT)]);
            header('Location: index.php?registered=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Buat akun</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth">
  <main class="panel">
    <h1>Buat akun baru</h1>
    <?php foreach ($errors as $err): ?>
      <p class="alert err"><?= e($err) ?></p>
    <?php endforeach; ?>

    <form method="post" novalidate>
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <label for="nama">Nama lengkap</label>
      <input type="text" id="nama" name="nama" value="<?= e($nama) ?>" required autofocus>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?= e($email) ?>" required>

      <label for="password">Kata sandi (minimal 8 karakter)</label>
      <input type="password" id="password" name="password" required>

      <label for="konfirmasi">Ulangi kata sandi</label>
      <input type="password" id="konfirmasi" name="konfirmasi" required>

      <button type="submit">Buat akun</button>
    </form>
    <p class="alt">Sudah punya akun? <a href="index.php">Masuk</a></p>
  </main>
</body>
</html>
