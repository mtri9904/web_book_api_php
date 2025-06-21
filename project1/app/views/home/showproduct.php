<?php include 'app/views/shares/header.php'; ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 animate__animated animate__fadeIn product-detail" data-product-id="<?php echo $product->id; ?>" style="background: rgba(255, 255, 255, 0.95); color: #1a2a44; border-radius: 15px;">
                <div class="row g-0">
                    <!-- Hình ảnh sách -->
                    <div class="col-md-5 p-3">
                        <div class="product-image-container">
                            <img src="<?php echo htmlspecialchars($product->image ? '/project1/' . $product->image : 'https://via.placeholder.com/300x450?text=Book+Cover', ENT_QUOTES, 'UTF-8'); ?>" 
                                 class="img-fluid" 
                                 alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    <!-- Thông tin sách -->
                    <div class="col-md-7">
                        <div class="card-body p-4">
                            <h2 class="card-title mb-3 fw-bold text-dark" style="font-size: 2rem;">
                                <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            </h2>
                            <!-- Badge danh mục -->
                            <span class="badge bg-primary mb-3">
                                <?php echo htmlspecialchars($product->category_name ?? __('Sách'), ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                            <!-- Mô tả -->
                            <p class="card-text mb-3" style="font-size: 0.95rem; line-height: 1.6;">
                                <strong><?php echo __('Mô tả:'); ?></strong><br>
                                <?php echo nl2br(htmlspecialchars($product->description ?? __('Không có mô tả'), ENT_QUOTES, 'UTF-8')); ?>
                            </p>
                            <!-- Giá -->
                            <p class="card-text fw-bold text-success fs-3 mb-4">
                                <?php echo number_format($product->price, 0, ',', '.'); ?> <?php echo __('VND'); ?>
                            </p>
                            <p class="card-text text-secondary fs-5 mb-3">
                                <?php echo __('Số lượng:'); ?> <?php echo (int)$product->quantity; ?>
                            </p>
                            
                            <?php if (isset($product->average_rating) && $product->average_rating > 0): ?>
                            <div class="d-flex align-items-center mb-4">
                                <div class="ratings">
                                    <?php
                                    $rating = round($product->average_rating * 2) / 2; // Làm tròn đến 0.5
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '<i class="bi bi-star-fill text-warning" style="font-size: 1.5rem;"></i>';
                                        } elseif ($i - 0.5 == $rating) {
                                            echo '<i class="bi bi-star-half text-warning" style="font-size: 1.5rem;"></i>';
                                        } else {
                                            echo '<i class="bi bi-star text-warning" style="font-size: 1.5rem;"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="ms-2 fs-5">
                                    <span class="fw-bold"><?php echo number_format($product->average_rating, 1); ?></span>
                                    <span class="text-muted">/5</span>
                                    <span class="ms-2 text-muted">(<?php echo $product->review_count; ?> <?php echo __('đánh giá'); ?>)</span>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="mb-4 text-muted">
                                <i class="bi bi-star text-muted me-1"></i><?php echo __('Chưa có đánh giá'); ?>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Nút hành động -->
                            <div class="d-flex gap-2">
                                <button onclick="addToCart(<?php echo $product->id; ?>)" 
                                        class="btn btn-success btn-sm d-flex align-items-center gap-1 add-to-cart-btn">
                                    <i class="bi bi-cart-plus"></i> <?php echo __('Thêm vào giỏ'); ?>
                                </button>
                                <a href="/project1/shop/listproduct" 
                                   class="btn btn-outline-primary btn-lg d-flex align-items-center gap-2">
                                    <i class="bi bi-arrow-left"></i> <?php echo __('Quay lại danh sách'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Phần đánh giá sản phẩm -->
    <?php 
    require_once 'app/models/ReviewModel.php';
    require_once 'app/config/database.php';
    $reviewModel = new ReviewModel((new Database())->getConnection());
    $reviews = $reviewModel->getReviewsByProductId($product->id);
    $ratingDistribution = $reviewModel->getRatingDistribution($product->id);
    $totalReviews = $product->review_count ?? 0;
    ?>
    
    <?php if ($totalReviews > 0): ?>
    <div class="mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="text-primary mb-0"><i class="bi bi-star-fill me-2"></i><?php echo __('Đánh giá từ khách hàng'); ?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Thống kê đánh giá -->
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="text-center mb-4">
                            <h4 class="display-4 fw-bold text-warning"><?php echo number_format($product->average_rating, 1); ?></h4>
                            <div class="ratings mb-2">
                                <?php
                                $avgRating = round($product->average_rating * 2) / 2;
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $avgRating) {
                                        echo '<i class="bi bi-star-fill text-warning"></i>';
                                    } elseif ($i - 0.5 == $avgRating) {
                                        echo '<i class="bi bi-star-half text-warning"></i>';
                                    } else {
                                        echo '<i class="bi bi-star text-warning"></i>';
                                    }
                                }
                                ?>
                            </div>
                            <p class="text-muted"><?php echo $totalReviews; ?> <?php echo __('đánh giá'); ?></p>
                        </div>
                        
                        <!-- Phân phối đánh giá -->
                        <div class="rating-bars">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                            <?php 
                            $count = $ratingDistribution[$i] ?? 0;
                            $percent = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                            ?>
                            <div class="rating-bar d-flex align-items-center mb-2">
                                <div class="col-2"><?php echo $i; ?> <i class="bi bi-star-fill text-warning small"></i></div>
                                <div class="col-8 px-2">
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $percent; ?>%" 
                                             aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="col-2 text-end small text-muted"><?php echo $count; ?></div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <!-- Danh sách đánh giá -->
                    <div class="col-md-8">
                        <div class="reviews-list">
                            <?php if (empty($reviews)): ?>
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-chat-square-text" style="font-size: 3rem;"></i>
                                    <p class="mt-3"><?php echo __('Chưa có đánh giá nào cho sản phẩm này.'); ?></p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($reviews as $review): ?>
                                <div class="review-item mb-4 pb-4 border-bottom">
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 48px; height: 48px; font-weight: bold;">
                                                <?php echo strtoupper(substr($review->username ?? 'U', 0, 1)); ?>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0"><?php echo htmlspecialchars($review->username ?? __('Người dùng ẩn danh')); ?></h6>
                                            <div class="d-flex align-items-center">
                                                <div class="ratings me-2">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="bi bi-star<?php echo ($i <= $review->rating) ? '-fill' : ''; ?> text-warning"></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <small class="text-muted"><?php echo date('d/m/Y', strtotime($review->created_at)); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!empty($review->comment)): ?>
                                    <p class="review-comment mb-0">
                                        <?php echo nl2br(htmlspecialchars($review->comment)); ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Sách đề xuất -->
    <div class="mt-5">
        <h3 class="text-warning mb-3"><?php echo __('Sách cùng danh mục'); ?></h3>
        <div class="row g-4">
            <?php foreach ($relatedProducts as $related): ?>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card h-100 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.95);">
                    <div class="related-image-container">
                        <img src="<?php echo htmlspecialchars($related->image ? '/project1/' . $related->image : 'https://via.placeholder.com/300x450?text=Book+Cover', ENT_QUOTES, 'UTF-8'); ?>" 
                            class="img-fluid"
                            alt="<?php echo htmlspecialchars($related->name, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="card-body">
                        <h6 class="card-title"><?php echo htmlspecialchars(substr($related->name, 0, 30) . (strlen($related->name) > 30 ? '...' : ''), ENT_QUOTES, 'UTF-8'); ?></h6>
                        <p class="card-text fw-bold text-success"><?php echo number_format($related->price, 0); ?> <?php echo __('VND'); ?></p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="/project1/shop/showproduct/<?php echo $related->id; ?>" class="btn btn-primary btn-sm"><?php echo __('Xem'); ?></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<style>
.product-image-container {
    background-color: #ffffff;
    height: 450px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    overflow: hidden;
}

.product-image-container img {
    max-height: 420px;
    max-width: 90%;
    object-fit: contain;
    transition: transform 0.4s ease-in-out;
}

.product-image-container:hover img {
    transform: scale(1.05);
}

.related-image-container {
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ffffff;
    padding: 15px;
    overflow: hidden;
}

.related-image-container img {
    max-height: 270px;
    max-width: 90%;
    object-fit: contain;
    transition: transform 0.4s ease-in-out;
}

.related-image-container:hover img {
    transform: scale(1.05);
}

.card:hover .btn-success {
    background: #218838;
}
.card:hover .btn-outline-primary {
    background: #00ffcc;
    color: #1a2a44;
}
.animate__fadeIn {
    animation-duration: 0.8s;
}
</style>
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="/project1/public/js/realtime.js"></script>
<script>
// Thêm sản phẩm vào giỏ hàng bằng AJAX
function addToCart(productId) {
    fetch('/project1/shop/addtoCart/' + productId, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.login_required) {
            window.location.href = '/project1/account/login';
            return;
        }
        Toastify({
            text: data.message,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: data.success ? "#28a745" : "#dc3545",
            stopOnFocus: true
        }).showToast();
    })
    .catch(error => {
        Toastify({
            text: "<?php echo __('Đã xảy ra lỗi khi thêm vào giỏ hàng!'); ?>",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#dc3545",
            stopOnFocus: true
        }).showToast();
    });
}

// Kết nối WebSocket khi trang đã tải xong
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra xem script realtime.js đã được tải chưa
    if (typeof realtime !== 'undefined') {
        // Kết nối với vai trò user
        realtime.connect('user');
        
        // Lấy ID sản phẩm hiện tại
        const productId = document.querySelector('.product-detail').dataset.productId;
        
        // Xử lý sự kiện khi nhận được thông báo cập nhật sản phẩm
        realtime.on('update_product', function(data) {
            // Nếu là sản phẩm hiện tại
            if (data.product.id == productId) {
                // Hiển thị thông báo
                Toastify({
                    text: `<?php echo __('Sản phẩm'); ?> "${data.product.name}" <?php echo __('vừa được cập nhật. Đang tải lại thông tin...'); ?>`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#0d6efd",
                    stopOnFocus: true
                }).showToast();
                
                // Tải lại trang sau 1 giây
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        });
        
        // Xử lý sự kiện khi nhận được thông báo xóa sản phẩm
        realtime.on('delete_product', function(data) {
            // Nếu là sản phẩm hiện tại
            if (data.product_id == productId) {
                // Hiển thị thông báo
                Toastify({
                    text: `<?php echo __('Sản phẩm này vừa bị xóa khỏi hệ thống. Đang chuyển hướng...'); ?>`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#dc3545",
                    stopOnFocus: true
                }).showToast();
                
                // Chuyển hướng về trang danh sách sản phẩm sau 2 giây
                setTimeout(() => {
                    window.location.href = '/project1/shop/listproduct';
                }, 2000);
            }
        });
        
        // Xử lý thông báo WebSocket cho voucher
        // Lắng nghe sự kiện cập nhật voucher
        realtime.on('update_voucher', function(data) {
            if (data && data.voucher) {
                <?php if (!empty($_SESSION['voucher_code'])): ?>
                // So sánh với mã voucher hiện tại trong session
                const currentVoucherCode = "<?php echo $_SESSION['voucher_code']; ?>";
                
                // Nếu voucher được cập nhật trùng với voucher đang sử dụng
                if (currentVoucherCode === data.voucher.code) {
                    // Kiểm tra xem voucher có bị vô hiệu hóa không
                    if (data.voucher.is_active === 0 || new Date(data.voucher.end_date) < new Date()) {
                        // Tự động gỡ bỏ voucher không hợp lệ
                        fetch('/project1/shop/removeVoucher', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                // Hiển thị thông báo
                                Toastify({
                                    text: "<?php echo __('Mã giảm giá đã hết hạn hoặc bị vô hiệu hóa và đã được gỡ bỏ'); ?>",
                                    duration: 4000,
                                    gravity: "top",
                                    position: "right",
                                    backgroundColor: "#fd7e14",
                                    stopOnFocus: true
                                }).showToast();
                            }
                        });
                    }
                }
                <?php endif; ?>
            }
        });
        
        // Lắng nghe sự kiện xóa voucher
        realtime.on('delete_voucher', function(data) {
            if (data && data.voucher_code) {
                <?php if (!empty($_SESSION['voucher_code'])): ?>
                // So sánh với mã voucher hiện tại trong session
                const currentVoucherCode = "<?php echo $_SESSION['voucher_code']; ?>";
                
                // Nếu voucher bị xóa trùng với voucher đang sử dụng
                if (currentVoucherCode === data.voucher_code) {
                    // Tự động gỡ bỏ voucher không hợp lệ
                    fetch('/project1/shop/removeVoucher', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            // Hiển thị thông báo
                            Toastify({
                                text: "<?php echo __('Mã giảm giá đã bị xóa và đã được gỡ bỏ khỏi giỏ hàng của bạn'); ?>",
                                duration: 4000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#dc3545",
                                stopOnFocus: true
                            }).showToast();
                        }
                    });
                }
                <?php endif; ?>
            }
        });
    } else {
        console.warn('<?php echo __("WebSocket client (realtime.js) chưa được tải. Tính năng cập nhật thời gian thực sẽ không hoạt động."); ?>');
    }
});
</script>
<?php include 'app/views/shares/footer.php'; ?>