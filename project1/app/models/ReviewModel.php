<?php
class ReviewModel
{
    private $conn;
    private $table_name = "product_reviews";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Thêm đánh giá mới
     */
    public function addReview($data)
    {
        $userId = $data['user_id'];
        $productId = $data['product_id'];
        $orderId = $data['order_id'];
        $rating = $data['rating'];
        $comment = $data['comment'] ?? '';

        // Kiểm tra xem người dùng đã từng mua sản phẩm này chưa
        if (!$this->checkUserPurchasedProduct($userId, $productId, $orderId)) {
            return false;
        }

        // Kiểm tra xem người dùng đã đánh giá sản phẩm này trong đơn hàng này chưa
        if ($this->checkUserReviewedProduct($userId, $productId, $orderId)) {
            return false;
        }

        // Thêm đánh giá mới
        $query = "INSERT INTO " . $this->table_name . " 
                  (product_id, user_id, order_id, rating, comment) 
                  VALUES (:product_id, :user_id, :order_id, :rating, :comment)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

        try {
            $stmt->execute();
            
            // Cập nhật điểm đánh giá trung bình của sản phẩm
            $this->updateProductAverageRating($productId);
            
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Cập nhật điểm đánh giá trung bình của sản phẩm
     */
    public function updateProductAverageRating($productId)
    {
        $query = "SELECT AVG(rating) as avg_rating, COUNT(*) as count 
                  FROM " . $this->table_name . " 
                  WHERE product_id = :product_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $avgRating = $result['avg_rating'];
        $reviewCount = $result['count'];
        
        // Cập nhật lại sản phẩm
        $query = "UPDATE product 
                  SET average_rating = :avg_rating, review_count = :review_count 
                  WHERE id = :product_id";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':avg_rating', $avgRating, PDO::PARAM_STR);
        $stmt->bindParam(':review_count', $reviewCount, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Kiểm tra xem người dùng đã mua sản phẩm này trong đơn hàng cụ thể chưa
     */
    public function checkUserPurchasedProduct($userId, $productId, $orderId)
    {
        $query = "SELECT COUNT(*) as count FROM orders o
                  JOIN order_details od ON o.id = od.order_id
                  WHERE o.user_id = :user_id 
                  AND o.id = :order_id
                  AND od.product_id = :product_id";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Kiểm tra xem người dùng đã đánh giá sản phẩm này trong đơn hàng cụ thể chưa
     */
    public function checkUserReviewedProduct($userId, $productId, $orderId)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . "
                  WHERE user_id = :user_id 
                  AND product_id = :product_id
                  AND order_id = :order_id";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Lấy danh sách đánh giá của một sản phẩm
     */
    public function getProductReviews($productId, $limit = 10, $offset = 0)
    {
        $query = "SELECT pr.*, a.username, a.fullname 
                  FROM " . $this->table_name . " pr
                  JOIN account a ON pr.user_id = a.id
                  WHERE pr.product_id = :product_id
                  ORDER BY pr.created_at DESC
                  LIMIT :limit OFFSET :offset";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Đếm tổng số đánh giá của một sản phẩm
     */
    public function countProductReviews($productId)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . "
                  WHERE product_id = :product_id";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Lấy thống kê đánh giá của một sản phẩm (số lượng đánh giá mỗi mức sao)
     */
    public function getProductRatingStats($productId)
    {
        $stats = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0
        ];
        
        $query = "SELECT rating, COUNT(*) as count FROM " . $this->table_name . "
                  WHERE product_id = :product_id
                  GROUP BY rating
                  ORDER BY rating DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($results as $row) {
            $stats[$row['rating']] = $row['count'];
        }
        
        return $stats;
    }

    /**
     * Lấy danh sách sản phẩm mà người dùng có thể đánh giá
     * (Những sản phẩm đã mua nhưng chưa đánh giá)
     */
    public function getReviewableProducts($userId)
    {
        $query = "SELECT DISTINCT p.id, p.name, od.order_id, o.created_at as order_date
                  FROM product p
                  JOIN order_details od ON p.id = od.product_id
                  JOIN orders o ON od.order_id = o.id
                  LEFT JOIN product_reviews pr ON p.id = pr.product_id AND o.id = pr.order_id AND pr.user_id = :user_id
                  WHERE o.user_id = :user_id AND pr.id IS NULL
                  ORDER BY o.created_at DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy danh sách đánh giá của một sản phẩm (không giới hạn)
     */
    public function getReviewsByProductId($productId)
    {
        $query = "SELECT pr.*, a.fullname as username
                  FROM " . $this->table_name . " pr
                  JOIN account a ON pr.user_id = a.id
                  WHERE pr.product_id = :product_id
                  ORDER BY pr.created_at DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy phân phối đánh giá (1-5 sao) của sản phẩm
     */
    public function getRatingDistribution($productId)
    {
        $distribution = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0
        ];
        
        $query = "SELECT rating, COUNT(*) as count 
                  FROM " . $this->table_name . "
                  WHERE product_id = :product_id
                  GROUP BY rating";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($results as $row) {
            $distribution[$row['rating']] = $row['count'];
        }
        
        return $distribution;
    }

    /**
     * Kiểm tra xem người dùng đã đánh giá sản phẩm này chưa (bất kỳ đơn hàng nào)
     */
    public function hasUserReviewedProduct($userId, $productId)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . "
                  WHERE user_id = :user_id 
                  AND product_id = :product_id";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
} 