<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
header("Location: admin.php#orders");
exit();


require_once 'config.php'; // Giả sử có file config để kết nối database

// Lấy danh sách đơn hàng
$result = $conn->query("SELECT * FROM orders");
$orders = $result->fetch_all(MYSQLI_ASSOC);

// Xử lý chi tiết đơn hàng (giả sử có bảng order_details liên kết với orders qua order_id)
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $details_result = $conn->query("SELECT * FROM order_details WHERE order_id = $order_id");
    $order_details = $details_result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .actions a { margin-right: 10px; text-decoration: none; color: #007bff; }
        .order-details { display: none; }
        .order-details.active { display: block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Quản lý đơn hàng</h1>

        <!-- Danh sách đơn hàng -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo $order['order_date']; ?></td>
                        <td><?php echo number_format($order['total_amount'], 0, ',', '.') . 'đ'; ?></td>
                        <td><?php echo $order['status']; ?></td>
                        <td class="actions">
                            <a href="?order_id=<?php echo $order['id']; ?>">Xem chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Chi tiết đơn hàng -->
        <?php if (isset($_GET['order_id'])): ?>
            <div class="order-details active">
                <h2>Chi tiết đơn hàng #<?php echo $order_id; ?></h2>
                <table>
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_details as $detail): ?>
                            <tr>
                                <td><?php echo $detail['product_name']; ?></td>
                                <td><?php echo $detail['quantity']; ?></td>
                                <td><?php echo number_format($detail['price'], 0, ',', '.') . 'đ'; ?></td>
                                <td><?php echo number_format($detail['quantity'] * $detail['price'], 0, ',', '.') . 'đ'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="admin-orders.php">Quay lại</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>