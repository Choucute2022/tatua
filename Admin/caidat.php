<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cài đặt hệ thống</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-main: #fffaf0;
            --bg-alt: #fff3e0;
            --bg-sidebar: #fff8e1;
            --bg-section: #ffffff;
            --text-main: #4b2e1f;
            --text-secondary: #a05e00;
            --primary: #f39c12;
        }

        body.dark-mode {
            --bg-main: #2e2e2e;
            --bg-alt: #1f1f1f;
            --bg-sidebar: #2c2c2c;
            --bg-section: #3a3a3a;
            --text-main: #f1f1f1;
            --text-secondary: #ffd699;
            --primary: #ffa500;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background: var(--bg-main);
            color: var(--text-main);
            transition: background 0.3s, color 0.3s;
        }

        header {
            background: var(--bg-alt);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(243, 156, 18, 0.1);
        }

        header h1 {
            color: var(--primary);
            font-size: 22px;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        aside {
            width: 240px;
            background: var(--bg-sidebar);
            padding: 24px;
            border-right: 1px solid #ffe0b2;
        }

        aside h2 {
            font-size: 16px;
            margin-bottom: 16px;
            color: var(--text-secondary);
        }

        aside a {
            display: block;
            padding: 10px 14px;
            margin-bottom: 8px;
            background: var(--bg-main);
            border-radius: 8px;
            color: var(--text-main);
            text-decoration: none;
        }

        aside a:hover, aside a.active {
            background: var(--primary);
            color: white;
            font-weight: bold;
        }

        main {
            flex: 1;
            padding: 32px;
        }

        .section {
            background: var(--bg-section);
            padding: 24px;
            margin-bottom: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .section h3 {
            margin-top: 0;
            color: var(--primary);
        }

        label {
            display: block;
            margin: 12px 0 6px;
        }

        input, select {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 100%;
            max-width: 400px;
            background: white;
        }

        button {
            margin-top: 16px;
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        footer {
            text-align: center;
            padding: 16px;
            background: var(--bg-alt);
            font-size: 14px;
            color: var(--text-secondary);
            border-top: 1px solid #ffe0b2;
        }
    </style>
</head>
<body>
<header>
    <h1>🔧 Cài đặt hệ thống</h1>
    <nav><a href="logout.php">Đăng xuất</a></nav>
</header>

<div class="container">
    <aside>
        <h2>Menu</h2>
        <a href="indexadmin.php" class="<?= $currentPage == 'indexadmin.php' ? 'active' : '' ?>">Tổng quan</a>
        <a href="indexorders.php" class="<?= $currentPage == 'indexorders.php' ? 'active' : '' ?>">Đơn hàng</a>
        <a href="indexproducts.php" class="<?= $currentPage == 'indexproducts.php' ? 'active' : '' ?>">Sản phẩm</a>
        <a href="indexuser.php" class="<?= $currentPage == 'indexuser.php' ? 'active' : '' ?>">Khách hàng</a>
        <a href="caidat.php" class="<?= $currentPage == 'caidat.php' ? 'active' : '' ?>">Cài đặt</a>
    </aside>

    <main>
        <div class="section">
            <h3>🎨 Giao diện</h3>
            <label><input type="checkbox" id="darkModeToggle"> Bật chế độ tối</label>
            <label>Logo cửa hàng</label>
            <input type="file">
            <label>Màu chủ đạo</label>
            <select>
                <option>Cam</option>
                <option>Vàng</option>
                <option>Nâu</option>
            </select>
        </div>

        <div class="section">
            <h3>🛍️ Thông tin cửa hàng</h3>
            <label>Tên quán</label>
            <input type="text" value="Tatua Milktea">
            <label>Số điện thoại</label>
            <input type="text" value="0123 456 789">
            <label>Địa chỉ</label>
            <input type="text" value="123 Trà Sữa Street">
        </div>

        <div class="section">
            <h3>🔐 Bảo mật</h3>
            <label>Mật khẩu hiện tại</label>
            <input type="password">
            <label>Mật khẩu mới</label>
            <input type="password">
            <label><input type="checkbox"> Bật xác thực 2 bước</label>
        </div>

        <div class="section">
            <h3>⚙️ Hệ thống</h3>
            <label>Giờ mở cửa</label>
            <input type="text" value="08:00 - 22:00">
            <label>Trạng thái website</label>
            <select>
                <option>Đang hoạt động</option>
                <option>Bảo trì</option>
            </select>
        </div>

        <div class="section">
            <h3>📤 Sao lưu & Khôi phục</h3>
            <button>Tải về dữ liệu</button>
            <label>Khôi phục từ file SQL</label>
            <input type="file">
        </div>
    </main>
</div>

<footer>
    &copy; <?= date('Y') ?> - Trang cài đặt hệ thống Tatua Milktea
</footer>

<script>
    const toggle = document.getElementById('darkModeToggle');

    // Load trạng thái dark mode từ localStorage
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
        toggle.checked = true;
    }

    toggle.addEventListener('change', () => {
        if (toggle.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'true');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'false');
        }
    });
</script>
</body>
</html>
