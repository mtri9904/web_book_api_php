<?php include 'app/views/shares/header.php'; ?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <h3 class="text-center font-weight-light my-2 text-white">
            <i class="bi bi-tags-fill me-2"></i><?php echo __('Thêm voucher mới'); ?>
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

        <form method="POST" action="/project1/voucher/save" id="voucherForm" class="needs-validation" novalidate>
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
                                    <input type="text" id="code" name="code" class="form-control" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="generateRandomCode()">
                                        <i class="bi bi-shuffle"></i> <?php echo __('Tạo mã'); ?>
                                    </button>
                                </div>
                                <div class="form-text"><?php echo __('Mã voucher độc nhất để khách hàng sử dụng'); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">
                                    <i class="bi bi-card-text me-1"></i><?php echo __('Mô tả:'); ?>
                                </label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="<?php echo __('Nhập mô tả chi tiết về voucher...'); ?>"></textarea>
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
                                        <input type="datetime-local" id="start_date" name="start_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label fw-bold">
                                            <i class="bi bi-calendar-minus me-1"></i><?php echo __('Ngày kết thúc:'); ?>
                                        </label>
                                        <input type="datetime-local" id="end_date" name="end_date" class="form-control" required>
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
                                    <option value="fixed"><?php echo __('Giảm cố định'); ?></option>
                                    <option value="percent"><?php echo __('Giảm theo %'); ?></option>
                                </select>
                            </div>
                            
                            <div class="mb-3" id="fixed_amount_group" style="display:none;">
                                <label for="discount_amount" class="form-label fw-bold">
                                    <i class="bi bi-cash me-1"></i><?php echo __('Giảm giá cố định (VNĐ):'); ?>
                                </label>
                                <div class="input-group">
                                    <input type="number" id="discount_amount" name="discount_amount" class="form-control" min="0" step="1000">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                            
                            <div class="mb-3" id="percent_group" style="display:none;">
                                <label for="discount_percent" class="form-label fw-bold">
                                    <i class="bi bi-percent me-1"></i><?php echo __('Giảm giá (%):'); ?>
                                </label>
                                <div class="input-group">
                                    <input type="number" id="discount_percent" name="discount_percent" class="form-control" min="0" max="100" step="1">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="min_order_amount" class="form-label fw-bold">
                                    <i class="bi bi-cart-check me-1"></i><?php echo __('Đơn tối thiểu (VNĐ):'); ?>
                                </label>
                                <div class="input-group">
                                    <input type="number" id="min_order_amount" name="min_order_amount" class="form-control" min="0" step="1000">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                <div class="form-text"><?php echo __('Giá trị đơn hàng tối thiểu để áp dụng voucher'); ?></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="max_uses" class="form-label fw-bold">
                                    <i class="bi bi-arrow-repeat me-1"></i><?php echo __('Số lần sử dụng tối đa:'); ?>
                                </label>
                                <input type="number" id="max_uses" name="max_uses" class="form-control" min="1" value="1" required>
                                <div class="form-text"><?php echo __('Số lần voucher có thể được sử dụng'); ?></div>
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
                    <i class="bi bi-save"></i> <?php echo __('Thêm voucher'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('discount_type').addEventListener('change', function() {
    var type = this.value;
    document.getElementById('fixed_amount_group').style.display = (type === 'fixed') ? 'block' : 'none';
    document.getElementById('percent_group').style.display = (type === 'percent') ? 'block' : 'none';
});

// Kiểm tra ngày kết thúc > ngày bắt đầu
document.getElementById('voucherForm').addEventListener('submit', function(e) {
    var start = document.getElementById('start_date').value;
    var end = document.getElementById('end_date').value;
    if (start && end && start >= end) {
        alert('<?php echo __('Ngày kết thúc phải lớn hơn ngày bắt đầu!'); ?>');
        e.preventDefault();
    }
});

// Tạo mã voucher ngẫu nhiên
function generateRandomCode() {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = '';
    for (let i = 0; i < 8; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    document.getElementById('code').value = result;
}

// Set default dates
window.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    const nextMonth = new Date();
    nextMonth.setMonth(now.getMonth() + 1);
    
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    startDateInput.value = now.toISOString().slice(0, 16);
    endDateInput.value = nextMonth.toISOString().slice(0, 16);
});
</script>

<style>
.bg-gradient-primary-to-secondary {
    background: linear-gradient(135deg, #1a2a44 0%, #293e63 100%);
}
</style>

<?php include 'app/views/shares/footer.php'; ?>