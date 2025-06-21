<?php
$activePage = 'product';
$pageTitle = __('Chi tiết sản phẩm');
ob_start();
?>

<div class="card shadow-lg border-0 rounded-3 overflow-hidden">
    <div class="card-header d-flex justify-content-between align-items-center bg-gradient-primary-to-secondary text-white py-3">
        <h4 class="m-0"><i class="bi bi-box-seam me-2"></i><?= $product->name ?></h4>
        <div>
            <a href="/project1/admin/product/edit/<?= $product->id ?>" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i> <?= __('Sửa') ?>
            </a>
            <a href="/project1/admin/product/list" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left"></i> <?= __('Quay lại danh sách') ?>
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="row g-0">
            <div class="col-md-5">
                <?php if (!empty($product->image)): ?>
                    <div class="product-image-container" style="height: 100%; min-height: 400px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                        <img src="/project1/<?= $product->image ?>" class="img-fluid" alt="<?= $product->name ?>" style="max-height: 100%; width: auto; object-fit: contain;">
                    </div>
                <?php else: ?>
                    <div class="product-image-container bg-light d-flex align-items-center justify-content-center" style="height: 100%; min-height: 400px;">
                        <div class="text-muted">
                            <i class="bi bi-image" style="font-size: 4rem;"></i>
                            <p class="mt-2"><?= __('Không có hình ảnh') ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-7">
                <div class="card-body p-4 h-100 d-flex flex-column">
                    <h2 class="card-title fw-bold mb-3"><?= $product->name ?></h2>
                    
                    <div class="mb-3 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-4 fw-bold text-primary"><?= number_format($product->price) ?> VND</span>
                            <span class="badge bg-info text-dark"><?= $product->category_name ?? __('Chưa phân loại') ?></span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="fw-bold mb-2"><?= __('Mô tả') ?>:</h5>
                        <div class="p-3 bg-white border rounded">
                            <?php if (!empty($product->description)): ?>
                                <p class="card-text"><?= nl2br($product->description) ?></p>
                            <?php else: ?>
                                <p class="text-muted fst-italic"><?= __('Không có mô tả') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i><?= __('Thông tin sản phẩm') ?></h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th class="text-muted" style="width: 40%;"><?= __('Mã sản phẩm') ?></th>
                                            <td><?= $product->id ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted"><?= __('Danh mục') ?></th>
                                            <td><?= $product->category_name ?? __('Chưa phân loại') ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted"><?= __('Giá') ?></th>
                                            <td><?= number_format($product->price) ?> VND</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-box me-2"></i><?= __('Kho hàng') ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-1"><?= __('Trạng thái kho') ?></h6>
                                        <?php if (isset($product->quantity) && $product->quantity > 0): ?>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-success me-2"><?= __('Còn hàng') ?></span>
                                                <span class="fs-5 fw-semibold"><?= $product->quantity ?> <?= __('sản phẩm') ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><?= __('Hết hàng') ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (isset($product->quantity) && $product->quantity > 0): ?>
                                    <div class="progress" style="height: 10px;">
                                        <?php 
                                        // Assume 100 is the maximum stock level for visualization
                                        $stockPercentage = min(100, ($product->quantity / 100) * 100);
                                        $progressClass = $product->quantity > 20 ? 'bg-success' : ($product->quantity > 5 ? 'bg-warning' : 'bg-danger');
                                        ?>
                                        <div class="progress-bar <?= $progressClass ?>" role="progressbar" 
                                            style="width: <?= $stockPercentage ?>%" 
                                            aria-valuenow="<?= $stockPercentage ?>" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100"></div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-star-fill me-2"></i><?= __('Đánh giá từ khách hàng') ?></h5>
                                </div>
                                <div class="card-body">
                                    <?php if (isset($product->average_rating) && $product->average_rating > 0): ?>
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <div class="d-flex align-items-center">
                                                <div class="display-4 fw-bold me-3"><?= number_format($product->average_rating, 1) ?></div>
                                                <div>
                                                    <div class="stars-outer">
                                                        <div class="stars-inner" style="width: <?= ($product->average_rating / 5) * 100 ?>%"></div>
                                                    </div>
                                                    <div class="text-muted"><?= $product->review_count ?? 0 ?> <?= __('đánh giá') ?></div>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="/project1/admin/review/byProduct/<?= $product->id ?>" class="btn btn-outline-primary">
                                                    <i class="bi bi-eye me-2"></i><?= __('Xem tất cả đánh giá') ?>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <?php
                                        // Lấy danh sách đánh giá
                                        require_once 'app/models/ReviewModel.php';
                                        $reviewModel = new ReviewModel($this->db);
                                        $reviews = $reviewModel->getProductReviews($product->id, 5, 0); // Chỉ lấy 5 đánh giá mới nhất
                                        $ratingStats = $reviewModel->getProductRatingStats($product->id);
                                        $totalRatings = array_sum($ratingStats);
                                        ?>
                                        
                                        <?php if (!empty($ratingStats)): ?>
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <h6 class="mb-3"><?= __('Thống kê đánh giá') ?></h6>
                                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div class="me-2"><?= $i ?> <i class="bi bi-star-fill text-warning"></i></div>
                                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                                <div class="progress-bar bg-warning" role="progressbar" 
                                                                     style="width: <?= ($ratingStats[$i] / $totalRatings) * 100 ?>%"></div>
                                                            </div>
                                                            <div class="ms-2 text-muted small"><?= $ratingStats[$i] ?></div>
                                                        </div>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($reviews)): ?>
                                            <h6 class="mb-3"><?= __('Đánh giá gần đây') ?></h6>
                                            <div class="list-group">
                                                <?php foreach ($reviews as $review): ?>
                                                    <div class="list-group-item list-group-item-action">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <strong>
                                                                <?= !empty($review->fullname) ? htmlspecialchars($review->fullname) : htmlspecialchars($review->username) ?>
                                                            </strong>
                                                            <small class="text-muted"><?= date('d/m/Y', strtotime($review->created_at)) ?></small>
                                                        </div>
                                                        <div class="mb-2">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <?php if ($i <= $review->rating): ?>
                                                                    <i class="bi bi-star-fill text-warning"></i>
                                                                <?php else: ?>
                                                                    <i class="bi bi-star text-muted"></i>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                        </div>
                                                        <?php if (!empty($review->comment)): ?>
                                                            <p class="mb-0"><?= nl2br(htmlspecialchars($review->comment)) ?></p>
                                                        <?php else: ?>
                                                            <p class="text-muted fst-italic mb-0"><?= __('Không có nhận xét') ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-info">
                                                <?= __('Chưa có đánh giá chi tiết nào.') ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="text-center py-4">
                                            <i class="bi bi-star display-1 text-muted"></i>
                                            <h5 class="mt-3"><?= __('Sản phẩm này chưa có đánh giá') ?></h5>
                                            <p class="text-muted"><?= __('Đánh giá sẽ xuất hiện khi khách hàng mua và đánh giá sản phẩm này.') ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-auto">
                        <a href="javascript:void(0)" onclick="confirmDelete(<?= $product->id ?>)" class="btn btn-danger">
                            <i class="bi bi-trash"></i> <?= __('Xóa sản phẩm') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('Xác nhận xóa') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= __('Bạn có chắc chắn muốn xóa sản phẩm này? Hành động này không thể hoàn tác.') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('Hủy') ?></button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger"><?= __('Xóa') ?></a>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary-to-secondary {
    background: linear-gradient(135deg, #1a2a44 0%, #293e63 100%);
}

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

<script>
    function confirmDelete(id) {
        document.getElementById('confirmDeleteBtn').href = '/project1/admin/product/delete/' + id;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?> 