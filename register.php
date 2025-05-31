<?php
session_start();
header('Content-Type: application/json');

// Kết nối cơ sở dữ liệu
try {
    $pdo = new PDO("mysql:host=localhost;dbname=dangki;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Kết nối cơ sở dữ liệu thất bại: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Kiểm tra dữ liệu
    if (empty($fullname) || empty($phone) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ.']);
        exit;
    }

    if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
        echo json_encode(['status' => 'error', 'message' => 'Số điện thoại không hợp lệ.']);
        exit;
    }

    if (strlen($password) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'Mật khẩu phải có ít nhất 6 ký tự.']);
        exit;
    }

    // Kiểm tra email đã tồn tại chưa
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email đã tồn tại.']);
        exit;
    }

    // Mã hóa mật khẩu và lưu vào cơ sở dữ liệu
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, phone_number, email, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$fullname, $phone, $email, $hashedPassword]);

    echo json_encode(['status' => 'success', 'message' => 'Đăng ký thành công! Vui lòng đăng nhập.']);
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ.']);
    exit;
}
?>