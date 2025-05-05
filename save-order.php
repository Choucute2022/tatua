<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $orderData = json_decode($input, true);

    // Kiểm tra CSRF Token
    if (!isset($orderData['csrfToken']) || $orderData['csrfToken'] !== $_SESSION['csrf_token']) {
        echo json_encode(['status' => 'error', 'message' => 'Phiên bảo mật không hợp lệ']);
        exit;
    }

    // Ở đây bạn có thể lưu đơn hàng vào cơ sở dữ liệu
    // Ví dụ: Lưu vào file hoặc database (MySQL, SQLite, v.v.)
    // Để đơn giản, ta chỉ trả về thông báo thành công

    // Xóa giỏ hàng trong session sau khi đặt hàng thành công
    $_SESSION['cart'] = [];

    echo json_encode(['status' => 'success', 'message' => 'Đơn hàng đã được đặt thành công']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ']);
}
?>