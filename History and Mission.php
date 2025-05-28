<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaTua Milktea - Giới Thiệu</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f9c846;
            --dark: #3e2723;
            --light: #fffdf6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }

        .header {
            background-color: var(--primary);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--dark);
        }
        .logo {
             font-family: 'Dancing Script', cursive;
            font-size: 24px;
            color: #FFF8E1;
            text-decoration: none;
        }

.logo:hover {
  opacity: 0.8;
  cursor: pointer;
}


        .nav a {
            margin-left: 20px;
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
        }

        .nav a:hover {
            text-decoration: underline;
        }

        .content {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .content h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: var(--dark);
        }

        .content p {
            font-size: 16px;
            text-align: justify;
        }

        .footer {
            background-color: var(--primary);
            color: var(--dark);
            padding: 30px 40px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .footer div {
            width: 30%;
            margin-bottom: 20px;
        }

        .footer h4 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .footer p {
            font-size: 14px;
            line-height: 1.4;
        }

        @media (max-width: 768px) {
            .footer div {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="logo">TaTua Milktea</div>
        <div class="nav">
            <a href="index.php">Trang chủ</a>
            <a href="duan1.php">Sản phẩm</a>
            <a href="History and Mission.php">Giới thiệu</a>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Giới thiệu</h2>
        <p>
            Luôn tâm huyết với việc khai thác nguồn nông sản Việt Nam để tạo ra những ly thức uống tươi ngon, an toàn và giàu giá trị dinh dưỡng. <strong>TaTua</strong> mở cửa hàng đầu tiên vào năm 2025, mang trong mình lòng đam mê và khát vọng xây dựng một thương hiệu trà sữa thuần Việt, mang đậm hương vị quê hương.
        </p>
        <p>
            <strong>TaTua Tea</strong> tin rằng thưởng thức một ly trà sữa được pha chế từ trà Mộc Châu, trân châu từ sắn dây Nghệ An hay mứt dâu tằm từ Đà Lạt sẽ là những trải nghiệm hoàn toàn khác biệt và tuyệt vời nhất cho khách hàng của mình.
        </p>
        <p>
            Chính từ sự khác biệt đó, thương hiệu <strong>TaTua</strong> đã có những bước phát triển thần tốc và chiếm lĩnh thị trường trà sữa với gần 1000 cửa hàng trên toàn quốc. Năm 2028 đánh dấu ước mơ vươn ra thế giới khi TaTua chính thức đặt chân lên nước Mỹ và tiếp nối thành công tại Úc, Nhật Bản, Hàn Quốc, Singapore...
        </p>
        <p>
            Hành trình này sẽ tiếp tục được lan rộng để đưa những ly trà thuần khiết từ nông sản Việt đến mọi miền đất nước và thế giới.
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>
            <h4>Về chúng tôi</h4>
            <p>TaTua là thương hiệu trà sữa thuần Việt với nguyên liệu nông sản tự nhiên và hương vị đặc trưng, đậm đà bản sắc quê hương.</p>
        </div>
        <div>
            <h4>Liên hệ</h4>
            <p>Hotline: 0123 456 789<br>Email: contact@tatua.vn<br>Địa chỉ: 123 Lê Lợi, TP. Hồ Chí Minh</p>
        </div>
        <div>
            <h4>Giờ mở cửa</h4>
            <p>Thứ 2 - Chủ Nhật: 9:00 - 22:00</p>
        </div>
    </div>

</body>
</html>
