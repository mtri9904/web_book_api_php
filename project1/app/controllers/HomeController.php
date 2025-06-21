<?php
require_once('app/models/ProductModel.php');

class HomeController {
    private $db;
    private $productModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->productModel = new ProductModel($db);
    }
    
    public function index() {
        // Lấy các sản phẩm mới nhất
        $latestProducts = $this->productModel->getLatestProducts(8);
        
        // Hiển thị trang chủ
        require_once 'app/views/home/index.php';
    }
    
    public function getLatestProducts() {
        // API để lấy sản phẩm mới nhất dưới dạng JSON
        $latestProducts = $this->productModel->getLatestProducts(8);
        
        // Chuyển đổi sang mảng để có thể encode thành JSON
        $productArray = [];
        foreach ($latestProducts as $product) {
            $productArray[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'description' => $product->description,
                'category_id' => $product->category_id,
                'category_name' => $product->category_name ?? '',
                'quantity' => $product->quantity
            ];
        }
        
        // Trả về dữ liệu dưới dạng JSON
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'products' => $productArray
        ]);
        exit;
    }
} 