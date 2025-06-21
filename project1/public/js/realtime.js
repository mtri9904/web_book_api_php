/**
 * Realtime WebSocket Client
 * Xử lý kết nối WebSocket và cập nhật giao diện theo thời gian thực
 */

class RealtimeClient {
    constructor(serverUrl) {
        this.serverUrl = serverUrl;
        this.socket = null;
        this.connected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectInterval = 3000; // 3 giây
        this.handlers = {
            'new_product': [],
            'update_product': [],
            'delete_product': []
        };
        this.connectionPromise = null;
        this.role = 'user';
        
        // Debug info
        console.log("RealtimeClient initialized with server URL:", this.serverUrl);
    }

    /**
     * Kết nối đến WebSocket server
     */
    connect(role = 'user') {
        this.role = role;
        
        // Nếu đã kết nối, không cần kết nối lại
        if (this.connected && this.socket && this.socket.readyState === WebSocket.OPEN) {
            console.log('WebSocket already connected');
            return Promise.resolve(true);
        }
        
        // Nếu đang kết nối, trả về promise hiện tại
        if (this.connectionPromise) {
            return this.connectionPromise;
        }
        
        // Tạo promise mới cho kết nối
        this.connectionPromise = new Promise((resolve, reject) => {
            try {
                console.log(`Attempting to connect to WebSocket server at ${this.serverUrl} with role ${role}...`);
                
                // Đóng kết nối cũ nếu có
                if (this.socket) {
                    try {
                        this.socket.close();
                    } catch (e) {
                        console.warn('Error closing existing socket:', e);
                    }
                }
                
                this.socket = new WebSocket(this.serverUrl);

                // Xử lý sự kiện khi kết nối mở
                this.socket.onopen = () => {
                    console.log('WebSocket connected successfully!');
                    this.connected = true;
                    this.reconnectAttempts = 0;
                    
                    // Đăng ký vai trò (admin hoặc user)
                    this.send({
                        type: 'register',
                        role: role
                    });
                    
                    resolve(true);
                };

                // Xử lý sự kiện khi nhận tin nhắn
                this.socket.onmessage = (event) => {
                    try {
                        console.log('WebSocket received message:', event.data);
                        const data = JSON.parse(event.data);
                        this.handleMessage(data);
                    } catch (error) {
                        console.error('Error parsing WebSocket message:', error);
                    }
                };

                // Xử lý sự kiện khi đóng kết nối
                this.socket.onclose = (event) => {
                    console.log('WebSocket disconnected. Code:', event.code, 'Reason:', event.reason);
                    this.connected = false;
                    this.connectionPromise = null;
                    
                    if (!event.wasClean) {
                        console.warn('WebSocket connection closed unexpectedly');
                        reject(new Error('WebSocket connection closed unexpectedly'));
                        this.attemptReconnect();
                    } else {
                        resolve(false);
                    }
                };

                // Xử lý sự kiện khi có lỗi
                this.socket.onerror = (error) => {
                    console.error('WebSocket error:', error);
                    this.connectionPromise = null;
                    reject(error);
                };
                
                // Timeout cho kết nối
                setTimeout(() => {
                    if (!this.connected) {
                        console.warn('WebSocket connection timeout');
                        this.connectionPromise = null;
                        reject(new Error('Connection timeout'));
                        this.attemptReconnect();
                    }
                }, 5000);
                
            } catch (error) {
                console.error('Failed to connect to WebSocket server:', error);
                this.connectionPromise = null;
                reject(error);
                this.attemptReconnect();
            }
        });
        
        return this.connectionPromise;
    }

    /**
     * Thử kết nối lại khi mất kết nối
     */
    attemptReconnect() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            console.log(`Attempting to reconnect (${this.reconnectAttempts}/${this.maxReconnectAttempts})...`);
            
            setTimeout(() => {
                this.connect(this.role);
            }, this.reconnectInterval);
        } else {
            console.error('Max reconnect attempts reached. Please refresh the page.');
        }
    }

    /**
     * Gửi tin nhắn đến server
     */
    send(data) {
        if (this.connected && this.socket && this.socket.readyState === WebSocket.OPEN) {
            console.log('Sending message to WebSocket server:', data);
            this.socket.send(JSON.stringify(data));
            return true;
        } else {
            console.warn('Cannot send message: WebSocket not connected');
            // Thử kết nối lại và gửi tin nhắn sau khi kết nối thành công
            this.connect(this.role).then(connected => {
                if (connected && this.socket && this.socket.readyState === WebSocket.OPEN) {
                    console.log('Reconnected, now sending message:', data);
                    this.socket.send(JSON.stringify(data));
                }
            }).catch(error => {
                console.error('Failed to reconnect for sending message:', error);
            });
            return false;
        }
    }

    /**
     * Xử lý tin nhắn nhận được
     */
    handleMessage(data) {
        if (data && data.type) {
            console.log(`Processing message of type: ${data.type}`, data);
            
            // Gọi các hàm xử lý đã đăng ký cho loại tin nhắn này
            const handlers = this.handlers[data.type] || [];
            if (handlers.length === 0) {
                console.warn(`No handlers registered for message type: ${data.type}`);
            }
            
            handlers.forEach(handler => {
                try {
                    handler(data);
                } catch (error) {
                    console.error(`Error in handler for ${data.type}:`, error);
                }
            });
        } else {
            console.warn('Received message without type or invalid format:', data);
        }
    }

    /**
     * Đăng ký hàm xử lý cho loại tin nhắn
     */
    on(type, handler) {
        if (!this.handlers[type]) {
            this.handlers[type] = [];
        }
        
        // Kiểm tra xem handler đã được đăng ký chưa
        const existingHandler = this.handlers[type].find(h => h === handler);
        if (!existingHandler) {
            this.handlers[type].push(handler);
            console.log(`Registered handler for message type: ${type}`);
        } else {
            console.warn(`Handler already registered for message type: ${type}`);
        }
        
        return this; // Cho phép method chaining
    }

    /**
     * Hủy đăng ký hàm xử lý
     */
    off(type, handler) {
        if (this.handlers[type]) {
            this.handlers[type] = this.handlers[type].filter(h => h !== handler);
            console.log(`Unregistered handler for message type: ${type}`);
        }
        return this;
    }

    /**
     * Đóng kết nối WebSocket
     */
    disconnect() {
        if (this.socket) {
            this.socket.close();
            this.socket = null;
            this.connected = false;
            this.connectionPromise = null;
            console.log('WebSocket disconnected by user');
        }
    }
    
    /**
     * Kiểm tra trạng thái kết nối
     */
    isConnected() {
        return this.connected && this.socket && this.socket.readyState === WebSocket.OPEN;
    }
}

// Tạo instance mặc định
const realtime = new RealtimeClient('ws://localhost:8090');

// Tự động kết nối khi trang được tải
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, connecting to WebSocket...');
    realtime.connect('user').catch(error => {
        console.error('Failed to connect to WebSocket server:', error);
    });
}); 