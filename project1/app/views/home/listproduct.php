<?php include 'app/views/shares/header.php'; ?>
<h1 class="mb-4 text-center text-warning"><?php echo __('BEST SELLER'); ?></h1>
<div class="container mb-5 product-list-container">
    <!-- Thanh tìm kiếm -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" 
                   placeholder="<?php echo __('Tìm kiếm sách...'); ?>" 
                   style="border-color: #00ffcc; background: rgba(255, 255, 255, 0.9); color: #1a2a44;">
        </div>
    </div>
    <!-- Nút danh mục -->
    <div class="row justify-content-center mb-4">
        <div class="col-auto">
            <a href="/project1/shop/listproduct" class="btn btn-outline-primary m-1 fw-bold"><?php echo __('Tất cả'); ?></a>
            <?php foreach ($categories as $cat): ?>
                <a href="/project1/shop/listproduct/<?php echo $cat->id; ?>" class="btn btn-outline-primary m-1 fw-bold">
                    <?php echo htmlspecialchars($cat->name, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="row justify-content-center mb-4">
        <div class="col-md-10">
            <div class="row g-2 align-items-end">
                <div class="col-sm-3">
                    <label class="form-label mb-1" for="minPrice"><?php echo __('Giá từ'); ?></label>
                    <input type="number" id="minPrice" class="form-control" placeholder="<?php echo __('Tối thiểu'); ?>">
                </div>
                <div class="col-sm-3">
                    <label class="form-label mb-1" for="maxPrice"><?php echo __('Đến giá'); ?></label>
                    <input type="number" id="maxPrice" class="form-control" placeholder="<?php echo __('Tối đa'); ?>">
                </div>
                <div class="col-sm-3">
                    <label class="form-label mb-1" for="sortSelect"><?php echo __('Sắp xếp'); ?></label>
                    <select id="sortSelect" class="form-select">
                        <option value=""><?php echo __('-- Chọn --'); ?></option>
                        <option value="price-asc"><?php echo __('Giá tăng dần'); ?></option>
                        <option value="price-desc"><?php echo __('Giá giảm dần'); ?></option>
                        <option value="name-az"><?php echo __('Tên A-Z'); ?></option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-primary w-100" id="filterBtn" type="button"><?php echo __('Lọc/Sắp xếp'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- Danh sách sản phẩm -->
    <div class="row g-4" id="productList">
        <?php if (empty($products)): ?>
            <div class="col-12 text-center text-white">
                <p><?php echo __('Không có sách nào trong danh sách.'); ?></p>
            </div>
        <?php else: ?>
            <?php foreach ($products as $i => $product): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 product-item" data-index="<?php echo $i; ?>" style="display:none;">
                <div class="card h-100 shadow-sm border-0 position-relative" style="background: rgba(255, 255, 255, 0.95); color: #1a2a44; transition: transform 0.3s, box-shadow 0.3s;">
                    <!-- Badge danh mục -->
                    <span class="position-absolute badge bg-primary" style="top: 10px; left: 10px; z-index: 10;">
                        <?php echo htmlspecialchars($product->category_name ?? 'Sách', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <!-- Hình ảnh sản phẩm -->
                    <div class="card-img-container">
                        <img src="<?php echo htmlspecialchars($product->image ? '/project1/' . $product->image : 'https://via.placeholder.com/300x200', ENT_QUOTES, 'UTF-8'); ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
                             style="max-height: 180px; object-fit: contain;">
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <!-- Tên sản phẩm -->
                        <h5 class="card-title mb-2" style="font-size: 1.25rem; font-weight: bold; height: 3rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            <?php echo htmlspecialchars(substr($product->name, 0, 50) . (strlen($product->name) > 50 ? '...' : ''), ENT_QUOTES, 'UTF-8'); ?>
                        </h5>
                    </div>
                    <div class="card-footer bg-transparent border-0 p-3 pt-0">
                        <!-- Giá -->
                        <p class="card-text fw-bold text-black fs-5 mb-0">
                            <?php echo __('Giá:'); ?> <?php echo number_format($product->price, 0, ',', '.'); ?> <?php echo __('VND'); ?>
                        </p>
                        <p class="card-text text-secondary mb-0">
                            <?php echo __('Số lượng:'); ?> <?php echo (int)$product->quantity; ?>
                        </p>
                        
                        <?php if (isset($product->average_rating) && $product->average_rating > 0): ?>
                        <div class="d-flex align-items-center mb-2 mt-1">
                            <div class="ratings">
                                <?php
                                $rating = round($product->average_rating * 2) / 2; // Làm tròn đến 0.5
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $rating) {
                                        echo '<i class="bi bi-star-fill text-warning"></i>';
                                    } elseif ($i - 0.5 == $rating) {
                                        echo '<i class="bi bi-star-half text-warning"></i>';
                                    } else {
                                        echo '<i class="bi bi-star text-warning"></i>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="ms-1 text-muted small">
                                <span><?php echo number_format($product->average_rating, 1); ?></span>
                                <span class="ms-1">(<?php echo $product->review_count; ?>)</span>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="mt-1 mb-2 text-muted small">
                            <i class="bi bi-star text-muted me-1"></i><?php echo __('Chưa có đánh giá'); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <!-- Nút hành động -->
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between p-3 pt-0">
                        <a href="/project1/shop/showproduct/<?php echo $product->id; ?>" 
                           class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                            <i class="bi bi-eye"></i> <?php echo __('Chi tiết'); ?>
                        </a>
                        <button onclick="addToCart(<?php echo $product->id; ?>)" 
                                class="btn btn-success btn-sm d-flex align-items-center gap-1 add-to-cart-btn">
                            <i class="bi bi-cart-plus"></i> <?php echo __('Thêm vào giỏ'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <!-- Thông báo không tìm thấy -->
    <div class="row mt-4" id="noResults" style="display: none;">
        <div class="col-12 text-center text-white">
            <p><?php echo __('Không tìm thấy sách phù hợp với từ khóa.'); ?></p>
        </div>
    </div>
</div>
<!-- Include Toastify.js for notifications -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="/project1/public/js/realtime.js"></script>
<style>
.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 20px rgba(0, 255, 204, 0.4), 0 0 20px rgba(0, 255, 204, 0.2);
}
.card-img-top {
    transition: transform 0.4s ease-in-out;
    background-color: #ffffff;
    padding: 10px;
    max-height: 200px;
    height: auto;
    object-position: center;
}
.card:hover .card-img-top {
    transform: scale(1.05);
}
.card-title {
    height: 3rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}
.card:hover .card-title {
    color: #00ffcc;
    transition: color 0.3s;
}
.card:hover .btn-primary {
    background: #ffd700;
    border-color: #ffd700;
    color: #1a2a44;
}
.card:hover .btn-success {
    background: #218838;
}
.form-control:focus {
    border-color: #ffd700;
    box-shadow: 0 0 5px rgba(255, 215, 0, 0.5);
}

/* Hiệu ứng highlight cho sản phẩm mới */
@keyframes highlightNew {
    0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
    70% { box-shadow: 0 0 0 15px rgba(220, 53, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}

/* Hiệu ứng highlight cho sản phẩm cập nhật */
@keyframes highlightUpdate {
    0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
    100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
}

.product-item.new-product .card {
    animation: highlightNew 3s ease-in-out;
}

.card-img-container {
    height: 200px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ffffff;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    padding: 15px;
    position: relative;
}

/* Badge NEW */
.badge-new {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
    background-color: #dc3545;
    color: white;
    border-radius: 20px;
    padding: 5px 10px;
    font-size: 0.8rem;
    font-weight: bold;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>
<script>
// Hàm chuyển đổi chuỗi có dấu thành không dấu
function removeDiacritics(str) {
    return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd').replace(/Đ/g, 'D');
}

// Lazy loading sản phẩm
let productsPerLoad = 8;
let firstLoad = 12;
let currentIndex = 0;
let loading = false;
let lastVisibleItemIndex = 0; // Lưu chỉ số của item cuối cùng đang hiển thị

function showProductsLazy() {
    let items = document.querySelectorAll('.product-item');
    if (items.length === 0) return;
    
    // Lưu lại số lượng item đã hiển thị trước đó
    let visibleItemsCount = Array.from(items).filter(item => item.style.display !== 'none').length;
    
    // Nếu là lần đầu tiên load hoặc đã reset lại currentIndex
    if (currentIndex === 0) {
        // Ẩn tất cả sản phẩm trước
        items.forEach(item => item.style.display = 'none');
        
        // Hiển thị số lượng sản phẩm đầu tiên
        let shown = 0;
        for (let i = 0; i < items.length && shown < firstLoad; i++, shown++) {
            items[i].style.display = '';
        }
        currentIndex = shown;
    } else {
        // Load thêm sản phẩm khi cuộn
        let shown = 0;
        for (let i = currentIndex; i < items.length && shown < productsPerLoad; i++, shown++) {
            items[i].style.display = '';
        }
        currentIndex += shown;
    }
    
    // Cập nhật chỉ số của item cuối cùng đang hiển thị
    lastVisibleItemIndex = Math.min(currentIndex, items.length) - 1;
}

function handleScrollLazy() {
    if (loading) return;
    let scrollY = window.scrollY || window.pageYOffset;
    let windowH = window.innerHeight;
    let docH = document.body.offsetHeight;
    
    // Kiểm tra xem người dùng đã cuộn gần đến cuối trang chưa
    if (scrollY + windowH + 200 >= docH) { // 200px trước khi chạm đáy
        loading = true;
        setTimeout(() => {
            showProductsLazy();
            loading = false;
        }, 300); // Giả lập loading
    }
}

// Tải lại vị trí cuộn sau khi cập nhật danh sách sản phẩm
function restoreScrollPosition(scrollPosition) {
    // Đảm bảo các sản phẩm đã được hiển thị trước khi khôi phục vị trí cuộn
    let items = document.querySelectorAll('.product-item');
    let visibleCount = 0;
    
    // Đếm số lượng sản phẩm đang hiển thị
    items.forEach(item => {
        if (item.style.display !== 'none') {
            visibleCount++;
        }
    });
    
    // Nếu số lượng sản phẩm hiển thị ít hơn chỉ số cuối cùng đã lưu
    // thì hiển thị thêm sản phẩm để đảm bảo người dùng thấy được vị trí cuộn trước đó
    if (visibleCount < lastVisibleItemIndex + 1) {
        for (let i = visibleCount; i <= lastVisibleItemIndex && i < items.length; i++) {
            items[i].style.display = '';
            currentIndex = Math.max(currentIndex, i + 1);
        }
    }
    
    // Khôi phục vị trí cuộn
    window.scrollTo(0, scrollPosition);
}

document.addEventListener('DOMContentLoaded', function() {
    // Hiển thị thông báo voucher không hợp lệ nếu có
    <?php if (isset($_SESSION['voucher_invalid'])): ?>
    Toastify({
        text: "<?php echo $_SESSION['voucher_invalid']; ?>",
        duration: 4000,
        gravity: "top",
        position: "right",
        backgroundColor: "#fd7e14",
        stopOnFocus: true
    }).showToast();
    <?php unset($_SESSION['voucher_invalid']); ?>
    <?php endif; ?>
    
    // Khởi tạo mảng sản phẩm mới trong sessionStorage nếu chưa có
    if (!sessionStorage.getItem('newProducts')) {
        sessionStorage.setItem('newProducts', JSON.stringify([]));
    }
    
    // Kiểm tra và thêm badge "New" cho các sản phẩm mới
    const newProductsJson = sessionStorage.getItem('newProducts');
    if (newProductsJson) {
        const newProducts = JSON.parse(newProductsJson);
        if (newProducts.length > 0) {
            newProducts.forEach(productId => {
                const productElement = document.querySelector(`.product-item a[href*="/shop/showproduct/${productId}"]`)?.closest('.product-item');
                if (productElement) {
                    // Thêm class new-product
                    productElement.classList.add('new-product');
                    
                    // Thêm badge NEW nếu chưa có
                    const cardElement = productElement.querySelector('.card');
                    if (cardElement && !cardElement.querySelector('.badge-new')) {
                        const badgeNew = document.createElement('span');
                        badgeNew.className = 'badge-new';
                        badgeNew.textContent = 'NEW';
                        cardElement.appendChild(badgeNew);
                    }
                }
            });
        }
    }
    
    // Ẩn tất cả sản phẩm ban đầu
    document.querySelectorAll('.product-item').forEach(function(item) {
        item.style.display = 'none';
    });
    
    // Hiện 12 sản phẩm đầu tiên
    showProductsLazy();
    // Lắng nghe sự kiện cuộn trang
    window.addEventListener('scroll', function() {
        let items = document.querySelectorAll('.product-item');
        if (currentIndex < items.length) handleScrollLazy();
    });
});

// Lắng nghe sự kiện trước khi rời khỏi trang để xóa danh sách sản phẩm mới
window.addEventListener('beforeunload', function() {
    // Xóa danh sách sản phẩm mới khi rời khỏi trang
    sessionStorage.removeItem('newProducts');
});

// Xử lý thông báo WebSocket cho voucher
if (typeof realtime !== 'undefined') {
    // Lắng nghe sự kiện cập nhật voucher
    realtime.on('update_voucher', function(data) {
        if (data && data.voucher) {
            <?php if (!empty($_SESSION['voucher_code'])): ?>
            // So sánh với mã voucher hiện tại trong session
            const currentVoucherCode = "<?php echo $_SESSION['voucher_code']; ?>";
            
            // Nếu voucher được cập nhật trùng với voucher đang sử dụng
            if (currentVoucherCode === data.voucher.code) {
                // Kiểm tra xem voucher có bị vô hiệu hóa không
                if (data.voucher.is_active === 0 || new Date(data.voucher.end_date) < new Date()) {
                    // Tự động gỡ bỏ voucher không hợp lệ
                    fetch('/project1/shop/removeVoucher', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            // Hiển thị thông báo
                            Toastify({
                                text: "<?php echo __('Mã giảm giá đã hết hạn hoặc bị vô hiệu hóa và đã được gỡ bỏ'); ?>",
                                duration: 4000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#fd7e14",
                                stopOnFocus: true
                            }).showToast();
                        }
                    });
                }
            }
            <?php endif; ?>
        }
    });
    
    // Lắng nghe sự kiện xóa voucher
    realtime.on('delete_voucher', function(data) {
        if (data && data.voucher_code) {
            <?php if (!empty($_SESSION['voucher_code'])): ?>
            // So sánh với mã voucher hiện tại trong session
            const currentVoucherCode = "<?php echo $_SESSION['voucher_code']; ?>";
            
            // Nếu voucher bị xóa trùng với voucher đang sử dụng
            if (currentVoucherCode === data.voucher_code) {
                // Tự động gỡ bỏ voucher không hợp lệ
                fetch('/project1/shop/removeVoucher', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Hiển thị thông báo
                        Toastify({
                            text: "<?php echo __('Mã giảm giá đã bị xóa và đã được gỡ bỏ khỏi giỏ hàng của bạn'); ?>",
                            duration: 4000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#dc3545",
                            stopOnFocus: true
                        }).showToast();
                    }
                });
            }
            <?php endif; ?>
        }
    });
}

// Thêm sản phẩm vào giỏ hàng bằng AJAX
function addToCart(productId) {
    fetch('/project1/shop/addtoCart/' + productId, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.login_required) {
            window.location.href = '/project1/account/login';
            return;
        }
        Toastify({
            text: data.message,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: data.success ? "#28a745" : "#dc3545",
            stopOnFocus: true
        }).showToast();
    })
    .catch(error => {
        Toastify({
            text: "<?php echo __('Đã xảy ra lỗi khi thêm vào giỏ hàng!'); ?>",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#dc3545",
            stopOnFocus: true
        }).showToast();
    });
}

// Lọc sản phẩm realtime khi nhập từ khóa (hỗ trợ không dấu)
document.getElementById('searchInput').addEventListener('input', function() {
    var keyword = removeDiacritics(this.value.toLowerCase().trim());
    var productItems = document.querySelectorAll('.product-item');
    var noResults = document.getElementById('noResults');
    var hasVisibleItems = false;

    productItems.forEach(function(item) {
        var name = removeDiacritics(item.querySelector('.card-title').innerText.toLowerCase());
        var category = removeDiacritics(item.querySelector('.badge').innerText.toLowerCase());
        if (name.includes(keyword) || category.includes(keyword)) {
            item.style.display = '';
            hasVisibleItems = true;
        } else {
            item.style.display = 'none';
        }
    });

    // Hiển thị thông báo nếu không có kết quả
    noResults.style.display = hasVisibleItems ? 'none' : 'block';
    // Reset lazy loading
    currentIndex = 0;
    showProductsLazy();
});

document.getElementById('filterBtn').addEventListener('click', filterAndSortProducts);
document.getElementById('sortSelect').addEventListener('change', filterAndSortProducts);
document.getElementById('minPrice').addEventListener('input', filterAndSortProducts);
document.getElementById('maxPrice').addEventListener('input', filterAndSortProducts);

function filterAndSortProducts() {
    var keyword = removeDiacritics(document.getElementById('searchInput').value.toLowerCase().trim());
    var minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
    var maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
    var sortType = document.getElementById('sortSelect').value;
    var productItems = Array.from(document.querySelectorAll('.product-item'));
    var noResults = document.getElementById('noResults');
    var hasVisibleItems = false;

    // Lọc theo từ khóa và giá
    productItems.forEach(function(item) {
        var name = removeDiacritics(item.querySelector('.card-title').innerText.toLowerCase());
        var category = removeDiacritics(item.querySelector('.badge').innerText.toLowerCase());
        var price = parseFloat(item.querySelector('.card-text.fw-bold').innerText.replace(/[^\d]/g, '')) || 0;
        var matchKeyword = (name.includes(keyword) || category.includes(keyword));
        var matchPrice = (price >= minPrice && price <= maxPrice);
        if (matchKeyword && matchPrice) {
            item.style.display = '';
            hasVisibleItems = true;
        } else {
            item.style.display = 'none';
        }
    });

    // Sắp xếp
    var visibleItems = productItems.filter(function(item) {
        return item.style.display !== 'none';
    });

    if (sortType === 'price-asc') {
        visibleItems.sort(function(a, b) {
            var pa = parseFloat(a.querySelector('.card-text.fw-bold').innerText.replace(/[^\d]/g, '')) || 0;
            var pb = parseFloat(b.querySelector('.card-text.fw-bold').innerText.replace(/[^\d]/g, '')) || 0;
            return pa - pb;
        });
    } else if (sortType === 'price-desc') {
        visibleItems.sort(function(a, b) {
            var pa = parseFloat(a.querySelector('.card-text.fw-bold').innerText.replace(/[^\d]/g, '')) || 0;
            var pb = parseFloat(b.querySelector('.card-text.fw-bold').innerText.replace(/[^\d]/g, '')) || 0;
            return pb - pa;
        });
    } else if (sortType === 'name-az') {
        visibleItems.sort(function(a, b) {
            var na = a.querySelector('.card-title').innerText.toLowerCase();
            var nb = b.querySelector('.card-title').innerText.toLowerCase();
            return na.localeCompare(nb);
        });
    }

    // Đưa lại thứ tự vào DOM
    var productList = document.getElementById('productList');
    visibleItems.forEach(function(item) {
        productList.appendChild(item);
    });

    // Hiển thị thông báo nếu không có kết quả
    noResults.style.display = hasVisibleItems ? 'none' : 'block';

    // Reset lazy loading
    currentIndex = 0;
    showProductsLazy();
}

// Khi bấm vào card sẽ chuyển đến trang chi tiết sản phẩm
document.querySelectorAll('.product-item .card').forEach(function(card) {
    card.style.cursor = 'pointer';
    card.addEventListener('click', function(e) {
        // Không chuyển trang nếu bấm vào nút bên trong card
        if (e.target.closest('a, button')) return;
        var detailBtn = card.querySelector('.btn-primary');
        if (detailBtn) {
            window.location.href = detailBtn.getAttribute('href');
        }
    });
});

// Kết nối WebSocket khi trang đã tải xong
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra xem script realtime.js đã được tải chưa
    if (typeof realtime !== 'undefined') {
        // Kết nối với vai trò user
        realtime.connect('user');
        
        // Xử lý sự kiện khi nhận được thông báo sản phẩm mới
        realtime.on('new_product', function(data) {
            // Đánh dấu sản phẩm mới
            if (data.product && data.product.id) {
                markProductAsNew(data.product.id);
            }
            
            // Hiển thị thông báo với tùy chọn xem sản phẩm mới
            Toastify({
                text: "<?php echo __('Sản phẩm mới'); ?> \"" + data.product.name + "\" <?php echo __('vừa được thêm vào hệ thống.'); ?>",
                duration: 5000,
                gravity: "top",
                position: "right",
                backgroundColor: "#28a745",
                stopOnFocus: true,
                onClick: function() {
                    // Khi click vào thông báo, tải lại danh sách và cuộn lên đầu trang
                    refreshProductList();
                    setTimeout(() => {
                        window.scrollTo(0, 0);
                    }, 500);
                },
                close: true
            }).showToast();
            
            // Hiển thị nút "Xem sản phẩm mới" ở góc màn hình
            const newProductBtn = document.createElement('button');
            newProductBtn.className = 'btn btn-success position-fixed';
            newProductBtn.style.bottom = '20px';
            newProductBtn.style.right = '20px';
            newProductBtn.style.zIndex = '1050';
            newProductBtn.style.borderRadius = '50px';
            newProductBtn.style.padding = '10px 15px';
            newProductBtn.style.boxShadow = '0 4px 10px rgba(0,0,0,0.2)';
            newProductBtn.innerHTML = '<i class="bi bi-arrow-up-circle me-1"></i> <?php echo __('Xem sản phẩm mới'); ?>';
            
            newProductBtn.addEventListener('click', function() {
                // Tải lại danh sách và cuộn lên đầu trang
                refreshProductList();
                setTimeout(() => {
                    window.scrollTo(0, 0);
                    // Xóa nút sau khi click
                    document.body.removeChild(newProductBtn);
                }, 500);
            });
            
            // Thêm nút vào body
            document.body.appendChild(newProductBtn);
            
            // Tự động xóa nút sau 10 giây
            setTimeout(() => {
                if (document.body.contains(newProductBtn)) {
                    document.body.removeChild(newProductBtn);
                }
            }, 10000);
            
            // Tải lại danh sách sản phẩm (nhưng không tự động cuộn lên đầu)
            refreshProductList();
        });
        
        // Xử lý sự kiện khi nhận được thông báo cập nhật sản phẩm
        realtime.on('update_product', function(data) {
            // Hiển thị thông báo
            Toastify({
                text: "<?php echo __('Sản phẩm'); ?> \"" + data.product.name + "\" <?php echo __('vừa được cập nhật.'); ?>",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#0d6efd",
                stopOnFocus: true
            }).showToast();
            
            // Tải lại danh sách sản phẩm
            refreshProductList();
        });
        
        // Xử lý sự kiện khi nhận được thông báo xóa sản phẩm
        realtime.on('delete_product', function(data) {
            // Hiển thị thông báo với tên sản phẩm nếu có
            const productName = data.product_name || (data.product ? data.product.name : null);
            Toastify({
                text: productName 
                    ? "<?php echo __('Sản phẩm'); ?> \"" + productName + "\" <?php echo __('vừa bị xóa khỏi hệ thống.'); ?>"
                    : "<?php echo __('Một sản phẩm vừa bị xóa khỏi hệ thống.'); ?>",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#dc3545",
                stopOnFocus: true
            }).showToast();
            
            // Lấy ID sản phẩm bị xóa
            const productId = data.product_id || (data.product ? data.product.id : null);
            
            // Kiểm tra nếu sản phẩm bị xóa có trong giỏ hàng và xóa nó
            if (productId) {
                // Kiểm tra và xóa sản phẩm từ giỏ hàng (nếu có)
                removeDeletedProductFromSessionCart(productId);
            }
            
            // Tải lại danh sách sản phẩm
            refreshProductList();
        });
    } else {
        console.warn('<?php echo __("WebSocket client (realtime.js) chưa được tải. Tính năng cập nhật thời gian thực sẽ không hoạt động."); ?>');
    }
});

// Hàm kiểm tra xem một sản phẩm có phải là mới thêm vào không
function isNewlyAddedProduct(productId) {
    // Lấy danh sách sản phẩm mới từ sessionStorage
    const newProductsJson = sessionStorage.getItem('newProducts');
    if (!newProductsJson) return false;
    
    const newProducts = JSON.parse(newProductsJson);
    return newProducts.includes(parseInt(productId));
}

// Hàm đánh dấu sản phẩm mới
function markProductAsNew(productId) {
    // Lấy danh sách sản phẩm mới từ sessionStorage
    let newProducts = [];
    const newProductsJson = sessionStorage.getItem('newProducts');
    
    if (newProductsJson) {
        newProducts = JSON.parse(newProductsJson);
    }
    
    // Thêm sản phẩm mới vào danh sách nếu chưa có
    if (!newProducts.includes(parseInt(productId))) {
        newProducts.push(parseInt(productId));
        // Lưu lại vào sessionStorage
        sessionStorage.setItem('newProducts', JSON.stringify(newProducts));
    }
}

// Hàm tải lại danh sách sản phẩm
function refreshProductList() {
    // Lưu lại vị trí cuộn trang hiện tại
    const scrollPosition = window.pageYOffset;
    
    // Lưu lại các giá trị lọc hiện tại
    const searchKeyword = document.getElementById('searchInput').value;
    const minPrice = document.getElementById('minPrice').value;
    const maxPrice = document.getElementById('maxPrice').value;
    const sortType = document.getElementById('sortSelect').value;
    
    // Lưu lại danh sách sản phẩm hiện tại để so sánh sau này
    const currentProducts = [];
    document.querySelectorAll('.product-item').forEach(item => {
        const productId = item.querySelector('a[href*="/shop/showproduct/"]').href.split('/').pop();
        currentProducts.push(parseInt(productId));
    });
    
    // Lấy thông tin danh mục hiện tại từ URL
    const currentUrl = new URL(window.location.href);
    const pathParts = currentUrl.pathname.split('/');
    const categoryId = !isNaN(pathParts[pathParts.length - 1]) ? pathParts[pathParts.length - 1] : null;
    
    // Tạo URL cho API lấy sản phẩm
    let apiUrl = '/project1/shop/getProductsJson';
    if (categoryId) {
        apiUrl += '/' + categoryId;
    }
    
    // Không hiện thông báo đang tải nữa
    let loadingToast = null;
    
    // Gọi API để lấy dữ liệu mới
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Tìm các sản phẩm mới được thêm vào
                const newProducts = data.products.filter(product => 
                    !currentProducts.includes(parseInt(product.id))
                );
                
                // Nếu có sản phẩm mới
                if (newProducts.length > 0) {
                    // Hiển thị thông báo
                    Toastify({
                        text: newProducts.length > 1
                            ? "<?php echo __('Đã thêm'); ?> " + newProducts.length + " <?php echo __('sản phẩm mới!'); ?>"
                            : "<?php echo __('Đã thêm 1 sản phẩm mới!'); ?>",
                        duration: 5000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#28a745",
                        stopOnFocus: true,
                        onClick: function() {
                            // Khi click vào thông báo, cuộn lên đầu trang để xem sản phẩm mới
                            window.scrollTo(0, 0);
                        }
                    }).showToast();
                    
                    // Đánh dấu các sản phẩm mới
                    newProducts.forEach(product => {
                        markProductAsNew(product.id);
                    });
                    
                    // Thêm sản phẩm mới vào đầu danh sách
                    const productList = document.getElementById('productList');
                    
                    // Thêm từng sản phẩm mới vào đầu danh sách
                    newProducts.forEach((product, index) => {
                        const productItem = createProductItem(product, index);
                        
                        // Thêm vào đầu danh sách
                        if (productList.firstChild) {
                            productList.insertBefore(productItem, productList.firstChild);
                        } else {
                            productList.appendChild(productItem);
                        }
                    });
                    
                    // Kiểm tra và cập nhật các sản phẩm hiện có
                    let hasUpdates = false;
                    data.products.forEach(newProduct => {
                        // Bỏ qua các sản phẩm mới đã được thêm ở trên
                        if (newProducts.find(p => p.id === newProduct.id)) {
                            return;
                        }
                        
                        // Tìm phần tử sản phẩm hiện tại
                        const productItem = document.querySelector(`.product-item a[href*="/shop/showproduct/${newProduct.id}"]`)?.closest('.product-item');
                        if (!productItem) return;
                        
                        // Kiểm tra và cập nhật thông tin
                        const priceElement = productItem.querySelector('.card-text.fw-bold');
                        const quantityElement = productItem.querySelector('.card-text.text-secondary');
                        
                        if (priceElement) {
                            const formattedPrice = new Intl.NumberFormat('vi-VN').format(newProduct.price);
                            if (!priceElement.innerText.includes(formattedPrice)) {
                                priceElement.innerHTML = "<?php echo __('Giá:'); ?> " + formattedPrice + " <?php echo __('VND'); ?>";
                                hasUpdates = true;
                            }
                        }
                        
                        if (quantityElement) {
                            if (!quantityElement.innerText.includes(newProduct.quantity)) {
                                quantityElement.innerHTML = "<?php echo __('Số lượng:'); ?> " + newProduct.quantity;
                                hasUpdates = true;
                            }
                        }
                        
                        if (hasUpdates) {
                            // Thêm hiệu ứng highlight
                            productItem.style.animation = 'highlightUpdate 2s';
                        }
                    });
                    
                    // Hiệu ứng highlight cho sản phẩm mới
                    document.querySelectorAll('.product-item.new-product').forEach(item => {
                        // Thêm hiệu ứng highlight
                        item.querySelector('.card').style.animation = 'highlightNew 3s';
                    });
                    
                    // Cập nhật lại chỉ số cho tất cả sản phẩm
                    document.querySelectorAll('.product-item').forEach((item, index) => {
                        item.dataset.index = index;
                    });
                    
                    // Reset lazy loading
                    currentIndex = 0;
                    showProductsLazy();
                    
                    // Áp dụng lại bộ lọc nếu có
                    if (searchKeyword || minPrice || maxPrice || sortType !== 'default') {
                        filterAndSortProducts();
                    }
                } else {
                    // Nếu không có sản phẩm mới, kiểm tra xem có sản phẩm nào bị xóa không
                    const deletedProducts = currentProducts.filter(id => 
                        !data.products.some(product => parseInt(product.id) === id)
                    );
                    
                    if (deletedProducts.length > 0) {
                        // Xóa các sản phẩm đã bị xóa khỏi DOM
                        deletedProducts.forEach(id => {
                            const item = document.querySelector(`.product-item a[href*="/shop/showproduct/${id}"]`);
                            if (item && item.closest('.product-item')) {
                                item.closest('.product-item').remove();
                            }
                        });
                        
                        // Hiển thị thông báo
                        Toastify({
                            text: deletedProducts.length > 1 
                                ? "<?php echo __('Đã xóa'); ?> " + deletedProducts.length + " <?php echo __('sản phẩm!'); ?>"
                                : "<?php echo __('Đã xóa 1 sản phẩm!'); ?>",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#dc3545",
                            stopOnFocus: true
                        }).showToast();
                        
                        // Cập nhật lại chỉ số cho tất cả sản phẩm
                        document.querySelectorAll('.product-item').forEach((item, index) => {
                            item.dataset.index = index;
                        });
                        
                        // Reset lazy loading
                        currentIndex = 0;
                        showProductsLazy();
                    } else {
                        // Kiểm tra xem có sản phẩm nào được cập nhật không
                        let hasUpdates = false;
                        
                        data.products.forEach(newProduct => {
                            const item = document.querySelector(`.product-item a[href*="/shop/showproduct/${newProduct.id}"]`);
                            if (item && item.closest('.product-item')) {
                                const productItem = item.closest('.product-item');
                                
                                // Cập nhật thông tin sản phẩm
                                const nameElement = productItem.querySelector('.card-title');
                                const priceElement = productItem.querySelector('.card-text.fw-bold');
                                const quantityElement = productItem.querySelector('.card-text.text-secondary');
                                
                                if (nameElement && nameElement.innerText !== (newProduct.name.length > 50 ? newProduct.name.substring(0, 50) + '...' : newProduct.name)) {
                                    nameElement.innerText = newProduct.name.length > 50 ? newProduct.name.substring(0, 50) + '...' : newProduct.name;
                                    hasUpdates = true;
                                }
                                
                                if (priceElement) {
                                    const formattedPrice = new Intl.NumberFormat('vi-VN').format(newProduct.price);
                                    if (!priceElement.innerText.includes(formattedPrice)) {
                                        priceElement.innerHTML = "<?php echo __('Giá:'); ?> " + formattedPrice + " <?php echo __('VND'); ?>";
                                        hasUpdates = true;
                                    }
                                }
                                
                                if (quantityElement) {
                                    if (!quantityElement.innerText.includes(newProduct.quantity)) {
                                        quantityElement.innerHTML = "<?php echo __('Số lượng:'); ?> " + newProduct.quantity;
                                        hasUpdates = true;
                                    }
                                }
                                
                                if (hasUpdates) {
                                    // Thêm hiệu ứng highlight
                                    productItem.style.animation = 'highlightUpdate 2s';
                                }
                            }
                        });
                        
                        if (hasUpdates) {
                            // Hiển thị thông báo
                            Toastify({
                                text: "<?php echo __('Đã cập nhật thông tin sản phẩm!'); ?>",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#0d6efd",
                                stopOnFocus: true
                            }).showToast();
                        } else {
                            // Hiển thị thông báo
                            Toastify({
                                text: "<?php echo __('Danh sách sản phẩm đã được cập nhật!'); ?>",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#28a745",
                                stopOnFocus: true
                            }).showToast();
                        }
                    }
                }
            } else {
                // Hiển thị thông báo lỗi
                Toastify({
                    text: "<?php echo __('Không thể cập nhật danh sách sản phẩm!'); ?>",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#dc3545",
                    stopOnFocus: true
                }).showToast();
            }
        })
        .catch(error => {
            console.error("<?php echo __('Lỗi khi cập nhật danh sách sản phẩm:'); ?>", error);
            
            // Hiển thị thông báo lỗi
            Toastify({
                text: "<?php echo __('Có lỗi xảy ra khi tải dữ liệu sản phẩm.'); ?>",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#dc3545",
                stopOnFocus: true
            }).showToast();
        })
        .finally(() => {
            // Đảm bảo xóa thông báo đang tải nếu có
            if (loadingToast && typeof loadingToast.hideToast === 'function') {
                loadingToast.hideToast();
            }
        });
}

// Hàm tạo phần tử sản phẩm mới
function createProductItem(product, index) {
    const productItem = document.createElement('div');
    productItem.className = 'col-12 col-sm-6 col-md-4 col-lg-3 product-item';
    
    // Kiểm tra nếu là sản phẩm mới
    const isNewProduct = isNewlyAddedProduct(product.id);
    if (isNewProduct) {
        productItem.classList.add('new-product');
    }
    
    productItem.dataset.index = index;
    
    const categoryName = product.category_name || 'Sách';
    const productImage = product.image ? `/project1/${product.image}` : 'https://via.placeholder.com/300x200';
    const productName = product.name.length > 50 ? product.name.substring(0, 50) + '...' : product.name;
    const formattedPrice = new Intl.NumberFormat('vi-VN').format(product.price);
    
    // Tạo HTML cho rating nếu có
    let ratingHTML = '';
    if (product.average_rating && product.average_rating > 0) {
        const rating = Math.round(product.average_rating * 2) / 2; // Làm tròn đến 0.5
        let starsHTML = '';
        
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                starsHTML += '<i class="bi bi-star-fill text-warning"></i>';
            } else if (i - 0.5 === rating) {
                starsHTML += '<i class="bi bi-star-half text-warning"></i>';
            } else {
                starsHTML += '<i class="bi bi-star text-warning"></i>';
            }
        }
        
        ratingHTML = `
            <div class="d-flex align-items-center mb-2 mt-1">
                <div class="ratings">
                    ${starsHTML}
                </div>
                <div class="ms-1 text-muted small">
                    <span>${product.average_rating.toFixed(1)}</span>
                    <span class="ms-1">(${product.review_count})</span>
                </div>
            </div>
        `;
    } else {
        ratingHTML = `
            <div class="mt-1 mb-2 text-muted small">
                <i class="bi bi-star text-muted me-1"></i><?php echo __('Chưa có đánh giá'); ?>
            </div>
        `;
    }
    
    productItem.innerHTML = `
        <div class="card h-100 shadow-sm border-0 position-relative" style="background: rgba(255, 255, 255, 0.95); color: #1a2a44; transition: transform 0.3s, box-shadow 0.3s;">
            <!-- Badge danh mục -->
            <span class="position-absolute badge bg-primary" style="top: 10px; left: 10px; z-index: 10;">
                ${categoryName}
            </span>
            ${isNewProduct ? '<span class="badge-new"><?php echo __("NEW"); ?></span>' : ''}
            <!-- Hình ảnh sản phẩm -->
            <div class="card-img-container">
                <img src="${productImage}" 
                     class="card-img-top" 
                     alt="${product.name}" 
                     style="max-height: 180px; object-fit: contain;">
            </div>
            <div class="card-body d-flex flex-column p-3">
                <!-- Tên sản phẩm -->
                <h5 class="card-title mb-2" style="font-size: 1.25rem; font-weight: bold; height: 3rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                    ${productName}
                </h5>
            </div>
            <div class="card-footer bg-transparent border-0 p-3 pt-0">
                <!-- Giá -->
                <p class="card-text fw-bold text-black fs-5 mb-0">
                    <?php echo __('Giá:'); ?> ${formattedPrice} <?php echo __('VND'); ?>
                </p>
                <p class="card-text text-secondary mb-0">
                    <?php echo __('Số lượng:'); ?> ${product.quantity}
                </p>
                
                ${ratingHTML}
            </div>
            <!-- Nút hành động -->
            <div class="card-footer bg-transparent border-0 d-flex justify-content-between p-3 pt-0">
                <a href="/project1/shop/showproduct/${product.id}" 
                   class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                    <i class="bi bi-eye"></i> <?php echo __('Chi tiết'); ?>
                </a>
                <button onclick="addToCart(${product.id})" 
                        class="btn btn-success btn-sm d-flex align-items-center gap-1 add-to-cart-btn">
                    <i class="bi bi-cart-plus"></i> <?php echo __('Thêm vào giỏ'); ?>
                </button>
            </div>
        </div>
    `;
    
    // Thêm sự kiện click cho card
    const card = productItem.querySelector('.card');
    card.style.cursor = 'pointer';
    card.addEventListener('click', function(e) {
        // Không chuyển trang nếu bấm vào nút bên trong card
        if (e.target.closest('a, button')) return;
        const detailBtn = card.querySelector('.btn-primary');
        if (detailBtn) {
            window.location.href = detailBtn.getAttribute('href');
        }
    });
    
    return productItem;
}

// Hàm lọc sản phẩm theo từ khóa
function filterProductsByKeyword(keyword) {
    const normalizedKeyword = removeDiacritics(keyword.toLowerCase().trim());
    const productItems = document.querySelectorAll('.product-item');
    const noResults = document.getElementById('noResults');
    let hasVisibleItems = false;

    productItems.forEach(function(item) {
        const name = removeDiacritics(item.querySelector('.card-title').innerText.toLowerCase());
        const category = removeDiacritics(item.querySelector('.badge').innerText.toLowerCase());
        if (name.includes(normalizedKeyword) || category.includes(normalizedKeyword)) {
            item.dataset.filtered = 'visible';
            hasVisibleItems = true;
        } else {
            item.dataset.filtered = 'hidden';
        }
    });

    // Hiển thị thông báo nếu không có kết quả
    noResults.style.display = hasVisibleItems ? 'none' : 'block';
    
    // Reset lazy loading và chỉ hiển thị các sản phẩm đã lọc
    currentIndex = 0;
    showFilteredProductsLazy();
}

// Hàm hiển thị lazy loading cho sản phẩm đã lọc
function showFilteredProductsLazy() {
    const items = document.querySelectorAll('.product-item[data-filtered="visible"]');
    if (items.length === 0) return;
    
    // Nếu là lần đầu tiên load hoặc đã reset lại currentIndex
    if (currentIndex === 0) {
        // Ẩn tất cả sản phẩm đã lọc trước
        items.forEach(item => item.style.display = 'none');
        
        // Hiển thị số lượng sản phẩm đầu tiên
        let shown = 0;
        for (let i = 0; i < items.length && shown < firstLoad; i++, shown++) {
            items[i].style.display = '';
        }
        currentIndex = shown;
    } else {
        // Load thêm sản phẩm khi cuộn
        let shown = 0;
        for (let i = currentIndex; i < items.length && shown < productsPerLoad; i++, shown++) {
            items[i].style.display = '';
        }
        currentIndex += shown;
    }
    
    // Cập nhật chỉ số của item cuối cùng đang hiển thị
    lastVisibleItemIndex = Math.min(currentIndex, items.length) - 1;
}

// Thêm hàm để xóa sản phẩm khỏi giỏ hàng khi admin xóa sản phẩm
function removeDeletedProductFromSessionCart(productId) {
    // Kiểm tra xem sản phẩm có trong giỏ hàng không
    fetch('/project1/shop/syncCartWithLatestProducts', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Kiểm tra nếu voucher đã bị xóa do giỏ hàng trống
            if (data.voucherRemoved) {
                Toastify({
                    text: "<?php echo __('Mã giảm giá đã bị hủy vì giỏ hàng trống.'); ?>",
                    duration: 5000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#dc3545",
                    stopOnFocus: true
                }).showToast();
            }
            
            if (data.updated && data.deletedProducts && data.deletedProducts.length > 0) {
                // Có sản phẩm bị xóa khỏi giỏ hàng
                const deletedProduct = data.deletedProducts.find(p => parseInt(p.id) === parseInt(productId));
                
                if (deletedProduct) {
                    // Hiển thị thông báo đã xóa sản phẩm khỏi giỏ hàng
                    Toastify({
                        text: "<?php echo __('Sản phẩm'); ?> \"" + deletedProduct.name + "\" <?php echo __('đã bị xóa khỏi giỏ hàng của bạn do không còn tồn tại'); ?>",
                        duration: 5000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#dc3545",
                        stopOnFocus: true,
                        onClick: function() {
                            // Điều hướng đến trang giỏ hàng khi nhấp vào thông báo
                            window.location.href = '/project1/shop/cart';
                        }
                    }).showToast();
                    
                    // Thêm nút tùy chọn xem giỏ hàng
                    const viewCartBtn = document.createElement('button');
                    viewCartBtn.className = 'btn btn-warning position-fixed';
                    viewCartBtn.style.bottom = '20px';
                    viewCartBtn.style.right = '20px';
                    viewCartBtn.style.zIndex = '1050';
                    viewCartBtn.style.borderRadius = '50px';
                    viewCartBtn.style.padding = '10px 15px';
                    viewCartBtn.style.boxShadow = '0 4px 10px rgba(0,0,0,0.2)';
                    viewCartBtn.innerHTML = '<i class="bi bi-cart-x me-1"></i> <?php echo __('Đã xóa sản phẩm khỏi giỏ hàng'); ?>';
                    
                    viewCartBtn.addEventListener('click', function() {
                        window.location.href = '/project1/shop/cart';
                    });
                    
                    // Thêm nút vào body
                    document.body.appendChild(viewCartBtn);
                    
                    // Tự động xóa nút sau 5 giây
                    setTimeout(() => {
                        if (document.body.contains(viewCartBtn)) {
                            document.body.removeChild(viewCartBtn);
                        }
                    }, 5000);
                    
                    // Cập nhật biểu tượng giỏ hàng (nếu có)
                    updateCartIconCount();
                }
            }
        }
    })
    .catch(error => {
        console.error('<?php echo __("Error syncing cart:"); ?>', error);
    });
}

// Hàm cập nhật số lượng sản phẩm trên biểu tượng giỏ hàng
function updateCartIconCount() {
    // Tìm kiếm phần tử hiển thị số lượng giỏ hàng (thường là badge trên biểu tượng giỏ hàng)
    const cartCountElement = document.querySelector('.cart-count');
    
    if (cartCountElement) {
        // Gọi API để lấy số lượng sản phẩm trong giỏ hàng
        fetch('/project1/shop/getCartCount', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật số lượng
                cartCountElement.textContent = data.count;
                
                // Nếu giỏ hàng trống, ẩn badge
                if (data.count <= 0) {
                    cartCountElement.style.display = 'none';
                } else {
                    cartCountElement.style.display = 'inline-block';
                }
            }
        })
        .catch(error => {
            console.error('<?php echo __("Error updating cart count:"); ?>', error);
        });
    }
}
</script>
<?php include 'app/views/shares/footer.php'; ?>