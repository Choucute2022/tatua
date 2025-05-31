<?php
session_start();

// Kiểm tra nếu đã đăng nhập thì chuyển hướng luôn
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: indexadmin.php');
    exit;
}

// Xử lý khi người dùng gửi form
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Tài khoản mẫu
    $validUsername = 'admin';
    $validPassword = '123456';

    if ($username === $validUsername && $password === $validPassword) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;

        // ✅ Chuyển hướng sang trang chính
        header('Location: index.php');
        exit;
    } else {
        $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #fff8e1, #ffe0b2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: white;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(243, 156, 18, 0.3);
            width: 100%;
            max-width: 400px;
        }

        .login-box h2 {
            text-align: center;
            color: #e67e22;
            margin-bottom: 24px;
        }

        .login-box input {
            width: 100%;
            padding: 12px 16px;
            margin-bottom: 16px;
            border: 1px solid #ffd699;
            border-radius: 8px;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background: #f39c12;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .login-box button:hover {
            background: #e67e22;
        }

        .error {
            color: red;
            margin-bottom: 16px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Đăng nhập Admin</h2>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng nhập</button>
    </form>
</div>

</body>
</html>
