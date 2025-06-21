<?php
// File test để thêm sản phẩm mới và gửi thông báo WebSocket

require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/utils/WebSocketClient.php');

// Khởi tạo database connection
$db = (new Database())->getConnection();

// Khởi tạo ProductModel
$productModel = new ProductModel($db);

// Khởi tạo WebSocketClient
$webSocketClient = new \App\Utils\WebSocketClient();

// Tạo dữ liệu sản phẩm mẫu
$name = "Sách Test Realtime " . date('H:i:s');
$description = "Đây là sách test cho realtime WebSocket";
$price = 150000;
$category_id = 1;
$image = "uploads/book.jpg";
$quantity = 10;

// Thêm sản phẩm vào database
$result = $productModel->addProduct($name, $description, $price, $category_id, $image, $quantity);

if (is_array($result)) {
    echo "Lỗi khi thêm sản phẩm: " . print_r($result, true) . "\n";
} else {
    // Lấy ID của sản phẩm vừa thêm
    $productId = $db->lastInsertId();
    echo "Đã thêm sản phẩm mới với ID: " . $productId . "\n";
    
    // Lấy thông tin sản phẩm vừa thêm
    $product = $productModel->getProductById($productId);
    
    if ($product) {
        echo "Thông tin sản phẩm: " . print_r($product, true) . "\n";
        
        // Gửi thông báo WebSocket về sản phẩm mới
        $notifyResult = $webSocketClient->notifyNewProduct($product);
        
        if ($notifyResult) {
            echo "Đã gửi thông báo WebSocket thành công!\n";
        } else {
            echo "Có lỗi khi gửi thông báo WebSocket!\n";
        }
        
        // Chạy script xử lý thông báo
        echo "Đang chạy script xử lý thông báo...\n";
        system('php process_notifications.php');
    } else {
        echo "Không thể lấy thông tin sản phẩm vừa thêm!\n";
    }
}

echo "Hoàn tất!\n";
?> 