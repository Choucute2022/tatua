<?php
session_start();

// Kết nối database
$pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", "");

// Nếu đã đăng nhập, chuyển hướng tới trang admin
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: indexadmin.php");
    exit;
}

// Xử lý form khi submit
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Lấy thông tin người dùng từ DB
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Đăng nhập thành công
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['user_email'] = $user['email'];
        header("Location: indexadmin.php");
        exit;
    } else {
        $error = "Email hoặc mật khẩu không đúng.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fffaf0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: white;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-box h2 {
            color: #e67e22;
            margin-bottom: 24px;
            text-align: center;
        }

        .login-box input {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .login-box button {
            background: #e67e22;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-box button:hover {
            background: #cf711c;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>🔐 Đăng nhập Admin</h2>
    
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email..." required>
        <input type="password" name="password" placeholder="Mật khẩu..." required>
        <button type="submit">Đăng nhập</button>
    </form>
</div>

</body>
</html>
