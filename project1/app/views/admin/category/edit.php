<?php
$activePage = 'category';
$pageTitle = __('Sửa danh mục');
ob_start();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-gradient-primary-to-secondary text-white py-3">
                    <h2 class="mb-0 text-center fw-bold">
                        <i class="bi bi-pencil-square me-2"></i><?= __('Sửa danh mục') ?>
                    </h2>
                </div>
                
                <div class="card-body p-4">
                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= __('Lỗi!') ?></h5>
                            <div><?= $errors['general'] ?></div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/project1/admin/category/edit/<?= $category->id ?>" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-lg-8 mx-auto">
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i><?= __('Thông tin danh mục') ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label for="name" class="form-label fw-bold">
                                                <i class="bi bi-tag me-1"></i><?= __('Tên danh mục') ?>:
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-bookmark-fill"></i></span>
                                                <input type="text" id="name" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                                    value="<?= $_POST['name'] ?? $category->name ?>" required>
                                            </div>
                                            <?php if (isset($errors['name'])): ?>
                                                <div class="invalid-feedback d-block"><?= $errors['name'] ?></div>
                                            <?php else: ?>
                                                <div class="form-text"><?= __('Tên danh mục sẽ hiển thị cho người dùng') ?></div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="description" class="form-label fw-bold">
                                                <i class="bi bi-card-text me-1"></i><?= __('Mô tả') ?>:
                                            </label>
                                            <textarea id="description" name="description" class="form-control" rows="5"><?= $_POST['description'] ?? $category->description ?></textarea>
                                            <div class="form-text"><?= __('Mô tả giúp người dùng hiểu rõ hơn về danh mục này') ?></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i><?= __('Thông tin bổ sung') ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-0">
                                                    <p class="mb-1 text-muted"><?= __('ID Danh mục') ?>:</p>
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
                                                    <p class="mb-1 text-muted"><?= __('Số sản phẩm') ?>:</p>
                                                    <p class="fw-bold"><?php echo $productCount; ?> <?= __('sản phẩm') ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-center mt-4 gap-2">
                                    <button type="submit" class="btn btn-primary px-4 py-2">
                                        <i class="bi bi-save me-2"></i><?= __('Lưu thay đổi') ?>
                                    </button>
                                    <a href="/project1/admin/category/list" class="btn btn-outline-secondary px-4 py-2">
                                        <i class="bi bi-arrow-left me-2"></i><?= __('Quay lại danh sách') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?> 