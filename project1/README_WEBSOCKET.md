# Hướng dẫn sử dụng WebSocket cho cập nhật thời gian thực

## Giới thiệu

Hệ thống WebSocket được triển khai để cho phép cập nhật thời gian thực giữa các người dùng khác nhau. Khi admin thêm, sửa hoặc xóa sản phẩm, thông tin sẽ được cập nhật ngay lập tức trên trang của người dùng mà không cần tải lại trang.

## Cài đặt

1. Đã cài đặt thư viện Ratchet thông qua Composer:
   ```
   composer require cboden/ratchet
   ```

2. Cập nhật autoloader để hỗ trợ namespace App:
   ```
   composer dump-autoload
   ```

## Chạy WebSocket Server

1. Mở terminal và điều hướng đến thư mục gốc của dự án:
   ```
   cd /path/to/project1
   ```

2. Chạy WebSocket server:
   ```
   php websocket_server.php
   ```

3. Server sẽ khởi chạy trên cổng 8090. Bạn sẽ thấy thông báo:
   ```
   Khởi chạy WebSocket server trên port 8090...
   [YYYY-MM-DD HH:MM:SS] WebSocket server đã khởi động
   ```

4. Giữ terminal này mở trong khi bạn muốn sử dụng tính năng cập nhật thời gian thực.

## Cách hoạt động

1. **WebSocket Server**: Xử lý kết nối và truyền thông điệp giữa các client.
2. **WebSocketClient**: Lớp PHP để gửi thông báo từ server đến WebSocket Server.
3. **RealtimeClient**: Lớp JavaScript để kết nối đến WebSocket Server và xử lý các sự kiện.

## Các tính năng

### Thông báo thời gian thực

Khi admin thực hiện các thao tác sau, người dùng sẽ nhận được thông báo ngay lập tức:

1. **Thêm sản phẩm mới**: Hiển thị thông báo và cập nhật danh sách sản phẩm.
2. **Cập nhật sản phẩm**: Hiển thị thông báo và cập nhật thông tin sản phẩm.
3. **Xóa sản phẩm**: Hiển thị thông báo và xóa sản phẩm khỏi danh sách.

### Cập nhật giao diện

- Nếu người dùng đang ở trang danh sách sản phẩm, danh sách sẽ được cập nhật tự động.
- Nếu người dùng đang ở trang chi tiết sản phẩm và sản phẩm đó bị xóa, người dùng sẽ được chuyển hướng về trang danh sách.
- Nếu người dùng đang ở trang chi tiết sản phẩm và sản phẩm đó được cập nhật, trang sẽ tự động tải lại để hiển thị thông tin mới nhất.

## Xử lý lỗi

- WebSocket Client sẽ tự động thử kết nối lại nếu mất kết nối.
- Tối đa 5 lần thử kết nối lại, mỗi lần cách nhau 3 giây.
- Nếu không thể kết nối sau 5 lần thử, người dùng sẽ được thông báo cần tải lại trang.

## Ghi log

- WebSocket Server ghi log vào thư mục logs/websocket.log.
- Log bao gồm thông tin về kết nối, tin nhắn nhận được và lỗi.

## Phát triển thêm

Để mở rộng tính năng cập nhật thời gian thực cho các phần khác của ứng dụng:

1. Thêm các loại thông điệp mới trong `WebSocketServer.php`.
2. Thêm các phương thức thông báo trong `WebSocketClient.php`.
3. Đăng ký xử lý sự kiện mới trong JavaScript client.

## Lưu ý

- WebSocket Server cần được chạy liên tục để duy trì tính năng cập nhật thời gian thực.
- Trong môi trường sản xuất, bạn nên sử dụng process manager như PM2 hoặc Supervisor để giữ cho WebSocket Server luôn hoạt động.
- Đảm bảo cổng 8090 không bị chặn bởi tường lửa và có thể truy cập từ các client. 