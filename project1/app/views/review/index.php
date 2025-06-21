<?php require_once 'app/views/shares/header.php'; ?>

<div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="bi bi-star-fill me-2"></i>Đánh giá sản phẩm</h3>
        </div>
        
        <div class="card-body">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (empty($reviewableProducts)): ?>
                <div class="text-center p-5">
                    <i class="bi bi-emoji-smile display-1 text-muted"></i>
                    <h4 class="mt-3">Bạn chưa có sản phẩm nào để đánh giá</h4>
                    <p class="text-muted">Hãy mua sắm để có thể đánh giá sản phẩm</p>
                    <a href="/project1/shop/listproduct" class="btn btn-primary mt-3">
                        <i class="bi bi-cart me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            <?php else: ?>
                <h5 class="mb-4">Sản phẩm bạn có thể đánh giá:</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Sản phẩm</th>
                                <th scope="col">Đơn hàng</th>
                                <th scope="col">Ngày mua</th>
                                <th scope="col">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reviewableProducts as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product->name) ?></td>
                                    <td>#<?= $product->order_id ?></td>
                                    <td><?= date('d/m/Y', strtotime($product->order_date)) ?></td>
                                    <td>
                                        <a href="/project1/review/add?product_id=<?= $product->id ?>&order_id=<?= $product->order_id ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-star me-1"></i>Đánh giá
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'app/views/shares/footer.php'; ?> 