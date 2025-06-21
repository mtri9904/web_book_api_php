<?php include 'app/views/shares/header.php'; ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-3 overflow-hidden product-detail" data-product-id="<?php echo $product->id; ?>">
                <div class="row g-0">
                    <div class="col-md-5">
                        <?php if ($product->image): ?>
                            <div class="product-image-container" style="height: 100%; min-height: 400px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                <img src="/project1/<?php echo htmlspecialchars($product->image); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product->name); ?>" style="max-height: 100%; width: auto; object-fit: contain;">
                            </div>
                        <?php else: ?>
                            <div class="product-image-container bg-light d-flex align-items-center justify-content-center" style="height: 100%; min-height: 400px;">
                                <div class="text-muted">
                                    <i class="bi bi-image" style="font-size: 4rem;"></i>
                                    <p class="mt-2"><?php echo __('Không có hình ảnh'); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-7">
                        <div class="card-body p-4 h-100 d-flex flex-column">
                            <h2 class="card-title fw-bold mb-3"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h2>
                            
                            <div class="mb-3 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-4 fw-bold text-primary"><?php echo number_format($product->price, 0, ',', '.'); ?> <?php echo __('VND'); ?></span>
                                    <span class="badge bg-info text-dark"><?php echo isset($product->category_name) ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : ''; ?></span>
                                </div>
                            </div>
                            
                            <?php if (isset($product->average_rating)): ?>
                                <div class="product-rating mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="stars-outer me-2">
                                            <div class="stars-inner" style="width: <?= ($product->average_rating / 5) * 100 ?>%"></div>
                                        </div>
                                        <div>
                                            <span class="fw-bold"><?= number_format($product->average_rating, 1) ?></span>
                                            <span class="text-muted">(<?= $product->review_count ?? 0 ?> đánh giá)</span>
                                            <a href="/project1/review/product?id=<?= $product->id ?>" class="ms-2 text-decoration-none">Xem đánh giá</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mb-4">
                                <h5 class="fw-bold mb-2"><?php echo __('Mô tả:'); ?></h5>
                                <div class="p-3 bg-white border rounded">
                                    <?php if (!empty($product->description)): ?>
                                        <p class="card-text"><?php echo nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?></p>
                                    <?php else: ?>
                                        <p class="text-muted fst-italic"><?php echo __('Không có mô tả'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mt-auto">
                                <div class="d-flex gap-2">
                                    <a href="/project1/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning">
                                        <i class="bi bi-pencil-square"></i> <?php echo __('Sửa'); ?>
                                    </a>
                                    <a href="/project1/Product/list" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> <?php echo __('Quay lại danh sách'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stars-outer {
    display: inline-block;
    position: relative;
    font-family: "bootstrap-icons";
    color: #ddd;
}

.stars-outer::before {
    content: "\F586\F586\F586\F586\F586";
}

.stars-inner {
    position: absolute;
    top: 0;
    left: 0;
    white-space: nowrap;
    overflow: hidden;
    color: #ffc107;
}

.stars-inner::before {
    content: "\F586\F586\F586\F586\F586";
}
</style>

<?php include 'app/views/shares/footer.php'; ?>