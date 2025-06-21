<?php
$activePage = 'review';
$pageTitle = __('Chi tiết đánh giá');
ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="text-white mb-0">
                    <i class="bi bi-star me-2"></i><?= __('Chi tiết đánh giá') ?>
                </h3>
            </div>
            <div class="col-auto">
                <a href="/project1/admin/review" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i> <?= __('Quay lại danh sách') ?>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><?php echo __('Thông tin đánh giá'); ?></h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3"><strong><?php echo __('ID đánh giá'); ?>:</strong></div>
                    <div class="col-md-9"><?php echo $review->id; ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong><?php echo __('Sản phẩm'); ?>:</strong></div>
                    <div class="col-md-9">
                        <a href="/project1/admin/product/show/<?php echo $review->product_id; ?>">
                            <?php echo htmlspecialchars($review->product_name); ?> (ID: <?php echo $review->product_id; ?>)
                        </a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong><?php echo __('Người dùng'); ?>:</strong></div>
                    <div class="col-md-9">
                        <?php echo htmlspecialchars($review->fullname); ?> 
                        (<?php echo $review->username; ?>, ID: <?php echo $review->user_id; ?>)
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong><?php echo __('Đơn hàng'); ?>:</strong></div>
                    <div class="col-md-9">
                        <a href="/project1/admin/order/show/<?php echo $review->order_id; ?>">
                            #<?php echo $review->order_id; ?>
                        </a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong><?php echo __('Đánh giá'); ?>:</strong></div>
                    <div class="col-md-9">
                        <?php
                        $rating = $review->rating;
                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $rating ? '<span class="text-warning">★</span>' : '<span class="text-secondary">☆</span>';
                        }
                        echo ' (' . $rating . '/5)';
                        ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong><?php echo __('Bình luận'); ?>:</strong></div>
                    <div class="col-md-9">
                        <div class="p-3 bg-white border rounded">
                            <?php echo nl2br(htmlspecialchars($review->comment)); ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong><?php echo __('Ngày tạo'); ?>:</strong></div>
                    <div class="col-md-9"><?php echo date('d/m/Y H:i:s', strtotime($review->created_at)); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong><?php echo __('Cập nhật cuối'); ?>:</strong></div>
                    <div class="col-md-9"><?php echo date('d/m/Y H:i:s', strtotime($review->updated_at)); ?></div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end">
            <a href="/project1/admin/review/delete/<?php echo $review->id; ?>" class="btn btn-danger" onclick="return confirm('<?php echo __('Bạn có chắc chắn muốn xóa đánh giá này?'); ?>');">
                <i class="bi bi-trash me-1"></i> <?php echo __('Xóa đánh giá'); ?>
            </a>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary-to-secondary {
    background: linear-gradient(135deg, #1a2a44 0%, #293e63 100%);
}
</style>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?> 