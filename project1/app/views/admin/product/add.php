<?php
$activePage = 'product';
$pageTitle = __('Thêm sản phẩm mới');
ob_start();
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><?= __('Thêm sản phẩm mới') ?></span>
        <a href="/project1/admin/product/list" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> <?= __('Quay lại danh sách') ?>
        </a>
    </div>
    <div class="card-body">
        <form action="/project1/admin/product/add" method="POST" enctype="multipart/form-data">
            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger"><?= $errors['general'] ?></div>
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i><?= __('Thông tin sản phẩm') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label"><?= __('Tên sản phẩm') ?> *</label>
                                <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= $_POST['name'] ?? '' ?>" required>
                                <?php if (isset($errors['name'])): ?>
                                    <div class="invalid-feedback"><?= $errors['name'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label"><?= __('Danh mục') ?></label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value=""><?= __('-- Chọn danh mục --') ?></option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category->id ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $category->id) ? 'selected' : '' ?>>
                                            <?= $category->name ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label"><?= __('Giá (VND)') ?> *</label>
                                <input type="number" class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" id="price" name="price" value="<?= $_POST['price'] ?? '' ?>" required>
                                <?php if (isset($errors['price'])): ?>
                                    <div class="invalid-feedback"><?= $errors['price'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label"><?= __('Số lượng') ?> *</label>
                                <input type="number" class="form-control <?= isset($errors['quantity']) ? 'is-invalid' : '' ?>" id="quantity" name="quantity" value="<?= $_POST['quantity'] ?? '1' ?>" required>
                                <?php if (isset($errors['quantity'])): ?>
                                    <div class="invalid-feedback"><?= $errors['quantity'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-file-text me-2"></i><?= __('Mô tả') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" id="description" name="description" rows="5" required><?= $_POST['description'] ?? '' ?></textarea>
                                <?php if (isset($errors['description'])): ?>
                                    <div class="invalid-feedback"><?= $errors['description'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-image me-2"></i><?= __('Hình ảnh sản phẩm') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="file" class="form-control <?= isset($errors['image']) ? 'is-invalid' : '' ?>" id="image" name="image" accept="image/*" required>
                                <?php if (isset($errors['image'])): ?>
                                    <div class="invalid-feedback"><?= $errors['image'] ?></div>
                                <?php endif; ?>
                                <div class="form-text"><?= __('Kích thước đề xuất: 500x500 pixels') ?></div>
                            </div>
                            
                            <div class="mt-3">
                                <div id="imagePreview" class="mt-2 d-none">
                                    <img src="#" alt="<?= __('Xem trước') ?>" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> <?= __('Lưu sản phẩm') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const img = preview.querySelector('img');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('d-none');
        }
    });
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?> 