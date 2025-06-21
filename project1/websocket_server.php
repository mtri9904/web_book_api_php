<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSocket\WebSocketServer;
use React\EventLoop\Factory as LoopFactory;
use React\Socket\Server as SocketServer;

// Tạo thư mục websocket nếu chưa tồn tại
if (!file_exists(__DIR__ . '/app/websocket')) {
    mkdir(__DIR__ . '/app/websocket', 0777, true);
}

// Kiểm tra xem file WebSocketServer.php đã tồn tại chưa
if (!file_exists(__DIR__ . '/app/websocket/WebSocketServer.php')) {
    die("Không tìm thấy file WebSocketServer.php. Vui lòng tạo file trước khi chạy server.");
}

// Đặt port cho WebSocket server
$port = 8090;

echo "Khởi chạy WebSocket server trên port {$port}...\n";

// Tạo event loop
$loop = LoopFactory::create();

// Tạo instance của WebSocketServer
$webSocketServer = new WebSocketServer();

// Tạo socket server
$socket = new SocketServer('0.0.0.0:' . $port, $loop);

// Tạo WebSocket server
$server = new IoServer(
    new HttpServer(
        new WsServer(
            $webSocketServer
        )
    ),
    $socket,
    $loop
);

echo "WebSocket server đang chạy trên 0.0.0.0:{$port}.\n";
echo "Nhấn Ctrl+C để dừng.\n";

// Thiết lập timer để kiểm tra thông báo mỗi 2 giây
$loop->addPeriodicTimer(2, function() use ($webSocketServer) {
    $webSocketServer->checkNotifications();
});

// Chạy server
$loop->run();
?> 