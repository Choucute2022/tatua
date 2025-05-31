<?php

$host = "localhost";
$dbname = "duan1"; // Thay bằng tên database của bạn
$username = "root"; // Thay bằng username của bạn
$password = ""; // Mật khẩu để trống

mysqli_report(MYSQLI_REPORT_
OFF);

$mysqli = new mysqli($host, $username, $password, $dbname); // Truyền biến $password

if ($mysqli->connect_error) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;