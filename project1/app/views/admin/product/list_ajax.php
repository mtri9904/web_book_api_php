<div id="productResults">
    <?php if (isset($products) && count($products) > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($products as $product): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm product-card">
                        <div class="position-relative">
                            <?php if (!empty($product->image)): ?>
                                <div class="product-img-container" style="height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                    <img src="/project1/<?= $product->image ?>" 
                                        alt="<?= $product->name ?>" 
                                        class="card-img-top" 
                                        style="max-height: 100%; width: auto; object-fit: contain;">
                                </div>
                            <?php else: ?>
                                <div class="product-img-container bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                                        <p class="mt-2"><?= __('Không có hình ảnh') ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge bg-primary rounded-pill"><?= isset($product->quantity) ? $product->quantity : 0 ?> <?= __('sản phẩm') ?></span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-2">
                                <a href="/project1/admin/product/show/<?= $product->id ?>" class="text-decoration-none text-dark product-title">
                                    <?= $product->name ?>
                                </a>
                            </h5>
                            
                            <p class="card-text text-primary fw-bold mb-2">
                                <?= number_format($product->price) ?> VND
                            </p>
                            
                            <p class="card-text mb-2">
                                <span class="badge bg-info text-dark">
                                    <?= $product->category_name ?? __('Chưa phân loại') ?>
                                </span>
                            </p>
                            
                            <p class="card-text description-truncate mb-3">
                                <?= (strlen($product->description) > 100) ? 
                                    substr($product->description, 0, 100) . '...' : 
                                    $product->description ?>
                            </p>
                        </div>
                        
                        <div class="card-footer bg-white border-top-0 pt-0">
                            <div class="d-flex justify-content-between">
                                <a href="/project1/admin/product/show/<?= $product->id ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i><?= __('Xem') ?>
                                </a>
                                <div class="d-flex">
                                    <a href="/project1/admin/review/byProduct/<?= $product->id ?>" class="btn btn-sm btn-info me-1" title="<?= __('Xem đánh giá') ?>">
                                        <i class="bi bi-star me-1"></i><?= $product->review_count ?? 0 ?>
                                    </a>
                                    <a href="/project1/admin/product/edit/<?= $product->id ?>" class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil-square me-1"></i><?= __('Sửa') ?>
                                    </a>
                                    <a href="javascript:void(0)" onclick="confirmDelete(<?= $product->id ?>)" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash me-1"></i><?= __('Xóa') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state text-center py-5">
            <i class="bi bi-box-seam display-4 text-muted"></i>
            <p class="mt-3"><?= __('Không tìm thấy sản phẩm nào.') ?></p>
            <a href="/project1/admin/product/add" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> <?= __('Thêm sản phẩm mới') ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
function confirmDelete(id) {
    document.getElementById('confirmDeleteBtn').href = '/project1/admin/product/delete/' + id;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script> 