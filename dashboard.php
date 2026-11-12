<?php
require 'config.php';

// Halaman ini hanya untuk user yang sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT nama, email, created_at FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) { // akun sudah dihapus
    session_destroy();
    header('Location: index.php');
    exit;
}

$total = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$terbaru = $pdo->query('SELECT nama, created_at FROM users ORDER BY id DESC LIMIT 5')->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="topbar">
    <strong>Dashboard</strong>
    <a href="logout.php" class="btn-link">Keluar</a>
  </header>

  <main class="content">
    <h1>Halo, <?= e($user['nama']) ?></h1>
    <p class="muted">Anda masuk sebagai <?= e($user['email']) ?>.</p>

    <section class="grid">
      <div class="card">
        <h2>Akun Anda</h2>
        <dl>
          <dt>Nama</dt><dd><?= e($user['nama']) ?></dd>
          <dt>Email</dt><dd><?= e($user['email']) ?></dd>
          <dt>Terdaftar sejak</dt><dd><?= e(date('d M Y, H:i', strtotime($user['created_at']))) ?></dd>
        </dl>
      </div>

      <div class="card">
        <h2><?= $total ?> pengguna terdaftar</h2>
        <ul class="list">
          <?php foreach ($terbaru as $u): ?>
            <li><span><?= e($u['nama']) ?></span>
                <span class="muted"><?= e(date('d M Y', strtotime($u['created_at']))) ?></span></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>
  </main>
</body>
</html>
