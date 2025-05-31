<?php
$pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $position = $_POST['position'];
    $cvFile   = $_FILES['cv_file'];

    // Tạo thư mục uploads nếu chưa có
    $uploadDir = "uploads_cv/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $cvPath = $uploadDir . time() . '_' . basename($cvFile['name']);
    move_uploaded_file($cvFile['tmp_name'], $cvPath);

    // Lưu vào database
    $stmt = $pdo->prepare("INSERT INTO recruit_applications (name, email, phone, position, cv_file) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $position, $cvPath]);

    echo "<script>alert('Ứng tuyển thành công!'); window.location.href='recruit.php';</script>";
}
