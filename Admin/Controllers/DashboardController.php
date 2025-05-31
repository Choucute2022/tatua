<?php

class DashboardController {
    public function index() {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['admin'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        // Logic hiển thị thông tin dashboard
        include 'views/dashboard.php';
    }
}
