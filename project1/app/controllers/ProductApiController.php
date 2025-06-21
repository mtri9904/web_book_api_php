<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/utils/JWTHandler.php');

class ProductApiController
{
    private $productModel;
    private $db;
    private $jwtHandler;
    
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->jwtHandler = new JWTHandler();
    }
    
    private function authenticate()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            $arr = explode(" ", $authHeader);
            $jwt = $arr[1] ?? null;
            if ($jwt) {
                $decoded = $this->jwtHandler->decode($jwt);
                return $decoded ? true : false;
            }
        }
        return false;
    }
    
    // Lấy danh sách sản phẩm
    public function index()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $products = $this->productModel->getProducts();
            echo json_encode($products);
        } else {
            http_response_code(401);
            echo json_encode(['message' => __('Unauthorized')]);
        }
    }
    
    // Lấy thông tin sản phẩm theo ID
    public function show($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            
            // Debug ID
            error_log("Requested product ID in controller: " . $id);
            
            // Đảm bảo ID là số nguyên
            $id = intval($id);
            
            $product = $this->productModel->getProductById($id);
            if ($product) {
                // Trả về đúng một sản phẩm, không phải mảng
                echo json_encode($product);
            } else {
                http_response_code(404);
                echo json_encode(['message' => __('Product not found')]);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => __('Unauthorized')]);
        }
    }
    
    // Thêm sản phẩm mới
    public function store()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $data = json_decode(file_get_contents("php://input"), true);
            $name = $data['name'] ?? '';
            $description = $data['description'] ?? '';
            $price = $data['price'] ?? '';
            $category_id = $data['category_id'] ?? null;
            $quantity = $data['quantity'] ?? 0;
            $image = $data['image'] ?? '';
            
            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image, $quantity);
            
            if (is_array($result)) {
                http_response_code(400);
                echo json_encode(['errors' => $result]);
            } else {
                http_response_code(201);
                echo json_encode(['message' => __('Product created successfully')]);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => __('Unauthorized')]);
        }
    }
    
    // Cập nhật sản phẩm theo ID
    public function update($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $data = json_decode(file_get_contents("php://input"), true);
            $name = $data['name'] ?? '';
            $description = $data['description'] ?? '';
            $price = $data['price'] ?? '';
            $category_id = $data['category_id'] ?? null;
            $quantity = $data['quantity'] ?? 0;
            $image = $data['image'] ?? '';
            
            $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image, $quantity);
            
            if ($result) {
                echo json_encode(['message' => __('Product updated successfully')]);
            } else {
                http_response_code(400);
                echo json_encode(['message' => __('Product update failed')]);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => __('Unauthorized')]);
        }
    }
    
    // Xóa sản phẩm theo ID
    public function destroy($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $result = $this->productModel->deleteProduct($id);
            
            if ($result) {
                echo json_encode(['message' => __('Product deleted successfully')]);
            } else {
                http_response_code(400);
                echo json_encode(['message' => __('Product deletion failed')]);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => __('Unauthorized')]);
        }
    }
}
?> 