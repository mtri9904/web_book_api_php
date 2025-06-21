<?php require_once 'app/views/shares/header.php'; ?>

<div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="bi bi-star-fill me-2"></i><?php echo __('Đánh giá sản phẩm'); ?></h3>
                <a href="/project1/order/show/<?= $orderId ?>" class="btn btn-sm btn-light">
                    <i class="bi bi-arrow-left me-1"></i><?php echo __('Quay lại'); ?>
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="text-center p-3">
                            <?php if (!empty($product->image)): ?>
                                <img src="/project1/<?= $product->image ?>" alt="<?= $product->name ?>" class="img-fluid rounded" style="max-height: 200px;">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                                    <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($product->name) ?></h5>
                            <p class="card-text text-primary"><?= number_format($product->price) ?> <?php echo __('VND'); ?></p>
                            <p class="card-text"><small class="text-muted"><?php echo __('Đơn hàng'); ?> #<?= $orderId ?></small></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <form action="/project1/review/save" method="post">
                        <input type="hidden" name="product_id" value="<?= $product->id ?>">
                        <input type="hidden" name="order_id" value="<?= $orderId ?>">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold"><?php echo __('Đánh giá của bạn:'); ?></label>
                            <div class="rating mb-4">
                                <div class="rating-stars">
                                    <input type="radio" id="star5" name="rating" value="5" required />
                                    <label for="star5" title="<?php echo __('5 sao'); ?>"></label>
                                    
                                    <input type="radio" id="star4" name="rating" value="4" />
                                    <label for="star4" title="<?php echo __('4 sao'); ?>"></label>
                                    
                                    <input type="radio" id="star3" name="rating" value="3" />
                                    <label for="star3" title="<?php echo __('3 sao'); ?>"></label>
                                    
                                    <input type="radio" id="star2" name="rating" value="2" />
                                    <label for="star2" title="<?php echo __('2 sao'); ?>"></label>
                                    
                                    <input type="radio" id="star1" name="rating" value="1" />
                                    <label for="star1" title="<?php echo __('1 sao'); ?>"></label>
                                </div>
                                <div class="rating-text mt-2">
                                    <span id="rating-text"><?php echo __('Vui lòng chọn đánh giá'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold"><?php echo __('Nhận xét của bạn (không bắt buộc):'); ?></label>
                            <textarea class="form-control" id="comment" name="comment" rows="5" placeholder="<?php echo __('Chia sẻ trải nghiệm của bạn về sản phẩm này...'); ?>"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i><?php echo __('Gửi đánh giá'); ?>
                            </button>
                            <a href="/project1/order/show/<?= $orderId ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i><?php echo __('Hủy'); ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.rating-stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
}

.rating-stars input {
    display: none;
}

.rating-stars label {
    cursor: pointer;
    width: 50px;
    height: 50px;
    margin: 0 5px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='%23ddd' class='bi bi-star-fill' viewBox='0 0 16 16'%3E%3Cpath d='M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center;
    background-size: 80%;
    transition: all 0.2s;
}

.rating-stars input:checked ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='%23ffc107' class='bi bi-star-fill' viewBox='0 0 16 16'%3E%3Cpath d='M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z'/%3E%3C/svg%3E");
}

.rating-text {
    color: #6c757d;
    font-size: 16px;
    font-weight: bold;
    margin-top: 10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingInputs = document.querySelectorAll('.rating-stars input');
    const ratingText = document.getElementById('rating-text');
    const ratingTexts = [
        '<?php echo __("Rất không hài lòng"); ?>',
        '<?php echo __("Không hài lòng"); ?>',
        '<?php echo __("Bình thường"); ?>',
        '<?php echo __("Hài lòng"); ?>',
        '<?php echo __("Rất hài lòng"); ?>'
    ];
    
    ratingInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            ratingText.textContent = ratingTexts[input.value - 1];
            ratingText.style.color = '#212529';
        });
    });
});
</script>

<?php require_once 'app/views/shares/footer.php'; ?> 