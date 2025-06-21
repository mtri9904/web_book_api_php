<?php
$activePage = 'review';
$pageTitle = __('Quản lý đánh giá');
ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="text-white mb-0">
                    <i class="bi bi-star me-2"></i><?= __('Quản lý đánh giá sản phẩm') ?>
                </h3>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th><?php echo __('ID'); ?></th>
                        <th><?php echo __('Sản phẩm'); ?></th>
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
                            <td colspan="8" class="text-center"><?php echo __('Không có đánh giá nào'); ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reviews as $review): ?>
                            <tr>
                                <td><?php echo $review->id; ?></td>
                                <td>
                                    <a href="/project1/admin/product/show/<?php echo $review->product_id; ?>">
                                        <?php echo htmlspecialchars($review->product_name); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($review->fullname); ?> (<?php echo $review->username; ?>)</td>
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
        
        <!-- Phân trang -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-3">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="/project1/admin/review?page=<?php echo $page - 1; ?>">
                                <?php echo __('Trước'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="/project1/admin/review?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="/project1/admin/review?page=<?php echo $page + 1; ?>">
                                <?php echo __('Sau'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
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