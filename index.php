<?php
session_start(); // Khởi tạo session

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Lấy thông tin người dùng từ session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trang chủ</title>
</head>
<body>
    <h2>Chào mừng, <?php echo $username; ?>!</h2>
    <p>ID người dùng: <?php echo $user_id; ?></p>
    <a href="logout.php">Đăng xuất</a>
</body>
</html>