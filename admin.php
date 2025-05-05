<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Tạo CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Kết nối cơ sở dữ liệu
require_once 'config.php';

// Xử lý thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed!");
    }

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $current_price = filter_var($_POST['current_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $original_price = filter_var($_POST['original_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?: null;
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $image = filter_var($_POST['image'], FILTER_SANITIZE_URL);
    $isNew = isset($_POST['isNew']) ? 1 : 0;
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);

    $stmt = $conn->prepare("INSERT INTO products (name, current_price, original_price, description, image, isNew, category) VALUES (:name, :current_price, :original_price, :description, :image, :isNew, :category)");
    $stmt->execute([
        ':name' => $name,
        ':current_price' => $current_price,
        ':original_price' => $original_price,
        ':description' => $description,
        ':image' => $image,
        ':isNew' => $isNew,
        ':category' => $category
    ]);
}

// Xử lý xóa sản phẩm
if (isset($_GET['delete_product'])) {
    $id = filter_var($_GET['delete_product'], FILTER_SANITIZE_NUMBER_INT);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

// Xử lý thêm danh mục
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed!");
    }

    $category_name = filter_var($_POST['category_name'], FILTER_SANITIZE_STRING);
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (:name)");
    $stmt->execute([':name' => $category_name]);
}

// Xử lý xóa danh mục
if (isset($_GET['delete_category'])) {
    $id = filter_var($_GET['delete_category'], FILTER_SANITIZE_NUMBER_INT);
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

// Xử lý cập nhật trạng thái đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order_status'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed!");
    }

    $order_id = filter_var($_POST['order_id'], FILTER_SANITIZE_NUMBER_INT);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    $stmt = $conn->prepare("UPDATE orders SET status = :status WHERE id = :order_id");
    $stmt->execute([':status' => $status, ':order_id' => $order_id]);
}

// Lấy dữ liệu
$products = $conn->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
$orders = $conn->query("SELECT * FROM orders")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Quản lý Sản phẩm, Danh mục và Đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .tab {
            overflow: hidden;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
        }
        .tab button {
            background-color: inherit;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 16px;
        }
        .tab button:hover {
            background-color: #ddd;
        }
        .tab button.active {
            background-color: #ccc;
        }
        .tab-content {
            display: none;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .tab-content.active {
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .add-form {
            margin-bottom: 20px;
        }
        .add-form input, .add-form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .add-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .add-form button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        <div class="tab">
            <button class="tablinks active" onclick="openTab(event, 'products')">Quản lý Sản phẩm</button>
            <button class="tablinks" onclick="openTab(event, 'categories')">Quản lý Danh mục</button>
            <button class="tablinks" onclick="openTab(event, 'orders')">Quản lý Đơn hàng</button>
        </div>

        <div id="products" class="tab-content active">
            <h2>Quản lý Sản phẩm</h2>
            <div class="add-form">
                <h3>Thêm sản phẩm mới</h3>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="text" name="name" placeholder="Tên sản phẩm" required>
                    <input type="number" name="current_price" placeholder="Giá hiện tại" step="0.01" required>
                    <input type="number" name="original_price" placeholder="Giá gốc (nếu có)" step="0.01">
                    <input type="text" name="description" placeholder="Mô tả" required>
                    <input type="url" name="image" placeholder="Đường dẫn ảnh" required>
                    <select name="category" required>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['name']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label><input type="checkbox" name="isNew"> Sản phẩm mới</label>
                    <button type="submit" name="add_product">Thêm sản phẩm</button>
                </form>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Giá hiện tại</th>
                        <th>Giá gốc</th>
                        <th>Mô tả</th>
                        <th>Ảnh</th>
                        <th>Mới</th>
                        <th>Danh mục</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo number_format($product['current_price'], 0, ',', '.') . 'đ'; ?></td>
                        <td><?php echo $product['original_price'] ? number_format($product['original_price'], 0, ',', '.') . 'đ' : 'N/A'; ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 50px;"></td>
                        <td><?php echo $product['isNew'] ? 'Có' : 'Không'; ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><a href="?delete_product=<?php echo $product['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="categories" class="tab-content">
            <h2>Quản lý Danh mục</h2>
            <div class="add-form">
                <h3>Thêm danh mục mới</h3>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="text" name="category_name" placeholder="Tên danh mục" required>
                    <button type="submit" name="add_category">Thêm danh mục</button>
                </form>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $category['id']; ?></td>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><a href="?delete_category=<?php echo $category['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="orders" class="tab-content">
            <h2>Quản lý Đơn hàng</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên người nhận</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['recipient_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['phone_number']); ?></td>
                        <td><?php echo htmlspecialchars($order['address']); ?></td>
                        <td><?php echo number_format($order['total'], 0, ',', '.') . 'đ'; ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="Chờ xử lý" <?php echo $order['status'] === 'Chờ xử lý' ? 'selected' : ''; ?>>Chờ xử lý</option>
                                    <option value="Đang giao" <?php echo $order['status'] === 'Đang giao' ? 'selected' : ''; ?>>Đang giao</option>
                                    <option value="Hoàn thành" <?php echo $order['status'] === 'Hoàn thành' ? 'selected' : ''; ?>>Hoàn thành</option>
                                </select>
                                <input type="hidden" name="update_order_status" value="1">
                            </form>
                        </td>
                        <td><a href="order_details.php?id=<?php echo $order['id']; ?>">Xem chi tiết</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i <tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i <tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
    </script>
</body>
</html>

<?php $conn = null; ?>