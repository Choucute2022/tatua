<?php


$mysqli = require __DIR__ . "/database.php";

// Kiểm tra kết nối database
if ($mysqli->connect_error) {
    // Nếu có lỗi kết nối, dừng và hiển thị thông báo
    die("Lỗi kết nối cơ sở dữ liệu: " . $mysqli->connect_error);
}

// --------------------------------------------------------------------------
// 1. Lấy và xác thực Email từ POST request
// --------------------------------------------------------------------------
if (!isset($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    // Chuyển hướng về trang quên mật khẩu nếu email không hợp lệ
    header("Location: forgot-password.php?message=" . urlencode("Yêu cầu đặt lại mật khẩu không hợp lệ."));
    exit;
}

$email = $_POST["email"];

// --------------------------------------------------------------------------
// 2. Tìm người dùng trong database bằng Email
// --------------------------------------------------------------------------
$sql = "SELECT id FROM user WHERE email = ?"; // Chỉ cần lấy ID để định danh người dùng
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    // Ghi log lỗi chi tiết nếu câu lệnh SQL không chuẩn bị được
    error_log("Lỗi chuẩn bị câu lệnh SELECT user trong process-reset-password.php: " . $mysqli->error);
    die("Có lỗi xảy ra, vui lòng thử lại sau. (Lỗi DB)");
}

$stmt->bind_param("s", $email); // 's' cho email là kiểu string
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc(); // Lấy hàng dữ liệu của người dùng

if ($user === null) {
    // Nếu không tìm thấy người dùng với email này
    header("Location: forgot-password.php?message=" . urlencode("Email này không tồn tại trong hệ thống của chúng tôi."));
    exit;
}

// --------------------------------------------------------------------------
// 3. Xác thực mật khẩu mới từ form
// --------------------------------------------------------------------------
$password = $_POST["password"];
$password_confirmation = $_POST["password_confirmation"];

if (strlen($password) < 8) {
    die("Mật khẩu phải có ít nhất 8 ký tự.");
}

if (!preg_match("/[a-z]/i", $password)) {
    die("Mật khẩu phải chứa ít nhất một chữ cái.");
}

if (!preg_match("/[0-9]/", $password)) {
    die("Mật khẩu phải chứa ít nhất một số.");
}

if ($password != $password_confirmation) {
    die("Mật khẩu xác nhận không khớp.");
}

// --------------------------------------------------------------------------
// 4. Băm (Hash) mật khẩu mới để lưu trữ an toàn
// --------------------------------------------------------------------------
// Sử dụng password_hash để tạo ra một hash an toàn
// PASSWORD_DEFAULT sử dụng thuật toán băm mạnh nhất hiện có (hiện tại là bcrypt)
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// --------------------------------------------------------------------------
// 5. Cập nhật mật khẩu đã băm vào database
// --------------------------------------------------------------------------
$sql = "UPDATE user SET password_hash = ? WHERE id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    // Ghi log lỗi chi tiết nếu câu lệnh SQL không chuẩn bị được
    error_log("Lỗi chuẩn bị câu lệnh UPDATE password trong process-reset-password.php: " . $mysqli->error);
    die("Có lỗi xảy ra khi cập nhật mật khẩu. (Lỗi DB)");
}

// 'si' nghĩa là: 's' cho $password_hash (string), 'i' cho $user["id"] (integer)
$stmt->bind_param("si", $password_hash, $user["id"]);

$execute_success = $stmt->execute(); // Thực thi câu lệnh UPDATE

if ($execute_success === false) {
    // Ghi log lỗi chi tiết nếu có lỗi trong quá trình thực thi
    error_log("Lỗi thực thi câu lệnh UPDATE password: " . $stmt->error);
    die("Lỗi khi thực thi cập nhật mật khẩu.");
}

// --------------------------------------------------------------------------
// 6. Xử lý kết quả cập nhật và chuyển hướng
// --------------------------------------------------------------------------
if ($mysqli->affected_rows > 0) {
    // Mật khẩu đã được đặt lại thành công, chuyển hướng người dùng
    // Đảm bảo đường dẫn này đúng với vị trí của file dangnhap.html
    header("Location: /duan01/dangnhap.html?message=" . urlencode("Mật khẩu của bạn đã được đặt lại thành công. Vui lòng đăng nhập!"));
    exit; // RẤT QUAN TRỌNG: Luôn gọi exit; sau header() để dừng script PHP
} else {
    // Trường hợp này có thể xảy ra nếu mật khẩu mới trùng với mật khẩu cũ (sau khi băm),
    // hoặc có lỗi DB nhưng không ném ra Exception, hoặc không có dòng nào bị ảnh hưởng.
    // Nếu bạn đã dùng password_hash(), thì việc trùng khớp hash là rất hiếm.
    // Thông báo lỗi chi tiết cho người dùng
    die("Không có gì thay đổi hoặc lỗi khi cập nhật mật khẩu. Vui lòng thử lại với một mật khẩu mới khác.");
}

?>