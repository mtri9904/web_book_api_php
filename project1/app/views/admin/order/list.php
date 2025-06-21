<?php
$activePage = 'order';
$pageTitle = __('Quản lý đơn hàng');
ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold">
                <i class="bi bi-cart3 me-2"></i><?= __('Danh sách đơn hàng') ?>
            </h2>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (isset($orders) && count($orders) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3"><?= __('ID') ?></th>
                            <th class="py-3"><?= __('Khách hàng') ?></th>
                            <th class="py-3"><?= __('Tổng tiền') ?></th>
                            <th class="py-3"><?= __('Trạng thái') ?></th>
                            <th class="py-3"><?= __('Ngày đặt') ?></th>
                            <th class="py-3 text-center"><?= __('Hành động') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= $order->id ?></td>
                                <td>
                                    <?php if (isset($order->full_name) && !empty($order->full_name)): ?>
                                        <?= htmlspecialchars($order->full_name) ?>
                                    <?php elseif (isset($order->name) && !empty($order->name)): ?>
                                        <?= htmlspecialchars($order->name) ?>
                                    <?php else: ?>
                                        <span class="text-muted"><?= __('Khách vãng lai') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($order->total_amount)): ?>
                                        <?= number_format($order->total_amount) ?> VND
                                    <?php elseif (isset($order->total)): ?>
                                        <?= number_format($order->total) ?> VND
                                    <?php else: ?>
                                        0 VND
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    $status = isset($order->status) ? $order->status : 'unknown';
                                    $badgeClass = 'bg-success';
                                    $statusText = __('Thành công');
                                    
                                    // Always show as success regardless of actual status
                                    /*
                                    switch ($status) {
                                        case 'pending':
                                            $badgeClass = 'bg-warning';
                                            $statusText = __('Đang chờ');
                                            break;
                                        case 'processing':
                                            $badgeClass = 'bg-info';
                                            $statusText = __('Đang xử lý');
                                            break;
                                        case 'shipped':
                                            $badgeClass = 'bg-primary';
                                            $statusText = __('Đã giao');
                                            break;
                                        case 'delivered':
                                            $badgeClass = 'bg-success';
                                            $statusText = __('Đã giao hàng');
                                            break;
                                        case 'cancelled':
                                            $badgeClass = 'bg-danger';
                                            $statusText = __('Đã hủy');
                                            break;
                                    }
                                    */
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                                </td>
                                <td>
                                    <?php if (isset($order->created_at)): ?>
                                        <?= date('d/m/Y H:i', strtotime($order->created_at)) ?>
                                    <?php else: ?>
                                        <span class="text-muted"><?= __('Không xác định') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="/project1/admin/order/show/<?= $order->id ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="<?= __('Xem chi tiết') ?>">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="javascript:void(0)" onclick="confirmDelete(<?= $order->id ?>)" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="<?= __('Xóa') ?>">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info m-3">
                <i class="bi bi-info-circle me-2"></i> <?= __('Không tìm thấy đơn hàng nào.') ?>
            </div>
        <?php endif; ?>
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
                <?= __('Bạn có chắc chắn muốn xóa đơn hàng này? Hành động này không thể hoàn tác.') ?>
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
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?>