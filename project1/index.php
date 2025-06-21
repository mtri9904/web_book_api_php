<?php
session_start();
require_once 'app/config/database.php'; // Kết nối cơ sở dữ liệu
require_once 'app/helpers/SessionHelper.php';
require_once 'app/helpers/LanguageHelper.php'; // Thêm helper ngôn ngữ
require_once 'app/models/VoucherModel.php';

// Tạo kết nối cơ sở dữ liệu
$db = (new Database())->getConnection();

// Cập nhật trạng thái voucher hết hạn mỗi khi trang web được tải
$voucherModel = new VoucherModel($db);
$voucherModel->updateVoucherStatus();

// Lấy URL từ request
$url = $_GET['url'] ?? '';
// Lấy controller và action từ query params nếu có (cho các route mới)
$controllerParam = $_GET['controller'] ?? '';
$actionParam = $_GET['action'] ?? '';

// Nếu có controller và action từ query params (route mới)
if (!empty($controllerParam)) {
    $controllerName = ucfirst($controllerParam) . 'Controller';
    $action = $actionParam ?: 'index';
} else {
    // Xử lý theo cách cũ nếu không có params mới
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    
    // Xác định controller và action từ URL
    $controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'HomeController';
    $action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';
}

// Xử lý các yêu cầu API
if ($controllerName === 'ApiController') {
    // Lấy resource API (product, category, etc.)
    $apiResource = isset($url[1]) ? $url[1] : '';
    
    // Xử lý API đăng nhập
    if ($apiResource === 'login') {
        require_once 'app/controllers/AccountController.php';
        $controller = new AccountController();
        $controller->apiLogin();
        exit;
    }
    
    $apiControllerName = ucfirst($apiResource) . 'ApiController';
    
    if (file_exists('app/controllers/' . $apiControllerName . '.php')) {
        require_once 'app/controllers/' . $apiControllerName . '.php';
        $controller = new $apiControllerName();
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Lấy ID từ URL nếu có
        $id = null;
        if (isset($url[2])) {
            // Debug URL parts
            error_log("API URL parts: " . implode('/', $url));
            
            if (is_numeric($url[2])) {
                $id = intval($url[2]);
                error_log("API ID: " . $id);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid ID format, must be numeric', 'provided' => $url[2]]);
                exit;
            }
        }
        
        switch ($method) {
            case 'GET':
                if ($id !== null) {
                    error_log("Calling show() with ID: " . $id);
                    $controller->show($id);
                } else {
                    error_log("Calling index()");
                    $controller->index();
                }
                break;
            case 'POST':
                $controller->store();
                break;
            case 'PUT':
                if ($id) {
                    $controller->update($id);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'ID is required for update']);
                }
                break;
            case 'DELETE':
                if ($id) {
                    $controller->destroy($id);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'ID is required for delete']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed']);
        }
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'API Controller not found']);
        exit;
    }
}

// Kiểm tra xem controller có tồn tại không
if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    die('Controller not found');
}
require_once 'app/controllers/' . $controllerName . '.php';
require_once 'app/models/OrderModel.php'; // Đảm bảo OrderModel được tải nếu cần
require_once 'app/controllers/ShopController.php';

// Tải AccountModel nếu cần
if ($controllerName === 'AccountController') {
    require_once 'app/models/AccountModel.php';
}

// Tải các model cho AdminController
if ($controllerName === 'AdminController') {
    require_once 'app/models/ProductModel.php';
    require_once 'app/models/CategoryModel.php';
    require_once 'app/models/VoucherModel.php';
    require_once 'app/models/OrderModel.php';
    require_once 'app/models/AccountModel.php';
    require_once 'app/models/ReviewModel.php';
}

// Tải các model cho AdminReviewController
if ($controllerName === 'AdminReviewController') {
    require_once 'app/models/ReviewModel.php';
    require_once 'app/models/ProductModel.php';
    require_once 'app/models/AccountModel.php';
}

// Tải các model cho ReviewController
if ($controllerName === 'ReviewController') {
    require_once 'app/models/ReviewModel.php';
    require_once 'app/models/ProductModel.php';
}

// Xử lý các route đặc biệt cho ProductController
if ($controllerName === 'ProductController') {
    // API để lấy danh sách sản phẩm dưới dạng JSON
    if ($action === 'getList') {
        $controller->getList();
        exit;
    }
}

// Xử lý các route đặc biệt cho HomeController
if ($controllerName === 'HomeController') {
    // API để lấy sản phẩm mới nhất dưới dạng JSON
    if ($action === 'getLatestProducts') {
        $controller->getLatestProducts();
        exit;
    }
}

// Khởi tạo controller và truyền kết nối cơ sở dữ liệu
$controller = new $controllerName($db);

// Xử lý routes đặc biệt cho AccountController
if ($controllerName === 'AccountController') {
    // Xử lý route cho đăng nhập Google
    if ($action === 'google_login') {
        $controller->googleLogin();
        exit;
    }
    
    // Xử lý route cho Google callback
    if ($action === 'google_callback') {
        $controller->googleCallback();
        exit;
    }
}

// Xử lý các route đặc biệt cho ShopController
if ($controllerName === 'ShopController' && $action === 'applyVoucher') {
    require_once 'app/controllers/ShopController.php';
    $controller = new ShopController($db);
    $controller->applyVoucher();
    exit;
}

// Xử lý các route đặc biệt cho AdminController
if ($controllerName === 'AdminController') {
    // Xử lý các route cho product trong admin
    if ($action === 'product') {
        $subAction = isset($url[2]) ? $url[2] : 'list';
        $id = isset($url[3]) ? $url[3] : null;
        
        switch ($subAction) {
            case 'list':
                $controller->productList();
                exit;
            case 'add':
                $controller->productAdd();
                exit;
            case 'edit':
                $controller->productEdit($id);
                exit;
            case 'show':
                $controller->productShow($id);
                exit;
            case 'delete':
                $controller->productDelete($id);
                exit;
        }
    }
    
    // Xử lý các route cho category trong admin
    if ($action === 'category') {
        $subAction = isset($url[2]) ? $url[2] : 'list';
        $id = isset($url[3]) ? $url[3] : null;
        
        switch ($subAction) {
            case 'list':
                $controller->categoryList();
                exit;
            case 'add':
                $controller->categoryAdd();
                exit;
            case 'edit':
                $controller->categoryEdit($id);
                exit;
            case 'show':
                $controller->categoryShow($id);
                exit;
            case 'delete':
                $controller->categoryDelete($id);
                exit;
        }
    }
    
    // Xử lý các route cho voucher trong admin
    if ($action === 'voucher') {
        $subAction = isset($url[2]) ? $url[2] : 'list';
        $id = isset($url[3]) ? $url[3] : null;
        
        switch ($subAction) {
            case 'list':
                $controller->voucherList();
                exit;
            case 'add':
                $controller->voucherAdd();
                exit;
            case 'edit':
                $controller->voucherEdit($id);
                exit;
            case 'show':
                $controller->voucherShow($id);
                exit;
            case 'delete':
                $controller->voucherDelete($id);
                exit;
        }
    }
    
    // Xử lý các route cho order trong admin
    if ($action === 'order') {
        $subAction = isset($url[2]) ? $url[2] : 'list';
        $id = isset($url[3]) ? $url[3] : null;
        
        switch ($subAction) {
            case 'list':
                $controller->orderList();
                exit;
            case 'show':
                $controller->orderShow($id);
                exit;
            case 'delete':
                $controller->orderDelete($id);
                exit;
        }
    }
    
    // Xử lý các route cho user trong admin
    if ($action === 'user') {
        $subAction = isset($url[2]) ? $url[2] : 'list';
        $id = isset($url[3]) ? $url[3] : null;
        
        switch ($subAction) {
            case 'list':
                $controller->userList();
                exit;
            case 'add':
                $controller->userAdd();
                exit;
            case 'edit':
                $controller->userEdit($id);
                exit;
            case 'show':
                $controller->userShow($id);
                exit;
            case 'delete':
                $controller->userDelete($id);
                exit;
        }
    }
    
    // Xử lý các route cho review trong admin
    if ($action === 'review') {
        // Khởi tạo AdminReviewController
        require_once 'app/controllers/AdminReviewController.php';
        $reviewController = new AdminReviewController($db);
        
        $subAction = isset($url[2]) ? $url[2] : 'index';
        $id = isset($url[3]) ? $url[3] : null;
        
        switch ($subAction) {
            case 'index':
                $reviewController->index();
                exit;
            case 'view':
                $reviewController->view($id);
                exit;
            case 'delete':
                $reviewController->delete($id);
                exit;
            case 'byProduct':
                $reviewController->byProduct($id);
                exit;
        }
    }
}

// Xử lý các route đặc biệt cho ReviewController
if ($controllerName === 'ReviewController') {
    if ($action === 'add') {
        $controller->add();
        exit;
    }
    
    if ($action === 'save') {
        $controller->save();
        exit;
    }
    
    if ($action === 'product') {
        $controller->product();
        exit;
    }
}

// Kiểm tra xem action có tồn tại không
if (!method_exists($controller, $action)) {
    die('Action not found');
}

// Gọi action tương ứng với các tham số từ URL
call_user_func_array([$controller, $action], array_slice($url, 2));