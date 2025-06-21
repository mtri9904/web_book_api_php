<?php include 'app/views/shares/header.php'; ?>
<section class="vh-100 gradient-custom" style="background: linear-gradient(135deg, #00ffcc 0%, #1a2a44 100%); min-height: 100vh;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0" style="border-radius: 1.5rem; background: rgba(255,255,255,0.97);">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <span style="display:inline-block; background:linear-gradient(135deg,#00ffcc,#1a2a44); color:#fff; width:60px; height:60px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:2rem; margin:auto; box-shadow:0 2px 12px #00ffcc55;">
                                <i class="bi bi-person-plus"></i>
                            </span>
                        </div>
                        <h2 class="fw-bold mb-2 text-uppercase text-dark"><?php echo __('Đăng ký'); ?></h2>
                        <p class="text-muted mb-4"><?php echo __('Tạo tài khoản mới để trải nghiệm dịch vụ tốt nhất'); ?></p>
                        <?php if (isset($errors) && count($errors) > 0): ?>
                            <div class="alert alert-danger text-start py-2 px-3 mb-4" style="border-radius:8px;">
                                <ul class="mb-0 ps-3">
                                    <?php foreach ($errors as $err): ?>
                                        <li><?php echo htmlspecialchars($err); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <form class="user" action="/project1/account/save" method="post" autocomplete="off">
                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="<?php echo __('Tên đăng nhập'); ?>" required>
                                        <label for="username"><i class="bi bi-person"></i> <?php echo __('Tên đăng nhập'); ?></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-lg" id="fullname" name="fullname" placeholder="<?php echo __('Họ và tên'); ?>" required>
                                        <label for="fullname"><i class="bi bi-person-lines-fill"></i> <?php echo __('Họ và tên'); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="<?php echo __('Email'); ?>" required>
                                        <label for="email"><i class="bi bi-envelope"></i> <?php echo __('Email'); ?></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control form-control-lg" id="phone" name="phone" placeholder="<?php echo __('Số điện thoại'); ?>" required>
                                        <label for="phone"><i class="bi bi-telephone"></i> <?php echo __('Số điện thoại'); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control form-control-lg" id="age" name="age" placeholder="<?php echo __('Tuổi'); ?>" min="1" max="120" required>
                                        <label for="age"><i class="bi bi-calendar"></i> <?php echo __('Tuổi'); ?></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <select class="form-select form-select-lg" id="security_question" name="security_question" required>
                                            <option value="" selected disabled><?php echo __('Chọn câu hỏi bảo mật'); ?></option>
                                            <?php foreach ($securityQuestions as $key => $question): ?>
                                                <option value="<?php echo $key; ?>"><?php echo $question; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label for="security_question"><i class="bi bi-shield-lock"></i> <?php echo __('Câu hỏi bảo mật'); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control form-control-lg" id="security_answer" name="security_answer" placeholder="<?php echo __('Câu trả lời bảo mật'); ?>" required>
                                <label for="security_answer"><i class="bi bi-shield-check"></i> <?php echo __('Câu trả lời bảo mật'); ?></label>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="<?php echo __('Mật khẩu'); ?>" required>
                                        <label for="password"><i class="bi bi-lock"></i> <?php echo __('Mật khẩu'); ?></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <input type="password" class="form-control form-control-lg" id="confirmpassword" name="confirmpassword" placeholder="<?php echo __('Xác nhận mật khẩu'); ?>" required>
                                        <label for="confirmpassword"><i class="bi bi-shield-lock"></i> <?php echo __('Xác nhận mật khẩu'); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-3 justify-content-center">
                                <button class="btn btn-primary btn-lg px-4" type="submit" style="font-weight:bold; letter-spacing:1px;"><?php echo __('Đăng ký'); ?></button>
                                <a href="/project1/account/login" class="btn btn-outline-primary btn-lg px-4"><?php echo __('Đăng nhập'); ?></a>
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
.card .form-control:focus, .card .form-select:focus {
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