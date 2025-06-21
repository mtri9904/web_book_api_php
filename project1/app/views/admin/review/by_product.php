<?php
$activePage = 'review';
$pageTitle = __('Đánh giá sản phẩm') . ': ' . $product->name;
ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="text-white mb-0">
                    <i class="bi bi-star me-2"></i><?= __('Đánh giá cho sản phẩm') ?>: <?= htmlspecialchars($product->name) ?>
                </h3>
            </div>
            <div class="col-auto">
                <a href="/project1/admin/product" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i> <?= __('Quay lại danh sách sản phẩm') ?>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><?php echo __('Thông tin sản phẩm'); ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center">
                        <img src="<?php echo $product->image ? '/project1/' . $product->image : '/project1/public/img/no-image.png'; ?>" 
                             alt="<?php echo htmlspecialchars($product->name); ?>" 
                             class="img-fluid mb-2" style="max-height: 100px;">
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><?php echo __('ID'); ?>:</strong> <?php echo $product->id; ?></p>
                                <p><strong><?php echo __('Tên sản phẩm'); ?>:</strong> <?php echo htmlspecialchars($product->name); ?></p>
                                <p><strong><?php echo __('Giá'); ?>:</strong> <?php echo number_format($product->price, 0, ',', '.'); ?> đ</p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <strong><?php echo __('Đánh giá trung bình'); ?>:</strong> 
                                    <?php 
                                    $avgRating = $product->average_rating;
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= round($avgRating) ? '<span class="text-warning">★</span>' : '<span class="text-secondary">☆</span>';
                                    }
                                    echo ' (' . number_format($avgRating, 1) . '/5)';
                                    ?>
                                </p>
                                <p><strong><?php echo __('Số lượng đánh giá'); ?>:</strong> <?php echo $product->review_count; ?></p>
                                <p>
                                    <a href="/project1/admin/product/edit/<?php echo $product->id; ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil-square"></i> <?php echo __('Sửa sản phẩm'); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><?php echo __('Danh sách đánh giá'); ?> (<?php echo count($reviews); ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><?php echo __('ID'); ?></th>
                                <th><?php echo __('Người dùng'); ?></th>
                                <th><?php echo __('Đơn hàng'); ?></th>
                                <th><?php echo __('Đánh giá'); ?></th>
                                <th><?php echo __('Bình luận'); ?></th>
                                <th><?php echo __('Ngày tạo'); ?></th>
                                <th><?php echo __('Thao tác'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reviews)): ?>
                                <tr>
                                    <td colspan="7" class="text-center"><?php echo __('Không có đánh giá nào cho sản phẩm này'); ?></td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($reviews as $review): ?>
                                    <tr>
                                        <td><?php echo $review->id; ?></td>
                                        <td><?php echo htmlspecialchars($review->username); ?></td>
                                        <td>
                                            <a href="/project1/admin/order/show/<?php echo $review->order_id; ?>">
                                                #<?php echo $review->order_id; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php
                                            $rating = $review->rating;
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $rating ? '<span class="text-warning">★</span>' : '<span class="text-secondary">☆</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars(mb_substr($review->comment, 0, 50, 'UTF-8')) . (mb_strlen($review->comment, 'UTF-8') > 50 ? '...' : ''); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($review->created_at)); ?></td>
                                        <td>
                                            <a href="/project1/admin/review/view/<?php echo $review->id; ?>" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="/project1/admin/review/delete/<?php echo $review->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('<?php echo __('Bạn có chắc chắn muốn xóa đánh giá này?'); ?>');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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