<?php
require_once('app/utils/WebSocketClient.php');

use App\Utils\WebSocketClient;

class AdminController
{
    private $db;
    private $productModel;
    private $categoryModel;
    private $voucherModel;
    private $orderModel;
    private $accountModel;
    private $webSocketClient;
    private $reviewModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->productModel = new ProductModel($db);
        $this->categoryModel = new CategoryModel($db);
        $this->voucherModel = new VoucherModel($db);
        $this->orderModel = new OrderModel($db);
        $this->accountModel = new AccountModel($db);
        $this->reviewModel = new ReviewModel($db);
        
        // Initialize WebSocket client with the correct host and port
        $this->webSocketClient = new WebSocketClient('localhost', 8080);
        
        // Check if user is admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /project1/account/login');
            exit;
        }
    }

    // Dashboard
    public function index()
    {
        $productCount = $this->productModel->getProductCount();
        $categoryCount = $this->categoryModel->getCategoryCount();
        $vouchers = $this->voucherModel->getVouchers();
        $userCount = $this->accountModel->getAccountCount();
        $orderCount = $this->orderModel->getOrderCount();
        
        // Lấy số lượng đánh giá
        $reviewCount = $this->countAllReviews();
        
        // Lấy danh sách tất cả danh mục
        $categories = $this->categoryModel->getCategories();
        
        // Lấy thống kê số lượng sản phẩm theo từng danh mục
        $categoryStats = $this->getCategoryProductStats();
        
        include 'app/views/admin/dashboard.php';
    }
    
    // Phương thức lấy thống kê sản phẩm theo danh mục
    private function getCategoryProductStats()
    {
        $stats = [];
        
        $query = "SELECT c.id AS category_id, COUNT(p.id) AS product_count 
                 FROM category c
                 LEFT JOIN product p ON c.id = p.category_id
                 GROUP BY c.id";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stats[$row['category_id']] = (int)$row['product_count'];
        }
        
        return $stats;
    }
    
    // Phương thức đếm tổng số đánh giá
    private function countAllReviews()
    {
        $query = "SELECT COUNT(*) as count FROM product_reviews";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    // Product management
    public function productList()
    {
        // Get search parameters
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
        $categoryFilter = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $isAjax = isset($_GET['ajax']) && $_GET['ajax'] == 1;
        
        // Get all categories for the filter dropdown
        $categories = $this->categoryModel->getCategories();
        
        if (!empty($searchTerm) || $categoryFilter > 0) {
            // Search products by name/description and/or category
            $products = $this->productModel->searchAdminProducts($searchTerm, $categoryFilter);
        } else {
            // Get all products if no search criteria
            $products = $this->productModel->getProducts();
        }
        
        // If it's an AJAX request, only return the products section
        if ($isAjax) {
            include 'app/views/admin/product/list_ajax.php';
        } else {
            include 'app/views/admin/product/list.php';
        }
    }

    public function productAdd()
    {
        $categories = $this->categoryModel->getCategories();
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? '';
            $quantity = $_POST['quantity'] ?? '';
            $image = '';
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/products/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $image = $uploadPath;
                } else {
                    $errors['image'] = 'Failed to upload image';
                }
            } else {
                $errors['image'] = 'Image is required';
            }
            
            if (empty($errors)) {
                $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image, $quantity);
                
                if ($result === true) {
                    // Lấy sản phẩm vừa thêm để gửi thông báo
                    $lastId = $this->db->lastInsertId();
                    $newProduct = $this->productModel->getProductById($lastId);
                    
                    // Gửi thông báo qua WebSocket
                    if ($newProduct) {
                        $this->webSocketClient->notifyNewProduct([
                            'id' => $newProduct->id,
                            'name' => $newProduct->name,
                            'price' => $newProduct->price,
                            'image' => $newProduct->image,
                            'description' => $newProduct->description
                        ]);
                    }
                    
                    header('Location: /project1/admin/product/list');
                    exit;
                } else {
                    $errors = $result;
                }
            }
        }
        
        include 'app/views/admin/product/add.php';
    }

    public function productEdit($id)
    {
        $product = $this->productModel->getProductById($id);
        $categories = $this->categoryModel->getCategories();
        $errors = [];
        
        if (!$product) {
            header('Location: /project1/admin/product/list');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? '';
            $quantity = $_POST['quantity'] ?? '';
            $image = $product->image;
            
            // Kiểm tra xem có thay đổi thực sự không
            $hasRealChanges = false;
            
            // So sánh với giá trị cũ
            if ($name !== $product->name || 
                $description !== $product->description || 
                (float)$price !== (float)$product->price || 
                (int)$category_id !== (int)$product->category_id || 
                (int)$quantity !== (int)$product->quantity) {
                $hasRealChanges = true;
            }
            
            // Handle image upload if a new image is provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/products/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    // Delete old image if exists
                    if (!empty($product->image) && file_exists($product->image)) {
                        unlink($product->image);
                    }
                    $image = $uploadPath;
                    $hasRealChanges = true;
                } else {
                    $errors['image'] = 'Failed to upload image';
                }
            }
            
            if (empty($errors)) {
                $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image, $quantity);
                
                if ($result) {
                    // Lấy thông tin sản phẩm sau khi cập nhật
                    $updatedProduct = $this->productModel->getProductById($id);
                    
                    // Gửi thông báo qua WebSocket chỉ khi có thay đổi thực sự
                    if ($updatedProduct && $hasRealChanges) {
                        $this->webSocketClient->notifyUpdateProduct([
                            'id' => $updatedProduct->id,
                            'name' => $updatedProduct->name,
                            'price' => $updatedProduct->price,
                            'image' => $updatedProduct->image,
                            'description' => $updatedProduct->description,
                            'quantity' => $updatedProduct->quantity,
                            'hasChanges' => true,
                            'oldQuantity' => $product->quantity
                        ]);
                    }
                    
                    header('Location: /project1/admin/product/list');
                    exit;
                }
            }
        }
        
        include 'app/views/admin/product/edit.php';
    }
    
    public function productShow($id)
    {
        $product = $this->productModel->getProductById($id);
        
        if (!$product) {
            header('Location: /project1/admin/product/list');
            exit;
        }
        
        include 'app/views/admin/product/show.php';
    }

    public function productDelete($id)
    {
        $product = $this->productModel->getProductById($id);
        
        if ($product && $this->productModel->deleteProduct($id)) {
            // Delete product image if exists
            if (!empty($product->image) && file_exists($product->image)) {
                unlink($product->image);
            }
            
            // Gửi thông báo qua WebSocket
            $this->webSocketClient->notifyDeleteProduct($id);
        }
        
        header('Location: /project1/admin/product/list');
        exit;
    }

    // Category management
    public function categoryList()
    {
        // Get search parameters
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
        $productCountFilter = isset($_GET['product_count']) ? (int)$_GET['product_count'] : 0;
        $isAjax = isset($_GET['ajax']) && $_GET['ajax'] == 1;
        
        // Get categories with search/filter
        if (!empty($searchTerm) || $productCountFilter > 0) {
            $categories = $this->categoryModel->searchCategories($searchTerm, $productCountFilter);
        } else {
            $categories = $this->categoryModel->getCategories();
        }
        
        // If it's an AJAX request, only return the categories section
        if ($isAjax) {
            include 'app/views/admin/category/list_ajax.php';
        } else {
            include 'app/views/admin/category/list.php';
        }
    }

    public function categoryAdd()
    {
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($name)) {
                $errors['name'] = 'Category name is required';
            }
            
            if (empty($errors)) {
                if ($this->categoryModel->addCategory($name, $description)) {
                    header('Location: /project1/admin/category/list');
                    exit;
                } else {
                    $errors['general'] = 'Failed to add category';
                }
            }
        }
        
        include 'app/views/admin/category/add.php';
    }

    public function categoryEdit($id)
    {
        $category = $this->categoryModel->getCategoryById($id);
        $errors = [];
        
        if (!$category) {
            header('Location: /project1/admin/category/list');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($name)) {
                $errors['name'] = 'Category name is required';
            }
            
            if (empty($errors)) {
                if ($this->categoryModel->updateCategory($id, $name, $description)) {
                    header('Location: /project1/admin/category/list');
                    exit;
                } else {
                    $errors['general'] = 'Failed to update category';
                }
            }
        }
        
        include 'app/views/admin/category/edit.php';
    }
    
    public function categoryShow($id)
    {
        $category = $this->categoryModel->getCategoryById($id);
        
        if (!$category) {
            header('Location: /project1/admin/category/list');
            exit;
        }
        
        // Get products in this category
        $products = $this->productModel->getProductsByCategory($id);
        
        include 'app/views/admin/category/show.php';
    }

    public function categoryDelete($id)
    {
        // Check if the category has associated products
        if ($this->categoryModel->hasProducts($id)) {
            // Set error message
            $_SESSION['message'] = __('Không thể xóa danh mục này vì có sản phẩm liên kết. Vui lòng xóa hoặc chuyển các sản phẩm sang danh mục khác trước.');
            $_SESSION['message_type'] = 'danger';
        } else {
            // Delete the category
            if ($this->categoryModel->deleteCategory($id)) {
                $_SESSION['message'] = __('Danh mục đã được xóa thành công.');
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = __('Có lỗi xảy ra khi xóa danh mục.');
                $_SESSION['message_type'] = 'danger';
            }
        }
        
        header('Location: /project1/admin/category/list');
        exit;
    }

    // Voucher management
    public function voucherList()
    {
        // Cập nhật trạng thái voucher hết hạn
        $this->voucherModel->updateVoucherStatus();
        
        $vouchers = $this->voucherModel->getAllVouchers();
        include 'app/views/admin/voucher/list.php';
    }

    public function voucherAdd()
    {
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ensure numeric fields have proper values (convert empty strings to 0)
            $discount_amount = isset($_POST['discount_amount']) && $_POST['discount_amount'] !== '' ? (float)$_POST['discount_amount'] : 0;
            $discount_percent = isset($_POST['discount_percent']) && $_POST['discount_percent'] !== '' ? (int)$_POST['discount_percent'] : 0;
            $min_order_amount = isset($_POST['min_order_amount']) && $_POST['min_order_amount'] !== '' ? (float)$_POST['min_order_amount'] : 0;
            $max_uses = isset($_POST['max_uses']) && $_POST['max_uses'] !== '' ? (int)$_POST['max_uses'] : 0;
            
            $data = [
                'code' => $_POST['code'] ?? '',
                'description' => $_POST['description'] ?? '',
                'discount_type' => $_POST['discount_type'] ?? 'amount',
                'discount_amount' => $discount_amount,
                'discount_percent' => $discount_percent,
                'min_order_amount' => $min_order_amount,
                'start_date' => $_POST['start_date'] ?? date('Y-m-d'),
                'end_date' => $_POST['end_date'] ?? date('Y-m-d', strtotime('+30 days')),
                'max_uses' => $max_uses,
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            if (empty($data['code'])) {
                $errors['code'] = 'Voucher code is required';
            }
            
            if ($data['discount_type'] === 'amount' && empty($data['discount_amount'])) {
                $errors['discount_amount'] = 'Discount amount is required';
            }
            
            if ($data['discount_type'] === 'percent' && (empty($data['discount_percent']) || $data['discount_percent'] > 100)) {
                $errors['discount_percent'] = 'Valid discount percentage is required (1-100)';
            }
            
            if (empty($errors)) {
                if ($this->voucherModel->addVoucher($data)) {
                    header('Location: /project1/admin/voucher/list');
                    exit;
                } else {
                    $errors['general'] = 'Failed to add voucher';
                }
            }
        }
        
        include 'app/views/admin/voucher/add.php';
    }

    public function voucherEdit($id)
    {
        $voucher = $this->voucherModel->getVoucherById($id);
        $errors = [];
        
        if (!$voucher) {
            header('Location: /project1/admin/voucher/list');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ensure numeric fields have proper values (convert empty strings to 0)
            $discount_amount = isset($_POST['discount_amount']) && $_POST['discount_amount'] !== '' ? (float)$_POST['discount_amount'] : 0;
            $discount_percent = isset($_POST['discount_percent']) && $_POST['discount_percent'] !== '' ? (int)$_POST['discount_percent'] : 0;
            $min_order_amount = isset($_POST['min_order_amount']) && $_POST['min_order_amount'] !== '' ? (float)$_POST['min_order_amount'] : 0;
            $max_uses = isset($_POST['max_uses']) && $_POST['max_uses'] !== '' ? (int)$_POST['max_uses'] : 0;
            
            $data = [
                'code' => $_POST['code'] ?? '',
                'description' => $_POST['description'] ?? '',
                'discount_amount' => $discount_amount,
                'discount_percent' => $discount_percent,
                'min_order_amount' => $min_order_amount,
                'start_date' => $_POST['start_date'] ?? date('Y-m-d'),
                'end_date' => $_POST['end_date'] ?? date('Y-m-d', strtotime('+30 days')),
                'max_uses' => $max_uses,
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            if (empty($data['code'])) {
                $errors['code'] = 'Voucher code is required';
            }
            
            if (empty($errors)) {
                if ($this->voucherModel->updateVoucher($id, $data)) {
                    // Gửi thông báo WebSocket về voucher đã cập nhật
                    $this->webSocketClient->notifyUpdateVoucher([
                        'id' => $id,
                        'code' => $data['code'],
                        'is_active' => $data['is_active'],
                        'end_date' => $data['end_date'],
                        'action' => 'update'
                    ]);
                    
                    header('Location: /project1/admin/voucher/list');
                    exit;
                } else {
                    $errors['general'] = 'Failed to update voucher';
                }
            }
        }
        
        include 'app/views/admin/voucher/edit.php';
    }
    
    public function voucherShow($id)
    {
        $voucher = $this->voucherModel->getVoucherById($id);
        
        if (!$voucher) {
            header('Location: /project1/admin/voucher/list');
            exit;
        }
        
        include 'app/views/admin/voucher/show.php';
    }

    public function voucherDelete($id)
    {
        // Get voucher information before deleting
        $voucher = $this->voucherModel->getVoucherById($id);
        
        if ($voucher && $this->voucherModel->deleteVoucher($id)) {
            // Send WebSocket notification about voucher deletion
            $this->webSocketClient->notifyDeleteVoucher($voucher->code);
        }
        
        header('Location: /project1/admin/voucher/list');
        exit;
    }
    
    // Order management
    public function orderList()
    {
        $orders = $this->orderModel->getAllOrders();
        include 'app/views/admin/order/list.php';
    }
    
    public function orderShow($id)
    {
        $order = $this->orderModel->getOrderById($id);
        
        if (!$order) {
            header('Location: /project1/admin/order/list');
            exit;
        }
        
        $orderDetails = $this->orderModel->getOrderDetailsByOrderId($id);
        include 'app/views/admin/order/show.php';
    }
    
    public function orderDelete($id)
    {
        $this->orderModel->deleteOrderById($id);
        $_SESSION['message'] = __('Order has been deleted successfully');
        $_SESSION['message_type'] = 'success';
        header('Location: /project1/admin/order/list');
        exit;
    }
    
    // User management
    public function userList()
    {
        $users = $this->accountModel->getAllUsers();
        include 'app/views/admin/user/list.php';
    }
    
    public function userAdd()
    {
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $role = $_POST['role'] ?? 'user';
            
            // Validate inputs
            if (empty($name)) {
                $errors['name'] = 'Name is required';
            }
            
            if (empty($email)) {
                $errors['email'] = 'Email is required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format';
            } elseif ($this->accountModel->emailExists($email)) {
                $errors['email'] = 'Email already exists';
            }
            
            if (empty($password)) {
                $errors['password'] = 'Password is required';
            } elseif (strlen($password) < 6) {
                $errors['password'] = 'Password must be at least 6 characters';
            }
            
            if ($password !== $confirm_password) {
                $errors['confirm_password'] = 'Passwords do not match';
            }
            
            if (empty($errors)) {
                $result = $this->accountModel->register($name, $email, $password, $role);
                
                if ($result) {
                    $_SESSION['message'] = __('User added successfully');
                    $_SESSION['message_type'] = 'success';
                    header('Location: /project1/admin/user/list');
                    exit;
                } else {
                    $errors['general'] = 'Failed to add user';
                }
            }
        }
        
        include 'app/views/admin/user/add.php';
    }
    
    public function userEdit($id)
    {
        $user = $this->accountModel->getUserById($id);
        $errors = [];
        
        if (!$user) {
            header('Location: /project1/admin/user/list');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 'user';
            $password = $_POST['password'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            // Validate inputs
            if (empty($name)) {
                $errors['name'] = 'Name is required';
            }
            
            if (empty($email)) {
                $errors['email'] = 'Email is required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format';
            } elseif ($email !== $user->email && $this->accountModel->emailExists($email)) {
                $errors['email'] = 'Email already exists';
            }
            
            if (!empty($password) && strlen($password) < 6) {
                $errors['password'] = 'Password must be at least 6 characters';
            }
            
            if (empty($errors)) {
                $result = $this->accountModel->updateUser($id, $name, $email, $password, $role, $is_active);
                
                if ($result) {
                    $_SESSION['message'] = __('User updated successfully');
                    $_SESSION['message_type'] = 'success';
                    header('Location: /project1/admin/user/list');
                    exit;
                } else {
                    $errors['general'] = 'Failed to update user';
                }
            }
        }
        
        include 'app/views/admin/user/edit.php';
    }
    
    public function userShow($id)
    {
        $user = $this->accountModel->getUserById($id);
        
        if (!$user) {
            header('Location: /project1/admin/user/list');
            exit;
        }
        
        // Get user's orders
        $orders = $this->orderModel->getOrdersByUserId($id);
        
        include 'app/views/admin/user/show.php';
    }
    
    public function userDelete($id)
    {
        // Don't allow deleting the current user
        if ($_SESSION['user_id'] == $id) {
            $_SESSION['message'] = __('You cannot delete your own account');
            $_SESSION['message_type'] = 'danger';
        } else {
            $this->accountModel->deleteUser($id);
            $_SESSION['message'] = __('User deleted successfully');
            $_SESSION['message_type'] = 'success';
        }
        
        header('Location: /project1/admin/user/list');
        exit;
    }
} 