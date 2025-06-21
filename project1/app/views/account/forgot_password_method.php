<?php include 'app/views/shares/header.php'; ?>
<section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0" style="border-radius: 1.5rem; background: rgba(255,255,255,0.97);">
                    <div class="card-body p-5 text-center">
                        <h2 class="fw-bold mb-2 text-uppercase text-dark"><?php echo __('Quên mật khẩu'); ?></h2>
                        <p class="text-muted mb-4"><?php echo __('Chọn phương thức lấy lại mật khẩu'); ?></p>
                        
                        <div class="d-grid gap-3">
                            <a href="/project1/account/forgotpassword" class="btn btn-primary p-3 rounded-lg">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-bag-check fs-2"></i>
                                    </div>
                                    <div class="text-start">
                                        <h5 class="mb-0"><?php echo __('Xác thực bằng sản phẩm đã mua'); ?></h5>
                                        <small><?php echo __('Chọn 1 sản phẩm bạn đã từng mua để xác thực'); ?></small>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="/project1/account/forgotpasswordbysecurityquestion" class="btn btn-outline-primary p-3 rounded-lg">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-shield-lock fs-2"></i>
                                    </div>
                                    <div class="text-start">
                                        <h5 class="mb-0"><?php echo __('Xác thực bằng câu hỏi bảo mật'); ?></h5>
                                        <small><?php echo __('Trả lời câu hỏi bảo mật bạn đã thiết lập'); ?></small>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="/project1/account/login" class="btn btn-outline-secondary"><?php echo __('Quay lại đăng nhập'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'app/views/shares/footer.php'; ?> 