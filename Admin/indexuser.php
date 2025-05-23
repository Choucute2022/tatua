<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Kết nối DB
$pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", "");

// Tìm kiếm
$search = $_GET['search'] ?? '';
$params = [];
$sql = "SELECT * FROM users WHERE 1=1";

if ($search !== '') {
    $sql .= " AND (email LIKE ? OR phone_number LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Phân trang
$limit = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// Đếm tổng
$countSql = str_replace("SELECT *", "SELECT COUNT(*)", $sql);
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalUsers = $countStmt->fetchColumn();
$totalPages = ceil($totalUsers / $limit);

// Thêm LIMIT
$sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Người dùng</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fffaf0, #fff3e0);
            color: #4b2e1f;
            margin: 0;
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
        aside a:hover, aside a.active {
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
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .filter-form input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 250px;
        }
        .filter-form button {
            padding: 8px 16px;
            background: #f39c12;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
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
            min-width: 800px;
        }
        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #ffd699;
            text-align: left;
        }
        th {
            background: #f9e3b8;
            color: #a05e00;
            text-transform: uppercase;
            font-size: 13px;
        }
        tr:hover {
            background-color: #fff3d1;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            margin: 0 5px;
            padding: 6px 12px;
            background: #f0f0f0;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .pagination a.active {
            background: #f39c12;
            color: white;
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
            .container {
                flex-direction: column;
            }
            aside {
                width: 100%;
            }
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
        <div class="title">👥 Danh sách Người dùng</div>

        <form method="GET" class="filter-form">
            <input type="text" name="search" placeholder="Tìm theo email hoặc SĐT..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Tìm kiếm</button>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone_number']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </main>
</div>

<footer>
    &copy; <?= date('Y') ?> - Quản trị người dùng bởi PTIT Dev
</footer>
</body>
</html>
