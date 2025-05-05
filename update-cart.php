<?php
session_start();
header('Content-Type: application/json');

// Kiểm tra CSRF token
if (!isset($_SESSION['csrf_token'])) {
    echo json_encode(['status' => 'error', 'message' => 'Phiên bảo mật không tồn tại']);
    exit;
}

// Nhận dữ liệu từ AJAX
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra CSRF token
if (!isset($data['csrfToken']) || $data['csrfToken'] !== $_SESSION['csrf_token']) {
    echo json_encode(['status' => 'error', 'message' => 'Phiên bảo mật không hợp lệ']);
    exit;
}

// Kiểm tra dữ liệu giỏ hàng
if (!isset($data['cart']) || !is_array($data['cart'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu giỏ hàng không hợp lệ']);
    exit;
}

// Làm sạch và cập nhật giỏ hàng
$cleaned_cart = [];
foreach ($data['cart'] as $item) {
    if (!isset($item['id'], $item['name'], $item['quantity'], $item['price'], $item['totalPrice'], $item['sizeLevel'], $item['sugarLevel'], $item['iceLevel'], $item['toppings'], $item['image'])) {
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu sản phẩm không đầy đủ']);
        exit;
    }

    // Kiểm tra giá trị hợp lệ
    $quantity = filter_var($item['quantity'], FILTER_SANITIZE_NUMBER_INT);
    $price = filter_var($item['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $totalPrice = filter_var($item['totalPrice'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    if ($quantity < 1 || $price < 0 || $totalPrice < 0) {
        echo json_encode(['status' => 'error', 'message' => 'Số lượng hoặc giá trị không hợp lệ']);
        exit;
    }

    $cleaned_item = [
        'id' => filter_var($item['id'], FILTER_SANITIZE_NUMBER_INT),
        'name' => filter_var($item['name'], FILTER_SANITIZE_STRING),
        'quantity' => $quantity,
        'price' => $price,
        'totalPrice' => $totalPrice,
        'sizeLevel' => filter_var($item['sizeLevel'], FILTER_SANITIZE_STRING),
        'sugarLevel' => filter_var($item['sugarLevel'], FILTER_SANITIZE_STRING),
        'iceLevel' => filter_var($item['iceLevel'], FILTER_SANITIZE_STRING),
        'toppings' => filter_var($item['toppings'], FILTER_SANITIZE_STRING),
        'image' => filter_var($item['image'], FILTER_SANITIZE_URL)
    ];
    $cleaned_cart[] = $cleaned_item;
}

// Cập nhật giỏ hàng trong session
$_SESSION['cart'] = $cleaned_cart;

// Trả về phản hồi thành công
echo json_encode(['status' => 'success', 'message' => 'Giỏ hàng đã được cập nhật']);
?>