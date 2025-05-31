<?php
// add_order.php

// Kết nối database
$connection = new mysqli("localhost", "root", "", "duan");
if ($connection->connect_error) {
    die("Kết nối thất bại: " . htmlspecialchars($connection->connect_error));
}

// Lấy danh sách users
$userQuery = "SELECT id, email FROM users";
$userResult = $connection->query($userQuery);

// Xử lý thêm đơn hàng
if (isset($_POST['add_order'])) {
    $userId = (int)$_POST['user_id'];
    $total = (float)$_POST['total'];
    $status = $_POST['status'];

    $insertOrder = $connection->prepare("
        INSERT INTO orders (user_id, total, status, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $insertOrder->bind_param("ids", $userId, $total, $status);

    if ($insertOrder->execute()) {
        echo "<script>alert('✅ Thêm đơn hàng thành công!'); window.location.href = 'index.php?url=orders';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Thêm đơn hàng thất bại: " . htmlspecialchars($insertOrder->error) . "');</script>";
    }

    $insertOrder->close();
}

// Đóng kết nối khi kết thúc
register_shutdown_function(function() use ($connection) {
    if ($connection) $connection->close();
});
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm đơn hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .form-label {
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h2 class="text-center mb-4">➕ Thêm đơn hàng mới</h2>

    <div class="card p-4 mx-auto" style="max-width: 600px;">
        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="user_id" class="form-label">Khách hàng</label>
                <select name="user_id" id="user_id" class="form-select" required>
                    <option value="" disabled selected>-- Chọn khách hàng --</option>
                    <?php if ($userResult && $userResult->num_rows > 0): ?>
                        <?php while ($user = $userResult->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($user['id']) ?>">
                                <?= htmlspecialchars($user['email']) ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="" disabled>Không có khách hàng nào</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="total" class="form-label">Tổng tiền (USD)</label>
                <input type="number" name="total" id="total" class="form-control" step="0.01" min="0" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="pending" selected>Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <div class="d-grid">
                <button type="submit" name="add_order" class="btn btn-primary">➕ Thêm đơn hàng</button>
            </div>

            <div class="text-center mt-3">
                <a href="index.php?url=orders" class="text-decoration-none text-primary">⬅️ Quay lại danh sách đơn hàng</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
