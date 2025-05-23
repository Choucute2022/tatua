<?php
$pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", "");

// Lấy tất cả người dùng
$stmt = $pdo->query("SELECT id, password FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$updated = 0;

foreach ($users as $user) {
    $id = $user['id'];
    $password = $user['password'];

    // Nếu password đã hash (bắt đầu bằng $2y$), thì bỏ qua
    if (preg_match('/^\$2y\$/', $password)) {
        continue;
    }

    // Hash mật khẩu
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Cập nhật lại vào DB
    $updateStmt = $pdo->prepare("UPDATE users SET password = :hashed WHERE id = :id");
    $updateStmt->execute([
        'hashed' => $hashed,
        'id' => $id
    ]);

    $updated++;
}

echo "✅ Đã hash và cập nhật $updated mật khẩu.";
