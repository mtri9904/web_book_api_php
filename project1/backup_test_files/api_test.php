<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');

// Kết nối database
$db = (new Database())->getConnection();
$productModel = new ProductModel($db);

// Lấy ID từ tham số URL
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

header('Content-Type: application/json');

if ($id) {
    // Lấy sản phẩm theo ID
    $product = $productModel->getProductById($id);
    if ($product) {
        echo json_encode($product);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Product not found']);
    }
} else {
    // Lấy tất cả sản phẩm
    $products = $productModel->getProducts();
    echo json_encode($products);
}
?> 