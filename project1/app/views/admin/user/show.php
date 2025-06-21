<?php
$activePage = 'user';
$pageTitle = __('Chi tiết người dùng');
ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="text-white mb-0">
                    <i class="bi bi-person-badge me-2"></i><?= __('Chi tiết người dùng') ?>: <?= htmlspecialchars($user->fullname) ?>
                </h3>
            </div>
            <div class="col-auto">
                <a href="/project1/admin/user/edit/<?= $user->id ?>" class="btn btn-warning btn-sm me-2">
                    <i class="bi bi-pencil me-1"></i> <?= __('Sửa') ?>
                </a>
                <a href="/project1/admin/user/list" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> <?= __('Quay lại danh sách') ?>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-person me-2"></i><?= __('Thông tin người dùng') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td><strong><?= __('ID người dùng') ?>:</strong></td>
                                        <td><?= $user->id ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= __('Tên đăng nhập') ?>:</strong></td>
                                        <td><?= htmlspecialchars($user->username ?? __('Không có')) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= __('Họ và tên') ?>:</strong></td>
                                        <td><?= htmlspecialchars($user->fullname ?? __('Không có')) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= __('Email') ?>:</strong></td>
                                        <td><?= htmlspecialchars($user->email ?? __('Không có')) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= __('Vai trò') ?>:</strong></td>
                                        <td>
                                            <?php if ($user->role === 'admin'): ?>
                                                <span class="badge bg-danger"><?= __('Quản trị viên') ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-info"><?= __('Người dùng') ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= __('Trạng thái') ?>:</strong></td>
                                        <td>
                                            <?php if (isset($user->is_active) && $user->is_active == 1): ?>
                                                <span class="badge bg-success"><?= __('Đang hoạt động') ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-danger"><?= __('Đã khóa') ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= __('Ngày đăng ký') ?>:</strong></td>
                                        <td><?= isset($user->created_at) ? date('d/m/Y H:i', strtotime($user->created_at)) : __('Không có') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php if ($_SESSION['user_id'] != $user->id): ?>
                        <div class="mt-3">
                            <a href="javascript:void(0)" onclick="confirmDelete(<?= $user->id ?>)" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i> <?= __('Xóa người dùng') ?>
                            </a>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= __('Bạn không thể xóa tài khoản của chính mình.') ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i><?= __('Thống kê') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="card bg-primary text-white mb-3">
                                    <div class="card-body text-center">
                                        <h3><?= count($orders) ?></h3>
                                        <p class="mb-0"><i class="bi bi-bag me-1"></i> <?= __('Tổng số đơn hàng') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <?php 
                                $totalSpent = 0;
                                foreach ($orders as $order) {
                                    $totalSpent += isset($order->total_amount) ? $order->total_amount : ($order->total ?? 0);
                                }
                                ?>
                                <div class="card bg-success text-white mb-3">
                                    <div class="card-body text-center">
                                        <h3><?= number_format($totalSpent) ?></h3>
                                        <p class="mb-0"><i class="bi bi-currency-exchange me-1"></i> <?= __('Tổng chi tiêu (VND)') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i><?= __('Lịch sử đơn hàng') ?></h5>
            </div>
            <div class="card-body">
                <?php if (count($orders) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th><?= __('Mã đơn hàng') ?></th>
                                    <th><?= __('Ngày đặt') ?></th>
                                    <th><?= __('Trạng thái') ?></th>
                                    <th><?= __('Tổng cộng') ?></th>
                                    <th class="text-center"><?= __('Thao tác') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><span class="fw-medium">#<?= $order->id ?></span></td>
                                        <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                                        <td>
                                            <span class="badge bg-success"><?= __('Thành công') ?></span>
                                        </td>
                                        <td><span class="fw-medium"><?= number_format(isset($order->total_amount) ? $order->total_amount : ($order->total ?? 0)) ?> VND</span></td>
                                        <td class="text-center">
                                            <a href="/project1/admin/order/show/<?= $order->id ?>" class="btn btn-info btn-sm">
                                                <i class="bi bi-eye me-1"></i> <?= __('Xem chi tiết') ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-cart-x display-4 text-muted"></i>
                        <p class="mt-3"><?= __('Người dùng này chưa đặt đơn hàng nào.') ?></p>
                    </div>
                <?php endif; ?>
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
                <p><?= __('Bạn có chắc chắn muốn xóa người dùng này? Hành động này không thể hoàn tác.') ?></p>
                <?php if (count($orders) > 0): ?>
                    <p class="text-danger"><strong><?= __('Cảnh báo') ?>:</strong> <?= __('Người dùng này có') ?> <?= count($orders) ?> <?= __('đơn hàng. Việc xóa người dùng này có thể ảnh hưởng đến lịch sử đơn hàng.') ?></p>
                <?php endif; ?>
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

.empty-state {
    padding: 2rem;
    text-align: center;
}
</style>

<script>
    function confirmDelete(id) {
        document.getElementById('confirmDeleteBtn').href = '/project1/admin/user/delete/' + id;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?>