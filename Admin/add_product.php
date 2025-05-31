<?php
$pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $current_price = $_POST['current_price'];
    $current_price_display = $_POST['current_price_display'];
    $original_price = $_POST['original_price'];
    $description = $_POST['description'];
    $is_new = isset($_POST['is_new']) ? 1 : 0;

    // Xử lý ảnh upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "uploads/";
        $file_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;

        // Di chuyển ảnh vào thư mục uploads/
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file; // Lưu đường dẫn file
        }
    }

    // Lưu sản phẩm vào CSDL
    $stmt = $pdo->prepare("INSERT INTO products (name, current_price, current_price_display, original_price, image, description, is_new)
                           VALUES (:name, :current_price, :current_price_display, :original_price, :image, :description, :is_new)");
    $stmt->execute([
        ':name' => $name,
        ':current_price' => $current_price,
        ':current_price_display' => $current_price_display,
        ':original_price' => $original_price,
        ':image' => $image,
        ':description' => $description,
        ':is_new' => $is_new,
    ]);

    // Chuyển hướng sau khi thêm sản phẩm
    header('Location: indexproducts.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <style>
        body {
            font-family: Arial;
            max-width: 600px;
            margin: 30px auto;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background: orange;
            border: none;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: darkorange;
        }
    </style>
</head>
<body>
    <h2>Thêm sản phẩm mới</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" name="name" id="name" required>

        <label for="current_price">Giá hiện tại (số):</label>
        <input type="number" name="current_price" id="current_price" required>

        <label for="current_price_display">Giá hiển thị:</label>
        <input type="text" name="current_price_display" id="current_price_display" required>

        <label for="original_price">Giá gốc:</label>
        <input type="number" name="original_price" id="original_price" required>

        <label for="image">Chọn hình ảnh sản phẩm:</label>
        <input type="file" name="image" id="image" accept="image/*" required>

        <label for="description">Mô tả:</label>
        <textarea name="description" id="description" rows="4"></textarea>

        <label><input type="checkbox" name="is_new" value="1"> Sản phẩm mới</label>

        <button type="submit">Thêm sản phẩm</button>
    </form>
</body>
</html>
