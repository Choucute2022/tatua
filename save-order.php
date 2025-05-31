<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ']);
    exit;
}

$input = file_get_contents('php://input');
$orderData = json_decode($input, true);

// Kiểm tra CSRF Token
if (!isset($orderData['csrfToken']) || $orderData['csrfToken'] !== $_SESSION['csrf_token']) {
    echo json_encode(['status' => 'error', 'message' => 'Phiên bảo mật không hợp lệ']);
    exit;
}

// Kiểm tra giỏ hàng
if (empty($_SESSION['cart'])) {
    echo json_encode(['status' => 'error', 'message' => 'Giỏ hàng trống']);
    exit;
}

// Kết nối database
$conn = new mysqli("localhost", "root", "", "duan");
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Kết nối database thất bại']);
    exit;
}

// Tính tổng tiền
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['totalPrice'];
}

// Lưu vào bảng orders
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null; // Giả sử người dùng đăng nhập
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
$stmt->bind_param("ii", $user_id, $total_amount);
if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi lưu đơn hàng: ' . $conn->error]);
    $stmt->close();
    $conn->close();
    exit;
}

$order_id = $conn->insert_id;

// Lưu vào bảng order_details
$stmt_detail = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($_SESSION['cart'] as $item) {
    $product_id = (int)$item['id'];
    $quantity = (int)$item['quantity'];
    $price = (int)$item['price'];
    $stmt_detail->bind_param("iiii", $order_id, $product_id, $quantity, $price);
    if (!$stmt_detail->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi lưu chi tiết đơn hàng: ' . $conn->error]);
        $stmt_detail->close();
        $stmt->close();
        $conn->close();
        exit;
    }
}
$stmt_detail->close();
$stmt->close();

// Lưu thông tin giao hàng (tùy chọn, có thể thêm bảng mới)
$delivery_info = [
    'name' => filter_var($orderData['name'] ?? '', FILTER_SANITIZE_STRING),
    'phone' => filter_var($orderData['phone'] ?? '', FILTER_SANITIZE_STRING),
    'address' => filter_var($orderData['address'] ?? '', FILTER_SANITIZE_STRING),
    'note' => filter_var($orderData['note'] ?? '', FILTER_SANITIZE_STRING),
    'payment' => filter_var($orderData['payment'] ?? 'cash', FILTER_SANITIZE_STRING),
    'store' => filter_var($orderData['store'] ?? '', FILTER_SANITIZE_STRING)
];

// Xóa giỏ hàng sau khi lưu
$_SESSION['cart'] = [];

// Đóng kết nối
$conn->close();

// Trả về phản hồi
echo json_encode(['status' => 'success', 'message' => 'Đơn hàng đã được đặt thành công']);
?>