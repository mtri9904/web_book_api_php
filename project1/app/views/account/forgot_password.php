<?php include 'app/views/shares/header.php'; ?>
<section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0" style="border-radius: 1.5rem; background: rgba(255,255,255,0.97);">
                    <div class="card-body p-5 text-center">
                        <h2 class="fw-bold mb-2 text-uppercase text-dark"><?php echo __('Quên mật khẩu'); ?></h2>
                        <p class="text-muted mb-4"><?php echo __('Nhập tên đăng nhập hoặc email để tiếp tục'); ?></p>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger text-start py-2 px-3 mb-4" style="border-radius:8px;"> <?php echo $error; ?> </div>
                        <?php endif; ?>
                        <form action="" method="post" autocomplete="off">
                            <div class="form-floating mb-4">
                                <input type="text" name="username_or_email" class="form-control form-control-lg" id="username_or_email" placeholder="<?php echo __('Tên đăng nhập hoặc Email'); ?>" required autofocus>
                                <label for="username_or_email"><i class="bi bi-person"></i> <?php echo __('Tên đăng nhập hoặc Email'); ?></label>
                            </div>
                            <button class="btn btn-primary btn-lg w-100 mb-3" type="submit" style="font-weight:bold; letter-spacing:1px;"><?php echo __('Tiếp tục'); ?></button>
                            <a href="/project1/account/login" class="btn btn-outline-secondary w-100"><?php echo __('Quay lại đăng nhập'); ?></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'app/views/shares/footer.php'; ?> 