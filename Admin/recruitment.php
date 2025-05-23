<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tuyển dụng - Tatua Milktea</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --orange: #f39c12;
            --light-orange: #fff4e1;
            --dark-orange: #c87f0a;
            --text: #3d2b1f;
            --bg: #fffdf8;
        }

        * {
            margin: 0; padding: 0; box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        header {
            background-color: var(--orange);
            color: white;
            padding: 24px 40px;
            text-align: center;
        }

        header h1 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        header p {
            font-size: 16px;
        }

        .section {
            padding: 40px 24px;
            max-width: 1100px;
            margin: auto;
        }

        .section-title {
            font-size: 26px;
            color: var(--dark-orange);
            margin-bottom: 20px;
            text-align: center;
        }

        .job-listing {
            background-color: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 24px;
        }

        .job-title {
            font-size: 20px;
            font-weight: bold;
            color: var(--orange);
        }

        .job-location {
            font-size: 14px;
            margin: 6px 0 12px;
            color: #666;
        }

        .job-desc {
            font-size: 15px;
            margin-bottom: 12px;
        }

        .apply-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--orange);
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .apply-btn:hover {
            background-color: var(--dark-orange);
        }

        footer {
            background-color: #ffe3b3;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: var(--dark-orange);
        }
    </style>
</head>
<body>

<header>
    <h1>Tatua Milktea - Cùng gia nhập đội ngũ năng động</h1>
    <p>Chúng tôi luôn chào đón những ứng viên trẻ trung, nhiệt huyết và sáng tạo</p>
</header>

<div class="section">
    <h2 class="section-title">Vị trí đang tuyển</h2>

    <div class="job-listing">
        <div class="job-title">Nhân viên pha chế</div>
        <div class="job-location">📍 Cơ sở Quận 1, TP.HCM</div>
        <div class="job-desc">Bạn sẽ là người trực tiếp pha chế các loại trà sữa theo công thức độc quyền của Tatua.</div>
        <a href="apply.php?job=barista" class="apply-btn">Ứng tuyển ngay</a>
    </div>

    <div class="job-listing">
        <div class="job-title">Nhân viên giao hàng</div>
        <div class="job-location">📍 Toàn quốc</div>
        <div class="job-desc">Thực hiện giao hàng tận nơi cho khách, đảm bảo đơn hàng đến nhanh và chính xác.</div>
        <a href="apply.php?job=shipper" class="apply-btn">Ứng tuyển ngay</a>
    </div>

    <div class="job-listing">
        <div class="job-title">Nhân viên chăm sóc khách hàng</div>
        <div class="job-location">📍 Văn phòng chính</div>
        <div class="job-desc">Tiếp nhận phản hồi và hỗ trợ khách hàng qua các kênh hotline, email, mạng xã hội.</div>
        <a href="apply.php?job=cs" class="apply-btn">Ứng tuyển ngay</a>
    </div>
</div>

<footer>
    &copy; <?= date('Y') ?> Tatua Milktea - Thương hiệu trà sữa Việt. Màu sắc của đam mê cam vàng 🍊
</footer>

</body>
</html>
