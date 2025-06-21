<?php include 'app/views/shares/header.php'; ?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <h3 class="text-center font-weight-light my-2 text-white">
            <i class="bi bi-folder-plus me-2"></i><?php echo __('Thêm danh mục mới'); ?>
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

        <form method="POST" action="/project1/Category/save" class="needs-validation" novalidate>
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
                                    <input type="text" id="name" name="name" class="form-control" required 
                                        placeholder="<?php echo __('Nhập tên danh mục...'); ?>">
                                </div>
                                <div class="form-text"><?php echo __('Tên danh mục sẽ hiển thị cho người dùng'); ?></div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">
                                    <i class="bi bi-card-text me-1"></i><?php echo __('Mô tả:'); ?>
                                </label>
                                <textarea id="description" name="description" class="form-control" rows="5" 
                                    placeholder="<?php echo __('Nhập mô tả chi tiết về danh mục...'); ?>"></textarea>
                                <div class="form-text"><?php echo __('Mô tả giúp người dùng hiểu rõ hơn về danh mục này'); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-lightbulb me-2"></i><?php echo __('Gợi ý'); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <?php echo __('Danh mục giúp phân loại sản phẩm và giúp người dùng tìm kiếm dễ dàng hơn. Hãy đặt tên danh mục ngắn gọn, dễ hiểu và có mô tả chi tiết.'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="/project1/Category/list" class="btn btn-secondary me-2">
                            <i class="bi bi-arrow-left"></i> <?php echo __('Quay lại danh sách'); ?>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> <?php echo __('Thêm danh mục'); ?>
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