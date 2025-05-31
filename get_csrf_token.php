<?php
// Bật output buffering để tránh lỗi đầu ra trước JSON
ob_start();

// Cấu hình session
ini_set('session.gc_maxlifetime', 3600); // 1 giờ
session_start();

// Khóa session để tránh race condition
session_write_close();

// Thiết lập tiêu đề Content-Type cho JSON
header('Content-Type: application/json; charset=utf-8');

// Kiểm tra phương thức yêu cầu
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Phương thức yêu cầu không được phép']);
    exit;
}

try {
    // Tạo CSRF token nếu chưa tồn tại
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    // Trả về phản hồi JSON
    echo json_encode([
        'status' => 'success',
        'csrf_token' => $_SESSION['csrf_token']
    ]);
} catch (Exception $e) {
    // Xử lý lỗi khi tạo random_bytes
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi khi tạo CSRF token: ' . $e->getMessage()
    ]);
}

// Xóa bộ đệm đầu ra
ob_end_flush();
?>