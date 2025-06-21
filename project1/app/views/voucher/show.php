<?php include 'app/views/shares/header.php'; ?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <h3 class="text-center font-weight-light my-2 text-white">
            <i class="bi bi-ticket-detailed-fill me-2"></i><?php echo __('Chi tiết voucher'); ?>
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="row g-0">
            <!-- Phần trái - Thông tin cơ bản -->
            <div class="col-md-4 border-end">
                <div class="p-4 h-100 d-flex flex-column">
                    <div class="text-center mb-4">
                        <div class="voucher-badge py-2 px-4 mb-3 mx-auto">
                            <span class="fs-4 fw-bold"><?php echo htmlspecialchars($voucher->code, ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        
                        <?php if ($voucher->is_active): ?>
                            <span class="badge bg-success fs-6 px-3 py-2 mb-2">
                                <i class="bi bi-check-circle-fill me-1"></i> <?php echo __('Đang hoạt động'); ?>
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary fs-6 px-3 py-2 mb-2">
                                <i class="bi bi-x-circle-fill me-1"></i> <?php echo __('Ngừng hoạt động'); ?>
                            </span>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <?php if ($voucher->discount_type == 'fixed'): ?>
                                <div class="discount-value">
                                    <span class="fs-1 fw-bold text-primary"><?php echo number_format($voucher->discount_amount, 0, ',', '.'); ?></span>
                                    <span class="fs-5">VNĐ</span>
                                </div>
                            <?php else: ?>
                                <div class="discount-value">
                                    <span class="fs-1 fw-bold text-primary"><?php echo $voucher->discount_percent; ?></span>
                                    <span class="fs-5">%</span>
                                </div>
                            <?php endif; ?>
                            <p class="text-muted"><?php echo $voucher->discount_type == 'fixed' ? __('Giảm cố định') : __('Giảm theo %'); ?></p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-card-text me-2"></i><?php echo __('Mô tả'); ?>
                        </h5>
                        <p><?php echo !empty($voucher->description) ? htmlspecialchars($voucher->description, ENT_QUOTES, 'UTF-8') : '<em class="text-muted">'.__('Không có mô tả').'</em>'; ?></p>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between">
                            <a href="/project1/voucher/edit/<?php echo $voucher->id; ?>" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> <?php echo __('Sửa'); ?>
                            </a>
                            <a href="/project1/voucher/list" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> <?php echo __('Quay lại'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Phần phải - Thông tin chi tiết -->
            <div class="col-md-8">
                <div class="p-4">
                    <div class="row">
                        <!-- Thông tin sử dụng -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i><?php echo __('Thông tin sử dụng'); ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="usage-progress mb-3">
                                        <?php 
                                        $percentage = ($voucher->current_uses / $voucher->max_uses) * 100;
                                        $progressClass = $percentage < 50 ? 'bg-success' : ($percentage < 80 ? 'bg-warning' : 'bg-danger');
                                        ?>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar <?php echo $progressClass; ?>" role="progressbar" style="width: <?php echo $percentage; ?>%" 
                                                aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <small><?php echo __('Đã sử dụng:'); ?> <?php echo $voucher->current_uses; ?></small>
                                            <small><?php echo __('Tối đa:'); ?> <?php echo $voucher->max_uses; ?></small>
                                        </div>
                                    </div>
                                    
                                    <?php if ($voucher->min_order_amount > 0): ?>
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-1"><?php echo __('Đơn tối thiểu'); ?></h6>
                                        <p class="fs-5 fw-semibold"><?php echo number_format($voucher->min_order_amount, 0, ',', '.'); ?> VNĐ</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thời gian -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i><?php echo __('Thời gian áp dụng'); ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-1"><?php echo __('Bắt đầu'); ?></h6>
                                        <p class="fs-6">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            <?php echo date('d/m/Y', strtotime($voucher->start_date)); ?>
                                        </p>
                                        <p class="fs-6">
                                            <i class="bi bi-clock me-1"></i>
                                            <?php echo date('H:i', strtotime($voucher->start_date)); ?>
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-1"><?php echo __('Kết thúc'); ?></h6>
                                        <p class="fs-6">
                                            <i class="bi bi-calendar-x me-1"></i>
                                            <?php echo date('d/m/Y', strtotime($voucher->end_date)); ?>
                                        </p>
                                        <p class="fs-6">
                                            <i class="bi bi-clock me-1"></i>
                                            <?php echo date('H:i', strtotime($voucher->end_date)); ?>
                                        </p>
                                    </div>
                                    
                                    <?php
                                    $now = new DateTime();
                                    $start = new DateTime($voucher->start_date);
                                    $end = new DateTime($voucher->end_date);
                                    
                                    if ($now < $start) {
                                        echo '<div class="alert alert-info mb-0"><i class="bi bi-info-circle-fill me-1"></i> '.__('Voucher chưa bắt đầu').'</div>';
                                    } elseif ($now > $end) {
                                        echo '<div class="alert alert-secondary mb-0"><i class="bi bi-exclamation-circle-fill me-1"></i> '.__('Voucher đã hết hạn').'</div>';
                                    } else {
                                        echo '<div class="alert alert-success mb-0"><i class="bi bi-check-circle-fill me-1"></i> '.__('Voucher đang có hiệu lực').'</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thông tin chi tiết -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i><?php echo __('Thông tin chi tiết'); ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th class="text-muted" style="width: 40%;"><?php echo __('ID'); ?></th>
                                                    <td><?php echo $voucher->id; ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted"><?php echo __('Ngày tạo'); ?></th>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($voucher->created_at)); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th class="text-muted" style="width: 40%;"><?php echo __('Cập nhật lần cuối'); ?></th>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($voucher->updated_at)); ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted"><?php echo __('Trạng thái'); ?></th>
                                                    <td>
                                                        <?php echo $voucher->is_active ? 
                                                            '<span class="badge bg-success">'.__('Đang hoạt động').'</span>' : 
                                                            '<span class="badge bg-secondary">'.__('Ngừng hoạt động').'</span>'; ?>
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

<?php include 'app/views/shares/footer.php'; ?>