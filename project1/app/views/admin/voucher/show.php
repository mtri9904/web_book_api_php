<?php
$activePage = 'voucher';
$pageTitle = __('Chi tiết mã giảm giá');
ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <h3 class="text-center font-weight-light my-2 text-white">
            <i class="bi bi-ticket-detailed-fill me-2"></i><?= __('Chi tiết mã giảm giá') ?>
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="row g-0">
            <!-- Left side - Basic information -->
            <div class="col-md-4 border-end">
                <div class="p-4 h-100 d-flex flex-column">
                    <div class="text-center mb-4">
                        <div class="voucher-badge py-2 px-4 mb-3 mx-auto">
                            <span class="fs-4 fw-bold"><?= $voucher->code ?></span>
                        </div>
                        
                        <?php if ($voucher->is_active): ?>
                            <span class="badge bg-success fs-6 px-3 py-2 mb-2">
                                <i class="bi bi-check-circle-fill me-1"></i> <?= __('Hoạt động') ?>
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary fs-6 px-3 py-2 mb-2">
                                <i class="bi bi-x-circle-fill me-1"></i> <?= __('Không hoạt động') ?>
                            </span>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <?php if ($voucher->discount_amount > 0): ?>
                                <div class="discount-value">
                                    <span class="fs-1 fw-bold text-primary"><?= number_format($voucher->discount_amount) ?></span>
                                    <span class="fs-5"><?= __('VND') ?></span>
                                </div>
                                <p class="text-muted"><?= __('Giảm giá cố định') ?></p>
                            <?php else: ?>
                                <div class="discount-value">
                                    <span class="fs-1 fw-bold text-primary"><?= $voucher->discount_percent ?></span>
                                    <span class="fs-5">%</span>
                                </div>
                                <p class="text-muted"><?= __('Giảm giá theo phần trăm') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-card-text me-2"></i><?= __('Mô tả') ?>
                        </h5>
                        <p><?= !empty($voucher->description) ? nl2br($voucher->description) : '<em class="text-muted">'.__('Không có mô tả').'</em>' ?></p>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between">
                            <a href="/project1/admin/voucher/edit/<?= $voucher->id ?>" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> <?= __('Sửa') ?>
                            </a>
                            <a href="/project1/admin/voucher/list" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> <?= __('Quay lại') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right side - Detailed information -->
            <div class="col-md-8">
                <div class="p-4">
                    <div class="row">
                        <!-- Usage information -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i><?= __('Thông tin sử dụng') ?></h5>
                                </div>
                                <div class="card-body">
                                    <?php 
                                    $usedCount = $voucher->current_uses ?? 0;
                                    if ($voucher->max_uses > 0): 
                                        $percentage = ($usedCount / $voucher->max_uses) * 100;
                                        $progressClass = $percentage < 50 ? 'bg-success' : ($percentage < 80 ? 'bg-warning' : 'bg-danger');
                                    ?>
                                    <div class="usage-progress mb-3">
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar <?= $progressClass ?>" role="progressbar" 
                                                style="width: <?= $percentage ?>%" 
                                                aria-valuenow="<?= $percentage ?>" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <small><?= __('Đã sử dụng:') ?> <?= $usedCount ?></small>
                                            <small><?= __('Số lần dùng tối đa') ?>: <?= $voucher->max_uses ?></small>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-info">
                                        <i class="bi bi-infinity"></i> <?= __('Không giới hạn số lần sử dụng') ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($voucher->min_order_amount > 0): ?>
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-1"><?= __('Giá trị đơn hàng tối thiểu') ?></h6>
                                        <p class="fs-5 fw-semibold"><?= number_format($voucher->min_order_amount) ?> <?= __('VND') ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Time period -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i><?= __('Thời gian hiệu lực') ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-1"><?= __('Ngày bắt đầu') ?></h6>
                                        <p class="fs-6">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            <?= date('d/m/Y', strtotime($voucher->start_date)) ?>
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-1"><?= __('Ngày kết thúc') ?></h6>
                                        <p class="fs-6">
                                            <i class="bi bi-calendar-x me-1"></i>
                                            <?= date('d/m/Y', strtotime($voucher->end_date)) ?>
                                        </p>
                                    </div>
                                    
                                    <?php
                                    $now = time();
                                    $startDate = strtotime($voucher->start_date);
                                    $endDate = strtotime($voucher->end_date);
                                    
                                    if ($now < $startDate) {
                                        echo '<div class="alert alert-info mb-0"><i class="bi bi-info-circle-fill me-1"></i> ' . __('Voucher chưa bắt đầu') . '</div>';
                                    } elseif ($now > $endDate) {
                                        echo '<div class="alert alert-secondary mb-0"><i class="bi bi-exclamation-circle-fill me-1"></i> ' . __('Voucher đã hết hạn') . '</div>';
                                    } else {
                                        echo '<div class="alert alert-success mb-0"><i class="bi bi-check-circle-fill me-1"></i> ' . __('Mã giảm giá hiện đang hoạt động') . '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Voucher status -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i><?= __('Trạng thái') ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th class="text-muted" style="width: 40%;">ID</th>
                                                    <td><?= $voucher->id ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted"><?= __('Mã giảm giá') ?></th>
                                                    <td><code><?= $voucher->code ?></code></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted"><?= __('Trạng thái') ?></th>
                                                    <td>
                                                        <?= $voucher->is_active ? 
                                                            '<span class="badge bg-success">'.__('Hoạt động').'</span>' : 
                                                            '<span class="badge bg-secondary">'.__('Không hoạt động').'</span>' ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th class="text-muted" style="width: 40%;"><?= __('Loại giảm giá') ?></th>
                                                    <td>
                                                        <?php if ($voucher->discount_amount > 0): ?>
                                                            <span class="badge bg-primary"><?= __('Giảm giá cố định') ?></span>
                                                        <?php else: ?>
                                                            <span class="badge bg-info"><?= __('Giảm giá theo phần trăm') ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted"><?= __('Giá trị giảm giá') ?></th>
                                                    <td>
                                                        <?php if ($voucher->discount_amount > 0): ?>
                                                            <?= number_format($voucher->discount_amount) ?> <?= __('VND') ?>
                                                        <?php else: ?>
                                                            <?= $voucher->discount_percent ?>%
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted"><?= __('Trạng thái thời gian') ?></th>
                                                    <td>
                                                        <?php
                                                        if ($now < $startDate) {
                                                            echo '<span class="badge bg-warning">'.__('Chưa bắt đầu').'</span>';
                                                        } elseif ($now > $endDate) {
                                                            echo '<span class="badge bg-danger">'.__('Đã hết hạn').'</span>';
                                                        } else {
                                                            echo '<span class="badge bg-success">'.__('Hoạt động').'</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="javascript:void(0)" onclick="confirmDelete(<?= $voucher->id ?>)" class="btn btn-danger">
            <i class="bi bi-trash"></i> <?= __('Xóa mã giảm giá') ?>
        </a>
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
                <p><?= __('Bạn có chắc chắn muốn xóa mã giảm giá này? Hành động này không thể hoàn tác.') ?></p>
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

.voucher-badge {
    background: linear-gradient(135deg, #1a2a44 0%, #293e63 100%);
    color: #fff;
    border-radius: 8px;
    display: inline-block;
    position: relative;
    max-width: 200px;
}

.voucher-badge:before,
.voucher-badge:after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background: #fff;
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
}

.voucher-badge:before {
    left: -10px;
}

.voucher-badge:after {
    right: -10px;
}

.discount-value {
    color: #1a2a44;
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
