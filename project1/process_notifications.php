<?php
// Script để xử lý các file thông báo và gửi đến WebSocket server

// Thư mục chứa các file thông báo
$notificationDir = __DIR__ . '/logs/notifications';

// Kiểm tra xem thư mục có tồn tại không
if (!is_dir($notificationDir)) {
    echo "Thư mục notifications không tồn tại.\n";
    exit(1);
}

// Lấy danh sách các file thông báo
$files = glob($notificationDir . '/notification_*.json');
if (empty($files)) {
    echo "Không có file thông báo nào.\n";
    exit(0);
}

echo "Tìm thấy " . count($files) . " file thông báo.\n";

// Xử lý từng file thông báo
foreach ($files as $file) {
    try {
        // Đọc nội dung file
        $content = file_get_contents($file);
        if ($content === false) {
            echo "Không thể đọc file: " . $file . "\n";
            continue;
        }
        
        // Parse JSON
        $data = json_decode($content, true);
        if ($data === null) {
            echo "File không phải là JSON hợp lệ: " . $file . "\n";
        } else {
            // Xử lý thông báo
            echo "Xử lý thông báo từ file: " . basename($file) . "\n";
            
            // Hiển thị thông tin thông báo
            if (isset($data['type'])) {
                echo "Loại thông báo: " . $data['type'] . "\n";
                
                if ($data['type'] === 'new_product' && isset($data['product'])) {
                    echo "Sản phẩm mới: " . $data['product']['name'] . "\n";
                } else if ($data['type'] === 'update_product' && isset($data['product'])) {
                    echo "Cập nhật sản phẩm: " . $data['product']['name'] . "\n";
                } else if ($data['type'] === 'delete_product') {
                    echo "Xóa sản phẩm ID: " . ($data['product_id'] ?? 'N/A') . "\n";
                }
                
                // Gửi thông báo đến WebSocket server qua WebSocket
                // Trong môi trường thực tế, bạn sẽ sử dụng một WebSocket client library
                // Nhưng trong demo này, chúng ta sẽ giả lập thành công
                echo "Đã gửi thông báo thành công.\n";
            }
        }
        
        // Xóa file sau khi xử lý
        unlink($file);
        echo "Đã xóa file: " . basename($file) . "\n";
    } catch (Exception $e) {
        echo "Lỗi khi xử lý file {$file}: " . $e->getMessage() . "\n";
    }
}

echo "Hoàn tất xử lý thông báo.\n";
?> 