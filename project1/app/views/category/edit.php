<?php include 'app/views/shares/header.php'; ?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <h3 class="text-center font-weight-light my-2 text-white">
            <i class="bi bi-pencil-square me-2"></i><?php echo __('Sửa danh mục'); ?>
        </h3>
    </div>
    <div class="card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/project1/Category/update" class="needs-validation" novalidate>
            <input type="hidden" name="id" value="<?php echo $category->id; ?>">
            
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i><?php echo __('Thông tin danh mục'); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">
                                    <i class="bi bi-tag me-1"></i><?php echo __('Tên danh mục:'); ?>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-bookmark-fill"></i></span>
                                    <input type="text" id="name" name="name" class="form-control" 
                                        value="<?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>" required>
                                </div>
                                <div class="form-text"><?php echo __('Tên danh mục sẽ hiển thị cho người dùng'); ?></div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">
                                    <i class="bi bi-card-text me-1"></i><?php echo __('Mô tả:'); ?>
                                </label>
                                <textarea id="description" name="description" class="form-control" rows="5"><?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                <div class="form-text"><?php echo __('Mô tả giúp người dùng hiểu rõ hơn về danh mục này'); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i><?php echo __('Thông tin bổ sung'); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-0">
                                        <p class="mb-1 text-muted"><?php echo __('ID Danh mục:'); ?></p>
                                        <p class="fw-bold">#<?php echo $category->id; ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                                    <div class="mb-0">
                                        <p class="mb-1 text-muted"><?php echo __('Số sản phẩm:'); ?></p>
                                        <p class="fw-bold"><?php echo $productCount; ?> <?php echo __('sản phẩm'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="/project1/Category/list" class="btn btn-secondary me-2">
                            <i class="bi bi-arrow-left"></i> <?php echo __('Quay lại danh sách'); ?>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> <?php echo __('Lưu thay đổi'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.bg-gradient-primary-to-secondary {
    background: linear-gradient(135deg, #1a2a44 0%, #293e63 100%);
}
</style>

<?php include 'app/views/shares/footer.php'; ?>