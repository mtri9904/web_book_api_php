<?php
$activePage = 'voucher';
$pageTitle = __('Sửa mã giảm giá');
ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <h3 class="text-center font-weight-light my-2 text-white">
            <i class="bi bi-pencil-square me-2"></i><?= __('Sửa mã giảm giá') ?>
        </h3>
    </div>
    <div class="card-body">
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <h5 class="alert-heading mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= __('Lỗi!') ?></h5>
                <div><?= $errors['general'] ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="/project1/admin/voucher/edit/<?= $voucher->id ?>" id="voucherForm" class="needs-validation" novalidate>
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i><?= __('Thông tin cơ bản') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="code" class="form-label fw-bold">
                                    <i class="bi bi-upc me-1"></i><?= __('Mã giảm giá') ?>:
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <input type="text" id="code" name="code" class="form-control <?= isset($errors['code']) ? 'is-invalid' : '' ?>" 
                                           value="<?= $_POST['code'] ?? $voucher->code ?>" required>
                                </div>
                                <?php if (isset($errors['code'])): ?>
                                    <div class="invalid-feedback d-block"><?= $errors['code'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">
                                    <i class="bi bi-card-text me-1"></i><?= __('Mô tả') ?>:
                                </label>
                                <textarea id="description" name="description" class="form-control" rows="3" 
                                          placeholder="<?= __('Nhập mô tả chi tiết về voucher...') ?>"><?= $_POST['description'] ?? $voucher->description ?></textarea>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?= (isset($_POST['is_active']) || $voucher->is_active) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="is_active">
                                    <i class="bi bi-toggle-on me-1"></i><?= __('Kích hoạt') ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i><?= __('Thời gian áp dụng') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label fw-bold">
                                            <i class="bi bi-calendar-plus me-1"></i><?= __('Ngày bắt đầu') ?>:
                                        </label>
                                        <input type="date" id="start_date" name="start_date" class="form-control" 
                                               value="<?= $_POST['start_date'] ?? date('Y-m-d', strtotime($voucher->start_date)) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label fw-bold">
                                            <i class="bi bi-calendar-minus me-1"></i><?= __('Ngày kết thúc') ?>:
                                        </label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" 
                                               value="<?= $_POST['end_date'] ?? date('Y-m-d', strtotime($voucher->end_date)) ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-percent me-2"></i><?= __('Thông tin giảm giá') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_amount" class="form-label fw-bold">
                                            <i class="bi bi-cash me-1"></i><?= __('Giảm giá cố định') ?> (<?= __('VND') ?>):
                                        </label>
                                        <div class="input-group">
                                            <input type="number" id="discount_amount" name="discount_amount" 
                                                   class="form-control <?= isset($errors['discount_amount']) ? 'is-invalid' : '' ?>" 
                                                   min="0" step="1000" value="<?= $_POST['discount_amount'] ?? $voucher->discount_amount ?>">
                                            <span class="input-group-text"><?= __('VND') ?></span>
                                        </div>
                                        <?php if (isset($errors['discount_amount'])): ?>
                                            <div class="invalid-feedback d-block"><?= $errors['discount_amount'] ?></div>
                                        <?php else: ?>
                                            <div class="form-text"><?= __('Đặt 0 nếu sử dụng giảm giá theo phần trăm') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_percent" class="form-label fw-bold">
                                            <i class="bi bi-percent me-1"></i><?= __('Giảm giá (%)') ?>:
                                        </label>
                                        <div class="input-group">
                                            <input type="number" id="discount_percent" name="discount_percent" 
                                                   class="form-control <?= isset($errors['discount_percent']) ? 'is-invalid' : '' ?>" 
                                                   min="0" max="100" value="<?= $_POST['discount_percent'] ?? $voucher->discount_percent ?>">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <?php if (isset($errors['discount_percent'])): ?>
                                            <div class="invalid-feedback d-block"><?= $errors['discount_percent'] ?></div>
                                        <?php else: ?>
                                            <div class="form-text"><?= __('Đặt 0 nếu sử dụng giảm giá cố định') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="min_order_amount" class="form-label fw-bold">
                                    <i class="bi bi-cart-check me-1"></i><?= __('Giá trị đơn hàng tối thiểu') ?> (<?= __('VND') ?>):
                                </label>
                                <div class="input-group">
                                    <input type="number" id="min_order_amount" name="min_order_amount" class="form-control" 
                                           min="0" step="1000" value="<?= $_POST['min_order_amount'] ?? $voucher->min_order_amount ?>">
                                    <span class="input-group-text"><?= __('VND') ?></span>
                                </div>
                                <div class="form-text"><?= __('Giá trị đơn hàng tối thiểu để áp dụng voucher') ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="max_uses" class="form-label fw-bold">
                                    <i class="bi bi-arrow-repeat me-1"></i><?= __('Số lần sử dụng tối đa') ?>:
                                </label>
                                <input type="number" id="max_uses" name="max_uses" class="form-control" 
                                       min="1" value="<?= $_POST['max_uses'] ?? $voucher->max_uses ?>">
                                <div class="form-text"><?= __('Số lần voucher có thể được sử dụng') ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i><?= __('Thông tin sử dụng') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <?= __('Đã sử dụng:') ?> 
                                <strong>
                                    <?php 
                                    $currentUses = isset($voucher->current_uses) ? $voucher->current_uses : 0;
                                    echo $currentUses . '/' . $voucher->max_uses; 
                                    ?>
                                </strong>
                            </div>
                            
                            <?php if ($currentUses > 0 && $voucher->max_uses > 0): ?>
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" 
                                     style="width: <?= min(100, ($currentUses / $voucher->max_uses) * 100) ?>%" 
                                     aria-valuenow="<?= $currentUses ?>" aria-valuemin="0" aria-valuemax="<?= $voucher->max_uses ?>">
                                    <?= round(($currentUses / $voucher->max_uses) * 100) ?>%
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-center mt-4 gap-2">
                <button type="submit" class="btn btn-primary px-4 py-2">
                    <i class="bi bi-save me-2"></i><?= __('Lưu thay đổi') ?>
                </button>
                <a href="/project1/admin/voucher/list" class="btn btn-outline-secondary px-4 py-2">
                    <i class="bi bi-arrow-left me-2"></i><?= __('Quay lại danh sách') ?>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('voucherForm').addEventListener('submit', function(e) {
    var start = document.getElementById('start_date').value;
    var end = document.getElementById('end_date').value;
    if (start && end && start >= end) {
        alert('<?= __('Ngày kết thúc phải lớn hơn ngày bắt đầu!') ?>');
        e.preventDefault();
    }
});
</script>

<style>
.bg-gradient-primary-to-secondary {
    background: linear-gradient(135deg, #1a2a44 0%, #293e63 100%);
}
.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
}
</style>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?>