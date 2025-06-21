<?php
$activePage = 'user';
$pageTitle = __('Sửa người dùng');
ob_start();
?>

<style>
    /* Hiển thị/ẩn label cho trạng thái tài khoản */
    .active-text {
        display: none;
    }
    .inactive-text {
        display: inline;
    }
    .form-check-input:checked ~ .form-check-label .active-text {
        display: inline;
    }
    .form-check-input:checked ~ .form-check-label .inactive-text {
        display: none;
    }
</style>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold">
                <i class="bi bi-pencil-square me-2"></i><?= __('Sửa người dùng') ?>: <?= $user->fullname ?>
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

        <form action="/project1/admin/user/edit/<?= $user->id ?>" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-person me-2"></i><?= __('Thông tin người dùng') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label"><?= __('Họ và tên') ?> *</label>
                                <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= $_POST['name'] ?? $user->fullname ?>" required>
                                <?php if (isset($errors['name'])): ?>
                                    <div class="invalid-feedback"><?= $errors['name'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label"><?= __('Địa chỉ email') ?> *</label>
                                <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= $_POST['email'] ?? $user->email ?>" required>
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
                            <div class="mb-3">
                                <label for="password" class="form-label"><?= __('Mật khẩu') ?></label>
                                <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password">
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?= $errors['password'] ?></div>
                                <?php endif; ?>
                                <div class="form-text"><?= __('Để trống để giữ mật khẩu hiện tại. Mật khẩu mới phải dài ít nhất 6 ký tự.') ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label"><?= __('Vai trò') ?> *</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="user" <?= ((isset($_POST['role']) && $_POST['role'] === 'user') || $user->role === 'user') ? 'selected' : '' ?>><?= __('Người dùng') ?></option>
                                    <option value="admin" <?= ((isset($_POST['role']) && $_POST['role'] === 'admin') || $user->role === 'admin') ? 'selected' : '' ?>><?= __('Quản trị viên') ?></option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><?= __('Trạng thái tài khoản') ?></label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= ((isset($_POST['is_active']) && $_POST['is_active'] == '1') || (isset($user->is_active) && $user->is_active == true)) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_active">
                                        <span class="text-success active-text"><?= __('Đang hoạt động') ?></span>
                                        <span class="text-danger inactive-text"><?= __('Đã khóa') ?></span>
                                    </label>
                                </div>
                                <div class="form-text"><?= __('Tài khoản bị khóa sẽ không thể đăng nhập vào hệ thống.') ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i><?= __('Lịch sử tài khoản') ?></h5>
                        </div>
                        <div class="card-body">
                            <p><strong><?= __('Ngày đăng ký') ?>:</strong> <?= date('d/m/Y H:i', strtotime($user->created_at)) ?></p>
                            <?php if (!empty($user->last_login)): ?>
                                <p class="mb-0"><strong><?= __('Lần đăng nhập cuối') ?>:</strong> <?= date('d/m/Y H:i', strtotime($user->last_login)) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-4 py-2">
                    <i class="bi bi-save me-2"></i> <?= __('Cập nhật người dùng') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?>