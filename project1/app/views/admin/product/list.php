<?php
$activePage = 'product';
$pageTitle = __('Danh sách sản phẩm');
ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="text-white mb-0">
                    <i class="bi bi-box-seam me-2"></i><?= __('Danh sách sản phẩm') ?>
                </h3>
            </div>
            <div class="col-auto">
                <a href="/project1/admin/product/add" class="btn btn-light">
                    <i class="bi bi-plus-circle me-1"></i> <?= __('Thêm sản phẩm mới') ?>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Search and Filter Form -->
    <div class="card-body border-bottom pb-3">
        <div class="row g-3 align-items-end" id="searchForm">
            <div class="col-md-6">
                <label for="search" class="form-label"><?= __('Tìm kiếm theo tên sản phẩm') ?></label>
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="<?= __('Nhập tên sản phẩm...') ?>" 
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button class="btn btn-primary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i> <?= __('Tìm') ?>
                    </button>
                </div>
            </div>
            
            <div class="col-md-4">
                <label for="category_id" class="form-label"><?= __('Lọc theo danh mục') ?></label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="0"><?= __('Tất cả danh mục') ?></option>
                    <?php if(isset($categories) && !empty($categories)): ?>
                        <?php foreach($categories as $category): ?>
                            <option value="<?= $category->id ?>" <?= (isset($_GET['category_id']) && $_GET['category_id'] == $category->id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category->name) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <button type="button" id="resetFilters" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x-circle"></i> <?= __('Xóa bộ lọc') ?>
                </button>
            </div>
        </div>
    </div>
    
    <div class="card-body">
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
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('Xác nhận xóa') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= __('Bạn có chắc chắn muốn xóa sản phẩm này? Hành động này không thể hoàn tác.') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('Hủy') ?></button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger"><?= __('Xóa') ?></a>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary-to-secondary {
        background: linear-gradient(135deg, #1a2a44 0%, #293e63 100%);
    }
    
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

<script>
    function confirmDelete(id) {
        document.getElementById('confirmDeleteBtn').href = '/project1/admin/product/delete/' + id;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    
    // AJAX search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const categorySelect = document.getElementById('category_id');
        const searchBtn = document.getElementById('searchBtn');
        const resetBtn = document.getElementById('resetFilters');
        const resultsContainer = document.getElementById('productResults');
        
        // Function to load products via AJAX
        function loadProducts() {
            // Show loading indicator
            resultsContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2"><?= __('Đang tải...') ?></p></div>';
            
            // Get search parameters
            const searchTerm = searchInput.value;
            const categoryId = categorySelect.value;
            
            // Create URL with parameters
            let url = '/project1/admin/product?ajax=1';
            if (searchTerm) url += '&search=' + encodeURIComponent(searchTerm);
            if (categoryId > 0) url += '&category_id=' + categoryId;
            
            // Update browser URL for bookmarking/sharing without reloading
            history.pushState({}, '', '/project1/admin/product?' + 
                (searchTerm ? 'search=' + encodeURIComponent(searchTerm) + '&' : '') + 
                (categoryId > 0 ? 'category_id=' + categoryId : ''));
            
            // Fetch results
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    // Extract just the products part from the response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const productResults = doc.getElementById('productResults');
                    
                    if (productResults) {
                        resultsContainer.innerHTML = productResults.innerHTML;
                    } else {
                        resultsContainer.innerHTML = '<div class="alert alert-danger"><?= __('Lỗi khi tải dữ liệu') ?></div>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                    resultsContainer.innerHTML = '<div class="alert alert-danger"><?= __('Lỗi khi tải dữ liệu') ?></div>';
                });
        }
        
        // Debounce function to limit API calls
        function debounce(func, wait) {
            let timeout;
            return function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, arguments), wait);
            };
        }
        
        // Event listeners
        searchBtn.addEventListener('click', loadProducts);
        
        // Live search with debounce
        searchInput.addEventListener('input', debounce(loadProducts, 500));
        
        // Auto-submit when category changes
        categorySelect.addEventListener('change', loadProducts);
        
        // Reset filters
        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            categorySelect.value = '0';
            loadProducts();
        });
    });
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?> 