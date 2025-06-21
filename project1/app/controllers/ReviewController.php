<?php
require_once 'app/models/ReviewModel.php';
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

class ReviewController {
    private $db;
    private $reviewModel;
    private $productModel;

    public function __construct($db) {
        $this->db = $db;
        $this->reviewModel = new ReviewModel($db);
        $this->productModel = new ProductModel($db);
    }

    /**
     * Hiển thị trang các sản phẩm có thể đánh giá
     */
    public function index() {
        // Kiểm tra đăng nhập
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /project1/account/login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $reviewableProducts = $this->reviewModel->getReviewableProducts($userId);
        
        include 'app/views/review/index.php';
    }

    /**
     * Hiển thị form đánh giá sản phẩm
     */
    public function add() {
        // Kiểm tra đăng nhập
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /project1/account/login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
        $orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
        
        if (!$productId || !$orderId) {
            $_SESSION['error'] = 'Thiếu thông tin cần thiết để đánh giá sản phẩm';
            header('Location: /project1/order/list');
            exit;
        }
        
        // Kiểm tra nếu người dùng đã đánh giá sản phẩm này ở bất kỳ đơn hàng nào
        if ($this->reviewModel->hasUserReviewedProduct($userId, $productId)) {
            $_SESSION['error'] = 'Bạn đã đánh giá sản phẩm này rồi. Mỗi người dùng chỉ được đánh giá một sản phẩm một lần.';
            header('Location: /project1/order/show/' . $orderId);
            exit;
        }
        
        // Kiểm tra nếu người dùng đã đánh giá sản phẩm này trong đơn hàng này
        if ($this->reviewModel->checkUserReviewedProduct($userId, $productId, $orderId)) {
            $_SESSION['error'] = 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi.';
            header('Location: /project1/order/show/' . $orderId);
            exit;
        }

        // Lấy thông tin sản phẩm
        $product = $this->productModel->getProductById($productId);
        if (!$product) {
            $_SESSION['error'] = 'Không tìm thấy sản phẩm';
            header('Location: /project1/order/list');
            exit;
        }

        include 'app/views/review/add.php';
    }

    /**
     * Xử lý đánh giá sản phẩm
     */
    public function save() {
        // Kiểm tra đăng nhập
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /project1/account/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /project1/review');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
        $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
        $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

        // Kiểm tra dữ liệu đầu vào
        if (!$productId || !$orderId || $rating < 1 || $rating > 5) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ';
            header('Location: /project1/order/show/' . $orderId);
            exit;
        }
        
        // Kiểm tra nếu người dùng đã đánh giá sản phẩm này ở bất kỳ đơn hàng nào
        if ($this->reviewModel->hasUserReviewedProduct($userId, $productId)) {
            $_SESSION['error'] = 'Bạn đã đánh giá sản phẩm này rồi. Mỗi người dùng chỉ được đánh giá một sản phẩm một lần.';
            header('Location: /project1/order/show/' . $orderId);
            exit;
        }
        
        // Kiểm tra nếu người dùng đã đánh giá sản phẩm này trong đơn hàng này
        if ($this->reviewModel->checkUserReviewedProduct($userId, $productId, $orderId)) {
            $_SESSION['error'] = 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi.';
            header('Location: /project1/order/show/' . $orderId);
            exit;
        }

        // Thêm đánh giá
        $data = [
            'user_id' => $userId,
            'product_id' => $productId,
            'order_id' => $orderId,
            'rating' => $rating,
            'comment' => $comment
        ];
        
        $result = $this->reviewModel->addReview($data);

        if ($result) {
            $_SESSION['success'] = 'Đánh giá của bạn đã được ghi nhận. Cảm ơn bạn đã đánh giá!';
            header('Location: /project1/order/show/' . $orderId);
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.';
            header("Location: /project1/review/add?product_id={$productId}&order_id={$orderId}");
        }
        exit;
    }

    /**
     * Hiển thị danh sách đánh giá của một sản phẩm
     */
    public function product() {
        $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$productId) {
            header('Location: /project1/shop/listproduct');
            exit;
        }

        // Lấy thông tin sản phẩm
        $product = $this->productModel->getProductById($productId);
        if (!$product) {
            header('Location: /project1/shop/listproduct');
            exit;
        }

        // Phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Lấy danh sách đánh giá
        $reviews = $this->reviewModel->getProductReviews($productId, $limit, $offset);
        $totalReviews = $this->reviewModel->countProductReviews($productId);
        $totalPages = ceil($totalReviews / $limit);

        // Lấy thống kê đánh giá
        $ratingStats = $this->reviewModel->getProductRatingStats($productId);

        include 'app/views/review/product.php';
    }

    /**
     * API: Lấy đánh giá sản phẩm dạng JSON
     */
    public function get() {
        $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$productId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => __('ID sản phẩm không hợp lệ')]);
            exit;
        }

        $reviews = $this->reviewModel->getReviewsByProductId($productId);
        $ratingStats = $this->reviewModel->getRatingDistribution($productId);
        
        // Lấy thông tin sản phẩm
        $product = $this->productModel->getProductById($productId);
        $avgRating = $product->average_rating ?? 0;
        $reviewCount = $product->review_count ?? 0;
        
        $response = [
            'success' => true,
            'product_id' => $productId,
            'average_rating' => $avgRating,
            'review_count' => $reviewCount,
            'rating_stats' => $ratingStats,
            'reviews' => $reviews
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
} 