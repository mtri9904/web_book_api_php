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
                        // Get product count if not already provided
                        $productCount = isset($category->product_count) ? $category->product_count : 0;
                        if (!isset($category->product_count)) {
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

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
    
    function confirmDelete(id) {
        document.getElementById('confirmDeleteBtn').href = '/project1/admin/category/delete/' + id;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script> 