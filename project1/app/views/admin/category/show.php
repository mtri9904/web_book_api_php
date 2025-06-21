<?php
$activePage = 'category';
$pageTitle = __('Chi tiết danh mục');
ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <h3 class="text-center font-weight-light my-2 text-white">
            <i class="bi bi-folder me-2"></i><?= __('Chi tiết danh mục') ?>
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="row g-0">
            <!-- Left side - Basic information -->
            <div class="col-md-4 border-end">
                <div class="p-4 h-100 d-flex flex-column">
                    <div class="text-center mb-4">
                        <div class="category-icon mb-3 mx-auto">
                            <i class="bi bi-folder2-open display-1"></i>
                        </div>
                        
                        <h4 class="fw-bold"><?= $category->name ?></h4>
                        <span class="badge bg-primary fs-6 px-3 py-2 mb-2">
                            ID: <?= $category->id ?>
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-card-text me-2"></i><?= __('Mô tả') ?>
                        </h5>
                        <p><?= !empty($category->description) ? nl2br($category->description) : '<em class="text-muted">' . __('Không có mô tả') . '</em>' ?></p>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between">
                            <a href="/project1/admin/category/edit/<?= $category->id ?>" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> <?= __('Sửa') ?>
                            </a>
                            <a href="/project1/admin/category/list" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> <?= __('Quay lại') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right side - Detailed information -->
            <div class="col-md-8">
                <div class="p-4">
                    <div class="row">
                        <!-- Products statistics -->
                        <div class="col-12 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i><?= __('Thống kê sản phẩm') ?></h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    // Count products in this category
                                    $productCount = count($products);
                                    $totalProducts = 0;
                                    $percentage = 0;
                                    
                                    try {
                                        // Get total products count
                                        $queryTotal = "SELECT COUNT(*) FROM product";
                                        $stmtTotal = $GLOBALS['db']->prepare($queryTotal);
                                        $stmtTotal->execute();
                                        $totalProducts = $stmtTotal->fetchColumn();
                                        
                                        $percentage = $totalProducts > 0 ? ($productCount / $totalProducts) * 100 : 0;
                                    } catch (PDOException $e) {
                                        // Ignore errors
                                        $totalProducts = 0;
                                        $percentage = 0;
                                    }
                                    ?>
                                    
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="stats-icon bg-primary rounded-circle p-3 me-3">
                                                    <i class="bi bi-box-seam text-white fs-4"></i>
                                                </div>
                                                <div>
                                                    <h3 class="mb-0"><?= $productCount ?></h3>
                                                    <p class="text-muted mb-0"><?= __('Sản phẩm') ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="progress mt-3 mt-md-0" style="height: 10px;">
                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                    style="width: <?= $percentage ?>%" 
                                                    aria-valuenow="<?= $percentage ?>" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">
                                                <?= number_format($percentage, 1) ?>% <?= __('trong tổng số sản phẩm') ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Products in this category -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-box me-2"></i><?= __('Sản phẩm trong danh mục này') ?></h5>
                                </div>
                                <div class="card-body">
                                    <?php if (count($products) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="60"><?= __('Hình ảnh') ?></th>
                                                    <th><?= __('Tên') ?></th>
                                                    <th><?= __('Giá') ?></th>
                                                    <th><?= __('Số lượng') ?></th>
                                                    <th width="100"><?= __('Thao tác') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($products as $product): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($product->image)): ?>
                                                        <img src="/project1/<?= $product->image ?>" alt="<?= $product->name ?>" width="50" height="50" class="img-thumbnail">
                                                        <?php else: ?>
                                                        <div class="bg-light text-center" style="width: 50px; height: 50px; line-height: 50px;">
                                                            <i class="bi bi-image text-muted"></i>
                                                        </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $product->name ?></td>
                                                    <td><?= number_format($product->price) ?> VND</td>
                                                    <td><?= $product->quantity ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="/project1/admin/product/show/<?= $product->id ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="<?= __('Xem') ?>">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="/project1/admin/product/edit/<?= $product->id ?>" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="<?= __('Sửa') ?>">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <?= __('Không tìm thấy sản phẩm nào trong danh mục này.') ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <?php if (count($products) == 0): ?>
            <a href="javascript:void(0)" onclick="confirmDelete(<?= $category->id ?>)" class="btn btn-danger">
                <i class="bi bi-trash"></i> <?= __('Xóa danh mục') ?>
            </a>
        <?php else: ?>
            <button type="button" class="btn btn-danger" disabled title="<?= __('Không thể xóa danh mục có sản phẩm') ?>" data-bs-toggle="tooltip">
                <i class="bi bi-trash"></i> <?= __('Xóa danh mục') ?>
            </button>
            <small class="d-block text-danger mt-2">
                <i class="bi bi-info-circle"></i> <?= __('Không thể xóa danh mục có sản phẩm liên kết') ?>
            </small>
        <?php endif; ?>
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
                <?php if (count($products) > 0): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong><?= __('Không thể xóa!') ?></strong> <?= __('Danh mục này chứa') ?> <b><?= count($products) ?></b> <?= __('sản phẩm. Bạn cần chuyển hoặc xóa các sản phẩm này trước khi xóa danh mục.') ?>
                    </div>
                <?php else: ?>
                    <p class="text-success"><i class="bi bi-check-circle me-2"></i><?= __('Danh mục này không chứa sản phẩm nào và có thể xóa an toàn.') ?></p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('Hủy') ?></button>
                <?php if (count($products) == 0): ?>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger"><?= __('Xóa') ?></a>
                <?php else: ?>
                    <button type="button" class="btn btn-danger" disabled title="<?= __('Không thể xóa danh mục có sản phẩm') ?>"><?= __('Xóa') ?></button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary-to-secondary {
    background: linear-gradient(135deg, #1a2a44 0%, #293e63 100%);
}

.category-icon {
    color: #1a2a44;
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 255, 204, 0.1);
    border-radius: 50%;
}

.stats-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
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
    });
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?> 