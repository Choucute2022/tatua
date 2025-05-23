<?php
// Xử lý thêm người dùng khi submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", "");

    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (phone_number, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$phone, $email, $password]);

    header("Location: indexuser.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm người dùng</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fffaf0, #fff3e0);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .form-container {
            background: white;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            margin-bottom: 24px;
            color: #e67e22;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #8e5c00;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #e67e22;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: #d35400;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Thêm người dùng</h2>
    <form method="POST">
        <label for="phone">Số điện thoại:</label>
        <input type="text" id="phone" name="phone" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Thêm người dùng</button>
    </form>
</div>

</body>
</html>
