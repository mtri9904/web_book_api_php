<?php include_once 'app/helpers/TranslateHelper.php'; ?>
<?php
// Xử lý chuyển đổi ngôn ngữ
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] === 'en' ? 'en' : 'vi';
    $url = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: $url");
    exit;
}
if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'vi';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books & Media Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="/project1/public/js/realtime.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('https://images.unsplash.com/photo-1519681393784-d120267933ba') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            overflow-x: hidden;
        }

        .top-bar {
            background: #1a2a44;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .top-bar a {
            color: #00ffcc;
            text-decoration: none;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: rgba(0, 0, 0, 0.7);
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .logo h1 {
            font-size: 24px;
            margin: 0;
        }

        .nav-menu {
            display: flex;
            gap: 30px;
        }

        .nav-menu a {
            color:rgba(0, 238, 255, 0.59);
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }

        .main-content {
            text-align: center;
            padding: 100px 20px;
        }

        .main-content h1 {
            font-size: 120px;
            margin: 0;
            color: #fff;
            text-shadow: 0 0 20px #00ffcc;
        }

        .main-content p {
            font-size: 24px;
            margin: 10px 0;
            color:rgba(243, 243, 247, 0.7);
        }

        .main-content .explore {
            font-size: 16px;
            color: #fff;
            margin-bottom: 20px;
        }

        .main-content button {
            background: #00ffcc;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: bold;
            color: #1a2a44;
            cursor: pointer;
            border-radius: 5px;
        }

        .sidebar {
            position: fixed;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

       .sidebar button {
            background: #00ffcc;
            border: none;
            padding: 12px 16px;    
            border-radius: 10px;
            font-size: 1.2rem;    
            cursor: pointer;
            transition: background 0.3s;
        }

        .chat-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: #00ffcc;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 16px;
            color: #1a2a44;
            cursor: pointer;
        }

        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: #00ffcc;
            border-radius: 50%;
            opacity: 0.5;
        }
        .highlight-subtitle {
            font-size: 24px;
            text-shadow: 2px 2px 5px #000000; /* Đổ bóng để dễ đọc */
            font-weight: 500;
        }
        .cart-button {
            background: #00ffcc; /* Màu nền */
            border: none; /* Không viền */
            padding: 12px 16px; /* Khoảng cách bên trong */
            border-radius: 10px; /* Bo góc */
            font-size: 1.2rem; /* Kích thước chữ */
            cursor: pointer; /* Con trỏ chuột */
            transition: background 0.3s; /* Hiệu ứng chuyển đổi */
        }
        .cart-button:hover {
            background: #ffd700; /* Màu nền khi hover */
        }
        .header-bar {
            width: 100%;
            padding: 0 30px;
            box-sizing: border-box;
        }
        .nav-menu {
            flex: 1;
            gap: 30px;
            display: flex;
            align-items: center;
        }
        .account-area {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        @media (max-width: 900px) {
            .header-bar { flex-direction: column; align-items: flex-start; }
            .nav-menu { flex-wrap: wrap; }
            .account-area { margin-top: 10px; }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <span><?php echo __('CONTACT US ON 800 123-4567 OR INFO@YOURSTORE.COM'); ?></span>
        <div>
            <a href="#">🔍</a>
            <a href="#">👤</a>
            <span><?php echo __('YOUR CART: 0 ITEMS - $0.00'); ?></span>
        </div>
    </div>

    <div class="header">
        <div class="logo">
            <img src="https://phuongnamvina.com/img_data/images/mau-logo-nha-sach.jpg" alt="Logo">
            <h1><?php echo __('BOOKS & MEDIA STORE'); ?></h1>
        </div>
        <div class="header-bar" style="display: flex; align-items: center; justify-content: space-between;">
            <div class="nav-menu" style="display: flex; gap: 30px; align-items: center;">
                <a href="/project1"><?php echo __('HOME'); ?></a>
                <a href="/project1/shop/listproduct"><?php echo __('SHOP'); ?></a>
                <a href="/project1/order/list"><?php echo __('ĐƠN HÀNG'); ?></a>
                <a href="/project1/voucher/list"><?php echo __('VOUCHER'); ?></a>
            </div>
            <div class="account-area" style="margin-left: auto; display: flex; align-items: center; gap: 18px;">
                <div class="dropdown me-2">
                    <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button" id="langDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo (isset($_SESSION['lang']) && $_SESSION['lang'] === 'en') ? 'EN' : 'VN'; ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="langDropdown">
                        <li><a class="dropdown-item" href="?lang=vi"><?php echo __('Tiếng Việt'); ?></a></li>
                        <li><a class="dropdown-item" href="?lang=en"><?php echo __('English'); ?></a></li>
                    </ul>
                </div>
                <?php if (SessionHelper::isLoggedIn()): ?>
                    <div class="user-dropdown" style="position: relative; display: flex; align-items: center; gap: 8px;">
                        <div class="avatar-circle" style="background: linear-gradient(135deg, #00ffcc, #1a2a44); color: #fff; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                            <?php 
                            // Lấy thông tin account từ database
                            require_once __DIR__ . '/../../models/AccountModel.php';
                            $db = (new Database())->getConnection();
                            $accountModel = new AccountModel($db);
                            $account = $accountModel->getAccountById($_SESSION['user_id']);
                            
                            // Hiển thị chữ cái đầu của fullname nếu có, ngược lại dùng username
                            echo strtoupper(substr($account && !empty($account->fullname) ? $account->fullname : $_SESSION['username'], 0, 1)); 
                            ?>
                        </div>
                        <span style="font-weight: bold; color: #00ffcc; cursor: pointer;" onclick="toggleUserMenu()">
                            <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>
                            <svg style="width: 16px; vertical-align: middle; margin-left: 2px;" fill="#00ffcc" viewBox="0 0 20 20"><path d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.06l3.71-3.83a.75.75 0 1 1 1.08 1.04l-4.25 4.39a.75.75 0 0 1-1.08 0L5.21 8.27a.75.75 0 0 1 .02-1.06z"/></svg>
                        </span>
                        <div id="userMenu" class="user-menu" style="display: none; position: absolute; top: 120%; right: 0; background: #fff; border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); min-width: 180px; z-index: 100;">
                        <a href="/project1/account/info" style="display: block; padding: 12px 20px; color: #1a2a44; text-decoration: none; border-bottom: 1px solid #f0f0f0;"><i class="fas fa-user"></i> <?php echo __('Tài khoản'); ?></a>
                            <a href="/project1/order/list" style="display: block; padding: 12px 20px; color: #1a2a44; text-decoration: none; border-bottom: 1px solid #f0f0f0;"><i class="fas fa-shopping-cart"></i> <?php echo __('Đơn hàng'); ?></a>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="/project1/admin/index" style="display: block; padding: 12px 20px; color: #1a2a44; text-decoration: none; border-bottom: 1px solid #f0f0f0;"><i class="fas fa-user-shield"></i> <?php echo __('TRANG ADMIN'); ?></a>
                            <?php endif; ?>
                            <a href="/project1/account/logout" style="display: block; padding: 12px 20px; color: #dc3545; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> <?php echo __('Đăng xuất'); ?></a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/project1/account/login" class="btn-login-glow" style="background: linear-gradient(90deg, #00ffcc, #1a2a44); color: #fff; padding: 8px 22px; border-radius: 20px; font-weight: bold; box-shadow: 0 2px 8px rgba(0,255,204,0.15); display: flex; align-items: center; gap: 8px; text-decoration: none;">
                        <svg style="width: 20px; height: 20px;" fill="#fff" viewBox="0 0 20 20"><path d="M10 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm-7 8a7 7 0 1 1 14 0H3z"/></svg>
                        <?php echo __('Đăng nhập'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="main-content">
        <p class="explore"><?php echo __('EXPLORE BOOK RECOMMENDATIONS'); ?></p>
        <h1><?php echo __('BOOKS'); ?></h1>
        <p class="highlight-subtitle"><?php echo __('DISCOVER YOUR NEXT FAVORITE BOOK WITH US'); ?></p>
        <a href="/project1/shop/listproduct">       
        <button><?php echo __('START SHOPPING NOW'); ?></button>
        </a>
    </div>
    
    <div class="sidebar">
        <a href="/project1/shop/cart" class="cart-button">🛒</a>
        <button>📦</button>
        <button>💳</button>
    </div>

    <button class="chat-button"><?php echo __('Presale Chat'); ?></button>

    <div class="particles" id="particles"></div>

    <script>
        // Tạo hiệu ứng hạt (particles)
        const particlesContainer = document.getElementById('particles');
        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            particle.style.width = `${Math.random() * 5 + 2}px`;
            particle.style.height = particle.style.width;
            particle.style.left = `${Math.random() * 100}vw`;
            particle.style.top = `${Math.random() * 100}vh`;
            particle.style.animation = `float ${Math.random() * 10 + 5}s infinite`;
            particlesContainer.appendChild(particle);
        }
    </script>

    <style>
        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0); }
        }
    </style>

    <style>
    .user-dropdown:hover .avatar-circle {
        box-shadow: 0 0 0 4px #00ffcc44;
    }
    .user-menu a:hover {
        background: #f6f6f6;
    }
    .btn-login-glow:hover {
        background: linear-gradient(90deg, #1a2a44, #00ffcc);
        color: #fff;
        box-shadow: 0 4px 16px #00ffcc55;
    }
    </style>

    <script>
    function toggleUserMenu() {
        var menu = document.getElementById('userMenu');
        if (menu) menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    }
    // Đóng menu khi click ra ngoài
    document.addEventListener('click', function(e) {
        var menu = document.getElementById('userMenu');
        if (!menu) return;
        var userDropdown = document.querySelector('.user-dropdown');
        if (userDropdown && !userDropdown.contains(e.target)) {
            menu.style.display = 'none';
        }
    });
    
    // Kết nối WebSocket khi trang đã tải xong
    document.addEventListener('DOMContentLoaded', function() {
        // Kiểm tra xem script realtime.js đã được tải chưa
        if (typeof realtime !== 'undefined') {
            // Kết nối với vai trò user
            realtime.connect('user');
            
            // Xử lý sự kiện khi nhận được thông báo sản phẩm mới
            realtime.on('new_product', function(data) {
                // Hiển thị thông báo
                showNotification('<?php echo __('Sản phẩm mới'); ?>', `<?php echo __('Sản phẩm'); ?> "${data.product.name}" <?php echo __('vừa được thêm vào hệ thống.'); ?>`);
            });
            
            // Xử lý sự kiện khi nhận được thông báo cập nhật sản phẩm
            realtime.on('update_product', function(data) {
                // Hiển thị thông báo
                showNotification('<?php echo __('Cập nhật sản phẩm'); ?>', `<?php echo __('Sản phẩm'); ?> "${data.product.name}" <?php echo __('vừa được cập nhật.'); ?>`);
            });
            
            // Xử lý sự kiện khi nhận được thông báo xóa sản phẩm
            realtime.on('delete_product', function(data) {
                // Hiển thị thông báo
                showNotification('<?php echo __('Xóa sản phẩm'); ?>', `<?php echo __('Sản phẩm đã bị xóa khỏi hệ thống.'); ?>`);
            });
        } else {
            console.warn('<?php echo __("WebSocket client (realtime.js) chưa được tải. Tính năng cập nhật thời gian thực sẽ không hoạt động."); ?>');
        }
    });
    
    // Hàm hiển thị thông báo
    function showNotification(title, message) {
        // Kiểm tra xem đã có hàm showNotification từ footer chưa
        if (window.showNotification) {
            window.showNotification(title, message);
            return;
        }
        
        // Nếu chưa có, tạo hàm hiển thị thông báo đơn giản
        // Kiểm tra xem trình duyệt có hỗ trợ thông báo không
        if (!("Notification" in window)) {
            alert(`${title}: ${message}`);
            return;
        }
        
        // Kiểm tra quyền thông báo
        if (Notification.permission === "granted") {
            new Notification(title, { body: message });
        } 
        // Nếu chưa được cấp quyền, yêu cầu quyền
        else if (Notification.permission !== "denied") {
            Notification.requestPermission().then(function (permission) {
                if (permission === "granted") {
                    new Notification(title, { body: message });
                }
            });
        }
        
        // Hiển thị thông báo trên giao diện
        const notificationContainer = document.getElementById('notification-container');
        if (!notificationContainer) {
            // Tạo container nếu chưa có
            const container = document.createElement('div');
            container.id = 'notification-container';
            container.style.position = 'fixed';
            container.style.top = '20px';
            container.style.right = '20px';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }
        
        // Tạo thông báo mới
        const notification = document.createElement('div');
        notification.className = 'toast show';
        notification.style.background = 'rgba(0, 0, 0, 0.8)';
        notification.style.color = '#fff';
        notification.style.borderRadius = '5px';
        notification.style.padding = '15px';
        notification.style.marginBottom = '10px';
        notification.style.minWidth = '250px';
        notification.innerHTML = `
            <div class="toast-header">
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;
        
        // Thêm vào container
        document.getElementById('notification-container').appendChild(notification);
        
        // Tự động ẩn sau 5 giây
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
    </script>

    <style>
        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0); }
        }
    </style>
</body>
</html>