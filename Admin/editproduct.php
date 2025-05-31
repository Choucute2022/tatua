<?php
$pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", "");

// Kiểm tra ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID sản phẩm không hợp lệ!";
    exit;
}

$id = $_GET['id'];

// Lấy thông tin sản phẩm
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "Không tìm thấy sản phẩm!";
    exit;
}

// Xử lý form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $current_price = $_POST['current_price'];
    $original_price = $_POST['original_price'];
    $image = $_POST['image'];
    $description = $_POST['description'];
    $is_new = isset($_POST['is_new']) ? 1 : 0;

    $update = $pdo->prepare("UPDATE products SET name = ?, current_price = ?, original_price = ?, image = ?, description = ?, is_new = ? WHERE id = ?");
    $update->execute([$name, $current_price, $original_price, $image, $description, $is_new, $id]);

    header("Location: indexproducts.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #fffaf0;
            color: #4b2e1f;
            padding: 40px;
        }

        h2 {
            color: #d35400;
            margin-bottom: 20px;
        }

        form {
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            max-width: 500px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-top: 12px;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        input[type="checkbox"] {
            margin-top: 10px;
        }

        button {
            margin-top: 20px;
            background: #f39c12;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #d35400;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>✏️ Sửa sản phẩm #<?= $id ?></h2>

    <form method="post">
        <label>Tên sản phẩm:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>Giá hiện tại (₫):</label>
        <input type="number" name="current_price" value="<?= htmlspecialchars($product['current_price']) ?>" required>

        <label>Giá gốc (₫):</label>
        <input type="number" name="original_price" value="<?= htmlspecialchars($product['original_price']) ?>">

        <label>Hình ảnh (URL):</label>
        <input type="text" name="image" value="<?= htmlspecialchars($product['image']) ?>">

        <label>Miêu tả:</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>

        <label>
            <input type="checkbox" name="is_new" <?= $product['is_new'] ? 'checked' : '' ?>>
            Hàng mới
        </label>

        <button type="submit">💾 Lưu thay đổi</button>
    </form>

    <a href="indexproducts.php">⬅️ Quay lại danh sách sản phẩm</a>

</body>
</html>
