<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
header("Location: admin.php#products");
  exit();

require_once 'config.php'; // Giả sử có file config để kết nối database

// Xử lý thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = 'default.png'; // Giả sử dùng ảnh mặc định, có thể mở rộng để upload ảnh
    $category = $_POST['category'];

    $stmt = $conn->prepare("INSERT INTO products (name, price, description, image, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $name, $price, $description, $image, $category);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-products.php");
    exit();
}

// Xử lý xóa sản phẩm
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-products.php");
    exit();
}

// Lấy danh sách sản phẩm
$result = $conn->query("SELECT * FROM products");
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { text-align: center; margin-bottom: 20px; }
        .add-product { margin-bottom: 20px; }
        .add-product form { display: flex; gap: 10px; }
        .add-product input, .add-product select, .add-product textarea, .add-product button {
            padding: 8px; margin: 5px 0;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .actions a { margin-right: 10px; text-decoration: none; color: #007bff; }
        .actions a.delete { color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Quản lý sản phẩm</h1>

        <!-- Form thêm sản phẩm -->
        <div class="add-product">
            <h2>Thêm sản phẩm mới</h2>
            <form method="POST">
                <input type="text" name="name" placeholder="Tên sản phẩm" required>
                <input type="number" name="price" placeholder="Giá" step="1000" required>
                <textarea name="description" placeholder="Mô tả" required></textarea>
                <select name="category" required>
                    <option value="highlight">Nổi bật</option>
                    <option value="milk-tea">Trà sữa</option>
                    <option value="fruit-tea">Trà trái cây</option>
                    <option value="macchiato">Macchiato</option>
                    <option value="coffee">Cà phê</option>
                    <option value="ice-cream">Kem</option>
                    <option value="special">Đặc biệt</option>
                </select>
                <button type="submit" name="add_product">Thêm</button>
            </form>
        </div>

        <!-- Danh sách sản phẩm -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Giá</th>
                    <th>Mô tả</th>
                    <th>Ảnh</th>
                    <th>Danh mục</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo number_format($product['price'], 0, ',', '.') . 'đ'; ?></td>
                        <td><?php echo $product['description']; ?></td>
                        <td><img src="<?php echo $product['image']; ?>" alt="Product" width="50"></td>
                        <td><?php echo $product['category']; ?></td>
                        <td class="actions">
                            <a href="edit-product.php?id=<?php echo $product['id']; ?>">Sửa</a>
                            <a href="?delete=<?php echo $product['id']; ?>" class="delete" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>