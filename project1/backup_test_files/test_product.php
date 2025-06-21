<?php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';

// Tạo kết nối cơ sở dữ liệu
$db = (new Database())->getConnection();
$productModel = new ProductModel($db);

// ID sản phẩm cần kiểm tra
$id = isset($_GET['id']) ? intval($_GET['id']) : 7;

// Lấy thông tin sản phẩm
$product = $productModel->getProductById($id);

// Hiển thị kết quả
header('Content-Type: application/json');

if ($product) {
    echo json_encode([
        'success' => true,
        'product' => $product,
        'id_requested' => $id
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Product not found',
        'id_requested' => $id
    ]);
}
?> 