<?php
session_start();

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?error=please_login');
    exit;
}

try {
    // Kết nối cơ sở dữ liệu
    $pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . htmlspecialchars($e->getMessage()));
}

// Xử lý tạo đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Lấy dữ liệu từ form
        $total_amount = filter_var($_POST['total_amount'] ?? 0, FILTER_VALIDATE_INT);
        $status = filter_var($_POST['status'] ?? 'pending', FILTER_SANITIZE_STRING);
        $user_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);

        // Kiểm tra dữ liệu
        if ($user_id === false || $user_id <= 0) {
            throw new Exception("ID người dùng không hợp lệ.");
        }
        if ($total_amount === false || $total_amount < 0) {
            throw new Exception("Tổng tiền không hợp lệ.");
        }

        // Chèn đơn hàng vào bảng orders
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (:user_id, :total_amount, :status)");
        $stmt->execute([
            'user_id' => $user_id,
            'total_amount' => $total_amount,
            'status' => $status
        ]);

        // Lấy ID đơn hàng vừa tạo
        $order_id = $pdo->lastInsertId();

        // Xử lý chi tiết đơn hàng (nếu có)
        if (isset($_POST['products']) && is_array($_POST['products'])) {
            $stmt = $pdo->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
            foreach ($_POST['products'] as $product) {
                $product_id = filter_var($product['product_id'] ?? 0, FILTER_VALIDATE_INT);
                $quantity = filter_var($product['quantity'] ?? 0, FILTER_VALIDATE_INT);
                $price = filter_var($product['price'] ?? 0, FILTER_VALIDATE_INT);

                if ($product_id > 0 && $quantity > 0 && $price >= 0) {
                    $stmt->execute([
                        'order_id' => $order_id,
                        'product_id' => $product_id,
                        'quantity' => $quantity,
                        'price' => $price
                    ]);
                }
            }
        }

        $pdo->commit();
        header("Location: indexorders.php?order_success=1&order_id=$order_id");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Lỗi lưu đơn hàng: " . htmlspecialchars($e->getMessage()));
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Đơn Hàng</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fffaf0, #fff3e0);
            color: #4b2e1f;
            padding: 32px;
        }
        .form-container {
            background: #fff8e1;
            padding: 24px;
            border-radius: 16px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .form-container h2 {
            color: #c87f0a;
            margin-bottom: 24px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-family: inherit;
        }
        .form-group button {
            background: #f39c12;
            border: none;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>📦 Tạo Đơn Hàng</h2>
        <form method="POST">
            <div class="form-group">
                <label for="total_amount">Tổng tiền (VNĐ)</label>
                <input type="number" name="total_amount" id="total_amount" required min="0">
            </div>
            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select name="status" id="status">
                    <option value="pending">Đang xử lý</option>
                    <option value="completed">Hoàn thành</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
            </div>
            <!-- Thêm sản phẩm nếu cần -->
            <div class="form-group">
                <button type="submit">Tạo đơn hàng</button>
            </div>
        </form>
    </div>
</body>
</html>