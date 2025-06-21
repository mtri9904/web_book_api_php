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
<html lang="<?= $_SESSION['lang'] ?? 'vi' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('Quản trị hệ thống') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/project1/public/css/admin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
        }
        .sidebar {
            background: #1a2a44;
            color: #fff;
            min-height: 100vh;
            position: fixed;
            width: 250px;
        }
        .sidebar .logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar .logo h2 {
            color: #00ffcc;
            margin: 0;
            font-size: 24px;
        }
        .sidebar .nav-item {
            margin: 5px 0;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            transition: all 0.3s;
            border-radius: 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(0, 255, 204, 0.2);
            color: #00ffcc;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .header {
            background: white;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        .card-header {
            background: #1a2a44;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            font-weight: bold;
        }
        .btn-primary {
            background: #00ffcc;
            border: none;
            color: #1a2a44;
            font-weight: bold;
        }
        .btn-primary:hover {
            background: #00e6b8;
            color: #1a2a44;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            border-top: none;
        }
        .language-selector {
            margin-right: 15px;
        }
        .language-selector a {
            color: #1a2a44;
            text-decoration: none;
            margin-right: 5px;
            font-weight: bold;
        }
        .language-selector a.active {
            color: #00ffcc;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto p-0 sidebar">
                <div class="logo">
                    <h2><?= __('Quản trị') ?></h2>
                </div>
                <ul class="nav flex-column mt-3">
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage === 'dashboard' ? 'active' : '' ?>" href="/project1/admin">
                            <i class="bi bi-speedometer2"></i> <?= __('Tổng quan') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage === 'product' ? 'active' : '' ?>" href="/project1/admin/product/list">
                            <i class="bi bi-box"></i> <?= __('Sản phẩm') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage === 'category' ? 'active' : '' ?>" href="/project1/admin/category/list">
                            <i class="bi bi-tags"></i> <?= __('Danh mục') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage === 'voucher' ? 'active' : '' ?>" href="/project1/admin/voucher/list">
                            <i class="bi bi-ticket-perforated"></i> <?= __('Mã giảm giá') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage === 'order' ? 'active' : '' ?>" href="/project1/admin/order/list">
                            <i class="bi bi-cart3"></i> <?= __('Đơn hàng') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage === 'review' ? 'active' : '' ?>" href="/project1/admin/review">
                            <i class="bi bi-star"></i> <?= __('Đánh giá') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage === 'user' ? 'active' : '' ?>" href="/project1/admin/user/list">
                            <i class="bi bi-people"></i> <?= __('Người dùng') ?>
                        </a>
                    </li>
                    <li class="nav-item mt-5">
                        <a class="nav-link" href="/project1">
                            <i class="bi bi-shop"></i> <?= __('Xem cửa hàng') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/project1/account/logout">
                            <i class="bi bi-box-arrow-right"></i> <?= __('Đăng xuất') ?>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Main content -->
            <div class="col main-content">
                <div class="header">
                    <h4 class="m-0"><?= $pageTitle ?? __('Tổng quan') ?></h4>
                    <div class="d-flex align-items-center">
                        <div class="language-selector">
                            <a href="?lang=vi" class="<?= ($_SESSION['lang'] ?? 'vi') === 'vi' ? 'active' : '' ?>">VI</a>
                            <span>|</span>
                            <a href="?lang=en" class="<?= ($_SESSION['lang'] ?? 'vi') === 'en' ? 'active' : '' ?>">EN</a>
                        </div>
                        <span><?= __('Xin chào') ?>, <?= $_SESSION['user']->name ?? 'Admin' ?></span>
                    </div>
                </div>
                
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?> alert-dismissible fade show">
                        <?= $_SESSION['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                <?php endif; ?>
                
                <?php echo $content; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
</body>
</html> 