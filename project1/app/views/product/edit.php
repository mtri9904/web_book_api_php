<?php include 'app/views/shares/header.php'; ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-gradient text-white py-3" style="background-color: #4e73df;">
                    <h2 class="mb-0 text-center fw-bold">
                        <i class="bi bi-pencil-square me-2"></i><?php echo __('Sửa sản phẩm'); ?>
                    </h2>
                </div>
                
                <div class="card-body p-4">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo __('Lỗi!'); ?></h5>
                            <ul class="mb-0 ps-3">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/project1/Product/update" enctype="multipart/form-data" onsubmit="return validateForm();" class="needs-validation" novalidate>
                        <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                        
                        <div class="row g-4">
                            <!-- Phần hình ảnh bên trái -->
                            <div class="col-md-4">
                                <div class="image-upload-container mb-3">
                                    <label class="d-block fw-bold mb-2">
                                        <i class="bi bi-image me-1"></i><?php echo __('Hình ảnh sản phẩm'); ?>
                                    </label>
                                    
                                    <div class="text-center">
                                        <!-- Hiển thị hình ảnh hiện tại -->
                                        <div class="current-image-container mb-3 p-3 bg-light rounded d-flex align-items-center justify-content-center" style="min-height: 250px;">
                                            <?php if ($product->image): ?>
                                                <img id="currentImage" src="/project1/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                                    alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
                                                    class="img-fluid rounded" style="max-height: 250px; object-fit: contain;">
                                            <?php else: ?>
                                                <div class="text-center text-muted">
                                                    <i class="bi bi-image" style="font-size: 4rem;"></i>
                                                    <p class="mt-2"><?php echo __('Không có hình ảnh'); ?></p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Hiển thị hình ảnh mới (preview) -->
                                        <div id="imagePreview" class="mb-3 p-3 bg-light rounded d-flex align-items-center justify-content-center" style="min-height: 250px; display: none;">
                                            <img id="previewImg" src="#" alt="<?php echo __('Hình ảnh mới'); ?>" class="img-fluid rounded" style="max-height: 250px; object-fit: contain;">
                                        </div>
                                        
                                        <div class="image-upload-btn">
                                            <label for="image" class="btn btn-outline-primary w-100">
                                                <i class="bi bi-upload me-1"></i><?php echo __('Chọn hình ảnh mới'); ?>
                                            </label>
                                            <input type="file" id="image" name="image" class="form-control d-none" accept="image/*">
                                            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        
                                        <div id="removeImageBtn" class="mt-2" <?php echo empty($product->image) ? 'style="display:none;"' : ''; ?>>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                                <i class="bi bi-trash me-1"></i><?php echo __('Xóa hình ảnh'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Thông tin sản phẩm bên phải -->
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" id="name" name="name" class="form-control" 
                                                   value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
                                                   placeholder="<?php echo __('Tên sản phẩm'); ?>" required>
                                            <label for="name">
                                                <i class="bi bi-tag me-1"></i><?php echo __('Tên sản phẩm'); ?>
                                            </label>
                                            <div class="invalid-feedback"><?php echo __('Vui lòng nhập tên sản phẩm'); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" id="price" name="price" class="form-control" 
                                                   step="0.01" value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>" 
                                                   placeholder="<?php echo __('Giá (VND)'); ?>" required>
                                            <label for="price">
                                                <i class="bi bi-currency-dollar me-1"></i><?php echo __('Giá (VND)'); ?>
                                            </label>
                                            <div class="invalid-feedback"><?php echo __('Giá phải lớn hơn 0'); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select id="category_id" name="category_id" class="form-select" required>
                                                <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category->id; ?>" 
                                                    <?php echo $category->id == $product->category_id ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="category_id">
                                                <i class="bi bi-grid me-1"></i><?php echo __('Danh mục'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" id="quantity" name="quantity" class="form-control"
                                                min="0" value="<?php echo htmlspecialchars($product->quantity, ENT_QUOTES, 'UTF-8'); ?>" required>
                                            <label for="quantity">
                                                <i class="bi bi-boxes me-1"></i><?php echo __('Số lượng'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <textarea id="description" name="description" class="form-control" style="height: 150px;" 
                                                      placeholder="<?php echo __('Mô tả'); ?>" required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                            <label for="description">
                                                <i class="bi bi-file-text me-1"></i><?php echo __('Mô tả'); ?>
                                            </label>
                                            <div class="invalid-feedback"><?php echo __('Vui lòng nhập mô tả sản phẩm'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4 gap-2">
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="bi bi-check-circle me-2"></i><?php echo __('Lưu thay đổi'); ?>
                            </button>
                            <a href="/project1/Product/list" class="btn btn-outline-secondary px-4 py-2">
                                <i class="bi bi-arrow-left me-2"></i><?php echo __('Quay lại danh sách'); ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
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
// Xem trước hình ảnh mới
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const currentImageContainer = document.querySelector('.current-image-container');
    const removeBtn = document.getElementById('removeImageBtn');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.style.display = 'flex';
            currentImageContainer.style.display = 'none';
            removeBtn.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
        currentImageContainer.style.display = 'flex';
    }
});

// Xóa hình ảnh
function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('previewImg').src = '';
    document.getElementById('imagePreview').style.display = 'none';
    
    const currentImage = document.getElementById('currentImage');
    if (currentImage) {
        currentImage.parentElement.innerHTML = `
            <div class="text-center text-muted">
                <i class="bi bi-image" style="font-size: 4rem;"></i>
                <p class="mt-2"><?php echo __('Không có hình ảnh'); ?></p>
            </div>
        `;
    }
    
    // Đánh dấu xóa hình ảnh hiện tại
    document.querySelector('input[name="existing_image"]').value = '';
    document.getElementById('removeImageBtn').style.display = 'none';
}

// Hàm validate form
function validateForm() {
    const name = document.getElementById('name').value.trim();
    const description = document.getElementById('description').value.trim();
    const price = parseFloat(document.getElementById('price').value);
    const quantity = parseInt(document.getElementById('quantity').value);
    
    let isValid = true;
    
    if (name === '') {
        document.getElementById('name').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('name').classList.remove('is-invalid');
    }
    
    if (description === '') {
        document.getElementById('description').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('description').classList.remove('is-invalid');
    }
    
    if (isNaN(price) || price <= 0) {
        document.getElementById('price').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('price').classList.remove('is-invalid');
    }
    
    if (isNaN(quantity) || quantity < 0) {
        document.getElementById('quantity').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('quantity').classList.remove('is-invalid');
    }
    
    return isValid;
}

// Bootstrap validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>
<?php include 'app/views/shares/footer.php'; ?>