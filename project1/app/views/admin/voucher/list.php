<?php
$activePage = 'voucher';
$pageTitle = __('Quản lý mã giảm giá');
ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="text-white mb-0">
                    <i class="bi bi-ticket-detailed-fill me-2"></i><?= __('Danh sách mã giảm giá') ?>
                </h3>
            </div>
            <div class="col-auto">
                <a href="/project1/admin/voucher/add" class="btn btn-light">
                    <i class="bi bi-plus-circle me-1"></i> <?= __('Thêm mã giảm giá mới') ?>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if (isset($vouchers) && count($vouchers) > 0): ?>
            <div class="row">
                <?php foreach ($vouchers as $voucher): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm voucher-card">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="voucher-badge-small py-1 px-3">
                                        <span class="fw-bold"><?= $voucher->code ?></span>
                                    </div>
                                    <?php if ($voucher->is_active): ?>
                                        <span class="badge bg-success"><?= __('Kích hoạt') ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= __('Không kích hoạt') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <?php if ($voucher->discount_amount > 0): ?>
                                        <div class="discount-value">
                                            <span class="fs-3 fw-bold text-primary"><?= number_format($voucher->discount_amount) ?></span>
                                            <span class="fs-6">VND</span>
                                        </div>
                                        <small class="text-muted"><?= __('Giảm giá cố định') ?></small>
                                    <?php else: ?>
                                        <div class="discount-value">
                                            <span class="fs-3 fw-bold text-primary"><?= $voucher->discount_percent ?></span>
                                            <span class="fs-6">%</span>
                                        </div>
                                        <small class="text-muted"><?= __('Giảm giá theo phần trăm') ?></small>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="text-muted mb-1"><?= __('Mô tả') ?></h6>
                                    <p class="small"><?= !empty($voucher->description) ? (strlen($voucher->description) > 80 ? substr($voucher->description, 0, 80) . '...' : $voucher->description) : '<em class="text-muted">' . __('Không có mô tả') . '</em>' ?></p>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted"><?= __('Thời gian hiệu lực:') ?></small>
                                        <small><?= date('d/m/Y', strtotime($voucher->start_date)) ?> - <?= date('d/m/Y', strtotime($voucher->end_date)) ?></small>
                                    </div>
                                    
                                    <?php if ($voucher->min_order_amount > 0): ?>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted"><?= __('Đơn hàng tối thiểu:') ?></small>
                                        <small><?= number_format($voucher->min_order_amount) ?> VND</small>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($voucher->max_uses > 0): ?>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted"><?= __('Lượt sử dụng:') ?></small>
                                        <small><?= $voucher->current_uses ?? 0 ?> / <?= $voucher->max_uses ?></small>
                                    </div>
                                    
                                    <?php 
                                    $usedCount = $voucher->current_uses ?? 0;
                                    $percentage = ($usedCount / $voucher->max_uses) * 100;
                                    $progressClass = $percentage < 50 ? 'bg-success' : ($percentage < 80 ? 'bg-warning' : 'bg-danger');
                                    ?>
                                    <div class="progress mt-1" style="height: 5px;">
                                        <div class="progress-bar <?= $progressClass ?>" role="progressbar" 
                                            style="width: <?= $percentage ?>%" 
                                            aria-valuenow="<?= $percentage ?>" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100"></div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between">
                                    <a href="/project1/admin/voucher/show/<?= $voucher->id ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> <?= __('Xem') ?>
                                    </a>
                                    <div>
                                        <a href="/project1/admin/voucher/edit/<?= $voucher->id ?>" class="btn btn-sm btn-warning me-1">
                                            <i class="bi bi-pencil"></i> <?= __('Sửa') ?>
                                        </a>
                                        <a href="javascript:void(0)" onclick="confirmDelete(<?= $voucher->id ?>)" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> <?= __('Xóa') ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state text-center py-5">
                <i class="bi bi-ticket-perforated display-4 text-muted"></i>
                <p class="mt-3"><?= __('Không tìm thấy mã giảm giá nào.') ?></p>
                <a href="/project1/admin/voucher/add" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> <?= __('Thêm mã giảm giá mới') ?>
                </a>
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
                <?= __('Bạn có chắc chắn muốn xóa mã giảm giá này? Hành động này không thể hoàn tác.') ?>
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

.voucher-card {
    transition: transform 0.3s, box-shadow 0.3s;
    border-radius: 0.5rem;
    overflow: hidden;
}

.voucher-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}

.voucher-badge-small {
    background: linear-gradient(135deg, #1a2a44 0%, #293e63 100%);
    color: #fff;
    border-radius: 4px;
    display: inline-block;
    position: relative;
}

.discount-value {
    color: #1a2a44;
}

.empty-state {
    padding: 2rem;
    text-align: center;
}
</style>

<script>
    function confirmDelete(id) {
        document.getElementById('confirmDeleteBtn').href = '/project1/admin/voucher/delete/' + id;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?> 