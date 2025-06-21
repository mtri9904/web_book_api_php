<?php
class OrderModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getOrdersByUserId($userId) {
        $query = "SELECT o.*, a.fullname as full_name,
                  o.total as total_amount,
                  o.voucher_discount,
                  a.email as email
                  FROM orders o
                  LEFT JOIN account a ON o.user_id = a.id
                  WHERE o.user_id = :user_id 
                  ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function getOrderById($id) {
        $query = "SELECT o.*, a.fullname as full_name,
                  o.total as total_amount,
                  o.voucher_discount,
                  a.email as email
                  FROM orders o
                  LEFT JOIN account a ON o.user_id = a.id
                  WHERE o.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public function getOrderDetailsByOrderId($orderId) {
        $query = "SELECT 
                  od.*,
                  COALESCE(od.product_name, p.name, '[Sản phẩm đã bị xóa]') AS product_name,
                  COALESCE(od.product_image, p.image) AS product_image,
                  COALESCE(od.product_description, p.description) AS product_description,
                  COALESCE(od.product_category_id, p.category_id) AS category_id,
                  COALESCE(od.product_category_name, c.name) AS category_name
                  FROM order_details od
                  LEFT JOIN product p ON od.product_id = p.id
                  LEFT JOIN category c ON COALESCE(od.product_category_id, p.category_id) = c.id
                  WHERE od.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function deleteOrderById($id) {
        // Xóa các bản ghi liên quan trong bảng order_details
        $queryDetails = "DELETE FROM order_details WHERE order_id = :order_id";
        $stmtDetails = $this->conn->prepare($queryDetails);
        $stmtDetails->bindParam(':order_id', $id, PDO::PARAM_INT);
        $stmtDetails->execute();

        // Xóa đơn hàng trong bảng orders
        $queryOrder = "DELETE FROM orders WHERE id = :id";
        $stmtOrder = $this->conn->prepare($queryOrder);
        $stmtOrder->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmtOrder->execute();
    }

    // Lấy danh sách sản phẩm đã mua của user (không trùng lặp)
    public function getPurchasedProductsByUserId($userId) {
        $query = "SELECT DISTINCT p.id, p.name, p.image FROM orders o
                  JOIN order_details od ON o.id = od.order_id
                  JOIN product p ON od.product_id = p.id
                  WHERE o.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getOrderCount() {
        $query = "SELECT COUNT(*) FROM orders";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getLatestOrders($limit = 5) {
        $query = "SELECT * FROM orders ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllOrders() {
        $query = "SELECT o.*, a.fullname as full_name,
                  o.total as total_amount,
                  o.voucher_discount,
                  a.email as email  
                  FROM orders o
                  LEFT JOIN account a ON o.user_id = a.id
                  ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getDb() {
        return $this->conn;
    }
}