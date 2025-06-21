<?php
// Kết nối đến cơ sở dữ liệu
require_once 'app/config/database.php';

// Tạo kết nối
$db = (new Database())->getConnection();

// Kiểm tra các cột trong bảng product
try {
    $stmt = $db->query("DESCRIBE product");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $hasAverageRating = in_array('average_rating', $columns);
    $hasReviewCount = in_array('review_count', $columns);
    
    echo "<h2>Kiểm tra cấu trúc bảng</h2>";
    echo "Cột average_rating: " . ($hasAverageRating ? "Đã tồn tại" : "Chưa tồn tại") . "<br>";
    echo "Cột review_count: " . ($hasReviewCount ? "Đã tồn tại" : "Chưa tồn tại") . "<br>";
    
    // Thêm cột nếu chưa tồn tại
    if (!$hasAverageRating) {
        $db->exec("ALTER TABLE product ADD COLUMN average_rating DECIMAL(3,2) DEFAULT 0");
        echo "Đã thêm cột average_rating vào bảng product<br>";
    }
    
    if (!$hasReviewCount) {
        $db->exec("ALTER TABLE product ADD COLUMN review_count INT DEFAULT 0");
        echo "Đã thêm cột review_count vào bảng product<br>";
    }
    
    // Kiểm tra bảng product_reviews
    try {
        $stmt = $db->query("DESCRIBE product_reviews");
        echo "Bảng product_reviews đã tồn tại<br>";
    } catch (PDOException $e) {
        echo "Bảng product_reviews chưa tồn tại. Đang tạo...<br>";
        
        // Tạo bảng product_reviews
        $sql = "CREATE TABLE IF NOT EXISTS product_reviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            user_id INT NOT NULL,
            order_id INT NOT NULL,
            rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
            comment TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES account(id) ON DELETE CASCADE,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            UNIQUE KEY (product_id, user_id, order_id)
        )";
        
        $db->exec($sql);
        echo "Đã tạo bảng product_reviews<br>";
    }
    
    // Cập nhật lại các giá trị đánh giá
    echo "<h2>Cập nhật đánh giá sản phẩm</h2>";
    
    $products = $db->query("SELECT id FROM product")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($products as $productId) {
        // Tính toán đánh giá trung bình và số lượng đánh giá
        $query = "SELECT AVG(rating) as avg_rating, COUNT(*) as count 
                  FROM product_reviews 
                  WHERE product_id = :product_id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $avgRating = $result['avg_rating'] ?: 0;
        $reviewCount = $result['count'] ?: 0;
        
        // Cập nhật lại sản phẩm
        $query = "UPDATE product 
                  SET average_rating = :avg_rating, review_count = :review_count 
                  WHERE id = :product_id";
                  
        $stmt = $db->prepare($query);
        $stmt->bindParam(':avg_rating', $avgRating, PDO::PARAM_STR);
        $stmt->bindParam(':review_count', $reviewCount, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        echo "Sản phẩm ID {$productId}: Đánh giá trung bình = {$avgRating}, Số lượng đánh giá = {$reviewCount}<br>";
    }
    
    echo "<h2>Hoàn tất</h2>";
    echo "Đã cập nhật xong tất cả đánh giá sản phẩm!<br>";
    
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
} 