<?php
class Product {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll($page = 1, $limit = 5) {
        $start = ($page - 1) * $limit;
        $query = "SELECT * FROM products LIMIT ?, ?";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bind_param("ii", $start, $limit);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getTotal() {
        $query = "SELECT COUNT(*) as total FROM products";
        $result = $this->db->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getById($id) {
        $query = "SELECT * FROM products WHERE productid = ?";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function add($name, $price, $image) {
        $query = "INSERT INTO products (name, price, image) VALUES (?, ?, ?)";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bind_param("sds", $name, $price, $image);

        if ($stmt->execute()) {
            return true; // Indicate success
        } else {
            return false;
        }
    }

    public function update($id, $name, $price, $image) {
        $query = "UPDATE products SET name = ?, price = ?, image = ? WHERE productid = ?";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bind_param("sdsi", $name, $price, $image, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM products WHERE productid = ?";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>