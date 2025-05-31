<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    echo "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
    exit;
}

// Xử lý xóa đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
    try {
        $pdo->beginTransaction();
        $order_id = $_POST['delete_order_id'];

        $stmt = $pdo->prepare("DELETE FROM order_details WHERE order_id = :order_id");
        $stmt->execute(['order_id' => $order_id]);

        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = :order_id");
        $stmt->execute(['order_id' => $order_id]);

        $pdo->commit();
        header("Location: indexorders.php?delete_success=1&order_id=$order_id&total_amount=0");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Lỗi khi xóa đơn hàng: " . $e->getMessage();
        exit;
    }
}

$currentPage = basename($_SERVER['PHP_SELF']);

// Xử lý lọc dữ liệu
$sql = "SELECT orders.id, orders.user_id, users.name AS customer_name, users.email, users.phone_number, orders.total_amount, orders.status, orders.created_at
        FROM orders
        LEFT JOIN users ON orders.user_id = users.id
        WHERE 1";
$params = [];

if (!empty($_GET['search_id'])) {
    $sql .= " AND orders.id = :search_id";
    $params['search_id'] = $_GET['search_id'];
}
if (!empty($_GET['search_name'])) {
    $sql .= " AND users.name LIKE :search_name";
    $params['search_name'] = '%' . $_GET['search_name'] . '%';
}
if (!empty($_GET['search_status'])) {
    $sql .= " AND orders.status = :search_status";
    $params['search_status'] = $_GET['search_status'];
}
if (!empty($_GET['start_date'])) {
    $sql .= " AND DATE(orders.created_at) >= :start_date";
    $params['start_date'] = $_GET['start_date'];
}
if (!empty($_GET['end_date'])) {
    $sql .= " AND DATE(orders.created_at) <= :end_date";
    $params['end_date'] = $_GET['end_date'];
}

$sql .= " ORDER BY orders.created_at DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn hàng</title>
    <style>
        * {margin: 0; padding: 0; box-sizing: border-box;}
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fffaf0, #fff3e0);
            color: #4b2e1f;
        }
        header {
            background: #fff3e0;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(243, 156, 18, 0.1);
        }
        header h1 {
            color: #e67e22;
            font-size: 22px;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        aside {
            width: 240px;
            background: #fff8e1;
            padding: 24px;
            border-right: 1px solid #ffe0b2;
        }
        aside h2 {
            font-size: 16px;
            margin-bottom: 16px;
            color: #a05e00;
        }
        aside a {
            display: block;
            padding: 10px 14px;
            margin-bottom: 8px;
            background: #fffaf0;
            border-radius: 8px;
            color: #4b2e1f;
            font-weight: 500;
            text-decoration: none;
        }
        aside a:hover,
        aside a.active {
            background: #f39c12;
            color: white;
            font-weight: bold;
        }
        main {
            flex: 1;
            padding: 32px;
        }
        .title {
            font-size: 24px;
            color: #c87f0a;
            margin-bottom: 24px;
        }
        .filter-form {
            margin-bottom: 24px;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            background: #fff8e1;
            padding: 16px;
            border-radius: 12px;
        }
        .filter-form input, .filter-form select {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-family: inherit;
        }
        .filter-form button {
            background: #f39c12;
            border: none;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .filter-form .reset-btn {
            background: #ccc;
            color: #333;
        }
        .table-container {
            background: #fff8e1;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }
        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #ffd699;
            text-align: left;
        }
        th {
            background: #f9e3b8;
            color: #a05e00;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
        }
        tr:hover {
            background-color: #fff3d1;
        }
        .delete-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .delete-btn:hover {
            background: #c0392b;
        }
        footer {
            text-align: center;
            padding: 16px;
            background: #fff3e0;
            font-size: 14px;
            color: #a05e00;
            border-top: 1px solid #ffe0b2;
        }
        @media (max-width: 768px) {
            .container { flex-direction: column; }
            aside { width: 100%; }
        }
    </style>
</head>
<body>
<header>
    <h1>🔶 Trang quản trị</h1>
    <nav><a href="logout.php">Đăng xuất</a></nav>
</header>

<div class="container">
    <aside>
        <h2>Menu</h2>
        <a href="indexadmin.php" class="<?= $currentPage == 'indexadmin.php' ? 'active' : '' ?>">Tổng quan</a>
        <a href="indexorders.php" class="<?= $currentPage == 'indexorders.php' ? 'active' : '' ?>">Đơn hàng</a>
        <a href="indexproducts.php" class="<?= $currentPage == 'indexproducts.php' ? 'active' : '' ?>">Sản phẩm</a>
        <a href="indexuser.php" class="<?= $currentPage == 'indexuser.php' ? 'active' : '' ?>">Khách hàng</a>
        <a href="caidat.php" class="<?= $currentPage == 'caidat.php' ? 'active' : '' ?>">Cài đặt</a>
    </aside>

    <main>
        <div class="title">📦 Quản lý Đơn hàng</div>

        <?php if (isset($_GET['delete_success'])): ?>
            <div style="color: green; margin-bottom: 16px;">
                Đã xóa đơn hàng ID <?= htmlspecialchars($_GET['order_id']) ?> với tổng tiền <?= number_format($_GET['total_amount'], 0, ',', '.') ?> ₫. 
                Doanh thu sẽ được cập nhật khi bạn xem lại trang Tổng quan.
            </div>
        <?php endif; ?>

        <form method="GET" class="filter-form">
            <input type="text" name="search_id" placeholder="ID đơn" value="<?= htmlspecialchars($_GET['search_id'] ?? '') ?>">
            <input type="text" name="search_name" placeholder="Tên khách hàng" value="<?= htmlspecialchars($_GET['search_name'] ?? '') ?>">
            <select name="search_status">
                <option value="">-- Trạng thái --</option>
                <option value="pending" <?= isset($_GET['search_status']) && $_GET['search_status'] === 'pending' ? 'selected' : '' ?>>Đang xử lý</option>
                <option value="completed" <?= isset($_GET['search_status']) && $_GET['search_status'] === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                <option value="cancelled" <?= isset($_GET['search_status']) && $_GET['search_status'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
            </select>
            <input type="date" name="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">
            <input type="date" name="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">
            <button type="submit">🔍 Lọc</button>
            <a href="indexorders.php" class="reset-btn" style="text-decoration: none;"><button type="button" class="reset-btn">❌ Xóa lọc</button></a>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($orders) === 0): ?>
                        <tr><td colspan="6">Không có đơn hàng phù hợp.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']) ?></td>
                            <td>
                                <?= htmlspecialchars($order['user_id'] ?? 'Không có') ?> - 
                                <?= htmlspecialchars($order['customer_name'] ?? 'Khách vãng lai') ?>
                            </td>
                            <td><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</td>
                            <td><?= htmlspecialchars($order['status']) ?></td>
                            <td><?= htmlspecialchars($order['created_at']) ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                                    <input type="hidden" name="delete_order_id" value="<?= htmlspecialchars($order['id']) ?>">
                                    <button type="submit" class="delete-btn">🗑️ Xóa</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<footer>
    © <?= date('Y') ?> - Dashboard quản trị bởi PTIT Dev
</footer>
</body>
</html>