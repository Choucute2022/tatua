<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "duan");
if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu form được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Kiểm tra dữ liệu
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Tên không được để trống.";
    }
    if (empty($phone)) {
        $errors[] = "Số điện thoại không được để trống.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = "Số điện thoại phải là 10 chữ số.";
    }
    if (empty($email)) {
        $errors[] = "Email không được để trống.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ.";
    }
    if (empty($password)) {
        $errors[] = "Mật khẩu không được để trống.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Mật khẩu và xác nhận mật khẩu không khớp.";
    }

    // Kiểm tra email và số điện thoại trùng lặp
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT email, phone_number FROM users WHERE email = ? OR phone_number = ?");
        if ($stmt) {
            $stmt->bind_param("ss", $email, $phone);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['email'] === $email) {
                    $errors[] = "Email đã được sử dụng.";
                }
                if ($row['phone_number'] === $phone) {
                    $errors[] = "Số điện thoại đã được sử dụng.";
                }
            }
            $stmt->close();
        } else {
            $errors[] = "Lỗi chuẩn bị truy vấn SQL.";
        }
    }

    // Nếu không có lỗi, lưu thông tin
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, phone_number, email, password) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $name, $phone, $email, $hashed_password);
            if ($stmt->execute()) {
                header("Location: dangnhap.php");
                exit();
            } else {
                $errors[] = "Đăng ký thất bại. Vui lòng thử lại.";
            }
            $stmt->close();
        } else {
            $errors[] = "Lỗi chuẩn bị truy vấn SQL.";
        }
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaTua Milktea</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url("https://tocotocotea.com/wp-content/uploads/2021/10/tocotoco-hanh-trinh-sang-lap.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 20px; /* Increased for rounder corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .brand-name {
            font-family: 'Dancing Script', cursive;
            font-size: 2.5em;
            color: #ff6f61;
            margin-bottom: 20px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 15px; /* Increased for rounder corners */
            font-size: 1em;
        }
        .register-button {
            width: 100%;
            padding: 10px;
            background: #ff6f61;
            color: white;
            border: none;
            border-radius: 15px; /* Increased for rounder corners */
            font-size: 1.2em;
            cursor: pointer;
            transition: background 0.3s;
        }
        .register-button:hover {
            background: #e65b50;
        }
        .login-link {
            margin-top: 15px;
            color: #333;
        }
        .login-link a {
            color: #ff6f61;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: -5px;
            margin-bottom: 10px;
            display: none;
        }
    </style>
    <script>
        function checkPasswordMatch() {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            const errorDiv = document.getElementById('passwordMatchError');
            if (password !== confirmPassword) {
                errorDiv.textContent = 'Mật khẩu và xác nhận mật khẩu không khớp.';
                errorDiv.style.display = 'block';
            } else {
                errorDiv.style.display = 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInput = document.querySelector('input[name="password"]');
            const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
            passwordInput.addEventListener('input', checkPasswordMatch);
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        });
    </script>
</head>
<body>
    <div class="register-container">
        <div class="brand-name">TaTua Milktea</div>
        <h2>Tạo Tài Khoản</h2>
        <?php
        if (!empty($errors)) {
            echo '<div class="error"><ul>';
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo '</ul></div>';
        }
        ?>
        <form method="POST" action="dangky.php">
            <input type="text" class="input-field" name="name" placeholder="Họ và tên" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
            <input type="text" class="input-field" name="phone_number" placeholder="Số điện thoại" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>" required>
            <input type="email" class="input-field" name="email" placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            <input type="password" class="input-field" name="password" placeholder="Mật khẩu" required>
            <input type="password" class="input-field" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
            <div id="passwordMatchError" class="error-message"></div>
            <button type="submit" class="register-button">ĐĂNG KÝ</button>
        </form>
        <button onclick="window.location.href='forgot/forgot-password.php'" class="register-button">Forgot Password</button>
        <div class="login-link">Đã có tài khoản? <a href="dangnhap.php">Đăng nhập</a></div>
    </div>
</body>
</html>