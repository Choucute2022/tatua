<?php
session_start();
ob_start();

// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "duan");
if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}

// Tạo CSRF token nếu chưa có
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if ($csrf_token !== $_SESSION['csrf_token']) {
        $errors[] = "Lỗi bảo mật: CSRF token không hợp lệ.";
    } else {
        $phone = $_POST['phone_number'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Kiểm tra dữ liệu đầu vào
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

        // Xử lý truy vấn nếu không có lỗi
        if (empty($errors)) {
            try {
                $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ? AND phone_number = ?");
                if ($stmt === false) {
                    throw new Exception("Lỗi chuẩn bị truy vấn: " . $conn->error);
                }
                $stmt->bind_param("ss", $email, $phone);
                if (!$stmt->execute()) {
                    throw new Exception("Lỗi thực thi truy vấn: " . $stmt->error);
                }
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        $_SESSION['user'] = [
                            'id' => $row['id'],
                            'name' => $row['name'],
                            'email' => $email,
                            'phone_number' => $phone,
                            'logged_in' => true
                        ];
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                        $duan1_path = __DIR__ . '/../duan1.php';
                        if (file_exists($duan1_path)) {
                            header("Location: ../duan1.php");
                            exit();
                        } else {
                            $errors[] = "Lỗi: Không tìm thấy trang sản phẩm (duan1.php). Vui lòng kiểm tra lại.";
                        }
                    } else {
                        $errors[] = "Mật khẩu không đúng.";
                    }
                } else {
                    $errors[] = "Số điện thoại hoặc email không tồn tại.";
                }
                $stmt->close();
            } catch (Exception $e) {
                $errors[] = "Lỗi hệ thống: " . $e->getMessage();
            }
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
            border-radius: 20px;
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
            border-radius: 15px;
            font-size: 1em;
            box-sizing: border-box;
        }
        .register-button {
            width: 100%;
            padding: 10px;
            background: #ff6f61;
            color: white;
            border: none;
            border-radius: 15px;
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
        .error ul {
            list-style: none;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="brand-name">TaTua Milktea</div>
        <h2>Đăng Nhập</h2>
        <?php
        if (!empty($errors)) {
            echo '<div class="error"><ul>';
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo '</ul></div>';
        }
        ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="text" class="input-field" name="phone_number" placeholder="Số điện thoại" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>" required>
            <input type="email" class="input-field" name="email" placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            <input type="password" class="input-field" name="password" placeholder="Mật khẩu" required>
            <button type="submit" class="register-button">Đăng Nhập</button>
        </form>
<button onclick="window.location.href='forgot/forgot-password.php'" class="register-button">Forgot Password</button>        <div class="login-link">Chưa có tài khoản? <a href="dangky.php">Đăng ký</a></div>
        <div class="login-link"><a href="index.php">← Quay về trang chủ</a></div>
    </div>
</body>
</html>
<?php
ob_end_flush();
?>