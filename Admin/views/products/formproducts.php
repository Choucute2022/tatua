<?php
$pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", "");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $current_price = (float)$_POST['current_price'];
    $original_price = (float)$_POST['original_price'];
    $description = $_POST['description'];
    $is_new = isset($_POST['is_new']) ? 1 : 0;

    // Xử lý ảnh
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageExt = pathinfo($imageName, PATHINFO_EXTENSION);
        $imageNewName = uniqid() . '.' . $imageExt;
        $uploadDir = __DIR__ . '/uploads';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Tạo thư mục nếu chưa có
        }

        $fullPath = $uploadDir . '/' . $imageNewName;
        if (move_uploaded_file($imageTmp, $fullPath)) {
            $imagePath = 'uploads/' . $imageNewName;
        }
    }

    // Hiển thị giá có định dạng
    $current_price_display = number_format($current_price, 0, ',', '.') . 'đ';

    // Thêm vào CSDL
    $stmt = $pdo->prepare("INSERT INTO products (name, current_price, current_price_display, original_price, image, description, is_new)
        VALUES (:name, :current_price, :current_price_display, :original_price, :image, :description, :is_new)");
    $stmt->execute([
        'name' => $name,
        'current_price' => $current_price,
        'current_price_display' => $current_price_display,
        'original_price' => $original_price,
        'image' => $imagePath,
        'description' => $description,
        'is_new' => $is_new
    ]);

    header("Location: indexproducts.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #fffbe6; }
        h1 { color: #e67e22; margin-bottom: 24px; }
        form { max-width: 600px; margin: auto; background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; margin-top: 6px; border: 1px solid #ccc; border-radius: 8px; }
        button { margin-top: 20px; background: #e67e22; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; }
        button:hover { background: #cf711f; }
    </style>
</head>
<body>
    <h1>Thêm sản phẩm mới</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Tên sản phẩm:</label>
        <input type="text" name="name" required>

        <label>Giá hiện tại:</label>
        <input type="number" name="current_price" required>

        <label>Giá gốc:</label>
        <input type="number" name="original_price" required>

        <label>Hình ảnh:</label>
        <input type="file" name="image" accept="image/*" required>

        <label>Mô tả:</label>
        <textarea name="description" rows="4" required></textarea>

        <label>
            <input type="checkbox" name="is_new"> Sản phẩm mới
        </label>

        <button type="submit">Thêm sản phẩm</button>
    </form>
</body>
</html>
