<?php
$activePage = 'category';
$pageTitle = __('Thêm danh mục mới');
ob_start();
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><?= __('Thêm danh mục mới') ?></span>
        <a href="/project1/admin/category/list" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> <?= __('Quay lại danh sách') ?>
        </a>
    </div>
    <div class="card-body">
        <form action="/project1/admin/category/add" method="POST">
            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger"><?= $errors['general'] ?></div>
            <?php endif; ?>

            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i><?= __('Thông tin danh mục') ?></h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label"><?= __('Tên danh mục') ?> *</label>
                        <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= $_POST['name'] ?? '' ?>" required>
                        <?php if (isset($errors['name'])): ?>
                            <div class="invalid-feedback"><?= $errors['name'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label"><?= __('Mô tả') ?></label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= $_POST['description'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> <?= __('Lưu danh mục') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?> 