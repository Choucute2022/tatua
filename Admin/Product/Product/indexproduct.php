<?php
session_start();

// Khởi tạo mảng sản phẩm (dữ liệu mẫu)
$products = isset($_SESSION['products']) ? $_SESSION['products'] : [];
$categories = [
    1 => 'Điện tử',
    2 => 'Thời trang',
    3 => 'Gia dụng'
];

// Xử lý xóa sản phẩm
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    if ($delete_id > 0 && $delete_id <= count($products)) {
        array_splice($products, $delete_id - 1, 1);
        // Cập nhật lại ID
        foreach ($products as $index => $product) {
            $products[$index]['id'] = $index + 1;
        }
        $_SESSION['products'] = $products;
    }
    header("Location: indexproduct.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
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
                <h2 class="text-xl font-semibold">Quản lý sản phẩm</h2>
            </div>
            <div>
                <a href="formproduct.php" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 mr-2">Thêm sản phẩm</a>
                <a href="#" class="settings-link hover:underline">Cài đặt</a>
            </div>
        </div>

        <!-- Product Table -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Danh sách sản phẩm</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">ID</th>
                        <th class="py-2 px-4 border-b">Tên</th>
                        <th class="py-2 px-4 border-b">Mô tả</th>
                        <th class="py-2 px-4 border-b">Giá hiện tại</th>
                        <th class="py-2 px-4 border-b">Định dạng giá</th>
                        <th class="py-2 px-4 border-b">Giá gốc</th>
                        <th class="py-2 px-4 border-b">Hình ảnh</th>
                        <th class="py-2 px-4 border-b">Mới</th>
                        <th class="py-2 px-4 border-b">Danh mục</th>
                        <th class="py-2 px-4 border-b">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo $product['id']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($product['name']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($product['description']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo number_format($product['current_price'], 0, ',', '.') . 'đ'; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($product['current_price_display']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo number_format($product['original_price'], 0, ',', '.') . 'đ'; ?></td>
                            <td class="py-2 px-4 border-b">
                                <?php if ($product['image']): ?>
                                    <a href="<?php echo htmlspecialchars($product['image']); ?>" target="_blank">Xem hình</a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="py-2 px-4 border-b"><?php echo $product['is_new'] ? 'Có' : 'Không'; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo isset($categories[$product['category_id']]) ? htmlspecialchars($categories[$product['category_id']]) : '-'; ?></td>
                            <td class="py-2 px-4 border-b">
                                <a href="formproduct.php?edit=<?php echo $product['id']; ?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Sửa</a>
                                <a href="indexproduct.php?delete=<?php echo $product['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>