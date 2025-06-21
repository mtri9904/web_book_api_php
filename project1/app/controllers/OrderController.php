<?php
require_once 'app/models/OrderModel.php'; // Thêm dòng này để tải OrderModel

class OrderController {
    private $orderModel;
    private $db;

    public function __construct($db) {
        $this->orderModel = new OrderModel($db);
        $this->db = $db;
    }

public function list() {

    if (!SessionHelper::isLoggedIn() || !isset($_SESSION['user_id'])) {
        header('Location: /project1/account/login');
        exit;
    }

    $userId = $_SESSION['user_id']; // Lấy ID người dùng từ session
    $orders = $this->orderModel->getOrdersByUserId($userId);
    $orderModel = $this->orderModel; // Truyền model cho view
    include 'app/views/order/list.php';
}
public function show($id) {
    if (!SessionHelper::isLoggedIn() || !isset($_SESSION['user_id'])) {
        header('Location: /project1/account/login');
        exit;
    }

    $order = $this->orderModel->getOrderById($id);
    $orderDetails = $this->orderModel->getOrderDetailsByOrderId($id);

    if ($order) {
        // Truyền kết nối database vào view để sử dụng cho ReviewModel
        $this->db = $this->orderModel->getDb();
        include 'app/views/order/show.php';
    } else {
        echo "Không tìm thấy đơn hàng.";
    }
}
public function delete($id) {
    if (!SessionHelper::isLoggedIn() || !isset($_SESSION['user_id'])) {
        header('Location: /project1/account/login');
        exit;
    }

    $result = $this->orderModel->deleteOrderById($id);
    if ($result) {
        header('Location: /project1/order/list');
    } else {
        echo "Không thể xóa đơn hàng.";
    }
}
}