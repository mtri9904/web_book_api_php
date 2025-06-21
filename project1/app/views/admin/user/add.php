<?php
$activePage = 'user';
$pageTitle = __('Thêm người dùng mới');
ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold">
                <i class="bi bi-person-plus me-2"></i><?= __('Thêm người dùng mới') ?>
            </h2>
            <a href="/project1/admin/user/list" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> <?= __('Quay lại danh sách') ?>
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger"><?= $errors['general'] ?></div>
        <?php endif; ?>

        <form action="/project1/admin/user/add" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-person me-2"></i><?= __('Thông tin người dùng') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label"><?= __('Họ và tên') ?> *</label>
                                <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= $_POST['name'] ?? '' ?>" required>
                                <?php if (isset($errors['name'])): ?>
                                    <div class="invalid-feedback"><?= $errors['name'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label"><?= __('Địa chỉ email') ?> *</label>
                                <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= $_POST['email'] ?? '' ?>" required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i><?= __('Bảo mật & Vai trò') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label"><?= __('Mật khẩu') ?> *</label>
                                    <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" required>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback"><?= $errors['password'] ?></div>
                                    <?php endif; ?>
                                    <div class="form-text"><?= __('Mật khẩu phải dài ít nhất 6 ký tự.') ?></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="confirm_password" class="form-label"><?= __('Xác nhận mật khẩu') ?> *</label>
                                    <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password" required>
                                    <?php if (isset($errors['confirm_password'])): ?>
                                        <div class="invalid-feedback"><?= $errors['confirm_password'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label"><?= __('Vai trò') ?> *</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="user" <?= (isset($_POST['role']) && $_POST['role'] === 'user') ? 'selected' : '' ?>><?= __('Người dùng') ?></option>
                                    <option value="admin" <?= (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : '' ?>><?= __('Quản trị viên') ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-4 py-2">
                    <i class="bi bi-save me-2"></i> <?= __('Lưu người dùng') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?>