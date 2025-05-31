<?php
$mysqli = require __DIR__ . "/database.php";

// Lấy email từ URL thay vì token
if (!isset($_GET["email"]) || !filter_var($_GET["email"], FILTER_VALIDATE_EMAIL)) {
    die("Yêu cầu không hợp lệ.");
}

$email = $_GET["email"];

// Lấy thông tin người dùng từ database dựa trên email
$sql = "SELECT * FROM user WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user === null) {
    die("Người dùng không tồn tại."); // Email không hợp lệ hoặc đã bị xóa
}

// Bỏ qua kiểm tra token và thời hạn
// Vì bạn đã chọn không sử dụng token

?>
<!DOCTYPE html>
<html>
<head>
    <title>Đặt lại mật khẩu</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Đặt lại mật khẩu cho <?= htmlspecialchars($user["email"]) ?></h1>
    <form method="post" action="process-reset-password.php">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        
        <label for="password">Mật khẩu mới</label>
        <input type="password" id="password" name="password" required>
        
        <label for="password_confirmation">Nhập lại mật khẩu</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
        
        <button>Đặt lại mật khẩu</button>
    </form>
</body>
</html>