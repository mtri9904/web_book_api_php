<?php
include 'app/views/shares/header.php'; ?>
<h1><?php echo __('Xác nhận đơn hàng'); ?></h1>
<?php
$order = $_SESSION['last_order'] ?? null;
if ($order):
    // Xóa session ngay sau khi lấy ra để tránh lặp lại khi F5 hoặc đặt đơn mới
    unset($_SESSION['last_order']);
    $cart = $order['cart'];
    $subtotal = $order['subtotal'];
    $voucher_code = $order['voucher_code'];
    $voucher_discount = $order['voucher_discount'];
    $discount = 0;
    if ($voucher_discount > 0) {
        if ($voucher_discount <= 100) {
            $discount = $subtotal * $voucher_discount / 100;
        } else {
            $discount = $voucher_discount;
        }
    }
    $total = $subtotal - $discount;
    if ($total < 0) $total = 0;
?>
<div class="card mb-4">
    <div class="card-body">
        <h4 class="mb-3 text-primary"><?php echo __('Thông tin khách hàng'); ?></h4>
        <p><strong><?php echo __('Họ tên:'); ?></strong> <?php echo htmlspecialchars($order['name']); ?></p>
        <p><strong><?php echo __('Số điện thoại:'); ?></strong> <?php echo htmlspecialchars($order['phone']); ?></p>
        <p><strong><?php echo __('Địa chỉ:'); ?></strong> <?php echo htmlspecialchars($order['address']); ?></p>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <h4 class="mb-3 text-primary"><?php echo __('Sản phẩm đã đặt'); ?></h4>
        <ul class="list-group mb-3">
            <?php foreach ($cart as $item): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                    <div class="small text-muted"><?php echo __('Giá:'); ?> <?php echo number_format($item['price'], 0, ',', '.'); ?> <?php echo __('VND'); ?></div>
                    <div class="small text-muted"><?php echo __('Số lượng:'); ?> <?php echo $item['quantity']; ?></div>
                </div>
                <span><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> <?php echo __('VND'); ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
        <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between">
                <span><?php echo __('Tạm tính:'); ?></span>
                <span><?php echo number_format($subtotal, 0, ',', '.'); ?> <?php echo __('VND'); ?></span>
            </li>
            <?php if ($discount > 0): ?>
            <li class="list-group-item d-flex justify-content-between text-success">
                <span><?php echo __('Giảm giá'); ?><?php if ($voucher_code) echo " ($voucher_code)"; ?>:</span>
                <span>-<?php echo number_format($discount, 0, ',', '.'); ?> <?php echo __('VND'); ?></span>
            </li>
            <?php endif; ?>
            <li class="list-group-item d-flex justify-content-between fw-bold">
                <span><?php echo __('Tổng cộng:'); ?></span>
                <span><?php echo number_format($total, 0, ',', '.'); ?> <?php echo __('VND'); ?></span>
            </li>
        </ul>
    </div>
</div>
<?php else: ?>
<p><?php echo __('Không tìm thấy thông tin đơn hàng.'); ?></p>
<?php endif; ?>
<a href="/project1/Shop/listproduct" class="btn btn-primary mt-2"><?php echo __('Tiếp tục mua sắm'); ?></a>
<?php include 'app/views/shares/footer.php'; ?>