<?php
$activePage = 'order';
$pageTitle = __('Chi tiết đơn hàng');
ob_start();
?>

<!-- CSS cho admin order -->
<style>
    /* Các hiệu ứng cho nút và giao diện admin */
</style>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold">
                <i class="bi bi-receipt me-2"></i><?= __('Đơn hàng') ?> #<?= $order->id ?>
            </h2>
            <a href="/project1/admin/order/list" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> <?= __('Quay lại danh sách') ?>
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-person me-2"></i><?= __('Thông tin khách hàng') ?></h5>
                    </div>
                    <div class="card-body">
                        <p><strong><?= __('Tên') ?>:</strong> 
                            <?php if (isset($order->full_name) && !empty($order->full_name)): ?>
                                <?= htmlspecialchars($order->full_name) ?>
                            <?php elseif (isset($order->name) && !empty($order->name)): ?>
                                <?= htmlspecialchars($order->name) ?>
                            <?php else: ?>
                                <span class="text-muted"><?= __('Khách vãng lai') ?></span>
                            <?php endif; ?>
                        </p>
                        <p><strong><?= __('Email') ?>:</strong> 
                            <?php if (isset($order->email) && !empty($order->email)): ?>
                                <?= htmlspecialchars($order->email) ?>
                            <?php else: ?>
                                <span class="text-muted"><?= __('Không cung cấp') ?></span>
                            <?php endif; ?>
                        </p>
                        <p class="mb-0"><strong><?= __('Số điện thoại') ?>:</strong> 
                            <?php if (isset($order->phone) && !empty($order->phone)): ?>
                                <?= htmlspecialchars($order->phone) ?>
                            <?php else: ?>
                                <span class="text-muted"><?= __('Không cung cấp') ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i><?= __('Thông tin đơn hàng') ?></h5>
                    </div>
                    <div class="card-body">
                        <p><strong><?= __('Ngày đặt hàng') ?>:</strong> 
                            <?php if (isset($order->created_at)): ?>
                                <?= date('d/m/Y H:i', strtotime($order->created_at)) ?>
                            <?php else: ?>
                                <span class="text-muted"><?= __('Không xác định') ?></span>
                            <?php endif; ?>
                        </p>
                        <p>
                            <strong><?= __('Trạng thái') ?>:</strong>
                            <span class="badge bg-success"><?= __('Thành công') ?></span>
                        </p>
                        <?php if (isset($order->voucher_code) && !empty($order->voucher_code)): ?>
                        <p>
                            <strong><?= __('Mã giảm giá') ?>:</strong>
                            <span class="badge bg-info"><?= htmlspecialchars($order->voucher_code) ?></span>
                        </p>
                        <?php if (isset($order->voucher_discount) && $order->voucher_discount > 0): ?>
                        <p>
                            <strong><?= __('Số tiền giảm') ?>:</strong>
                            <span class="text-success">-<?= number_format($order->voucher_discount) ?> VND</span>
                        </p>
                        <?php endif; ?>
                        <?php endif; ?>
                        <p class="mb-0"><strong><?= __('Phương thức thanh toán') ?>:</strong> 
                            <?php if (isset($order->payment_method) && !empty($order->payment_method)): ?>
                                <?= ucfirst(htmlspecialchars($order->payment_method)) ?>
                            <?php else: ?>
                                <span class="text-muted"><?= __('Không xác định') ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (isset($order->address) || isset($order->city) || isset($order->country)): ?>
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i><?= __('Địa chỉ giao hàng') ?></h5>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    <?php
                    $addressParts = [];
                    if (isset($order->address) && !empty($order->address)) $addressParts[] = htmlspecialchars($order->address);
                    if (isset($order->city) && !empty($order->city)) $addressParts[] = htmlspecialchars($order->city);
                    if (isset($order->country) && !empty($order->country)) $addressParts[] = htmlspecialchars($order->country);
                    
                    echo !empty($addressParts) ? implode(', ', $addressParts) : '<span class="text-muted">' . __('Không cung cấp địa chỉ') . '</span>';
                    ?>
                </p>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i><?= __('Sản phẩm trong đơn hàng') ?></h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th><?= __('Sản phẩm') ?></th>
                                <th><?= __('Hình ảnh') ?></th>
                                <th><?= __('Giá') ?></th>
                                <th><?= __('Số lượng') ?></th>
                                <th class="text-end"><?= __('Tổng phụ') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $subtotal = 0; 
                            if (isset($orderDetails) && is_array($orderDetails) && count($orderDetails) > 0):
                            ?>
                                <?php foreach ($orderDetails as $item): ?>
                                    <?php 
                                    $price = isset($item->price) ? $item->price : 0;
                                    $quantity = isset($item->quantity) ? $item->quantity : 0;
                                    $itemTotal = $price * $quantity;
                                    $subtotal += $itemTotal;
                                    ?>
                                    <tr>
                                        <td>
                                            <?php if (isset($item->product_name) && !empty($item->product_name)): ?>
                                                <strong><?= htmlspecialchars($item->product_name) ?></strong>
                                                

                                                
                                                <?php if (isset($item->category_name) && !empty($item->category_name)): ?>
                                                    <div class="small">
                                                        <span class="badge bg-info text-dark mt-1"><?= htmlspecialchars($item->category_name) ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if (strpos($item->product_name, '[Sản phẩm đã bị xóa]') !== false): ?>
                                                    <div class="mt-1">
                                                        <span class="badge bg-secondary"><?= __('Sản phẩm đã bị xóa khỏi hệ thống') ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted"><?= __('Sản phẩm không xác định') ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (isset($item->product_image) && !empty($item->product_image)): ?>
                                                <img src="/project1/<?= $item->product_image ?>" 
                                                     alt="<?= htmlspecialchars($item->product_name) ?>" 
                                                     class="img-thumbnail" 
                                                     style="width: 60px; height: 60px; object-fit: cover;"
                                                     title="<?= htmlspecialchars($item->product_name) ?>">
                                            <?php else: ?>
                                                <span class="text-muted"><?= __('Không có hình ảnh') ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= number_format($price) ?> VND</td>
                                        <td><?= $quantity ?></td>
                                        <td class="text-end"><?= number_format($itemTotal) ?> VND</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-3"><?= __('Không tìm thấy sản phẩm nào trong đơn hàng') ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="4" class="text-end"><strong><?= __('Tổng phụ') ?>:</strong></td>
                                <td class="text-end"><?= number_format($subtotal) ?> VND</td>
                            </tr>
                            <?php if (isset($order->voucher_code) && !empty($order->voucher_code)): ?>
                            <tr>
                                <td colspan="4" class="text-end"><strong><?= __('Mã giảm giá đã áp dụng') ?> (<?= htmlspecialchars($order->voucher_code) ?>):</strong></td>
                                <td class="text-end text-success">
                                    <?php if (isset($order->voucher_discount) && $order->voucher_discount > 0): ?>
                                        -<?= number_format($order->voucher_discount) ?> VND
                                    <?php else: ?>
                                        0 VND
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php elseif (isset($order->discount) && $order->discount > 0): ?>
                            <tr>
                                <td colspan="4" class="text-end"><strong><?= __('Số tiền giảm') ?>:</strong></td>
                                <td class="text-end text-success">-<?= number_format($order->discount) ?> VND</td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="4" class="text-end"><strong><?= __('Tổng cộng') ?>:</strong></td>
                                <td class="text-end">
                                    <strong>
                                        <?php if (isset($order->total_amount)): ?>
                                            <?= number_format($order->total_amount) ?> VND
                                        <?php elseif (isset($order->total)): ?>
                                            <?= number_format($order->total) ?> VND
                                        <?php else: ?>
                                            <?= number_format($subtotal - (isset($order->voucher_discount) ? $order->voucher_discount : 0)) ?> VND
                                        <?php endif; ?>
                                    </strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <?php if (isset($order->notes) && !empty($order->notes)): ?>
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-chat-left-text me-2"></i><?= __('Ghi chú của khách hàng') ?></h5>
            </div>
            <div class="card-body">
                <p class="mb-0"><?= nl2br(htmlspecialchars($order->notes)) ?></p>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="text-center mt-4">
            <a href="javascript:void(0)" onclick="confirmDelete(<?= $order->id ?>)" class="btn btn-danger px-4 py-2">
                <i class="bi bi-trash me-2"></i> <?= __('Xóa đơn hàng') ?>
            </a>
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
                <p><?= __('Bạn có chắc chắn muốn xóa đơn hàng này? Hành động này không thể hoàn tác.') ?></p>
                <p class="text-danger"><strong><?= __('Cảnh báo') ?>:</strong> <?= __('Tất cả chi tiết đơn hàng cũng sẽ bị xóa.') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('Hủy') ?></button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger"><?= __('Xóa') ?></a>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        document.getElementById('confirmDeleteBtn').href = '/project1/admin/order/delete/' + id;
    }
    
    // Chỉ xử lý hiện modal xác nhận xóa
    document.addEventListener('DOMContentLoaded', function() {
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?>