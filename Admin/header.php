<?php
// include 'darkmode.php';
?>

<header style="display: flex; justify-content: space-between; align-items: center; padding: 16px 32px;
    background: <?= $darkMode ? '#2c2c2c' : '#fff8e1' ?>;
    color: <?= $darkMode ? '#f1c40f' : '#d35400' ?>;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">

    <!-- Logo + tên thương hiệu -->
    <div style="display: flex; align-items: center; gap: 12px;">
        <div style="font-size: 24px;">🍹</div>
        <h1 style="font-size: 20px; font-weight: bold;">Tatua Milktea Admin</h1>
    </div>

    <!-- Nút Dark Mode -->
    <form method="POST" action="" style="margin: 0;">
        <button type="submit" name="toggle_dark" style="
            background: <?= $darkMode ? '#f39c12' : '#e67e22' ?>;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        ">
            <!-- <?= $darkMode ? '☀️ Sáng' : '🌙 Tối' ?> -->
        </button>
    </form>
</header>
