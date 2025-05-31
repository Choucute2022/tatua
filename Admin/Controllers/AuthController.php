<?php

class AuthController {
    public function login() {
        // Xử lý logic đăng nhập, hiển thị form đăng nhập
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Xác thực người dùng, ví dụ với UserModel
            $userModel = new UserModel();
            if ($userModel->checkLogin($username, $password)) {
                $_SESSION['admin'] = true;
                header("Location: index.php?url=dashboard/index");
                exit;
            } else {
                echo 'Đăng nhập không thành công';
            }
        }

        // Hiển thị trang login
        include 'views/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?url=auth/login");
        exit;
    }
}
