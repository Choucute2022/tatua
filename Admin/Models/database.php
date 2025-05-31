<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "duan";
    public $conn;

    public function __construct() {
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        if (!$this->conn) {
            die("Kết nối thất bại: " . mysqli_connect_error());
        }
    }

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }

}
?>