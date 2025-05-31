<?php
// Cấu hình cơ sở dữ liệu
$host = 'localhost';       // Địa chỉ máy chủ MySQL
$user = 'root';            // Tên người dùng MySQL
$password = '';            // Mật khẩu MySQL
$dbname = 'duan'; // Tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($host, $user, $password, $duan);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Cấu hình trả về trong mảng
return [
    'db' => [
        'conn' => $conn // Trả về đối tượng kết nối $conn
    ]
];
?>
//     $stmt = $conn->prepare("SELECT * FROM products");