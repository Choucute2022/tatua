<?php
// Bật hiển thị lỗi trong quá trình phát triển (xóa khi triển khai production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// Xác định URL chuyển hướng dựa trên trạng thái đăng nhập
// Biến này hiện không được sử dụng trong index.php nhưng được giữ lại để tương thích với logic khác (nếu có)
$redirect_url = isset($_SESSION['user']) && $_SESSION['user']['logged_in'] ? 'duan1.php' : 'dangnhap.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tatua Milktea</title>
    <link rel="stylesheet" href="assets/css/styleindex.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">Tatua Milktea</div>
        <ul class="nav-center">
            <li><a href="index.php" class="active">Trang chủ</a></li>
            <li><a href="History and Mission.php">Giới thiệu</a></li>
            <li><a href="duan1.php">Sản phẩm</a></li>
        </ul>
    </div>

    <div class="banner">
    </div>

    <div class="featured-products">
        <h1 class="section-title">Sản Phẩm Nổi Bật</h1>
        <div class="products">
            <div class="product-row">
                <div class="product-card">
                    <img src="assets/images/img43.jpg" alt="Trà Xanh Chanh Leo Phô Mai">
                    <p>Trà Xanh Chanh Leo Phô Mai</p>
                    <p class="price">35.000đ</p>
                </div>
                <div class="product-card">
                    <img src="assets/images/img44.jpg" alt="Trà Sữa Khoai Môn Đường Hổ">
                    <p>Trà Sữa Khoai Môn Đường Hổ</p>
                    <p class="price">35.000đ</p>
                </div>
                <div class="product-card">
                    <img src="assets/images/img45.jpg" alt="Trà Xanh Đào Chanh Leo">
                    <p>Trà Xanh Đào Chanh Leo</p>
                    <p class="price">25.000đ</p>
                </div>
                <div class="product-card">
                    <img src="assets/images/img46.jpg" alt="Ô Long Sữa Boba Cheese">
                    <p>Ô Long Sữa Boba Cheese</p>
                    <p class="price">38.000đ</p>
                </div>
            </div>
            <div class="product-row">
                <div class="product-card">
                    <img src="assets/images/img47.jpg" alt="Ô Long Đào Tiên">
                    <p>Ô Long Đào Tiên</p>
                    <p class="price">35.000đ</p>
                </div>
                <div class="product-card">
                    <img src="assets/images/img9.png" alt="Sữa Tươi Yến Mạch">
                    <p>Sữa Tươi Yến Mạch</p>
                    <p class="price">30.000đ</p>
                </div>
                <div class="product-card">
                    <img src="assets/images/img2.jpg" alt="Xanh Sữa Nhài Đào Tiên">
                    <p>Xanh Sữa Nhài Đào Tiên</p>
                    <p class="price">25.000đ</p>
                </div>
                <div class="product-card">
                    <img src="assets/images/img3.jpg" alt="Ô Long Dâu Tây">
                    <p>Ô Long Dâu Tây</p>
                    <p class="price">25.000đ</p>
                </div>
            </div>
        </div>
        <div class="order-button-container">
<a href="duan1.php" class="order-button">ĐẶT HÀNG NGAY</a>
        </div>
    </div>

    <div class="about">
        <div class="about-content">
            <div class="about-text">
                <h1 class="about-title">Tatua Story</h1>
                <h2 class="about-subtitle">VỀ CHÚNG TÔI</h2>
                <p>Bên cạnh niềm tự hào về nguồn nguyên liệu tự nhiên, sạch, tươi, chúng tôi luôn tin rằng mỗi ly trà đều chứa đựng giá trị trải nghiệm tuyệt vời và không gian.</p>
                <a href="duan1.php" class="about-button">XEM THÊM</a>
            </div>
        </div>
    </div>

    <?php include_once 'tatua-chou/footer.html'; ?>
</body>
</html>