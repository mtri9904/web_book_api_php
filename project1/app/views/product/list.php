<?php include 'app/views/shares/header.php'; ?>
<div class="container my-5 product-list-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 fw-bold"><?php echo __('Danh sách sản phẩm'); ?></h1>
        <a href="/project1/Product/add" class="btn btn-success">
            <i class="bi bi-plus-circle me-2"></i><?php echo __('Thêm sản phẩm mới'); ?>
        </a>
    </div>

    <?php
    // PHÂN TRANG
    $perPage = 6; // Tăng số sản phẩm trên mỗi trang
    $totalProducts = count($products);
    $totalPages = ceil($totalProducts / $perPage);
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    if ($page > $totalPages) $page = $totalPages;
    $start = ($page - 1) * $perPage;
    $productsPage = array_slice($products, $start, $perPage);
    ?>

    <?php if (empty($productsPage)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i> Không có sản phẩm nào trong danh sách.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($productsPage as $product): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm product-card">
                        <div class="position-relative">
                            <?php if ($product->image): ?>
                                <div class="product-img-container" style="height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                    <img src="/project1/<?php echo htmlspecialchars($product->image); ?>" 
                                        alt="<?php echo htmlspecialchars($product->name); ?>" 
                                        class="card-img-top" 
                                        style="max-height: 100%; width: auto; object-fit: contain;">
                                </div>
                            <?php else: ?>
                                <div class="product-img-container bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                                        <p class="mt-2"><?php echo __('Không có hình ảnh'); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($product->quantity); ?> <?php echo __('sản phẩm'); ?></span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-2">
                                <a href="/project1/Product/show/<?php echo $product->id; ?>" class="text-decoration-none text-dark product-title">
                                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </h5>
                            
                            <p class="card-text text-primary fw-bold mb-2">
                                <?php echo number_format($product->price, 0, ',', '.'); ?> <?php echo __('VND'); ?>
                            </p>
                            
                            <p class="card-text mb-2">
                                <span class="badge bg-info text-dark">
                                    <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </p>
                            
                            <?php if (isset($product->average_rating) && $product->average_rating > 0): ?>
                            <div class="d-flex align-items-center mb-2">
                                <div class="ratings">
                                    <?php
                                    $rating = round($product->average_rating * 2) / 2; // Làm tròn đến 0.5
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '<i class="bi bi-star-fill text-warning"></i>';
                                        } elseif ($i - 0.5 == $rating) {
                                            echo '<i class="bi bi-star-half text-warning"></i>';
                                        } else {
                                            echo '<i class="bi bi-star text-warning"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="ms-1 text-muted small">
                                    <span><?php echo number_format($product->average_rating, 1); ?></span>
                                    <span class="ms-1">(<?php echo $product->review_count; ?>)</span>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="mb-2 text-muted small">
                                <i class="bi bi-star text-muted me-1"></i><?php echo __('Chưa có đánh giá'); ?>
                            </div>
                            <?php endif; ?>
                            
                            <p class="card-text description-truncate mb-3">
                                <?php echo (strlen($product->description) > 100) ? 
                                    htmlspecialchars(substr($product->description, 0, 100), ENT_QUOTES, 'UTF-8') . '...' : 
                                    htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?>
                            </p>
                        </div>
                        
                        <div class="card-footer bg-white border-top-0 pt-0">
                            <div class="d-flex justify-content-between">
                                <a href="/project1/Product/show/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i><?php echo __('Xem'); ?>
                                </a>
                                <div class="d-flex">
                                    <a href="/project1/Product/edit/<?php echo $product->id; ?>" class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil-square me-1"></i><?php echo __('Sửa'); ?>
                                    </a>
                                    <a href="/project1/Product/delete/<?php echo $product->id; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('<?php echo __('Bạn có chắc chắn muốn xóa sản phẩm này?'); ?>');">
                                        <i class="bi bi-trash me-1"></i><?php echo __('Xóa'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- PHÂN TRANG -->
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page-1; ?>">
                            <i class="bi bi-chevron-left"></i> <?php echo __('Trước'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php 
                // Hiển thị tối đa 5 trang và trang hiện tại ở giữa
                $startPage = max(1, min($page - 2, $totalPages - 4));
                $endPage = min($totalPages, max(5, $page + 2));
                
                if ($startPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=1">1</a>
                    </li>
                    <?php if ($startPage > 2): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($endPage < $totalPages): ?>
                    <?php if ($endPage < $totalPages - 1): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a>
                    </li>
                <?php endif; ?>
                
                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page+1; ?>">
                            <?php echo __('Sau'); ?> <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<style>
    .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    
    .product-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 48px;
    }
    
    .product-title:hover {
        color: #0d6efd !important;
    }
    
    .description-truncate {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        color: #6c757d;
        font-size: 0.9rem;
        height: 60px;
    }
    
    .card-footer .btn-sm {
        transition: all 0.2s;
    }
    
    .card-footer .btn-sm:hover {
        transform: scale(1.05);
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>