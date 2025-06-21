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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            margin-right: 15px;
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
            font-weight: bold;
            letter-spacing: 1px;
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
            transition: color 0.3s;
        }

        .nav-menu a:hover {
            color: #00ffcc;
        }

        .main-content {
            padding: 40px 20px;
            background: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            margin: 20px auto;
            max-width: 1200px;
        }

        .main-content h1 {
            font-size: 36px;
            color: #ffd700;
            text-shadow: 0 0 10px #00ffcc;
            margin-bottom: 20px;
        }

        .btn-primary {
            background: #00ffcc;
            border: none;
            color: #1a2a44;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn-primary:hover {
            background: #ffd700;
        }

        .btn-secondary {
            background: #1a2a44;
            border: none;
            color: #fff;
            font-weight: bold;
        }

        .btn-warning {
            background: #ffd700;
            color: #1a2a44;
            font-weight: bold;
        }

        .btn-danger {
            background: #ff4d4d;
            color: #fff;
            font-weight: bold;
        }

        .table {
            background: rgba(255, 255, 255, 0.9);
            color: #1a2a44;
            border-radius: 10px;
        }

        .table th {
            background: #1a2a44;
            color: #00ffcc;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            color: #1a2a44;
            border: 1px solid #00ffcc;
        }

        .form-control:focus {
            border-color: #ffd700;
            box-shadow: 0 0 5px #ffd700;
        }

        .alert-danger {
            background: rgba(255, 75, 75, 0.9);
            color: #fff;
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

        .sidebar button:hover {
            background: #ffd700;
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
            transition: background 0.3s;
        }

        .chat-button:hover {
            background: #ffd700;
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

        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0); }
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
        
        /* Toast notification styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast {
            min-width: 250px;
            margin-bottom: 10px;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: slide-in 0.3s ease-out;
        }
        
        .toast.success {
            border-left: 4px solid #28a745;
        }
        
        .toast.error {
            border-left: 4px solid #dc3545;
        }
        
        .toast.warning {
            border-left: 4px solid #ffc107;
        }
        
        .toast-header {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            background-color: rgba(255, 255, 255, 0.85);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .toast-body {
            padding: 0.75rem;
            color: #212529;
        }
        
        .toast-icon {
            margin-right: 8px;
            font-size: 1.25rem;
        }
        
        .toast.success .toast-icon {
            color: #28a745;
        }
        
        .toast.error .toast-icon {
            color: #dc3545;
        }
        
        .toast.warning .toast-icon {
            color: #ffc107;
        }
        
        .toast-close {
            margin-left: auto;
            background: transparent;
            border: 0;
            font-size: 1.25rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            opacity: 0.5;
            cursor: pointer;
        }
        
        .toast-close:hover {
            opacity: 0.75;
        }
        
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fade-out {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Toast container -->
    <div class="toast-container" id="toastContainer"></div>
    
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
        <div class="header-bar" style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
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
                            <a href="/project1/admin" style="display: block; padding: 12px 20px; color: #1a2a44; text-decoration: none; border-bottom: 1px solid #f0f0f0;"><i class="fas fa-user-shield"></i> <?php echo __('TRANG ADMIN'); ?></a>
                            <?php endif; ?>
                            <a href="/project1/account/logout" style="display: block; padding: 12px 20px; color: #dc3545; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> <?php echo __('Đăng xuất'); ?></a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/project1/account/login" class="btn-login-glow" style="background: linear-gradient(90deg, #00ffcc, #1a2a44); color: #fff; padding: 8px 22px; border-radius: 20px; font-weight: bold; box-shadow: 0 2px 8px rgba(0,255,204,0.15); display: flex; align-items: center; gap: 8px; text-decoration: none;">
                        <svg style="width: 20px; height: 20px;" fill="#fff" viewBox="0 0 20 20"><path d="M10 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm-7 8a7 7 0 1 1 14 0H3z"/></svg>
                        <?php echo __('Login'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="sidebar">
        <a href="/project1/shop/cart" class="cart-button">🛒</a>
        <button>📦</button>
        <button>💳</button>
    </div>

    <button class="chat-button"><?php echo __('Presale Chat'); ?></button>

    <div class="particles" id="particles"></div>

    <div class="main-content">
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
    </script>