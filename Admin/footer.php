<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Tatua Milktea - Footer</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #fffdf5;
      color: #333;
    }

    .footer {
      background: linear-gradient(to right, #f57c00, #ffb300); /* cam đậm đến vàng */
      color: white;
      padding: 40px 60px;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    .footer-column {
      flex: 1 1 220px;
      margin-bottom: 30px;
    }

    .footer h3 {
      font-size: 18px;
      margin-bottom: 16px;
      color: #fffde7;
      font-weight: bold;
    }

    .footer p,
    .footer a {
      font-size: 14px;
      color: #fffde7;
      text-decoration: none;
      line-height: 1.6;
      display: block;
      margin-bottom: 6px;
    }

    .footer a:hover {
      text-decoration: underline;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 12px;
      color: #fff8e1;
    }

    .logo::before {
      content: "🍹";
      margin-right: 8px;
    }

    .social-icons a {
      margin-right: 10px;
      font-size: 20px;
      display: inline-block;
      color: #fffde7;
    }

    .social-icons a:hover {
      color: #ffffff;
    }

    .store-badges img {
      width: 120px;
      margin-top: 8px;
      margin-right: 8px;
    }

    .footer-bottom {
      background: #e65100;
      color: #fff8e1;
      font-size: 13px;
      text-align: center;
      padding: 12px;
    }

    @media (max-width: 768px) {
      .footer {
        flex-direction: column;
        padding: 30px 20px;
      }

      .footer-column {
        margin-bottom: 25px;
      }
    }
  </style>
</head>
<body>

<!-- Footer -->
<footer>
  <div class="footer">
    <div class="footer-column">
      <div class="logo">Tatua Milktea</div>
      <p>Trụ sở chính: 123 Đường Trà Sữa, Q. Bình Thạnh, TP. Hồ Chí Minh</p>
      <p>Chi nhánh: 456 Đường Đào Tạo, Q. Cầu Giấy, Hà Nội</p>
      <p>📞 1900 123 456</p>
      <p>✉️ info@tatuamilktea.vn</p>
    </div>

    <div class="footer-column">
      <h3>Về chúng tôi</h3>
      <a href="#">Giới thiệu</a>
      <a href="#">Khuyến mãi</a>
      <a href="#">Hệ thống cửa hàng</a>
      <a href="#">Liên hệ</a>
    </div>

    <div class="footer-column">
      <h3>Chính sách</h3>
      <a href="#">Giao hàng</a>
      <a href="#">Bảo mật</a>
      <a href="#">Đổi trả</a>
      <a href="#">Bảo hành</a>
    </div>

    <div class="footer-column">
      <h3>Kết nối</h3>
      <div class="social-icons">
        <a href="#">📘</a>
        <a href="#">📸</a>
        <a href="#">🐦</a>
        <a href="#">▶️</a>
      </div>
      <div class="store-badges">
        <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d7/Google_Play_Store_badge_EN.svg/512px-Google_Play_Store_badge_EN.svg.png" alt="Google Play"></a>
        <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/96/Download_on_the_App_Store_Badge.svg/512px-Download_on_the_App_Store_Badge.svg.png" alt="App Store"></a>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    &copy; 2025 Tatua Milktea. Tất cả các quyền được bảo lưu.
  </div>
</footer>

</body>
</html>
