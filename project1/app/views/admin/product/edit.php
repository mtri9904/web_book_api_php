<?php
$activePage = 'product';
$pageTitle = __('Sửa sản phẩm');
ob_start();
?>

<div class="container my-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-gradient-primary-to-secondary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-center fw-bold">
                    <i class="bi bi-pencil-square me-2"></i><?= __('Sửa sản phẩm') ?>: <?= $product->name ?>
                </h2>
                <a href="/project1/admin/product/list" class="btn btn-light btn-sm"> 
                    <i class="bi bi-arrow-left"></i> <?= __('Quay lại danh sách') ?>
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= __('Lỗi!') ?></h5>
                    <div><?= $errors['general'] ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="/project1/admin/product/edit/<?= $product->id ?>" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="row g-4">
                    <!-- Phần hình ảnh bên trái -->
                    <div class="col-md-4">
                        <div class="image-upload-container mb-3">
                            <label class="d-block fw-bold mb-2">
                                <i class="bi bi-image me-1"></i><?= __('Hình ảnh sản phẩm') ?>
                            </label>
                            
                            <div class="text-center">
                                <!-- Hiển thị hình ảnh hiện tại -->
                                <div class="current-image-container mb-3 p-3 bg-light rounded d-flex align-items-center justify-content-center" style="min-height: 250px;">
                                    <?php if (!empty($product->image)): ?>
                                        <img id="currentImage" src="/project1/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                            alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
                                            class="img-fluid rounded" style="max-height: 250px; object-fit: contain;">
                                    <?php else: ?>
                                        <div class="text-center text-muted">
                                            <i class="bi bi-image" style="font-size: 4rem;"></i>
                                            <p class="mt-2"><?= __('Không có hình ảnh') ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Hiển thị hình ảnh mới (preview) -->
                                <div id="imagePreview" class="mb-3 p-3 bg-light rounded d-flex align-items-center justify-content-center" style="min-height: 250px; display: none;">
                                    <img id="previewImg" src="#" alt="<?= __('Hình ảnh mới') ?>" class="img-fluid rounded" style="max-height: 250px; object-fit: contain;">
                                </div>
                                
                                <div class="image-upload-btn">
                                    <label for="image" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-upload me-1"></i><?= __('Chọn hình ảnh mới') ?>
                                    </label>
                                    <input type="file" id="image" name="image" class="form-control d-none" accept="image/*">
                                    <input type="hidden" name="existing_image" value="<?= !empty($product->image) ? $product->image : '' ?>">
                                </div>
                                
                                <div class="form-text"><?= __('Để trống nếu muốn giữ hình ảnh hiện tại. Kích thước khuyến nghị: 500x500 pixels') ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Thông tin sản phẩm bên phải -->
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" id="name" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                           value="<?= $_POST['name'] ?? $product->name ?>" 
                                           placeholder="<?= __('Tên sản phẩm') ?>" required>
                                    <label for="name">
                                        <i class="bi bi-tag me-1"></i><?= __('Tên sản phẩm') ?>
                                    </label>
                                    <?php if (isset($errors['name'])): ?>
                                        <div class="invalid-feedback"><?= $errors['name'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number" id="price" name="price" class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" 
                                           step="0.01" value="<?= $_POST['price'] ?? $product->price ?>" 
                                           placeholder="<?= __('Giá (VND)') ?>" required>
                                    <label for="price">
                                        <i class="bi bi-currency-dollar me-1"></i><?= __('Giá (VND)') ?>
                                    </label>
                                    <?php if (isset($errors['price'])): ?>
                                        <div class="invalid-feedback"><?= $errors['price'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select id="category_id" name="category_id" class="form-select">
                                        <option value=""><?= __('-- Chọn danh mục --') ?></option>
                                        <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category->id; ?>" 
                                            <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category->id) || $product->category_id == $category->id ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="category_id">
                                        <i class="bi bi-grid me-1"></i><?= __('Danh mục') ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number" id="quantity" name="quantity" class="form-control <?= isset($errors['quantity']) ? 'is-invalid' : '' ?>"
                                        min="0" value="<?= $_POST['quantity'] ?? (isset($product->quantity) ? $product->quantity : 0) ?>" required>
                                    <label for="quantity">
                                        <i class="bi bi-boxes me-1"></i><?= __('Số lượng') ?>
                                    </label>
                                    <?php if (isset($errors['quantity'])): ?>
                                        <div class="invalid-feedback"><?= $errors['quantity'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea id="description" name="description" class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
                                              style="height: 150px;" placeholder="<?= __('Mô tả') ?>" 
                                              required><?= $_POST['description'] ?? $product->description ?></textarea>
                                    <label for="description">
                                        <i class="bi bi-file-text me-1"></i><?= __('Mô tả') ?>
                                    </label>
                                    <?php if (isset($errors['description'])): ?>
                                        <div class="invalid-feedback"><?= $errors['description'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-center mt-4 gap-2">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-save me-2"></i><?= __('Lưu thay đổi') ?>
                    </button>
                    <a href="/project1/admin/product/list" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-arrow-left me-2"></i><?= __('Quay lại danh sách') ?>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-floating > label {
        padding-left: 1rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    .current-image-container, #imagePreview {
        transition: all 0.3s ease;
    }
    .image-upload-btn label {
        cursor: pointer;
        transition: all 0.2s;
    }
    .image-upload-btn label:hover {
        background-color: #4e73df;
        color: white;
    }
</style>

<script>
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewContainer = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const currentImageContainer = document.querySelector('.current-image-container');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'flex';
                currentImageContainer.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
            currentImageContainer.style.display = 'flex';
        }
    });
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?> 