<?php
session_start();
header('Content-Type: application/json');

// Kết nối cơ sở dữ liệu
try {
    $pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Kết nối cơ sở dữ liệu thất bại: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Kiểm tra dữ liệu
    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ.']);
        exit;
    }

    // Kiểm tra tài khoản
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        echo json_encode(['status' => 'success', 'message' => 'Đăng nhập thành công!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email hoặc mật khẩu không đúng.']);
    }
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ.']);
    exit;
}
?>