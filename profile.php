<?php
session_start();
$host = "localhost";
$dbname = "duan";
$username = "root";
$password = "";

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $address = $_POST["address"];

    if ($_FILES["avatar"]["error"] === UPLOAD_ERR_OK) {
        $avatar_tmp = $_FILES["avatar"]["tmp_name"];
        $avatar_name = basename($_FILES["avatar"]["name"]);
        $avatar_path = "uploads/" . $avatar_name;
        move_uploaded_file($avatar_tmp, $avatar_path);
    } else {
        $avatar_path = $user["avatar"];
    }

    $update = $conn->prepare("UPDATE users SET name = ?, email = ?, phone_number = ?, address = ?, avatar = ? WHERE id = ?");
    $update->execute([$name, $email, $phone_number, $address, $avatar_path, $user_id]);
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin tài khoản</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --yellow: #FFD700;
            --orange: #F5A623;
            --black: #222;
            --gray: #f5f5f5;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #fff;
            color: var(--black);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background-color: white;
            border-right: 1px solid #eee;
            padding: 20px 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar li {
            margin: 18px 0;
        }

        .sidebar li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            display: flex;
            align-items: center;
            padding: 10px;
            border-left: 4px solid transparent;
        }

        .sidebar li a:hover, .sidebar li a.active {
            background-color: var(--gray);
            border-left: 4px solid var(--yellow);
            color: var(--orange);
        }

        .main {
            flex-grow: 1;
            background-color: #fff;
        }

        .banner {
            height: 200px;
            background-image: url('https://tocotocotea.com.vn/images/bg_member.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .profile-card {
            background-color: #fff;
            width: 90%;
            max-width: 850px;
            margin: -60px auto 40px auto;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            position: relative;
        }

        .avatar-wrapper {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        .avatar-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--yellow);
        }

        .camera-icon {
            position: absolute;
            right: 42%;
            bottom: 0;
            background: white;
            padding: 6px;
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .camera-icon input {
            display: none;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .submit-btn {
            margin-top: 30px;
            padding: 12px 20px;
            background-color: var(--orange);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #e08e00;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .main {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <ul>
            <li><a href="index.php">🏠 Trang chủ</a></li>
            <li><a href="profile.php" class="active">👤 Thông tin tài khoản</a></li>
            <li><a href="orders.php">🧾 Đơn hàng của tôi</a></li>
            <li><a href="#">🏷️ Mã khuyến mãi</a></li>
            <li><a href="logout.php">↩️ Đăng xuất</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="banner"></div>
        <div class="profile-card">
            <form method="POST" enctype="multipart/form-data">
                <div class="avatar-wrapper">
                    <img src="<?= htmlspecialchars($user["avatar"]) ?: 'default-avatar.png' ?>" class="avatar-img" alt="Avatar">
                    <label class="camera-icon">
                        📷
                        <input type="file" name="avatar">
                    </label>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user["name"]) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Số điện thoại</label>
                        <input type="text" name="phone_number" value="<?= htmlspecialchars($user["phone_number"]) ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user["email"]) ?>" required>
                    </div>
                    <div class="form-group">
                      <label for="address">Địa chỉ</label>
                      <input type="text" name="address" value="<?= htmlspecialchars($user["address"]) ?>" required>
                </div>
                </div>
                <button type="submit" class="submit-btn">Cập nhật thông tin</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
