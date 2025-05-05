<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            text-align: center;
        }
        .cart-btn, .products-btn {
            padding: 10px 20px;
            background-color: #ff5733;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
        }
        .cart-btn:hover, .products-btn:hover {
            background-color: #e64a2a;
        }
    </style>
</head>
<body>
    <h1>Chào mừng đến với cửa hàng</h1>
    <button class="products-btn" onclick="window.location.href='duan1.php'">Xem sản phẩm</button>
    <button class="cart-btn" onclick="window.location.href='order-details.php'">Xem giỏ hàng</button>
</body>
</html>