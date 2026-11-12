<?php
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$email = '';
$info  = isset($_GET['registered']) ? 'Akun berhasil dibuat. Silakan masuk.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!csrf_valid()) {
        $error = 'Sesi tidak valid. Muat ulang halaman dan coba lagi.';
    } else {
        $stmt = $pdo->prepare('SELECT id, nama, password FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_nama'] = $user['nama'];
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Email atau kata sandi salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masuk</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth">
  <main class="panel">
    <h1>Masuk ke akun Anda</h1>
    <?php if ($info): ?><p class="alert ok"><?= e($info) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="alert err"><?= e($error) ?></p><?php endif; ?>

    <form method="post" novalidate>
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?= e($email) ?>" required autofocus>

      <label for="password">Kata sandi</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Masuk</button>
    </form>
    <p class="alt">Belum punya akun? <a href="register.php">Buat akun</a></p>
  </main>
</body>
</html>
