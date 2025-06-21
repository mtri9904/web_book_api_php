<?php require_once 'app/views/shares/header.php'; ?>

<div class="container my-5">
    <div class="card shadow">
        <div class="card-body">
            <div class="row">
                <!-- Hình ảnh sản phẩm -->
                <div class="col-md-5 mb-4">
                    <?php if (!empty($product->image)): ?>
                        <img src="/project1/<?= $product->image ?>" alt="<?= $product->name ?>" class="img-fluid rounded">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 400px;">
                            <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Thông tin sản phẩm -->
                <div class="col-md-7">
                    <h2 class="mb-3"><?= htmlspecialchars($product->name) ?></h2>
                    
                    <!-- Hiển thị đánh giá sao -->
                    <?php if (isset($product->average_rating) && $product->average_rating > 0): ?>
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="stars-outer me-2">
                                    <div class="stars-inner" style="width: <?= ($product->average_rating / 5) * 100 ?>%"></div>
                                </div>
                                <div>
                                    <span class="fw-bold"><?= number_format($product->average_rating, 1) ?></span>
                                    <span class="text-muted">(<?= $product->review_count ?? 0 ?> đánh giá)</span>
                                    <a href="/project1/review/product?id=<?= $product->id ?>" class="ms-2 text-decoration-none">Xem tất cả</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <h4 class="text-primary"><?= number_format($product->price) ?> VND</h4>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Mô tả:</h5>
                        <p><?= nl2br(htmlspecialchars($product->description)) ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="mb-1">Danh mục: <span class="badge bg-info"><?= $product->category_name ?? 'Chưa phân loại' ?></span></p>
                        <p>
                            Tình trạng: 
                            <?php if (isset($product->quantity) && $product->quantity > 0): ?>
                                <span class="badge bg-success">Còn hàng (<?= $product->quantity ?>)</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Hết hàng</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                        <?php if (isset($product->quantity) && $product->quantity > 0): ?>
                            <form action="/project1/shop/addToCart" method="post" class="d-flex gap-2">
                                <input type="hidden" name="product_id" value="<?= $product->id ?>">
                                <div class="input-group" style="width: 150px;">
                                    <span class="input-group-text">Số lượng</span>
                                    <input type="number" class="form-control" name="quantity" value="1" min="1" max="<?= $product->quantity ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>
                                <i class="bi bi-cart-x me-1"></i> Hết hàng
                            </button>
                        <?php endif; ?>
                        
                        <?php if (SessionHelper::isLoggedIn()): ?>
                            <a href="/project1/review" class="btn btn-outline-primary">
                                <i class="bi bi-star me-1"></i> Đánh giá sản phẩm
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Phần đánh giá sản phẩm -->
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="mb-4">Đánh giá từ khách hàng</h3>
                    
                    <?php
                    // Lấy danh sách đánh giá
                    require_once 'app/models/ReviewModel.php';
                    $reviewModel = new ReviewModel($this->db);
                    $reviews = $reviewModel->getProductReviews($product->id, 5, 0); // Chỉ lấy 5 đánh giá mới nhất
                    $ratingStats = $reviewModel->getProductRatingStats($product->id);
                    $totalRatings = array_sum($ratingStats);
                    ?>
                    
                    <?php if (isset($product->average_rating) && $product->average_rating > 0): ?>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="text-center mb-4">
                                    <div class="display-1 fw-bold"><?= number_format($product->average_rating, 1) ?></div>
                                    <div class="stars-outer mx-auto" style="font-size: 1.5rem;">
                                        <div class="stars-inner" style="width: <?= ($product->average_rating / 5) * 100 ?>%"></div>
                                    </div>
                                    <div class="mt-2"><?= $product->review_count ?? 0 ?> đánh giá</div>
                                </div>
                                
                                <?php if (!empty($ratingStats)): ?>
                                    <div class="rating-stats">
                                        <?php for ($i = 5; $i >= 1; $i--): ?>
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="me-2"><?= $i ?> <i class="bi bi-star-fill text-warning"></i></div>
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-warning" role="progressbar" 
                                                         style="width: <?= ($ratingStats[$i] / $totalRatings) * 100 ?>%"></div>
                                                </div>
                                                <div class="ms-2 text-muted"><?= $ratingStats[$i] ?></div>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mt-4 text-center">
                                    <a href="/project1/review/product?id=<?= $product->id ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-chat-square-text me-1"></i> Xem tất cả đánh giá
                                    </a>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <?php if (!empty($reviews)): ?>
                                    <div class="list-group">
                                        <?php foreach ($reviews as $review): ?>
                                            <div class="list-group-item border-0 mb-3 shadow-sm rounded">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">
                                                            <?= !empty($review->fullname) ? htmlspecialchars($review->fullname) : htmlspecialchars($review->username) ?>
                                                        </h6>
                                                        <div class="text-muted small">
                                                            <?= date('d/m/Y', strtotime($review->created_at)) ?>
                                                        </div>
                                                    </div>
                                                    <div class="stars">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <?php if ($i <= $review->rating): ?>
                                                                <i class="bi bi-star-fill text-warning"></i>
                                                            <?php else: ?>
                                                                <i class="bi bi-star text-muted"></i>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                                
                                                <?php if (!empty($review->comment)): ?>
                                                    <p class="mb-0"><?= nl2br(htmlspecialchars($review->comment)) ?></p>
                                                <?php else: ?>
                                                    <p class="text-muted fst-italic mb-0">Không có nhận xét</p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center p-5 bg-light rounded">
                                        <i class="bi bi-chat-square-text display-4 text-muted"></i>
                                        <h5 class="mt-3">Chưa có đánh giá nào</h5>
                                        <?php if (SessionHelper::isLoggedIn()): ?>
                                            <p class="mb-4">Hãy là người đầu tiên đánh giá sản phẩm này</p>
                                            <a href="/project1/review" class="btn btn-primary">
                                                <i class="bi bi-star me-1"></i> Viết đánh giá
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center p-5 bg-light rounded">
                            <i class="bi bi-star display-4 text-muted"></i>
                            <h5 class="mt-3">Sản phẩm này chưa có đánh giá</h5>
                            <?php if (SessionHelper::isLoggedIn()): ?>
                                <p class="mb-4">Hãy là người đầu tiên đánh giá sản phẩm này</p>
                                <a href="/project1/review" class="btn btn-primary">
                                    <i class="bi bi-star me-1"></i> Viết đánh giá
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stars-outer {
    display: inline-block;
    position: relative;
    font-family: "bootstrap-icons";
    color: #ddd;
}

.stars-outer::before {
    content: "\F586\F586\F586\F586\F586";
}

.stars-inner {
    position: absolute;
    top: 0;
    left: 0;
    white-space: nowrap;
    overflow: hidden;
    color: #ffc107;
}

.stars-inner::before {
    content: "\F586\F586\F586\F586\F586";
}
</style>

<?php require_once 'app/views/shares/footer.php'; ?> 