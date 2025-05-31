<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tuyển dụng - Tatua Milktea</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fff9f0;
            margin: 0;
            padding: 0;
        }

        header {
            background: #f39c12;
            color: white;
            padding: 20px 30px;
            text-align: center;
        }

        .hero {
            background: url('images/tuyendung.jpg') center/cover no-repeat;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 32px;
            font-weight: bold;
            text-shadow: 1px 1px 5px rgba(0,0,0,0.5);
        }

        .container {
            padding: 40px;
            max-width: 900px;
            margin: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-top: -50px;
            position: relative;
            z-index: 1;
        }

        h2 {
            color: #d35400;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-top: 16px;
            color: #333;
        }

        form input, form select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        form input[type="submit"] {
            margin-top: 20px;
            background: #f39c12;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        form input[type="submit"]:hover {
            background: #e67e22;
        }

        footer {
            text-align: center;
            padding: 18px;
            background: #fcebd3;
            color: #a05e00;
        }
    </style>
</head>
<body>

<header>
    <h1>Tuyển dụng tại Tatua Milktea 🍹</h1>
</header>

<div class="hero">
    Gia nhập đội ngũ năng động của chúng tôi!
</div>

<div class="container">
    <h2>Ứng tuyển ngay hôm nay</h2>
    <form action="submit_application.php" method="POST" enctype="multipart/form-data">
        <label>Họ tên:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Số điện thoại:</label>
        <input type="text" name="phone" required>

        <label>Vị trí ứng tuyển:</label>
        <select name="position" required>
            <option value="">-- Chọn vị trí --</option>
            <option>Nhân viên pha chế</option>
            <option>Nhân viên phục vụ</option>
            <option>Quản lý chi nhánh</option>
            <option>Marketing online</option>
        </select>

        <label>CV (PDF hoặc Word):</label>
        <input type="file" name="cv_file" accept=".pdf,.doc,.docx" required>

        <input type="submit" value="Gửi đơn ứng tuyển">
    </form>
</div>

<footer>
    &copy; <?= date('Y') ?> Tatua Milktea - Giao diện tuyển dụng tông cam vàng
</footer>

</body>
</html>
