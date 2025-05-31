<?php

$mysqli = require __DIR__ . "/database.php";

// Không cần PHPMailer nếu không gửi email
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    // Không hiển thị lỗi email không hợp lệ trực tiếp
    // Có thể chuyển hướng về trang forgot-password.php với thông báo lỗi chung
    header("Location: forgot-password.php?message=" . urlencode("Email không hợp lệ."));
    exit;
}

$email = $_POST["email"];

$sql = "SELECT id FROM user WHERE email = ?"; // Chỉ cần lấy ID để xác nhận tồn tại
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    // Ghi log lỗi để debug
    error_log("Error preparing statement: " . $mysqli->error);
    header("Location: forgot-password.php?message=" . urlencode("Có lỗi xảy ra, vui lòng thử lại sau."));
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    // ------------------------------------------------------------------
    // THAY ĐỔI LỚN TẠI ĐÂY: Chuyển hướng thẳng đến trang đặt lại mật khẩu
    // KHÔNG TẠO TOKEN VÀ KHÔNG GỬI EMAIL
    //
    // LƯU Ý BẢO MẬT: Phương pháp này kém an toàn hơn nhiều
    // nếu bạn không có bước xác minh bổ sung (ví dụ: câu hỏi bảo mật).
    // Bất kỳ ai biết email đều có thể đổi mật khẩu.
    // ------------------------------------------------------------------

    // Chuyển hướng người dùng trực tiếp đến trang reset-password.php
    // Bạn có thể truyền ID người dùng qua session hoặc ẩn đi
    // Tuy nhiên, việc không có token bảo mật là rất rủi ro.

    // ************* KHÔNG KHUYẾN NGHỊ TRỪ KHI CÓ XÁC MINH BỔ SUNG *************
    // VÍ DỤ NẾU BẠN CHẮC CHẮN CHỈ CÓ ADMIN ĐANG SỬ DỤNG HOẶC ĐỂ TEST NỘI BỘ
    // THAY THẾ BẰNG CÁCH CHUYỂN HƯỚNG TRỰC TIẾP ĐẾN TRANG ĐẶT LẠI MẬT KHẨU
    // VỚI MỘT CƠ CHẾ BẢO MẬT KHÁC (VÍ DỤ: CÂU HỎI BẢO MẬT TRÊN TRANG RESET)

    // Ví dụ cơ bản nhất (ít bảo mật nhất):
    $message = "Email được tìm thấy. Bạn sẽ được chuyển hướng đến trang đặt lại mật khẩu.";
    header("Location: reset-password.php?email=" . urlencode($email)); // Truyền email qua URL để reset-password biết là ai
    exit;

} else {
    // Không tìm thấy email, thông báo cho người dùng
    $message = "Email này không tồn tại trong hệ thống của chúng tôi.";
    header("Location: forgot-password.php?message=" . urlencode($message));
    exit;
}
?>