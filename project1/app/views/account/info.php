<?php include 'app/views/shares/header.php'; ?>
<section class="gradient-custom" style="background: linear-gradient(135deg, #00ffcc 0%, #1a2a44 100%); min-height: 100vh; padding: 40px 0;">
    <div class="container py-3">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <div class="card shadow-lg border-0" style="border-radius: 1rem; background: rgba(255,255,255,0.97);">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="avatar-circle mb-3">
                                <span class="avatar-initials"><?php echo substr($account->fullname ?? $account->username, 0, 1); ?></span>
                            </div>
                            <h2 class="fw-bold text-uppercase text-dark"><?php echo __('Thông tin tài khoản'); ?></h2>
                            <p class="text-muted"><?php echo __('Xem và cập nhật thông tin cá nhân'); ?></p>
                        </div>
                        
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success text-start py-2 px-3 mb-4" style="border-radius:8px;"> <?php echo $success; ?> </div>
                        <?php endif; ?>
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger text-start py-2 px-3 mb-4" style="border-radius:8px;">
                                <ul class="mb-0 ps-3">
                                    <?php foreach ($errors as $err): ?>
                                        <li><?php echo htmlspecialchars($err); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <form action="" method="post" autocomplete="off">
                            <div class="form-group mb-3">
                                <label for="username" class="form-label"><i class="bi bi-person-badge"></i> <?php echo __('Tên đăng nhập'); ?></label>
                                <input type="text" class="form-control bg-light" id="username" value="<?php echo htmlspecialchars($account->username); ?>" disabled>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="fullname" class="form-label"><i class="bi bi-person"></i> <?php echo __('Họ và tên'); ?></label>
                                <input type="text" name="fullname" class="form-control" id="fullname" value="<?php echo htmlspecialchars($account->fullname ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="email" class="form-label"><i class="bi bi-envelope"></i> <?php echo __('Email'); ?></label>
                                <input type="email" name="email" class="form-control" id="email" value="<?php echo htmlspecialchars($account->email ?? ''); ?>" required>
                            </div>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                    <div class="form-group">
                                        <label for="age" class="form-label"><i class="bi bi-calendar"></i> <?php echo __('Tuổi'); ?></label>
                                        <input type="number" name="age" class="form-control" id="age" value="<?php echo htmlspecialchars($account->age ?? ''); ?>" min="1" max="120" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label"><i class="bi bi-telephone"></i> <?php echo __('Số điện thoại'); ?></label>
                                        <input type="tel" name="phone" class="form-control" id="phone" value="<?php echo htmlspecialchars($account->phone ?? ''); ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            <h5 class="mb-3 text-secondary"><i class="bi bi-shield-lock"></i> <?php echo __('Đổi mật khẩu'); ?></h5>
                            
                            <div class="form-group mb-3">
                                <label for="password" class="form-label"><?php echo __('Mật khẩu mới'); ?></label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="••••••••">
                                <small class="form-text text-muted"><?php echo __('Bỏ trống nếu không muốn đổi mật khẩu'); ?></small>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="confirm" class="form-label"><?php echo __('Xác nhận mật khẩu mới'); ?></label>
                                <input type="password" name="confirm" class="form-control" id="confirm" placeholder="••••••••">
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-check-circle me-2"></i><?php echo __('Cập nhật'); ?>
                                </button>
                                <a href="/project1" class="btn btn-outline-secondary">
                                    <i class="bi bi-house me-2"></i><?php echo __('Về trang chủ'); ?>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
.gradient-custom {
    background: linear-gradient(135deg, #00ffcc 0%, #1a2a44 100%) !important;
}
.card {
    box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
}
.form-control, .form-select {
    padding: 0.5rem 0.75rem;
    font-size: 1rem;
}
.form-control:focus, .form-select:focus {
    border-color: #00ffcc;
    box-shadow: 0 0 0 2px rgba(0, 255, 204, 0.25);
}
.btn {
    padding: 0.5rem 1rem;
    font-weight: 500;
}
.btn-primary {
    background: linear-gradient(90deg, #00ffcc, #1a2a44);
    border: none;
}
.btn-primary:hover {
    background: linear-gradient(90deg, #1a2a44, #00ffcc);
}
.avatar-circle {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #00ffcc, #1a2a44);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
.avatar-initials {
    color: white;
    font-size: 2rem;
    font-weight: bold;
    text-transform: uppercase;
}
.form-group label {
    font-weight: 500;
    margin-bottom: 0.25rem;
}

/* Responsive fixes */
@media (max-width: 576px) {
    .card-body {
        padding: 1.25rem !important;
    }
    h2 {
        font-size: 1.5rem;
    }
    .avatar-circle {
        width: 60px;
        height: 60px;
    }
    .avatar-initials {
        font-size: 1.75rem;
    }
}
</style>
<?php include 'app/views/shares/footer.php'; ?> 