<?php
$activePage = 'category';
$pageTitle = __('Quản lý danh mục');
ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="text-white mb-0">
                    <i class="bi bi-folder2 me-2"></i><?= __('Danh sách danh mục') ?>
                </h3>
            </div>
            <div class="col-auto">
                <a href="/project1/admin/category/add" class="btn btn-light">
                    <i class="bi bi-plus-circle me-1"></i> <?= __('Thêm danh mục mới') ?>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Search and Filter Form -->
    <div class="card-body border-bottom pb-3">
        <div class="row g-3 align-items-end" id="searchForm">
            <div class="col-md-6">
                <label for="search" class="form-label"><?= __('Tìm kiếm theo tên danh mục') ?></label>
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="<?= __('Nhập tên danh mục...') ?>" 
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button class="btn btn-primary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i> <?= __('Tìm') ?>
                    </button>
                </div>
            </div>
            
            <div class="col-md-4">
                <label for="product_count" class="form-label"><?= __('Lọc theo số lượng sản phẩm') ?></label>
                <select class="form-select" id="product_count" name="product_count">
                    <option value="0" <?= (!isset($_GET['product_count']) || $_GET['product_count'] == 0) ? 'selected' : '' ?>><?= __('Tất cả số sản phẩm') ?></option>
                    <option value="1" <?= (isset($_GET['product_count']) && $_GET['product_count'] == 1) ? 'selected' : '' ?>><?= __('≥ 1 sản phẩm') ?></option>
                    <option value="5" <?= (isset($_GET['product_count']) && $_GET['product_count'] == 5) ? 'selected' : '' ?>><?= __('≥ 5 sản phẩm') ?></option>
                    <option value="10" <?= (isset($_GET['product_count']) && $_GET['product_count'] == 10) ? 'selected' : '' ?>><?= __('≥ 10 sản phẩm') ?></option>
                    <option value="20" <?= (isset($_GET['product_count']) && $_GET['product_count'] == 20) ? 'selected' : '' ?>><?= __('≥ 20 sản phẩm') ?></option>
                    <option value="50" <?= (isset($_GET['product_count']) && $_GET['product_count'] == 50) ? 'selected' : '' ?>><?= __('≥ 50 sản phẩm') ?></option>
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
        <div id="categoryResults">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="60">ID</th>
                        <th><?= __('Tên') ?></th>
                        <th><?= __('Mô tả') ?></th>
                        <th><?= __('Sản phẩm') ?></th>
                        <th width="200" class="text-center"><?= __('Thao tác') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($categories) && count($categories) > 0): ?>
                        <?php foreach ($categories as $category): ?>
                            <?php
                            // Count products in this category
                            $productCount = 0;
                            try {
                                // Check if product table exists
                                $checkTable = $GLOBALS['db']->query("SHOW TABLES LIKE 'product'");
                                if ($checkTable->rowCount() > 0) {
                                    $query = "SELECT COUNT(*) FROM product WHERE category_id = ?";
                                    $stmt = $GLOBALS['db']->prepare($query);
                                    $stmt->execute([$category->id]);
                                    $productCount = $stmt->fetchColumn();
                                }
                            } catch (PDOException $e) {
                                // Ignore errors if table doesn't exist
                                $productCount = 0;
                            }
                            ?>
                            <tr>
                                <td><?= $category->id ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="category-icon-small me-2">
                                            <i class="bi bi-folder2"></i>
                                        </div>
                                        <div>
                                            <span class="fw-medium"><?= $category->name ?></span>
                                            <?php if ($productCount > 0): ?>
                                                <span class="badge bg-info ms-2" data-bs-toggle="tooltip" title="<?= __('Có sản phẩm liên kết') ?>">
                                                    <i class="bi bi-link-45deg"></i>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $description = $category->description;
                                    echo !empty($description) ? (strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description) : '<em class="text-muted">' . __('Không có mô tả') . '</em>'; 
                                    ?>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= $productCount ?></span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="/project1/admin/category/show/<?= $category->id ?>" class="btn btn-info btn-sm" title="<?= __('Xem chi tiết') ?>">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/project1/admin/category/edit/<?= $category->id ?>" class="btn btn-warning btn-sm" title="<?= __('Sửa') ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if ($productCount > 0): ?>
                                            <button type="button" class="btn btn-danger btn-sm" disabled title="<?= __('Không thể xóa danh mục có sản phẩm') ?>" data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <a href="javascript:void(0)" onclick="confirmDelete(<?= $category->id ?>)" class="btn btn-danger btn-sm" title="<?= __('Xóa') ?>" data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-folder-x display-4 text-muted"></i>
                                    <p class="mt-3"><?= __('Không tìm thấy danh mục nào') ?></p>
                                    <a href="/project1/admin/category/add" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i> <?= __('Thêm danh mục mới') ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
                <p><?= __('Bạn có chắc chắn muốn xóa danh mục này? Hành động này không thể hoàn tác.') ?></p>
                <p class="text-danger"><strong><?= __('Cảnh báo:') ?></strong> <?= __('Danh mục chỉ có thể xóa khi không có sản phẩm nào liên kết với nó. Nếu có sản phẩm liên kết, bạn cần chuyển hoặc xóa các sản phẩm đó trước.') ?></p>
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

.category-icon-small {
    width: 32px;
    height: 32px;
    background-color: rgba(0, 255, 204, 0.1);
    color: #1a2a44;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-state {
    padding: 2rem;
    text-align: center;
}
</style>

<script>
    function confirmDelete(id) {
        document.getElementById('confirmDeleteBtn').href = '/project1/admin/category/delete/' + id;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // AJAX search functionality
        const searchInput = document.getElementById('search');
        const productCountSelect = document.getElementById('product_count');
        const searchBtn = document.getElementById('searchBtn');
        const resetBtn = document.getElementById('resetFilters');
        const resultsContainer = document.getElementById('categoryResults');
        
        // Function to load categories via AJAX
        function loadCategories() {
            // Show loading indicator
            resultsContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2"><?= __('Đang tải...') ?></p></div>';
            
            // Get search parameters
            const searchTerm = searchInput.value;
            const productCount = productCountSelect.value;
            
            // Create URL with parameters
            let url = '/project1/admin/category?ajax=1';
            if (searchTerm) url += '&search=' + encodeURIComponent(searchTerm);
            if (productCount > 0) url += '&product_count=' + productCount;
            
            // Update browser URL for bookmarking/sharing without reloading
            history.pushState({}, '', '/project1/admin/category?' + 
                (searchTerm ? 'search=' + encodeURIComponent(searchTerm) + '&' : '') + 
                (productCount > 0 ? 'product_count=' + productCount : ''));
            
            // Fetch results
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    // Extract just the categories part from the response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const categoryResults = doc.getElementById('categoryResults');
                    
                    if (categoryResults) {
                        resultsContainer.innerHTML = categoryResults.innerHTML;
                        
                        // Re-initialize tooltips for the new content
                        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl)
                        });
                    } else {
                        resultsContainer.innerHTML = '<div class="alert alert-danger"><?= __('Lỗi khi tải dữ liệu') ?></div>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
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
        searchBtn.addEventListener('click', loadCategories);
        
        // Live search with debounce
        searchInput.addEventListener('input', debounce(loadCategories, 500));
        
        // Auto-submit when product count filter changes
        productCountSelect.addEventListener('change', loadCategories);
        
        // Reset filters
        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            productCountSelect.value = '0';
            loadCategories();
        });
    });
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?> 