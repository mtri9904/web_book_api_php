<?php
class AdminReviewController
{
    private $db;
    private $reviewModel;
    private $productModel;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->reviewModel = new ReviewModel($db);
        $this->productModel = new ProductModel($db);
        
        // Kiểm tra quyền admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /project1/account/login');
            exit;
        }
    }
    
    /**
     * Hiển thị danh sách đánh giá sản phẩm trong admin
     */
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        // Lấy danh sách đánh giá
        $reviews = $this->getReviews($limit, $offset);
        
        // Đếm tổng số đánh giá
        $totalReviews = $this->countAllReviews();
        $totalPages = ceil($totalReviews / $limit);
        
        $activePage = 'review';
        $pageTitle = __('Quản lý đánh giá');
        include 'app/views/admin/review/list.php';
    }
    
    /**
     * Lấy danh sách tất cả đánh giá
     */
    private function getReviews($limit = 20, $offset = 0)
    {
        $query = "SELECT pr.*, p.name as product_name, a.username, a.fullname, o.id as order_id
                  FROM product_reviews pr
                  JOIN product p ON pr.product_id = p.id
                  JOIN account a ON pr.user_id = a.id
                  JOIN orders o ON pr.order_id = o.id
                  ORDER BY pr.created_at DESC
                  LIMIT :limit OFFSET :offset";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Đếm tổng số đánh giá
     */
    private function countAllReviews()
    {
        $query = "SELECT COUNT(*) as count FROM product_reviews";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    /**
     * Xóa đánh giá
     */
    public function delete($id)
    {
        if (!$id) {
            $_SESSION['message'] = __('ID đánh giá không hợp lệ');
            $_SESSION['message_type'] = 'danger';
            header('Location: /project1/admin/review');
            exit;
        }
        
        // Lấy thông tin đánh giá trước khi xóa
        $query = "SELECT product_id FROM product_reviews WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $review = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$review) {
            $_SESSION['message'] = __('Đánh giá không tồn tại');
            $_SESSION['message_type'] = 'danger';
            header('Location: /project1/admin/review');
            exit;
        }
        
        $productId = $review['product_id'];
        
        // Xóa đánh giá
        $query = "DELETE FROM product_reviews WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // Cập nhật lại đánh giá trung bình của sản phẩm
            $this->reviewModel->updateProductAverageRating($productId);
            
            $_SESSION['message'] = __('Xóa đánh giá thành công');
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = __('Không thể xóa đánh giá');
            $_SESSION['message_type'] = 'danger';
        }
        
        header('Location: /project1/admin/review');
        exit;
    }
    
    /**
     * Xem chi tiết đánh giá
     */
    public function view($id)
    {
        if (!$id) {
            $_SESSION['message'] = __('ID đánh giá không hợp lệ');
            $_SESSION['message_type'] = 'danger';
            header('Location: /project1/admin/review');
            exit;
        }
        
        $query = "SELECT pr.*, p.name as product_name, a.username, a.fullname, o.id as order_id
                  FROM product_reviews pr
                  JOIN product p ON pr.product_id = p.id
                  JOIN account a ON pr.user_id = a.id
                  JOIN orders o ON pr.order_id = o.id
                  WHERE pr.id = :id";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $review = $stmt->fetch(PDO::FETCH_OBJ);
        
        if (!$review) {
            $_SESSION['message'] = __('Đánh giá không tồn tại');
            $_SESSION['message_type'] = 'danger';
            header('Location: /project1/admin/review');
            exit;
        }
        
        $activePage = 'review';
        $pageTitle = __('Chi tiết đánh giá');
        include 'app/views/admin/review/view.php';
    }
    
    /**
     * Xem danh sách đánh giá theo sản phẩm
     */
    public function byProduct($productId)
    {
        if (!$productId) {
            $_SESSION['message'] = __('ID sản phẩm không hợp lệ');
            $_SESSION['message_type'] = 'danger';
            header('Location: /project1/admin/product');
            exit;
        }
        
        // Kiểm tra sản phẩm có tồn tại
        $product = $this->productModel->getProductById($productId);
        
        if (!$product) {
            $_SESSION['message'] = __('Sản phẩm không tồn tại');
            $_SESSION['message_type'] = 'danger';
            header('Location: /project1/admin/product');
            exit;
        }
        
        // Lấy danh sách đánh giá của sản phẩm
        $reviews = $this->reviewModel->getReviewsByProductId($productId);
        
        $activePage = 'review';
        $pageTitle = __('Đánh giá sản phẩm') . ': ' . $product->name;
        include 'app/views/admin/review/by_product.php';
    }
} 