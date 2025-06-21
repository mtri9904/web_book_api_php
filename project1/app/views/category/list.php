<?php include 'app/views/shares/header.php'; ?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="text-white mb-0">
                    <i class="bi bi-folder2 me-2"></i><?php echo __('Danh sách danh mục'); ?>
                </h3>
            </div>
            <div class="col-auto">
                <a href="/project1/Category/add" class="btn btn-light">
                    <i class="bi bi-plus-circle me-1"></i> <?php echo __('Thêm danh mục mới'); ?>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="60">ID</th>
                        <th><?php echo __('Tên danh mục'); ?></th>
                        <th><?php echo __('Mô tả'); ?></th>
                        <th><?php echo __('Số sản phẩm'); ?></th>
                        <th width="200" class="text-center"><?php echo __('Hành động'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($categories) > 0): ?>
                        <?php foreach ($categories as $category): ?>
                            <?php
                            // Đếm số sản phẩm trong danh mục
                            $productCount = 0;
                            try {
                                // Kiểm tra xem bảng product có tồn tại không
                                $checkTable = $GLOBALS['db']->query("SHOW TABLES LIKE 'product'");
                                if ($checkTable->rowCount() > 0) {
                                    $query = "SELECT COUNT(*) FROM product WHERE category_id = ?";
                                    $stmt = $GLOBALS['db']->prepare($query);
                                    $stmt->execute([$category->id]);
                                    $productCount = $stmt->fetchColumn();
                                }
                            } catch (PDOException $e) {
                                // Bỏ qua lỗi nếu bảng không tồn tại
                                $productCount = 0;
                            }
                            ?>
                            <tr>
                                <td><?php echo $category->id; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="category-icon-small me-2">
                                            <i class="bi bi-folder2"></i>
                                        </div>
                                        <div>
                                            <span class="fw-medium"><?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $description = htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8');
                                    echo !empty($description) ? (strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description) : '<em class="text-muted">'.__('Không có mô tả').'</em>'; 
                                    ?>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo $productCount; ?></span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="/project1/Category/show/<?php echo $category->id; ?>" class="btn btn-info btn-sm" title="<?php echo __('Xem chi tiết'); ?>">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/project1/Category/edit/<?php echo $category->id; ?>" class="btn btn-warning btn-sm" title="<?php echo __('Sửa'); ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/project1/Category/delete/<?php echo $category->id; ?>" class="btn btn-danger btn-sm" 
                                           onclick="return confirm('<?php echo __('Bạn có chắc chắn muốn xóa?'); ?>');" title="<?php echo __('Xóa'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-folder-x display-4 text-muted"></i>
                                    <p class="mt-3"><?php echo __('Chưa có danh mục nào'); ?></p>
                                    <a href="/project1/Category/add" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i> <?php echo __('Thêm danh mục mới'); ?>
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

<?php include 'app/views/shares/footer.php'; ?>