<?php include 'app/views/shares/header.php'; ?>
<h1><?php echo __('Danh sách đơn hàng'); ?></h1>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th><?php echo __('Ngày tạo'); ?></th>
            <th><?php echo __('Tên người nhận'); ?></th>
            <th><?php echo __('Số điện thoại'); ?></th>
            <th><?php echo __('Địa chỉ'); ?></th>
            <th><?php echo __('Tổng tiền'); ?></th>
            <th><?php echo __('Hành động'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?php echo $order->id; ?></td>
            <td><?php echo $order->created_at; ?></td>
            <td><?php echo htmlspecialchars($order->name, ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($order->phone, ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($order->address, ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo number_format($order->total, 0, ',', '.'); ?> VND</td>
            <td>
                <div class="d-flex flex-wrap gap-1">
                    <a href="/project1/order/show/<?php echo $order->id; ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-eye me-1"></i><?php echo __('Chi tiết'); ?>
                    </a>
                    
                    <?php
                    // Kiểm tra xem đơn hàng này có sản phẩm nào chưa được đánh giá không
                    require_once 'app/models/ReviewModel.php';
                    require_once 'app/config/database.php';
                    $reviewModel = new ReviewModel((new Database())->getConnection());
                    $orderDetails = $orderModel->getOrderDetailsByOrderId($order->id);
                    $hasProductsToReview = false;
                    
                    foreach ($orderDetails as $item) {
                        // Kiểm tra cả hai điều kiện: chưa đánh giá trong đơn hàng này và chưa đánh giá sản phẩm này ở bất kỳ đâu
                        if (!$reviewModel->checkUserReviewedProduct($_SESSION['user_id'], $item->product_id, $order->id) && 
                            !$reviewModel->hasUserReviewedProduct($_SESSION['user_id'], $item->product_id)) {
                            $hasProductsToReview = true;
                            break;
                        }
                    }
                    ?>
                    
                    <?php if ($hasProductsToReview): ?>
                    <a href="/project1/order/show/<?php echo $order->id; ?>#review-section" class="btn btn-success btn-sm">
                        <i class="bi bi-star me-1"></i><?php echo __('Đánh giá'); ?>
                    </a>
                    <?php endif; ?>
                    
                    <a href="/project1/order/delete/<?php echo $order->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo __('Bạn có chắc chắn muốn xóa đơn hàng này?'); ?>');">
                        <i class="bi bi-trash me-1"></i><?php echo __('Xóa'); ?>
                    </a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include 'app/views/shares/footer.php'; ?>