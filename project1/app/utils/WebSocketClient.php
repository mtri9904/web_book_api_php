<?php
namespace App\Utils;

class WebSocketClient {
    private $host;
    private $port;
    private $notificationUrl;
    
    public function __construct($host = 'localhost', $port = 8080) {
        $this->host = $host;
        $this->port = $port;
        // URL để gửi thông báo qua HTTP thay vì WebSocket trực tiếp
        $this->notificationUrl = "http://{$host}:{$port}/notify";
    }
    
    /**
     * Gửi thông báo đến WebSocket server
     * 
     * @param array $data Dữ liệu cần gửi
     * @return bool Kết quả gửi
     */
    public function send($data) {
        try {
            // Thêm thông tin ngôn ngữ hiện tại vào dữ liệu
            $data['lang'] = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'vi';
            
            // Ghi log thông báo để debug
            error_log("Sending notification: " . json_encode($data));
            
            // Phương pháp 1: Sử dụng file để gửi thông báo
            // Lưu thông báo vào thư mục logs để WebSocket server đọc
            $notificationDir = __DIR__ . '/../../logs/notifications';
            
            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($notificationDir)) {
                mkdir($notificationDir, 0777, true);
            }
            
            // Tạo tên file duy nhất theo thời gian
            $filename = $notificationDir . '/notification_' . time() . '_' . uniqid() . '.json';
            
            // Lưu dữ liệu thông báo vào file
            $result = file_put_contents($filename, json_encode($data));
            
            if ($result === false) {
                error_log("WebSocketClient error: Không thể ghi file thông báo");
                return false;
            }
            
            error_log("WebSocketClient: Đã lưu thông báo vào file: " . $filename);
            
            // Phương pháp 2: Trực tiếp gửi thông báo đến tất cả client đang kết nối
            // Thực hiện trong môi trường thực tế
            // Trong demo này, chúng ta sẽ giả lập thành công
            return true;
        } catch (\Exception $e) {
            // Ghi log lỗi nếu cần
            error_log("WebSocketClient error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Gửi thông báo qua HTTP request
     * 
     * @param array $data Dữ liệu cần gửi
     * @return bool Kết quả gửi
     */
    private function sendViaHTTP($data) {
        try {
            // Thêm thông tin ngôn ngữ hiện tại vào dữ liệu
            $data['lang'] = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'vi';
            
            // Tạo một file socket để gửi HTTP request
            $fp = fsockopen($this->host, $this->port, $errno, $errstr, 5);
            if (!$fp) {
                error_log("WebSocketClient error: $errstr ($errno)");
                return false;
            }
            
            // Chuẩn bị dữ liệu JSON
            $jsonData = json_encode($data);
            
            // Tạo HTTP request
            $out = "POST /notify HTTP/1.1\r\n";
            $out .= "Host: {$this->host}:{$this->port}\r\n";
            $out .= "Content-Type: application/json\r\n";
            $out .= "Content-Length: " . strlen($jsonData) . "\r\n";
            $out .= "Connection: Close\r\n\r\n";
            $out .= $jsonData;
            
            // Gửi request
            fwrite($fp, $out);
            
            // Đọc response (không quan trọng lắm vì đây là one-way notification)
            $response = '';
            while (!feof($fp)) {
                $response .= fgets($fp, 128);
            }
            
            fclose($fp);
            
            error_log("WebSocketClient: Đã gửi thông báo qua HTTP thành công");
            error_log("WebSocketClient response: " . $response);
            
            return true;
        } catch (\Exception $e) {
            error_log("WebSocketClient HTTP error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Thông báo sản phẩm mới được thêm
     * 
     * @param array $product Thông tin sản phẩm
     * @return bool Kết quả gửi
     */
    public function notifyNewProduct($product) {
        return $this->send([
            'type' => 'new_product',
            'product' => $product,
            'messages' => [
                'vi' => 'Sản phẩm mới đã được thêm',
                'en' => 'New product has been added'
            ]
        ]);
    }
    
    /**
     * Thông báo sản phẩm được cập nhật
     * 
     * @param array $product Thông tin sản phẩm
     * @return bool Kết quả gửi
     */
    public function notifyUpdateProduct($product) {
        // Đảm bảo có flag hasChanges
        if (!isset($product['hasChanges'])) {
            $product['hasChanges'] = true; // Mặc định là có thay đổi
        }
        
        return $this->send([
            'type' => 'update_product',
            'product' => $product,
            'timestamp' => time(),
            'messages' => [
                'vi' => 'Sản phẩm đã được cập nhật',
                'en' => 'Product has been updated'
            ]
        ]);
    }
    
    /**
     * Thông báo sản phẩm bị xóa
     * 
     * @param int $productId ID sản phẩm
     * @return bool Kết quả gửi
     */
    public function notifyDeleteProduct($productId) {
        return $this->send([
            'type' => 'delete_product',
            'product_id' => $productId,
            'messages' => [
                'vi' => 'Sản phẩm đã bị xóa',
                'en' => 'Product has been deleted'
            ]
        ]);
    }
    
    /**
     * Thông báo voucher được cập nhật
     * 
     * @param array $voucher Thông tin voucher
     * @return bool Kết quả gửi
     */
    public function notifyUpdateVoucher($voucher) {
        return $this->send([
            'type' => 'update_voucher',
            'voucher' => $voucher,
            'timestamp' => time(),
            'messages' => [
                'vi' => 'Mã giảm giá đã được cập nhật',
                'en' => 'Voucher has been updated'
            ]
        ]);
    }
    
    /**
     * Thông báo voucher bị xóa
     * 
     * @param string $voucherCode Mã voucher
     * @return bool Kết quả gửi
     */
    public function notifyDeleteVoucher($voucherCode) {
        return $this->send([
            'type' => 'delete_voucher',
            'voucher_code' => $voucherCode,
            'messages' => [
                'vi' => 'Mã giảm giá đã bị xóa',
                'en' => 'Voucher has been deleted'
            ]
        ]);
    }
} 