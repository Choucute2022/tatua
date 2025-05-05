<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

$order_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : null;
$order_details = [];
if ($order_id) {
    $stmt = $conn->prepare("SELECT * FROM order_details WHERE order_id = :order_id");
    $stmt->execute([':order_id' => $order_id]);
    $order_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Chi tiết đơn hàng #<?php echo $order_id; ?></h1>
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
                        <td><?php echo htmlspecialchars($detail['product_name']); ?></td>
                        <td><?php echo $detail['quantity']; ?></td>
                        <td><?php echo number_format($detail['price'], 0, ',', '.') . 'đ'; ?></td>
                        <td><?php echo number_format($detail['quantity'] * $detail['price'], 0, ',', '.') . 'đ'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="admin.php#orders">Quay lại</a>
    </div>
</body>
</html>

<?php $conn = null; ?>