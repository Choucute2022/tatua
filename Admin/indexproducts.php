<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", "");

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';
$filter = $_GET['filter'] ?? '';

$sql = "SELECT * FROM products WHERE name LIKE :search";
$params = [':search' => "%$search%"];

if ($filter === 'new') {
    $sql .= " AND is_new = 1";
} elseif ($filter === 'old') {
    $sql .= " AND is_new = 0";
}

if ($sort === 'asc') {
    $sql .= " ORDER BY current_price ASC";
} elseif ($sort === 'desc') {
    $sql .= " ORDER BY current_price DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Sản phẩm</title>
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

        .actions {
            margin-bottom: 16px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .actions input, .actions select {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
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

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-edit {
            background: #f39c12;
            color: white;
        }

        .btn-delete {
            background: #e74c3c;
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
        <div class="title">🧋 Quản lý Sản phẩm</div>
        <form class="actions" method="get">
            <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?= htmlspecialchars($search) ?>">
            <select name="sort">
                <option value="">-- Sắp xếp giá --</option>
                <option value="asc" <?= $sort == 'asc' ? 'selected' : '' ?>>Tăng dần</option>
                <option value="desc" <?= $sort == 'desc' ? 'selected' : '' ?>>Giảm dần</option>
            </select>
            <select name="filter">
                <option value="">-- Lọc sản phẩm --</option>
                <option value="new" <?= $filter == 'new' ? 'selected' : '' ?>>Mới</option>
                <option value="old" <?= $filter == 'old' ? 'selected' : '' ?>>Cũ</option>
            </select>
            <button class="btn btn-edit" type="submit">Lọc</button>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Giá hiện tại</th>
                        <th>Giá gốc</th>
                        <th>Mới</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['id']) ?></td>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= number_format((float)$p['current_price'], 0, ',', '.') ?> ₫</td>
                        <td><?= number_format((float)$p['original_price'], 0, ',', '.') ?> ₫</td>
                        <td><?= $p['is_new'] ? '✅' : '❌' ?></td>
                        <td>
                            <a class="btn btn-edit" href="editproduct.php?id=<?= $p['id'] ?>">Sửa</a>
                            <a class="btn btn-delete" href="delete_product.php?id=<?= $p['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xoá sản phẩm này?');">Xoá</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<footer>
    &copy; <?= date('Y') ?> - Dashboard quản trị bởi PTIT Dev
</footer>
</body>
</html>
