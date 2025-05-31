<?php
session_start();
$host = "localhost";
$dbname = "duan";
$username = "root";
$password = "";

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của tôi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --yellow: #FFD700;
            --orange: #F5A623;
            --light-gray: #f9f9f9;
            --dark: #333;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: white;
            color: var(--dark);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background-color: #fff8e1;
            border-right: 1px solid #eee;
            padding: 20px 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar li {
            margin: 18px 0;
        }

        .sidebar li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            display: flex;
            align-items: center;
            padding: 10px;
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
        }

        .sidebar li a:hover,
        .sidebar li a.active {
            background-color: var(--yellow);
            border-left: 4px solid var(--orange);
            color: var(--dark);
        }

        .main {
            flex-grow: 1;
            padding: 30px;
            background-color: #fffdf6;
        }

        h2 {
            color: var(--orange);
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        table thead {
            background-color: var(--yellow);
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background-color: #fff8e1;
        }

        .status {
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 12px;
            display: inline-block;
            font-size: 0.9em;
        }

        .pending { background-color: #ffe082; color: #795548; }
        .completed { background-color: #a5d6a7; color: #2e7d32; }
        .cancelled { background-color: #ef9a9a; color: #b71c1c; }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main {
                padding: 15px;
            }

            table, thead, tbody, th, td, tr {
                display: block;
            }

            th {
                display: none;
            }

            td {
                border: none;
                position: relative;
                padding-left: 50%;
                margin-bottom: 10px;
                background-color: white;
            }

            td:before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                font-weight: bold;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <ul>
            <li><a href="../index.php">🏠 Trang chủ</a></li>
            <li><a href="../profile.php">👤 Thông tin tài khoản</a></li>
            <li><a href="orders.php" class="active">🧾 Đơn hàng của tôi</a></li>
            <li><a href="#">🏷️ Mã khuyến mãi</a></li>
            <li><a href="../logout.php">↩️ Đăng xuất</a></li>
        </ul>
    </div>

    <div class="main">
        <h2>Đơn hàng của bạn</h2>

        <?php if (count($orders) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td data-label="ID">#<?= htmlspecialchars($order['id']) ?></td>
                    <td data-label="Ngày đặt"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                    <td data-label="Tổng tiền"><?= number_format($order['total_amount'], 0, ',', '.') ?> đ</td>
                    <td data-label="Trạng thái">
                        <span class="status <?= strtolower($order['status']) ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>Bạn chưa có đơn hàng nào.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
