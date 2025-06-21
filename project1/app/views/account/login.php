<?php include 'app/views/shares/header.php'; ?>
<section class="vh-100 gradient-custom" style="background: linear-gradient(135deg, #00ffcc 0%, #1a2a44 100%); min-height: 100vh;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0" style="border-radius: 1.5rem; background: rgba(255,255,255,0.97);">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <span style="display:inline-block; background:linear-gradient(135deg,#00ffcc,#1a2a44); color:#fff; width:60px; height:60px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:2rem; margin:auto; box-shadow:0 2px 12px #00ffcc55;">
                                <i class="bi bi-person-circle"></i>
                            </span>
                        </div>
                        <h2 class="fw-bold mb-2 text-uppercase text-dark"><?php echo __('Đăng nhập'); ?></h2>
                        <p class="text-muted mb-4"><?php echo __('Vui lòng nhập tài khoản và mật khẩu để tiếp tục'); ?></p>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger text-start py-2 px-3 mb-4" style="border-radius:8px;"> <?php echo $error; ?> </div>
                        <?php endif; ?>
                        <form action="/project1/account/checklogin" method="post" autocomplete="off">
                            <div class="form-floating mb-4">
                                <input type="text" name="username" class="form-control form-control-lg" id="username" placeholder="<?php echo __('Tên đăng nhập'); ?>" required autofocus>
                                <label for="username"><i class="bi bi-person"></i> <?php echo __('Tên đăng nhập'); ?></label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="<?php echo __('Mật khẩu'); ?>" required>
                                <label for="password"><i class="bi bi-lock"></i> <?php echo __('Mật khẩu'); ?></label>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <a href="/project1/account/forgotpasswordmethod" class="text-muted small"><?php echo __('Quên mật khẩu?'); ?></a>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3" style="font-weight:bold; letter-spacing:1px;"><?php echo __('Đăng nhập'); ?></button>
                            </div>
                            <div class="text-center mt-3">
                                <p><?php echo __('Hoặc đăng nhập với:'); ?></p>
                                <a href="/project1/account/google_login" class="btn btn-danger btn-block">
                                    <i class="fab fa-google"></i> <?php echo __('Đăng nhập bằng Google'); ?>
                                </a>
                            </div>
                            <p class="mb-0"><?php echo __('Chưa có tài khoản?'); ?> <a href="/project1/account/register" class="text-primary fw-bold"><?php echo __('Đăng ký ngay'); ?></a></p>
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
.card .form-control:focus {
    border-color: #00ffcc;
    box-shadow: 0 0 0 2px #00ffcc44;
}
.btn-primary {
    background: linear-gradient(90deg, #00ffcc, #1a2a44);
    border: none;
}
.btn-primary:hover {
    background: linear-gradient(90deg, #1a2a44, #00ffcc);
}
</style>
<?php include 'app/views/shares/footer.php'; ?>