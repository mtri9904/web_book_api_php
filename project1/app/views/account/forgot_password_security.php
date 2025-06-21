<?php include 'app/views/shares/header.php'; ?>
<section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0" style="border-radius: 1.5rem; background: rgba(255,255,255,0.97);">
                    <div class="card-body p-5 text-center">
                        <h2 class="fw-bold mb-2 text-uppercase text-dark"><?php echo __('Quên mật khẩu'); ?></h2>
                        <p class="text-muted mb-4"><?php echo __('Xác thực bằng câu hỏi bảo mật'); ?></p>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger text-start py-2 px-3 mb-4" style="border-radius:8px;"> <?php echo $error; ?> </div>
                        <?php endif; ?>
                        
                        <form action="" method="post" autocomplete="off">
                            <?php if (!$showSecurityQuestion): ?>
                                <!-- Bước 1: Nhập username hoặc email -->
                                <div class="form-floating mb-4">
                                    <input type="text" name="username_or_email" class="form-control form-control-lg" id="username_or_email" placeholder="<?php echo __('Tên đăng nhập hoặc Email'); ?>" required autofocus>
                                    <label for="username_or_email"><i class="bi bi-person"></i> <?php echo __('Tên đăng nhập hoặc Email'); ?></label>
                                </div>
                                <button class="btn btn-primary btn-lg w-100 mb-3" type="submit" style="font-weight:bold; letter-spacing:1px;"><?php echo __('Tiếp tục'); ?></button>
                            <?php else: ?>
                                <!-- Bước 2: Trả lời câu hỏi bảo mật -->
                                <div class="alert alert-info mb-4">
                                    <strong><?php echo __('Câu hỏi bảo mật'); ?>:</strong><br>
                                    <?php echo $securityQuestions[$question] ?? $question; ?>
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="text" name="security_answer" class="form-control form-control-lg" id="security_answer" placeholder="<?php echo __('Câu trả lời bảo mật'); ?>" required autofocus>
                                    <label for="security_answer"><i class="bi bi-shield-check"></i> <?php echo __('Câu trả lời bảo mật'); ?></label>
                                </div>
                                <button class="btn btn-primary btn-lg w-100 mb-3" type="submit" style="font-weight:bold; letter-spacing:1px;"><?php echo __('Xác nhận'); ?></button>
                            <?php endif; ?>
                            
                            <a href="/project1/account/forgotpasswordmethod" class="btn btn-outline-secondary w-100"><?php echo __('Quay lại'); ?></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'app/views/shares/footer.php'; ?> 