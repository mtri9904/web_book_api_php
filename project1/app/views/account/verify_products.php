<?php include 'app/views/shares/header.php'; ?>
<section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0" style="border-radius: 1.5rem; background: rgba(255,255,255,0.97);">
                    <div class="card-body p-5 text-center">
                        <h2 class="fw-bold mb-2 text-uppercase text-dark"><?php echo __('Xác thực sản phẩm đã mua'); ?></h2>
                        <p class="text-muted mb-4"><?php echo __('Chọn đúng 1 sản phẩm bạn đã từng mua'); ?></p>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger text-start py-2 px-3 mb-4" style="border-radius:8px;"> <?php echo $error; ?> </div>
                        <?php endif; ?>
                        <form action="" method="post" autocomplete="off">
                            <div class="mb-4 text-start">
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $product): ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="product" value="<?php echo $product->id; ?>" id="product_<?php echo $product->id; ?>" required>
                                            <label class="form-check-label" for="product_<?php echo $product->id; ?>">
                                                <?php if (!empty($product->image)): ?>
                                                    <img src="<?php echo $product->image ? '/project1/' . htmlspecialchars($product->image) : 'https://via.placeholder.com/32x32?text=No+Image'; ?>" alt="<?php echo htmlspecialchars($product->name); ?>" style="width:32px;height:32px;object-fit:cover;border-radius:4px;margin-right:8px;">
                                                <?php endif; ?>
                                                <?php echo htmlspecialchars($product->name); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="alert alert-warning"><?php echo __('Bạn chưa từng mua sản phẩm nào!'); ?></div>
                                <?php endif; ?>
                            </div>
                            <button class="btn btn-primary btn-lg w-100 mb-3" type="submit" style="font-weight:bold; letter-spacing:1px;"><?php echo __('Xác nhận'); ?></button>
                            <a href="/project1/account/login" class="btn btn-outline-secondary w-100"><?php echo __('Quay lại đăng nhập'); ?></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'app/views/shares/footer.php'; ?> 