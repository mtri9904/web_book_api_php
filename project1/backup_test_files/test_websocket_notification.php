<?php
// Tệp test để gửi thông báo WebSocket

require_once('app/utils/WebSocketClient.php');

// Khởi tạo WebSocketClient
$client = new \App\Utils\WebSocketClient();

// Tạo dữ liệu sản phẩm mẫu
$product = [
    'id' => 999,
    'name' => 'Sách Test WebSocket ' . date('H:i:s'),
    'description' => 'Đây là sản phẩm test cho WebSocket',
    'price' => 150000,
    'category_id' => 1,
    'category_name' => 'Sách giáo khoa',
    'image' => 'uploads/book.jpg',
    'quantity' => 10,
    'average_rating' => 4.5,
    'review_count' => 10
];

// Gửi thông báo sản phẩm mới
$result = $client->notifyNewProduct($product);

if ($result) {
    echo "Đã gửi thông báo sản phẩm mới thành công!\n";
} else {
    echo "Có lỗi khi gửi thông báo!\n";
}

// Gửi thông báo cập nhật sản phẩm
$product['price'] = 180000;
$product['name'] = 'Sách Test WebSocket (Đã cập nhật) ' . date('H:i:s');
$result = $client->notifyUpdateProduct($product);

if ($result) {
    echo "Đã gửi thông báo cập nhật sản phẩm thành công!\n";
} else {
    echo "Có lỗi khi gửi thông báo cập nhật!\n";
}

// Gửi thông báo xóa sản phẩm
$result = $client->notifyDeleteProduct($product['id']);

if ($result) {
    echo "Đã gửi thông báo xóa sản phẩm thành công!\n";
} else {
    echo "Có lỗi khi gửi thông báo xóa!\n";
}

echo "Hoàn tất!\n";
?> 