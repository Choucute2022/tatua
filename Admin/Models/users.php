<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll($page = 1, $limit = 5) {
        $start = ($page - 1) * $limit;
        $query = "SELECT * FROM users LIMIT $start, $limit";
        return mysqli_query($this->db->conn, $query);
    }

    public function getTotal() {
        $query = "SELECT COUNT(*) as total FROM users";
        $result = mysqli_query($this->db->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    public function getByEmail($email) {
        $email = mysqli_real_escape_string($this->db->conn, $email);
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($this->db->conn, $query);
        return mysqli_fetch_assoc($result);
    }

    public function add($email, $password, $role) {
        $email = mysqli_real_escape_string($this->db->conn, $email);
        $password = mysqli_real_escape_string($this->db->conn, $password);
        $role = mysqli_real_escape_string($this->db->conn, $role);

        // Kiểm tra xem mật khẩu đã được hash chưa
        if (!preg_match('/^\$2y\$10\$/', $password)) {
            // Nếu mật khẩu chưa được hash, hash nó
            $db = new Database();
            $password = $db->hashPassword($password);
        }

        $query = "INSERT INTO users (email, password, role) VALUES ('$email', '$password', '$role')";
        return mysqli_query($this->db->conn, $query);
    }

    public function delete($id) {
        $query = "DELETE FROM users WHERE id=$id";
        return mysqli_query($this->db->conn, $query);
    }
}
?>