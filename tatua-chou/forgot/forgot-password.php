<!DOCTYPE html>
<html>
<head>
    <title>Quên mật khẩu</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>

    <h1>Quên mật khẩu</h1>

    <p>Vui lòng nhập địa chỉ email của bạn để nhận hướng dẫn đặt lại mật khẩu.</p>

    <form method="post" action="process-forgot-password.php">
        <label for="email">Email</label>
        <input type="email" id="email" name="email">
        <button>Gửi</button>
    </form>

    <p>
        <?php
        if (isset($_GET["message"])) {
            echo htmlspecialchars($_GET["message"]);
        }
        ?>
    </p>

</body>
</html>