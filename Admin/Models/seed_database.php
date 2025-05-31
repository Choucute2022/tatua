<?php
// Hash mật khẩu cho admin
$adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
echo "Mật khẩu hash cho admin (admin123): $adminPassword\n";

// Hash mật khẩu cho user
$userPassword = password_hash('user123', PASSWORD_DEFAULT);
echo "Mật khẩu hash cho user (user123): $userPassword\n";
?>