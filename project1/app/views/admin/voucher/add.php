<?php
$activePage = 'voucher';
$pageTitle = __('Thêm mã giảm giá mới');
ob_start();
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><?= __('Thêm mã giảm giá mới') ?></span>
        <a href="/project1/admin/voucher/list" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> <?= __('Quay lại danh sách') ?>
        </a>
    </div>
    <div class="card-body">
        <form action="/project1/admin/voucher/add" method="POST">
            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger"><?= $errors['general'] ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-ticket-perforated me-2"></i><?= __('Thông tin mã giảm giá') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="code" class="form-label"><?= __('Mã giảm giá') ?> *</label>
                                <input type="text" class="form-control <?= isset($errors['code']) ? 'is-invalid' : '' ?>" id="code" name="code" value="<?= $_POST['code'] ?? '' ?>" required>
                                <?php if (isset($errors['code'])): ?>
                                    <div class="invalid-feedback"><?= $errors['code'] ?></div>
                                <?php endif; ?>
                                <div class="form-text"><?= __('Nhập mã duy nhất cho voucher này (ví dụ: SUMMER2023)') ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label"><?= __('Mô tả') ?></label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= $_POST['description'] ?? '' ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-percent me-2"></i><?= __('Thông tin giảm giá') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label"><?= __('Loại giảm giá') ?> *</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="discount_type" id="discount_type_amount" value="amount" <?= (!isset($_POST['discount_type']) || $_POST['discount_type'] === 'amount') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="discount_type_amount">
                                        <?= __('Giảm giá cố định') ?> (VND)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="discount_type" id="discount_type_percent" value="percent" <?= (isset($_POST['discount_type']) && $_POST['discount_type'] === 'percent') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="discount_type_percent">
                                        <?= __('Giảm giá theo phần trăm') ?> (%)
                                    </label>
                                </div>
                            </div>

                            <div id="amount_discount" class="mb-3">
                                <label for="discount_amount" class="form-label"><?= __('Số tiền giảm giá') ?> (VND) *</label>
                                <input type="number" class="form-control <?= isset($errors['discount_amount']) ? 'is-invalid' : '' ?>" id="discount_amount" name="discount_amount" value="<?= $_POST['discount_amount'] ?? '0' ?>">
                                <?php if (isset($errors['discount_amount'])): ?>
                                    <div class="invalid-feedback"><?= $errors['discount_amount'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div id="percent_discount" class="mb-3 d-none">
                                <label for="discount_percent" class="form-label"><?= __('Phần trăm giảm giá') ?> (%) *</label>
                                <input type="number" class="form-control <?= isset($errors['discount_percent']) ? 'is-invalid' : '' ?>" id="discount_percent" name="discount_percent" min="0" max="100" value="<?= $_POST['discount_percent'] ?? '0' ?>">
                                <?php if (isset($errors['discount_percent'])): ?>
                                    <div class="invalid-feedback"><?= $errors['discount_percent'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-calendar-range me-2"></i><?= __('Thời gian áp dụng') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="min_order_amount" class="form-label"><?= __('Giá trị đơn hàng tối thiểu') ?> (VND)</label>
                                <input type="number" class="form-control" id="min_order_amount" name="min_order_amount" value="<?= $_POST['min_order_amount'] ?? '0' ?>">
                                <div class="form-text"><?= __('Đặt là 0 nếu không yêu cầu đơn hàng tối thiểu') ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="start_date" class="form-label"><?= __('Ngày bắt đầu') ?> *</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $_POST['start_date'] ?? date('Y-m-d') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="end_date" class="form-label"><?= __('Ngày kết thúc') ?> *</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $_POST['end_date'] ?? date('Y-m-d', strtotime('+30 days')) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="max_uses" class="form-label"><?= __('Số lần sử dụng tối đa') ?></label>
                                <input type="number" class="form-control" id="max_uses" name="max_uses" value="<?= $_POST['max_uses'] ?? '0' ?>">
                                <div class="form-text"><?= __('Đặt là 0 để không giới hạn số lần sử dụng') ?></div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?= (!isset($_POST['is_active']) || $_POST['is_active']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active"><?= __('Kích hoạt') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> <?= __('Lưu mã giảm giá') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle discount type fields
    document.addEventListener('DOMContentLoaded', function() {
        const amountRadio = document.getElementById('discount_type_amount');
        const percentRadio = document.getElementById('discount_type_percent');
        const amountField = document.getElementById('amount_discount');
        const percentField = document.getElementById('percent_discount');
        
        function toggleDiscountFields() {
            if (amountRadio.checked) {
                amountField.classList.remove('d-none');
                percentField.classList.add('d-none');
            } else {
                amountField.classList.add('d-none');
                percentField.classList.remove('d-none');
            }
        }
        
        toggleDiscountFields();
        
        amountRadio.addEventListener('change', toggleDiscountFields);
        percentRadio.addEventListener('change', toggleDiscountFields);
    });
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?> 