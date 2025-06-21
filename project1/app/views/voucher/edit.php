<?php include 'app/views/shares/header.php'; ?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <h3 class="text-center font-weight-light my-2 text-white">
            <i class="bi bi-pencil-square me-2"></i><?php echo __('Sửa voucher'); ?>
        </h3>
    </div>
    <div class="card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/project1/voucher/update" id="voucherForm" class="needs-validation" novalidate>
            <input type="hidden" name="id" value="<?php echo $voucher->id; ?>">
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i><?php echo __('Thông tin cơ bản'); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="code" class="form-label fw-bold">
                                    <i class="bi bi-upc me-1"></i><?php echo __('Mã voucher:'); ?>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <input type="text" id="code" name="code" class="form-control" value="<?php echo htmlspecialchars($voucher->code, ENT_QUOTES, 'UTF-8'); ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">
                                    <i class="bi bi-card-text me-1"></i><?php echo __('Mô tả:'); ?>
                                </label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="<?php echo __('Nhập mô tả chi tiết về voucher...'); ?>"><?php echo htmlspecialchars($voucher->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i><?php echo __('Thời gian áp dụng'); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label fw-bold">
                                            <i class="bi bi-calendar-plus me-1"></i><?php echo __('Ngày bắt đầu:'); ?>
                                        </label>
                                        <input type="datetime-local" id="start_date" name="start_date" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($voucher->start_date)); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label fw-bold">
                                            <i class="bi bi-calendar-minus me-1"></i><?php echo __('Ngày kết thúc:'); ?>
                                        </label>
                                        <input type="datetime-local" id="end_date" name="end_date" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($voucher->end_date)); ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-percent me-2"></i><?php echo __('Thông tin giảm giá'); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="discount_type" class="form-label fw-bold">
                                    <i class="bi bi-coin me-1"></i><?php echo __('Loại giảm giá:'); ?>
                                </label>
                                <select id="discount_type" name="discount_type" class="form-select" required>
                                    <option value=""><?php echo __('-- Chọn loại giảm giá --'); ?></option>
                                    <option value="fixed" <?php if($voucher->discount_type=='fixed') echo 'selected'; ?>><?php echo __('Giảm cố định'); ?></option>
                                    <option value="percent" <?php if($voucher->discount_type=='percent') echo 'selected'; ?>><?php echo __('Giảm theo %'); ?></option>
                                </select>
                            </div>
                            
                            <div class="mb-3" id="fixed_amount_group" style="display:none;">
                                <label for="discount_amount" class="form-label fw-bold">
                                    <i class="bi bi-cash me-1"></i><?php echo __('Giảm giá cố định (VNĐ):'); ?>
                                </label>
                                <div class="input-group">
                                    <input type="number" id="discount_amount" name="discount_amount" class="form-control" min="0" step="1000" value="<?php echo $voucher->discount_amount; ?>">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                            
                            <div class="mb-3" id="percent_group" style="display:none;">
                                <label for="discount_percent" class="form-label fw-bold">
                                    <i class="bi bi-percent me-1"></i><?php echo __('Giảm giá (%):'); ?>
                                </label>
                                <div class="input-group">
                                    <input type="number" id="discount_percent" name="discount_percent" class="form-control" min="0" max="100" step="1" value="<?php echo $voucher->discount_percent; ?>">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="min_order_amount" class="form-label fw-bold">
                                    <i class="bi bi-cart-check me-1"></i><?php echo __('Đơn tối thiểu (VNĐ):'); ?>
                                </label>
                                <div class="input-group">
                                    <input type="number" id="min_order_amount" name="min_order_amount" class="form-control" min="0" step="1000" value="<?php echo $voucher->min_order_amount; ?>">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                <div class="form-text"><?php echo __('Giá trị đơn hàng tối thiểu để áp dụng voucher'); ?></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="max_uses" class="form-label fw-bold">
                                    <i class="bi bi-arrow-repeat me-1"></i><?php echo __('Số lần sử dụng tối đa:'); ?>
                                </label>
                                <input type="number" id="max_uses" name="max_uses" class="form-control" min="1" value="<?php echo $voucher->max_uses; ?>" required>
                                <div class="form-text"><?php echo __('Số lần voucher có thể được sử dụng'); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i><?php echo __('Thông tin sử dụng'); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <?php echo __('Đã sử dụng:'); ?> <strong><?php echo $voucher->current_uses ?? 0; ?>/<?php echo $voucher->max_uses; ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <a href="/project1/voucher/list" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> <?php echo __('Quay lại danh sách'); ?>
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> <?php echo __('Lưu thay đổi'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showDiscountFields() {
    var type = document.getElementById('discount_type').value;
    document.getElementById('fixed_amount_group').style.display = (type === 'fixed') ? 'block' : 'none';
    document.getElementById('percent_group').style.display = (type === 'percent') ? 'block' : 'none';
}
document.getElementById('discount_type').addEventListener('change', showDiscountFields);
showDiscountFields();

document.getElementById('voucherForm').addEventListener('submit', function(e) {
    var start = document.getElementById('start_date').value;
    var end = document.getElementById('end_date').value;
    if (start && end && start >= end) {
        alert('<?php echo __('Ngày kết thúc phải lớn hơn ngày bắt đầu!'); ?>');
        e.preventDefault();
    }
});
</script>

<style>
.bg-gradient-primary-to-secondary {
    background: linear-gradient(135deg, #1a2a44 0%, #293e63 100%);
}
</style>

<?php include 'app/views/shares/footer.php'; ?>