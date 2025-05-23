<?php
session_start();

// Giả lập thông tin admin
$admin = [  
    'name' => 'Nguyễn Văn A',
    'email' => 'admin@tatuamilktea.vn',
    'phone' => '0909 999 999',
    'role' => 'Quản trị viên cấp cao',
    'joined' => '01/01/2024'
];

// Dark mode toggle
if (isset($_POST['toggle_dark'])) {
    $darkMode = isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] === true;
}

$darkMode = isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] === true;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Hồ sơ Admin - Tatua Milktea</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    :root {
      --main-color: #f57c00;
      --accent-color: #ffb300;
      --light-bg: #fffdf5;
      --light-text: #333;
      --dark-bg: #1e1e1e;
      --dark-card: #2c2c2c;
      --dark-text: #eee;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, sans-serif;
      background-color: <?= $darkMode ? 'var(--dark-bg)' : 'var(--light-bg)' ?>;
      color: <?= $darkMode ? 'var(--dark-text)' : 'var(--light-text)' ?>;
    }

    header {
      background: linear-gradient(to right, var(--main-color), var(--accent-color));
      color: white;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
    }

    .logo::before {
      content: "🍹";
      margin-right: 10px;
    }

    .dark-toggle {
      background: white;
      border: none;
      padding: 6px 12px;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
    }

    .container {
      max-width: 800px;
      margin: 40px auto;
      padding: 20px;
      background: <?= $darkMode ? 'var(--dark-card)' : 'white' ?>;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h1 {
      color: <?= $darkMode ? '#ffcc80' : '#f57c00' ?>;
      margin-bottom: 20px;
      font-size: 26px;
    }

    .profile-row {
      margin-bottom: 16px;
    }

    .profile-label {
      font-weight: bold;
      color: <?= $darkMode ? '#ffa726' : '#555' ?>;
    }

    footer {
      margin-top: 40px;
      background: <?= $darkMode ? '#111' : '#fff3e0' ?>;
      color: <?= $darkMode ? '#aaa' : '#a05e00' ?>;
      text-align: center;
      padding: 16px;
    }

    a.button {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 16px;
      background: var(--main-color);
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
    }

    a.button:hover {
      background: #e65100;
    }
  </style>
</head>
<body>

<header>
  <div class="logo">Tatua Milktea Admin</div>
  <form method="post">
    <button name="toggle_dark" class="dark-toggle">
      <?= $darkMode ? '🌞 Light Mode' : '🌙 Dark Mode' ?>
    </button>
  </form>
</header>

<div class="container">
  <h1>👤 Hồ sơ Quản trị viên</h1>

  <div class="profile-row">
    <span class="profile-label">👨 Họ tên:</span> <?= htmlspecialchars($admin['name']) ?>
  </div>
  <div class="profile-row">
    <span class="profile-label">📧 Email:</span> <?= htmlspecialchars($admin['email']) ?>
  </div>
  <div class="profile-row">
    <span class="profile-label">📱 Số điện thoại:</span> <?= htmlspecialchars($admin['phone']) ?>
  </div>
  <div class="profile-row">
    <span class="profile-label">🔐 Vai trò:</span> <?= htmlspecialchars($admin['role']) ?>
  </div>
  <div class="profile-row">
    <span class="profile-label">📅 Ngày tham gia:</span> <?= htmlspecialchars($admin['joined']) ?>
  </div>

  <a href="indexadmin.php" class="button">← Quay về Trang chính</a>
</div>

<footer>
  &copy; <?= date('Y') ?> Tatua Milktea. Hệ thống quản trị chuyên nghiệp.
</footer>

</body>
</html>
