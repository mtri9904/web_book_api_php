<?php
namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $adminClients = [];
    protected $userClients = [];
    protected $clientLanguages = [];
    protected $logFile;
    protected $notificationDir;
    protected $lastCheckTime;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->logFile = __DIR__ . '/../../logs/websocket.log';
        $this->notificationDir = __DIR__ . '/../../logs/notifications';
        $this->lastCheckTime = time();
        
        // Tạo thư mục logs nếu chưa tồn tại
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0777, true);
        }
        
        // Tạo thư mục notifications nếu chưa tồn tại
        if (!file_exists($this->notificationDir)) {
            mkdir($this->notificationDir, 0777, true);
        }
        
        $this->log("WebSocket server đã khởi động");
        
        // Thiết lập timer để kiểm tra thông báo mới mỗi 2 giây
        $this->setupNotificationChecker();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Lưu kết nối mới
        $this->clients->attach($conn);
        $this->log("Kết nối mới! ({$conn->resourceId})");
        
        // Set default language for the connection
        $this->clientLanguages[$conn->resourceId] = 'vi';
        
        // Kiểm tra thông báo mới khi có kết nối mới
        $this->checkNotifications();
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        $this->log("Nhận tin nhắn: " . json_encode($data));
        
        // Kiểm tra thông báo mới khi nhận tin nhắn
        $this->checkNotifications();
        
        // Store client language if provided
        if (isset($data['lang'])) {
            $this->clientLanguages[$from->resourceId] = $data['lang'];
            $this->log("Ngôn ngữ của client {$from->resourceId} được cập nhật: {$data['lang']}");
        }
        
        // Xử lý thông điệp đến
        if (isset($data['type'])) {
            switch ($data['type']) {
                case 'register':
                    // Đăng ký client là admin hoặc user
                    if (isset($data['role']) && $data['role'] === 'admin') {
                        $this->adminClients[$from->resourceId] = $from;
                        $this->log("Admin đã đăng ký: {$from->resourceId}");
                    } else {
                        $this->userClients[$from->resourceId] = $from;
                        $this->log("User đã đăng ký: {$from->resourceId}");
                    }
                    break;
                    
                case 'new_product':
                    // Admin thêm sản phẩm mới, gửi thông báo đến tất cả users
                    $this->log("Nhận thông báo sản phẩm mới");
                    $this->broadcastToUsers($data);
                    break;
                    
                case 'update_product':
                    // Admin cập nhật sản phẩm, gửi thông báo đến tất cả users
                    $this->log("Nhận thông báo cập nhật sản phẩm");
                    $this->broadcastToUsers($data);
                    break;
                    
                case 'delete_product':
                    // Admin xóa sản phẩm, gửi thông báo đến tất cả users
                    $this->log("Nhận thông báo xóa sản phẩm");
                    $this->broadcastToUsers($data);
                    break;
                    
                case 'update_voucher':
                    // Admin cập nhật voucher, gửi thông báo đến tất cả users
                    $this->log("Nhận thông báo cập nhật voucher");
                    $this->broadcastToUsers($data);
                    break;
                    
                case 'delete_voucher':
                    // Admin xóa voucher, gửi thông báo đến tất cả users
                    $this->log("Nhận thông báo xóa voucher");
                    $this->broadcastToUsers($data);
                    break;
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Xóa kết nối khi đóng
        $this->clients->detach($conn);
        
        // Xóa khỏi danh sách admin hoặc user nếu có
        if (isset($this->adminClients[$conn->resourceId])) {
            unset($this->adminClients[$conn->resourceId]);
        }
        
        if (isset($this->userClients[$conn->resourceId])) {
            unset($this->userClients[$conn->resourceId]);
        }
        
        // Remove client language
        if (isset($this->clientLanguages[$conn->resourceId])) {
            unset($this->clientLanguages[$conn->resourceId]);
        }
        
        $this->log("Kết nối {$conn->resourceId} đã đóng");
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->log("Lỗi: {$e->getMessage()}");
        $conn->close();
    }
    
    // Gửi thông báo đến tất cả người dùng
    public function broadcastToUsers($data)
    {
        $this->log("Gửi thông báo đến " . count($this->userClients) . " người dùng");
        foreach ($this->userClients as $resourceId => $client) {
            // Add language information to the outgoing message if available
            $outgoingData = $data;
            if (isset($this->clientLanguages[$resourceId])) {
                $outgoingData['lang'] = $this->clientLanguages[$resourceId];
            }
            
            $client->send(json_encode($outgoingData));
        }
    }
    
    // Thiết lập timer để kiểm tra thông báo mới
    protected function setupNotificationChecker()
    {
        // Sử dụng periodic timer để kiểm tra thông báo mới mỗi 2 giây
        // Trong Ratchet, chúng ta không thể sử dụng timer trực tiếp
        // Thay vào đó, chúng ta sẽ kiểm tra mỗi khi có kết nối hoặc tin nhắn mới
        $this->checkNotifications();
    }
    
    // Kiểm tra và xử lý thông báo mới từ thư mục notifications
    public function checkNotifications()
    {
        // Chỉ kiểm tra mỗi 2 giây để tránh quá tải
        $currentTime = time();
        if ($currentTime - $this->lastCheckTime < 2) {
            return;
        }
        
        $this->lastCheckTime = $currentTime;
        
        // Kiểm tra xem thư mục notifications có tồn tại không
        if (!is_dir($this->notificationDir)) {
            return;
        }
        
        // Lấy danh sách các file thông báo
        $files = glob($this->notificationDir . '/notification_*.json');
        if (empty($files)) {
            return;
        }
        
        $this->log("Tìm thấy " . count($files) . " thông báo mới");
        
        // Xử lý từng file thông báo
        foreach ($files as $file) {
            try {
                // Đọc nội dung file
                $content = file_get_contents($file);
                if ($content === false) {
                    $this->log("Không thể đọc file: " . $file);
                    continue;
                }
                
                // Parse JSON
                $data = json_decode($content, true);
                if ($data === null) {
                    $this->log("File không phải là JSON hợp lệ: " . $file);
                } else {
                    // Xử lý thông báo
                    $this->log("Xử lý thông báo từ file: " . basename($file));
                    
                    // Add translation metadata if missing
                    if (!isset($data['translations'])) {
                        $data['translations'] = $this->getDefaultTranslations($data);
                    }
                    
                    // Gửi thông báo đến tất cả người dùng
                    if (isset($data['type'])) {
                        $this->broadcastToUsers($data);
                    }
                }
                
                // Xóa file sau khi xử lý
                unlink($file);
            } catch (\Exception $e) {
                $this->log("Lỗi khi xử lý file {$file}: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Generate default translations for notification data if not provided
     * 
     * @param array $data Notification data
     * @return array Default translations
     */
    protected function getDefaultTranslations($data)
    {
        $translations = [];
        
        if (!isset($data['type'])) {
            return $translations;
        }
        
        switch ($data['type']) {
            case 'new_product':
                if (isset($data['product']) && isset($data['product']['name'])) {
                    $translations = [
                        'title' => [
                            'vi' => 'Sản phẩm mới',
                            'en' => 'New product'
                        ],
                        'message' => [
                            'vi' => 'Sản phẩm "' . $data['product']['name'] . '" vừa được thêm vào cửa hàng',
                            'en' => 'Product "' . $data['product']['name'] . '" has been added to the store'
                        ]
                    ];
                }
                break;
                
            case 'update_product':
                if (isset($data['product']) && isset($data['product']['name'])) {
                    $translations = [
                        'title' => [
                            'vi' => 'Cập nhật sản phẩm',
                            'en' => 'Product update'
                        ],
                        'message' => [
                            'vi' => 'Sản phẩm "' . $data['product']['name'] . '" vừa được cập nhật',
                            'en' => 'Product "' . $data['product']['name'] . '" has been updated'
                        ]
                    ];
                }
                break;
                
            case 'delete_product':
                $translations = [
                    'title' => [
                        'vi' => 'Sản phẩm đã xóa',
                        'en' => 'Product deleted'
                    ],
                    'message' => [
                        'vi' => 'Sản phẩm với ID ' . ($data['product_id'] ?? 'không xác định') . ' đã bị xóa khỏi cửa hàng',
                        'en' => 'Product with ID ' . ($data['product_id'] ?? 'unknown') . ' has been removed from the store'
                    ]
                ];
                break;
        }
        
        return $translations;
    }
    
    // Ghi log
    protected function log($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        
        // Hiển thị log ra console chỉ khi không phải là thông báo kết nối
        if (!str_contains($message, "Kết nối mới") && 
            !str_contains($message, "Kết nối") && 
            !str_contains($message, "đã đóng") &&
            !str_contains($message, "đã đăng ký")) {
            echo $logMessage;
        }
        
        // Ghi log vào file
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}
?> 