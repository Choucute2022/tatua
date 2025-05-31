<?php
// Kết nối database
$conn = mysqli_connect('localhost', 'root', '', 'duan');
if (!$conn) {
    die('Không kết nối được database');
}

// Nhận bộ lọc trạng thái và tìm kiếm User ID
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$search_user_id = isset($_GET['user_id']) ? trim($_GET['user_id']) : '';

// Phân trang
$limit = 100; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page <= 0) $page = 1;
$offset = ($page - 1) * $limit;

// Xây dựng điều kiện WHERE
$where = 'WHERE 1';
if ($status_filter === 'pending') {
    $where .= " AND status = 'pending'";
} elseif ($status_filter === 'completed') {
    $where .= " AND status = 'completed'";
}
if ($search_user_id !== '') {
    $where .= " AND user_id = '" . mysqli_real_escape_string($conn, $search_user_id) . "'";
}

// Lấy danh sách đơn hàng
$sql = "SELECT * FROM orders $where ORDER BY id ASC LIMIT $offset, $limit";
$orders = mysqli_query($conn, $sql);

// Tính tổng số trang
$countSql = "SELECT COUNT(*) as total FROM orders $where";
$totalResult = mysqli_query($conn, $countSql);
$totalRow = mysqli_fetch_assoc($totalResult);
$total_pages = ceil($totalRow['total'] / $limit);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fb;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .table-container {
            max-width: 1100px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .add-btn {
            padding: 10px 18px;
            background-color: #0984e3;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
            margin-bottom: 10px;
        }

        .add-btn:hover {
            background-color: #0864b3;
        }

        .filter-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-select, .filter-input {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            padding: 12px 8px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        table th { background-color: #f9fafb; color: #333; }
        table tr:hover { background-color: #f1f7ff; }

        .delete-btn {
            padding: 6px 12px;
            border-radius: 6px;
            background-color: #d63031;
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }

        .delete-btn:hover {
            background-color: #b52b2c;
        }

        select[name="status"] {
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        /* Phân trang */
        .pagination {
            margin-top: 30px;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .pagination a {
            width: 10px;
            height: 10px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background: #eee;
            color: #333;
            border-radius: 50%;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .pagination a.active {
            background: #00cec9;
            color: white;
        }

        .pagination a:hover {
            background: #dfe6e9;
        }
    </style>
</head>
<body>

<h2>🧾 Quản lý đơn hàng</h2>

<div class="table-container">

    <div class="actions">
        <a href="index.php?url=orders/add" class="add-btn">➕ Thêm đơn hàng mới</a>

        <form method="GET" action="index.php" class="filter-group">
            <input type="hidden" name="url" value="orders">
            
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>Tất cả trạng thái</option>
                <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                <option value="completed" <?= $status_filter === 'completed' ? 'selected' : '' ?>>Đã hoàn thành</option>
            </select>

            <input type="text" name="user_id" value="<?= htmlspecialchars($search_user_id) ?>" placeholder="Tìm theo User ID..." class="filter-input">
            <button type="submit" class="add-btn" style="background: #27ae60;">🔍 Tìm</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($orders) > 0) { ?>
                <?php while ($row = mysqli_fetch_assoc($orders)) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['user_id']); ?></td>
                    <td><?= number_format($row['total'], 2, '.', ',') ?> USD</td>
                    <td>
                        <form method="POST" action="index.php?url=orders/update/<?= $row['id']; ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="completed" <?= $row['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <a href="index.php?url=orders/delete/<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">Xóa</a>
                    </td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5"><em>Không tìm thấy đơn hàng nào.</em></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <a href="index.php?url=orders&page=<?= $i ?>&status=<?= $status_filter ?>&user_id=<?= urlencode($search_user_id) ?>" class="<?= ($i == $page) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php } ?>
    </div>

</div>

</body>
</html>
