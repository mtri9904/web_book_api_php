<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once __DIR__ . '/../helpers/SessionHelper.php';

class ShopController
{
    private $productModel;
    private $categoryModel;
    private $voucherModel; // Khai báo biến voucherModel
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
        $this->voucherModel = new VoucherModel($this->db); // Khởi tạo VoucherModel
        if (session_status() == PHP_SESSION_NONE) session_start();
    }

    // Hiển thị danh sách sản phẩm (có lọc theo danh mục nếu có)
    public function listproduct($category_id = null)
    {
        $categories = $this->categoryModel->getCategories();
        $keyword = $_GET['keyword'] ?? '';

        if ($category_id) {
            $products = $this->productModel->getProductsByCategory($category_id);
        } else {
            $products = $this->productModel->getProducts();
        }

        // Lọc theo từ khóa nếu có
        if ($keyword !== '') {
            $products = array_filter($products, function($product) use ($keyword) {
                return stripos($product->name, $keyword) !== false
                    || stripos($product->description, $keyword) !== false;
            });
        }
        
        // Kiểm tra tính hợp lệ của voucher nếu có
        if (!empty($_SESSION['voucher_code'])) {
            // Cập nhật trạng thái voucher hết hạn
            $this->voucherModel->updateVoucherStatus();
            
            $voucher = $this->voucherModel->getVoucherByCode($_SESSION['voucher_code']);
            if (!$voucher || !$voucher->is_active || strtotime($voucher->end_date) < time()) {
                // Nếu voucher không tồn tại, không còn active hoặc đã hết hạn
                $voucherCode = $_SESSION['voucher_code']; // Lưu lại mã voucher để thông báo
                
                // Xóa khỏi session
                $_SESSION['voucher_code'] = '';
                $_SESSION['voucher_discount'] = 0;
                
                // Thiết lập thông báo lỗi để hiển thị khi load trang
                $_SESSION['voucher_invalid'] = __("Mã giảm giá") . " '$voucherCode' " . __("đã hết hạn hoặc không còn hiệu lực.");
            }
        }

        include 'app/views/home/listproduct.php';
    }

    // Hiển thị chi tiết sản phẩm
    public function showproduct($id)
    {
        $product = $this->productModel->getProductById($id);
        $relatedProducts = [];
        if ($product) {
            // Lấy các sản phẩm cùng danh mục, loại trừ chính nó, lấy tối đa 3 sản phẩm ngẫu nhiên
            $allRelated = $this->productModel->getProductsByCategory($product->category_id);
            // Loại bỏ sản phẩm hiện tại
            $relatedProducts = array_filter($allRelated, function($p) use ($id) {
                return $p->id != $id;
            });
            // Lấy ngẫu nhiên tối đa 3 sản phẩm
            if (count($relatedProducts) > 3) {
                $relatedProducts = array_slice($this->shuffle_assoc($relatedProducts), 0, 3);
            }
            include 'app/views/home/showproduct.php';
        } else {
            echo __('Không tìm thấy sản phẩm.');
        }
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addtoCart($id)
    {
        $response = ['success' => false, 'message' => ''];
        // Kiểm tra đăng nhập
        if (!SessionHelper::isLoggedIn()) {
            $response['message'] = __('Bạn cần đăng nhập để thêm vào giỏ hàng!');
            $response['login_required'] = true;
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            $response['message'] = __('Không tìm thấy sản phẩm.');
            echo json_encode($response);
            exit;
        }

        if ($product->quantity <= 0) {
            $response['message'] = __('Sản phẩm đã hết hàng!');
            echo json_encode($response);
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $max_quantity = (int)$product->quantity;

        if (isset($_SESSION['cart'][$id])) {
            if ($_SESSION['cart'][$id]['quantity'] < $max_quantity) {
                $_SESSION['cart'][$id]['quantity']++;
                $response['success'] = true;
                $response['message'] = __('Đã thêm') . ' "' . htmlspecialchars($product->name) . '" ' . __('vào giỏ hàng!');
            } else {
                $response['message'] = __('Số lượng vượt quá tồn kho!');
            }
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image,
                'max_quantity' => $max_quantity
            ];
            $response['success'] = true;
            $response['message'] = __('Đã thêm') . ' "' . htmlspecialchars($product->name) . '" ' . __('vào giỏ hàng!');
        }

        // Update max_quantity
        $_SESSION['cart'][$id]['max_quantity'] = $max_quantity;

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function updateCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xóa sản phẩm nếu có nút Xóa
            if (isset($_POST['remove'])) {
                $id = (int)$_POST['remove'];
                if (isset($_SESSION['cart'][$id])) {
                    unset($_SESSION['cart'][$id]);
                }
                
                // Kiểm tra nếu giỏ hàng trống sau khi xóa sản phẩm, hủy mã giảm giá
                if (empty($_SESSION['cart'])) {
                    $_SESSION['voucher_code'] = '';
                    $_SESSION['voucher_discount'] = 0;
                    unset($_SESSION['voucher_error']);
                }
            }
            // Cập nhật số lượng sản phẩm
            if (isset($_POST['quantities'])) {
                foreach ($_POST['quantities'] as $id => $qty) {
                    $id = (int)$id;
                    $qty = (int)$qty;
                    if (isset($_SESSION['cart'][$id])) {
                        // Lấy lại số lượng tồn kho mới nhất
                        $product = $this->productModel->getProductById($id);
                        $max_quantity = (int)$product->quantity;
                        if ($qty > $max_quantity) {
                            $_SESSION['cart'][$id]['quantity'] = $max_quantity;
                            $_SESSION['cart'][$id]['max_quantity'] = $max_quantity;
                            $_SESSION['cart'][$id]['error'] = __('Số lượng vượt quá tồn kho!');
                        } elseif ($qty < 1) {
                            $_SESSION['cart'][$id]['quantity'] = 1;
                            $_SESSION['cart'][$id]['max_quantity'] = $max_quantity;
                        } else {
                            $_SESSION['cart'][$id]['quantity'] = $qty;
                            $_SESSION['cart'][$id]['max_quantity'] = $max_quantity;
                            unset($_SESSION['cart'][$id]['error']);
                        }
                    }
                }
            }
        }
        header('Location: /project1/shop/cart');
        exit;
    }

    public function applyVoucher()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['voucher_code'] ?? '');
            
            // Nếu mã voucher trống, hủy voucher hiện tại
            if (empty($code)) {
                $this->removeVoucher();
                return;
            }
            
            // Cập nhật trạng thái voucher hết hạn
            $this->voucherModel->updateVoucherStatus();
            
            $voucher = $this->voucherModel->getVoucherByCode($code);

            if (!$voucher) {
                $_SESSION['voucher_error'] = __('Voucher không tồn tại.');
                header('Location: /project1/shop/cart');
                exit;
            }

            if (!$voucher->is_active) {
                $_SESSION['voucher_error'] = __('Voucher không còn hiệu lực.');
                header('Location: /project1/shop/cart');
                exit;
            }
            
            if (strtotime($voucher->end_date) < time()) {
                $_SESSION['voucher_error'] = __('Voucher đã hết hạn.');
                header('Location: /project1/shop/cart');
                exit;
            }
            
            if (strtotime($voucher->start_date) > time()) {
                $_SESSION['voucher_error'] = __('Voucher chưa đến thời gian sử dụng.');
                header('Location: /project1/shop/cart');
                exit;
            }

            // Kiểm tra lượt sử dụng tối đa
            if ($voucher->max_uses > 0) {
                $currentUses = isset($voucher->current_uses) ? $voucher->current_uses : 0;
                if ($currentUses >= $voucher->max_uses) {
                    $_SESSION['voucher_error'] = __('Voucher đã hết lượt sử dụng.');
                    header('Location: /project1/shop/cart');
                    exit;
                }
            }

            $subtotal = $_SESSION['cart_subtotal'] ?? 0;
            
            // Kiểm tra giá trị đơn hàng tối thiểu
            if ($voucher->min_order_amount > 0 && $subtotal < $voucher->min_order_amount) {
                $_SESSION['voucher_error'] = __('Giá trị đơn hàng không đủ để áp dụng voucher này. Tối thiểu:') . ' ' . number_format($voucher->min_order_amount, 0, ',', '.') . ' ' . __('VND');
                header('Location: /project1/shop/cart');
                exit;
            }
            
            $discount = 0;

            if ($voucher->discount_type === 'fixed') {
                $discount = $voucher->discount_amount;
            } elseif ($voucher->discount_type === 'percent') {
                $discount = $subtotal * ($voucher->discount_percent / 100);
            }

            if ($discount > $subtotal) {
                $discount = $subtotal;
            }

            $_SESSION['voucher_code'] = $voucher->code;
            $_SESSION['voucher_discount'] = $discount;

            header('Location: /project1/shop/cart');
            exit;
        }
    }

    public function removeVoucher()
    {
        // Đảm bảo giá trị được đặt thành chuỗi rỗng, không phải null
        $_SESSION['voucher_code'] = '';
        $_SESSION['voucher_discount'] = 0;
        unset($_SESSION['voucher_error']);
        header('Location: /project1/shop/cart');
        exit;
    }

    // Hiển thị giỏ hàng
    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $_SESSION['cart_subtotal'] = $subtotal; // luôn cập nhật lại subtotal
        
        // Kiểm tra tính hợp lệ của voucher nếu có
        if (!empty($_SESSION['voucher_code'])) {
            // Cập nhật trạng thái voucher hết hạn
            $this->voucherModel->updateVoucherStatus();
            
            $voucher = $this->voucherModel->getVoucherByCode($_SESSION['voucher_code']);
            if (!$voucher || !$voucher->is_active || strtotime($voucher->end_date) < time()) {
                // Nếu voucher không tồn tại, không còn active hoặc đã hết hạn
                $voucherCode = $_SESSION['voucher_code']; // Lưu lại mã voucher để thông báo
                
                // Xóa khỏi session
                $_SESSION['voucher_code'] = '';
                $_SESSION['voucher_discount'] = 0;
                
                // Thiết lập thông báo lỗi
                $_SESSION['voucher_error'] = __("Mã giảm giá") . " '$voucherCode' " . __("đã hết hạn hoặc không còn hiệu lực.");
            } else if (strtotime($voucher->start_date) > time()) {
                // Nếu voucher chưa đến thời gian sử dụng
                $voucherCode = $_SESSION['voucher_code']; // Lưu lại mã voucher để thông báo
                
                // Xóa khỏi session
                $_SESSION['voucher_code'] = '';
                $_SESSION['voucher_discount'] = 0;
                
                // Thiết lập thông báo lỗi
                $_SESSION['voucher_error'] = __("Mã giảm giá") . " '$voucherCode' " . __("chưa đến thời gian sử dụng.");
            } else if ($voucher->max_uses > 0 && $voucher->current_uses >= $voucher->max_uses) {
                // Nếu voucher đã hết lượt sử dụng
                $voucherCode = $_SESSION['voucher_code']; // Lưu lại mã voucher để thông báo
                
                // Xóa khỏi session
                $_SESSION['voucher_code'] = '';
                $_SESSION['voucher_discount'] = 0;
                
                // Thiết lập thông báo lỗi
                $_SESSION['voucher_error'] = __("Mã giảm giá") . " '$voucherCode' " . __("đã hết lượt sử dụng.");
            } else if ($voucher->min_order_amount > 0 && $subtotal < $voucher->min_order_amount) {
                // Nếu giá trị đơn hàng không đủ để áp dụng voucher
                $voucherCode = $_SESSION['voucher_code']; // Lưu lại mã voucher để thông báo
                
                // Xóa khỏi session
                $_SESSION['voucher_code'] = '';
                $_SESSION['voucher_discount'] = 0;
                
                // Thiết lập thông báo lỗi
                $_SESSION['voucher_error'] = __("Giá trị đơn hàng không đủ để áp dụng mã giảm giá") . " '$voucherCode'. " . __("Tối thiểu:") . " " . number_format($voucher->min_order_amount, 0, ',', '.') . " " . __("VND");
            }
        }
        
        include 'app/views/home/Cart.php';
    }

    // Hiển thị trang thanh toán
    public function checkout()
    {
        include 'app/views/home/Checkout.php';
    }

    // Xử lý thanh toán
    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $cart = $_SESSION['cart'] ?? [];

            // Lấy user_id từ session
            $userId = $_SESSION['user_id'] ?? null;
            if (!$userId) {
                echo __('Bạn cần đăng nhập để thanh toán.');
                return;
            }

            // Kiểm tra giỏ hàng
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo __('Giỏ hàng trống.');
                return;
            }

            // Tính tổng tiền của giỏ hàng
            $cart = $_SESSION['cart'];
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['quantity'] * $item['price'];
            }
            // Trừ giảm giá nếu có
            $voucher_discount = $_SESSION['voucher_discount'] ?? 0;
            $total_after_discount = $total - $voucher_discount;
            if ($total_after_discount < 0) $total_after_discount = 0;
            
            // Bắt đầu giao dịch
            $this->db->beginTransaction();
            try {
                // Lấy mã voucher nếu có và kiểm tra tính hợp lệ
                $voucher_code = !empty($_SESSION['voucher_code']) ? $_SESSION['voucher_code'] : null;
                $voucher_discount = !empty($_SESSION['voucher_discount']) ? $_SESSION['voucher_discount'] : 0;
                
                // Kiểm tra xem voucher có tồn tại không
                if (!empty($voucher_code)) {
                    $voucher = $this->voucherModel->getVoucherByCode($voucher_code);
                    if (!$voucher) {
                        // Nếu không tìm thấy voucher, đặt lại giá trị thành null
                        $voucher_code = null;
                        $voucher_discount = 0;
                        
                        // Xóa voucher khỏi session
                        $_SESSION['voucher_code'] = '';
                        $_SESSION['voucher_discount'] = 0;
                        unset($_SESSION['voucher_error']);
                    }
                }
                
                // Nếu không có mã voucher, sử dụng câu truy vấn không có voucher_code
                if (empty($voucher_code)) {
                    $query = "INSERT INTO orders (user_id, name, phone, address, total, voucher_discount) 
                              VALUES (:user_id, :name, :phone, :address, :total, :voucher_discount)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':phone', $phone);
                    $stmt->bindParam(':address', $address);
                    $stmt->bindParam(':total', $total_after_discount);
                    $stmt->bindParam(':voucher_discount', $voucher_discount);
                } else {
                    // Nếu có mã voucher hợp lệ, sử dụng câu truy vấn có voucher_code
                    $query = "INSERT INTO orders (user_id, name, phone, address, total, voucher_code, voucher_discount) 
                              VALUES (:user_id, :name, :phone, :address, :total, :voucher_code, :voucher_discount)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':phone', $phone);
                    $stmt->bindParam(':address', $address);
                    $stmt->bindParam(':total', $total_after_discount);
                    $stmt->bindParam(':voucher_code', $voucher_code);
                    $stmt->bindParam(':voucher_discount', $voucher_discount);
                }
                
                $stmt->execute();
                $order_id = $this->db->lastInsertId();

                // Lưu chi tiết đơn hàng vào bảng order_details
                foreach ($cart as $product_id => $item) {
                    // Lấy thông tin chi tiết của sản phẩm từ CSDL
                    $product = $this->productModel->getProductById($product_id);
                    
                    // Lấy thông tin danh mục nếu có
                    $category_name = null;
                    if ($product && $product->category_id) {
                        $category = $this->categoryModel->getCategoryById($product->category_id);
                        $category_name = $category ? $category->name : null;
                    }
                    
                    $query = "INSERT INTO order_details (
                        order_id, product_id, product_name, product_description, 
                        product_image, product_category_id, product_category_name,
                        quantity, price
                    ) VALUES (
                        :order_id, :product_id, :product_name, :product_description,
                        :product_image, :product_category_id, :product_category_name,
                        :quantity, :price
                    )";
                    
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':product_name', $item['name']);
                    
                    // Lưu thông tin chi tiết nếu sản phẩm còn tồn tại, nếu không lưu từ giỏ hàng
                    $description = $product ? $product->description : null;
                    $image = $product ? $product->image : $item['image'];
                    $category_id = $product ? $product->category_id : null;
                    
                    $stmt->bindParam(':product_description', $description);
                    $stmt->bindParam(':product_image', $image);
                    $stmt->bindParam(':product_category_id', $category_id);
                    $stmt->bindParam(':product_category_name', $category_name);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    
                    $stmt->execute();
                    
                    // Trừ số lượng tồn kho sản phẩm
                    $this->productModel->decreaseQuantity($product_id, $item['quantity']);
                }
                
                // Sau khi lưu đơn hàng thành công:
                $_SESSION['last_order'] = [
                    'name' => $name,
                    'phone' => $phone,
                    'address' => $address,
                    'cart' => $cart,
                    'subtotal' => $_SESSION['cart_subtotal'] ?? 0,
                    'voucher_code' => $voucher_code ?? '',
                    'voucher_discount' => $voucher_discount ?? 0,
                ];
                
                // Xóa giỏ hàng sau khi đặt hàng thành công
                unset($_SESSION['cart']);
                
                // Tăng số lần đã dùng lên 1 nếu có sử dụng voucher
                if (!empty($voucher_code)) {
                    // Sử dụng phương thức incrementVoucherUsage từ VoucherModel
                    $this->voucherModel->incrementVoucherUsage($voucher_code);
                }
                
                // Xóa mã giảm giá sau khi đặt hàng thành công
                unset($_SESSION['voucher_code']);
                unset($_SESSION['voucher_discount']);
                $this->db->commit();

                // Chuyển hướng đến trang xác nhận đơn hàng
                header('Location: /project1/shop/orderConfirmation');
            } catch (Exception $e) {
                $this->db->rollBack();
                echo __('Đã xảy ra lỗi khi xử lý đơn hàng:') . ' ' . $e->getMessage();
            }
        }
    }

    // Trang xác nhận đơn hàng
    public function orderConfirmation()
    {
        include 'app/views/home/orderConfirmation.php';
    }

    public function removeFromCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
            $id = (int)$_POST['remove'];
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
            }
        }
        header('Location: /project1/shop/cart');
        exit;
    }

    // Hàm hỗ trợ trộn mảng đối tượng (nếu cần)
    public function shuffle_assoc($list) {
        if (!is_array($list)) return $list;
        $keys = array_keys($list);
        shuffle($keys);
        $random = array();
        foreach ($keys as $key) {
            $random[$key] = $list[$key];
        }
        return $random;
    }
    
    // API lấy danh sách sản phẩm dưới dạng JSON
    public function getProductsJson($category_id = null)
    {
        header('Content-Type: application/json');
        
        try {
            if ($category_id) {
                $products = $this->productModel->getProductsByCategory($category_id);
            } else {
                $products = $this->productModel->getProducts();
            }
            
            echo json_encode([
                'success' => true,
                'products' => $products
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        exit;
    }
    
    // Đồng bộ giỏ hàng với thông tin sản phẩm mới nhất
    public function syncCartWithLatestProducts()
    {
        // Đảm bảo có giỏ hàng
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            // Nếu giỏ hàng trống, hủy mã giảm giá
            if (isset($_SESSION['voucher_code']) && !empty($_SESSION['voucher_code'])) {
                $_SESSION['voucher_code'] = '';
                $_SESSION['voucher_discount'] = 0;
                unset($_SESSION['voucher_error']);
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'updated' => false, 
                'message' => __('Không có sản phẩm nào trong giỏ hàng'),
                'voucherRemoved' => true
            ]);
            exit;
        }
        
        $updatedProducts = [];
        $deletedProducts = [];
        $cartSummary = [];
        
        // Kiểm tra từng sản phẩm trong giỏ hàng
        foreach ($_SESSION['cart'] as $id => $item) {
            $product = $this->productModel->getProductById($id);
            
            // Nếu sản phẩm không tồn tại, đánh dấu để xóa
            if (!$product) {
                $deletedProducts[] = [
                    'id' => $id,
                    'name' => $item['name']
                ];
                unset($_SESSION['cart'][$id]);
                continue;
            }
            
            // Cập nhật thông tin sản phẩm trong giỏ hàng
            $changes = [];
            $changeDetails = [];
            
            // Kiểm tra tên sản phẩm
            if ($product->name !== $item['name']) {
                $changes[] = 'name';
                $changeDetails['name'] = [
                    'old' => $item['name'],
                    'new' => $product->name
                ];
                $_SESSION['cart'][$id]['name'] = $product->name;
            }
            
            // Kiểm tra giá sản phẩm
            if ((float)$product->price !== (float)$item['price']) {
                $changes[] = 'price';
                $changeDetails['price'] = [
                    'old' => (float)$item['price'],
                    'new' => (float)$product->price
                ];
                $_SESSION['cart'][$id]['price'] = $product->price;
            }
            
            // Kiểm tra số lượng tối đa
            $max_quantity = (int)$product->quantity;
            if ($max_quantity !== (int)$item['max_quantity']) {
                $changes[] = 'quantity';
                $changeDetails['quantity'] = [
                    'old' => (int)$item['max_quantity'],
                    'new' => $max_quantity
                ];
                $_SESSION['cart'][$id]['max_quantity'] = $max_quantity;
                
                // Điều chỉnh số lượng nếu vượt quá tồn kho
                if ($item['quantity'] > $max_quantity) {
                    $_SESSION['cart'][$id]['quantity'] = $max_quantity;
                    $_SESSION['cart'][$id]['error'] = __('Số lượng đã được điều chỉnh do thay đổi tồn kho');
                }
            }
            
            // Kiểm tra hình ảnh sản phẩm
            if ($product->image !== $item['image']) {
                $changes[] = 'image';
                $_SESSION['cart'][$id]['image'] = $product->image;
            }
            
            // Nếu có thay đổi, thêm vào danh sách cập nhật
            if (!empty($changes)) {
                $updatedProducts[] = [
                    'id' => $id,
                    'name' => $product->name,
                    'price' => (float)$product->price,
                    'max_quantity' => $max_quantity,
                    'image' => $product->image,
                    'changes' => $changes,
                    'changeDetails' => $changeDetails,
                    'hasRealChanges' => true
                ];
            }
        }
        
        // Kiểm tra nếu giỏ hàng trống sau khi xóa sản phẩm, hủy mã giảm giá
        $voucherRemoved = false;
        if (empty($_SESSION['cart']) && isset($_SESSION['voucher_code']) && !empty($_SESSION['voucher_code'])) {
            $_SESSION['voucher_code'] = '';
            $_SESSION['voucher_discount'] = 0;
            unset($_SESSION['voucher_error']);
            $voucherRemoved = true;
        }
        
        // Tính lại tổng giỏ hàng
        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Áp dụng voucher nếu có
        $discount = isset($_SESSION['voucher_discount']) ? $_SESSION['voucher_discount'] : 0;
        $total = $subtotal - $discount;
        if ($total < 0) $total = 0;
        
        $cartSummary = [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total
        ];
        
        // Trả về kết quả
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'updated' => (!empty($updatedProducts) || !empty($deletedProducts) || $voucherRemoved),
            'updatedProducts' => $updatedProducts,
            'deletedProducts' => $deletedProducts,
            'cartSummary' => $cartSummary,
            'voucherRemoved' => $voucherRemoved
        ]);
        exit;
    }

    // API lấy số lượng sản phẩm trong giỏ hàng
    public function getCartCount()
    {
        header('Content-Type: application/json');
        
        $count = 0;
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $count += (int)$item['quantity'];
            }
        }
        
        echo json_encode([
            'success' => true,
            'count' => $count
        ]);
        exit;
    }
}