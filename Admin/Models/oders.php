<?php
class Order {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll($page = 1, $limit = 5) {
        $start = ($page - 1) * $limit;
        $query = "SELECT * FROM orders LIMIT $start, $limit";
        return mysqli_query($this->db->conn, $query);
    }

    public function getTotal() {
        $query = "SELECT COUNT(*) as total FROM orders";
        $result = mysqli_query($this->db->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    public function add($user_id, $total, $status) {
        $user_id = (int)$user_id;
        $total = mysqli_real_escape_string($this->db->conn, $total);
        $status = mysqli_real_escape_string($this->db->conn, $status);
        $query = "INSERT INTO orders (user_id, total, status) VALUES ('$user_id', '$total', '$status')";
        return mysqli_query($this->db->conn, $query);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE orders SET status='$status' WHERE id=$id";
        return mysqli_query($this->db->conn, $query);
    }

    public function delete($id) {
        $query = "DELETE FROM orders WHERE id=$id";
        return mysqli_query($this->db->conn, $query);
    }
}
?>