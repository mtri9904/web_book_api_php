<?php require_once 'app/views/shares/header.php'; ?>

<div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="bi bi-star-fill me-2"></i>Đánh giá sản phẩm: <?= htmlspecialchars($product->name) ?></h3>
                <a href="/project1/shop/show/<?= $product->id ?>" class="btn btn-sm btn-light">
                    <i class="bi bi-arrow-left me-1"></i>Quay lại sản phẩm
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="text-center p-3">
                            <?php if (!empty($product->image)): ?>
                                <img src="/project1/<?= $product->image ?>" alt="<?= $product->name ?>" class="img-fluid rounded" style="max-height: 200px;">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                                    <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($product->name) ?></h5>
                            <p class="card-text text-primary"><?= number_format($product->price) ?> VND</p>
                            
                            <?php if (isset($product->average_rating) && $product->average_rating > 0): ?>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <span class="display-4 fw-bold me-2"><?= number_format($product->average_rating, 1) ?></span>
                                        <div>
                                            <div class="stars-outer">
                                                <div class="stars-inner" style="width: <?= ($product->average_rating / 5) * 100 ?>%"></div>
                                            </div>
                                            <div class="text-muted small"><?= $product->review_count ?> đánh giá</div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Chưa có đánh giá</p>
                            <?php endif; ?>
                            
                            <?php if (SessionHelper::isLoggedIn()): ?>
                                <a href="/project1/review" class="btn btn-outline-primary mt-2">
                                    <i class="bi bi-pencil-square me-1"></i>Viết đánh giá
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($ratingStats) && array_sum($ratingStats) > 0): ?>
                        <div class="card border-0 shadow-sm mt-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Thống kê đánh giá</h5>
                            </div>
                            <div class="card-body">
                                <?php $totalRatings = array_sum($ratingStats); ?>
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2"><?= $i ?> <i class="bi bi-star-fill text-warning"></i></div>
                                        <div class="progress flex-grow-1" style="height: 10px;">
                                            <div class="progress-bar bg-warning" role="progressbar" 
                                                style="width: <?= ($ratingStats[$i] / $totalRatings) * 100 ?>%"></div>
                                        </div>
                                        <div class="ms-2 text-muted small"><?= $ratingStats[$i] ?></div>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Nhận xét từ khách hàng</h5>
                        </div>
                        
                        <?php if (empty($reviews)): ?>
                            <div class="card-body text-center p-5">
                                <i class="bi bi-chat-square-text display-4 text-muted"></i>
                                <h5 class="mt-3">Chưa có nhận xét nào</h5>
                                <p class="text-muted">Hãy là người đầu tiên đánh giá sản phẩm này</p>
                            </div>
                        <?php else: ?>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <?php foreach ($reviews as $review): ?>
                                        <div class="list-group-item p-3">
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
                            </div>
                            
                            <?php if ($totalPages > 1): ?>
                                <div class="card-footer bg-white">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-center mb-0">
                                            <?php if ($page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="/project1/review/product?id=<?= $product->id ?>&page=<?= $page - 1 ?>">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            
                                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                    <a class="page-link" href="/project1/review/product?id=<?= $product->id ?>&page=<?= $i ?>">
                                                        <?= $i ?>
                                                    </a>
                                                </li>
                                            <?php endfor; ?>
                                            
                                            <?php if ($page < $totalPages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="/project1/review/product?id=<?= $product->id ?>&page=<?= $page + 1 ?>">
                                                        <i class="bi bi-chevron-right"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
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