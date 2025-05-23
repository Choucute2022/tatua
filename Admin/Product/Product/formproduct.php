<?php
session_start();

// Khởi tạo mảng sản phẩm (dữ liệu mẫu)
$products = isset($_SESSION['products']) ? $_SESSION['products'] : [];
$categories = [
    1 => 'Điện tử',
    2 => 'Thời trang',
    3 => 'Gia dụng'
];

// Xử lý thêm/sửa sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $current_price = floatval($_POST['current_price'] ?? 0);
    $current_price_display = $_POST['current_price_display'] ?? '';
    $original_price = floatval($_POST['original_price'] ?? 0);
    $image = $_POST['image'] ?? '';
    $is_new = isset($_POST['is_new']) ? 1 : 0;
    $category_id = intval($_POST['category_id'] ?? 0);
    $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : -1;

    if ($name && $current_price && $category_id) {
        $product = [
            'id' => $edit_id === -1 ? (count($products) + 1) : $edit_id,
            'name' => $name,
            'description' => $description,
            'current_price' => $current_price,
            'current_price_display' => $current_price_display,
            'original_price' => $original_price,
            'image' => $image,
            'is_new' => $is_new,
            'category_id' => $category_id
        ];

        if ($edit_id === -1) {
            $products[] = $product;
        } else {
            $products[$edit_id - 1] = $product;
        }
        $_SESSION['products'] = $products;
        header("Location: indexproduct.php");
        exit;
    }
}

// Xử lý chỉnh sửa (lấy dữ liệu để điền vào form)
$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    if ($edit_id > 0 && $edit_id <= count($products)) {
        $edit_product = $products[$edit_id - 1];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $edit_product ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #F5E5C5;
        }
        .navbar {
            background-color: #FDF8F0;
        }
        .navbar-title {
            color: #D35400;
        }
        .text-default {
            color: #000000;
        }
        .settings-link {
            color: #2980B9;
        }
    </style>
</head>
<body class="flex text-default">
    <!-- Vertical Navbar -->
    <div class="w-64 navbar h-screen shadow-md">
        <div class="p-4">
            <h1 class="text-2xl font-bold navbar-title">Trang quản trị</h1>
        </div>
        <nav class="mt-4">
            <ul>
                <li class="px-4 py-2 hover:bg-gray-200 cursor-pointer">Tổng quan</li>
                <li class="px-4 py-2 hover:bg-gray-200 cursor-pointer">Đơn hàng</li>
                <li class="px-4 py-2 bg-gray-200 font-bold">Sản phẩm</li>
                <li class="px-4 py-2 hover:bg-gray-200 cursor-pointer">Khách hàng</li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold"><?php echo $edit_product ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm'; ?></h2>
            </div>
            <div>
                <a href="indexproduct.php" class="bg-gray-500 text-white p-2 rounded hover:bg-gray-600 mr-2">Quay lại</a>
                <a href="#" class="settings-link hover:underline">Cài đặt</a>
            </div>
        </div>

        <!-- Form to Add/Edit Product -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <form method="POST" class="grid grid-cols-1 gap-4">
                <input type="hidden" name="edit_id" value="<?php echo $edit_product ? $edit_product['id'] : -1; ?>">
                <input type="text" name="name" placeholder="Tên sản phẩm" value="<?php echo $edit_product ? htmlspecialchars($edit_product['name']) : ''; ?>" class="border p-2 rounded">
                <input type="text" name="description" placeholder="Mô tả sản phẩm" value="<?php echo $edit_product ? htmlspecialchars($edit_product['description']) : ''; ?>" class="border p-2 rounded">
                <input type="number" name="current_price" placeholder="Giá hiện tại (VNĐ)" value="<?php echo $edit_product ? $edit_product['current_price'] : ''; ?>" class="border p-2 rounded">
                <input type="text" name="current_price_display" placeholder="Định dạng giá (ví dụ: 1.000.000đ)" value="<?php echo $edit_product ? htmlspecialchars($edit_product['current_price_display']) : ''; ?>" class="border p-2 rounded">
                <input type="number" name="original_price" placeholder="Giá gốc (VNĐ)" value="<?php echo $edit_product ? $edit_product['original_price'] : ''; ?>" class="border p-2 rounded">
                <input type="text" name="image" placeholder="URL hình ảnh" value="<?php echo $edit_product ? htmlspecialchars($edit_product['image']) : ''; ?>" class="border p-2 rounded">
                <div class="flex items-center">
                    <input type="checkbox" name="is_new" id="is_new" <?php echo $edit_product && $edit_product['is_new'] ? 'checked' : ''; ?> class="mr-2">
                    <label for="is_new">Sản phẩm mới</label>
                </div>
                <select name="category_id" class="border p-2 rounded">
                    <option value="">Chọn danh mục</option>
                    <?php foreach ($categories as $id => $category): ?>
                        <option value="<?php echo $id; ?>" <?php echo $edit_product && $edit_product['category_id'] == $id ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600"><?php echo $edit_product ? 'Cập nhật sản phẩm' : 'Thêm sản phẩm'; ?></button>
            </form>
        </div>
    </div>
</body>
</html>