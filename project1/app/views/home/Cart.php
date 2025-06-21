<?php include 'app/views/shares/header.php'; ?>
<div class="container my-5">
    <h1 class="text-center mb-4"><?php echo __('Giỏ hàng của bạn'); ?></h1>
    
    <?php
$subtotal = 0;
foreach ($cart as $id => $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$voucher_code = $_SESSION['voucher_code'] ?? '';
$discount = $_SESSION['voucher_discount'] ?? 0;
$total = $subtotal - $discount;
if ($total < 0) $total = 0;
?>

    <?php if (!empty($cart)): ?>
    <div class="row">
        <div class="col-lg-8">
            <form method="post" action="/project1/shop/updateCart" id="cartForm">
                <?php foreach ($cart as $id => $item): ?>
                <div class="card mb-3 shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div class="d-flex align-items-center">
                            <?php if ($item['image']): ?>
                                <img src="/project1/<?php echo $item['image']; ?>" alt="Product Image" class="img-fluid rounded me-3" style="max-width: 100px;">
                            <?php endif; ?>
                            <div>
                                <h5 class="card-title mb-1"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                                <p class="text-muted mb-0"><?php echo __('Giá:'); ?> <?php echo number_format($item['price'], 0, ',', '.'); ?> <?php echo __('VND'); ?></p>
                                <?php if (!empty($item['error'])): ?>
                                    <div class="text-danger small mt-1"><?php echo $item['error']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-outline-secondary btn-sm btn-decrease me-2" data-id="<?php echo $id; ?>">-</button>
                            <input type="number" 
                                   name="quantities[<?php echo $id; ?>]" 
                                   value="<?php echo $item['quantity']; ?>" 
                                   min="1" 
                                   max="<?php echo $item['max_quantity'] ?? 99; ?>" 
                                   data-max="<?php echo $item['max_quantity'] ?? 99; ?>"
                                   class="form-control quantity-input text-center" 
                                   style="width: 70px;"
                                   data-id="<?php echo $id; ?>">
                            <button type="button" class="btn btn-outline-secondary btn-sm btn-increase ms-2" data-id="<?php echo $id; ?>">+</button>
                        </div>
                        <button type="submit" name="remove" value="<?php echo $id; ?>" class="btn btn-danger btn-sm ms-3"><?php echo __('Xóa'); ?></button>
                    </div>
                </div>
                <?php endforeach; ?>
            </form>
        </div>
        <div class="col-lg-4">
            <!-- Mã giảm giá -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3"><?php echo __('Mã giảm giá'); ?></h5>
<form id="voucherForm" method="post" action="/project1/shop/applyVoucher" class="d-flex gap-2 mb-3">
    <input type="text" name="voucher_code" class="form-control" placeholder="<?php echo __('Nhập mã giảm giá'); ?>" value="<?php echo htmlspecialchars($voucher_code ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <button type="submit" class="btn btn-success" id="applyVoucherBtn"><?php echo __('Áp dụng'); ?></button>
    <?php if (!empty($voucher_code)): ?>
        <button type="button" id="removeVoucherBtn" class="btn btn-outline-danger"><?php echo __('Hủy'); ?></button>
    <?php endif; ?>
</form>
<div id="voucherMessage"></div>
<?php if (isset($_SESSION['voucher_error'])): ?>
    <div class="text-danger small"><?php echo $_SESSION['voucher_error']; unset($_SESSION['voucher_error']); ?></div>
<?php endif; ?>
                </div>
            </div>
            <!-- Tổng hợp -->
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3"><?php echo __('Tổng hợp'); ?></h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between border-0 py-2">
                            <span><?php echo __('Tạm tính:'); ?></span>
                            <span><?php echo number_format($subtotal, 0, ',', '.'); ?> <?php echo __('VND'); ?></span>
                        </li>
                        <?php if ($discount > 0): ?>
                            <li class="list-group-item d-flex justify-content-between border-0 py-2 text-success">
                                <span><?php echo __('Giảm giá:'); ?></span>
                                <span>-<?php echo number_format($discount, 0, ',', '.'); ?> <?php echo __('VND'); ?></span>
                            </li>
                        <?php endif; ?>
                        <li class="list-group-item d-flex justify-content-between border-0 py-2 fw-bold">
                            <span><?php echo __('Tổng cộng:'); ?></span>
                            <span><?php echo number_format($total, 0, ',', '.'); ?> <?php echo __('VND'); ?></span>
                        </li>
                    </ul>
                    <div class="d-flex gap-2 mt-4">
                        <a href="/project1/shop/listproduct" class="btn btn-outline-secondary w-50"><?php echo __('Tiếp tục mua sắm'); ?></a>
                        <a href="/project1/shop/checkout" class="btn btn-primary w-50"><?php echo __('Thanh Toán'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="text-center">
        <p class="text-white fs-5"><?php echo __('Giỏ hàng của bạn đang trống.'); ?></p>
        <a href="/project1/shop/listproduct" class="btn btn-primary mt-3"><?php echo __('Tiếp tục mua sắm'); ?></a>
    </div>
    <?php endif; ?>
</div>

<style>


.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.btn-decrease, .btn-increase {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.btn-decrease:hover, .btn-increase:hover {
    background-color: #007bff;
    color: #fff;
}

.quantity-input {
    border-radius: 8px;
    border: 1px solid #ced4da;
    transition: border-color 0.3s ease;
}

.quantity-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.btn-danger {
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.btn-danger:hover {
    background-color: #dc3545;
}

.btn-primary, .btn-success, .btn-outline-secondary, .btn-outline-danger {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-primary:hover, .btn-success:hover {
    filter: brightness(110%);
}

.list-group-item {
    background: transparent;
}

@media (max-width: 768px) {
    .card-body {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    .btn-decrease, .btn-increase, .quantity-input {
        width: 100%;
        max-width: 50px;
    }
    .btn-danger {
        width: 100%;
        text-align: center;
    }
}
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="/project1/public/js/realtime.js"></script>
<script>
// Biến toàn cục để theo dõi voucher đã áp dụng gần đây nhất
window.lastAppliedVoucher = null;
// Biến để theo dõi xem có thông báo nào đang hiển thị không
window.currentToasts = {};

// Handle real-time voucher updates
document.addEventListener('DOMContentLoaded', function() {
    // Connect to WebSocket server when the page loads
    if (typeof realtime !== 'undefined') {
        // Listen for voucher update events
        realtime.on('update_voucher', function(data) {
            if (data && data.voucher) {
                // Get the current voucher code from the form
                const voucherInput = document.querySelector('input[name="voucher_code"]');
                if (voucherInput && voucherInput.value) {
                    const currentVoucherCode = voucherInput.value.trim();
                    
                    // If the updated voucher matches our current one
                    if (currentVoucherCode === data.voucher.code) {
                        // Check if voucher was deactivated
                        if (data.voucher.is_active === 0 || new Date(data.voucher.end_date) < new Date()) {
                            // Automatically remove the invalid voucher
                            fetch('/project1/shop/removeVoucher', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    // Show notification and reload page
                                    showUniqueToast("<?php echo __('Mã giảm giá đã hết hạn hoặc bị vô hiệu hóa và đã được gỡ bỏ'); ?>", 'warning');
                                    
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                }
                            });
                        } else {
                            // The voucher was updated but still valid
                            // Kiểm tra nếu voucher này vừa được áp dụng bằng AJAX, không hiển thị thông báo trùng lặp
                            if (window.lastAppliedVoucher !== currentVoucherCode) {
                                showUniqueToast("<?php echo __('Mã giảm giá đã được cập nhật, trang sẽ tải lại để áp dụng thay đổi'); ?>", 'primary');
                            }
                            
                            // Xóa biến lưu trữ voucher đã áp dụng
                            window.lastAppliedVoucher = null;
                            
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        }
                    }
                }
            }
        });
        
        // Listen for voucher deletion events
        realtime.on('delete_voucher', function(data) {
            if (data && data.voucher_code) {
                // Get the current voucher code from the form
                const voucherInput = document.querySelector('input[name="voucher_code"]');
                if (voucherInput && voucherInput.value) {
                    const currentVoucherCode = voucherInput.value.trim();
                    
                    // If the deleted voucher matches our current one
                    if (currentVoucherCode === data.voucher_code) {
                        // Automatically remove the invalid voucher
                        fetch('/project1/shop/removeVoucher', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                // Show notification and reload page
                                showUniqueToast("<?php echo __('Mã giảm giá đã bị xóa và đã được gỡ bỏ khỏi giỏ hàng của bạn'); ?>", 'error');
                                
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            }
                        });
                    }
                }
            }
        });
    }
});

function updateCartAjax(productId, quantity) {
    // Get old quantity before updating
    const inputElement = document.querySelector(`input.quantity-input[data-id="${productId}"]`);
    const oldQuantity = inputElement ? parseInt(inputElement.defaultValue || 0) : 0;
    let productName = "sản phẩm";
    
    const productElement = document.querySelector(`.btn-danger[name="remove"][value="${productId}"]`);
    if (productElement && productElement.closest('.card-body')) {
        const nameElement = productElement.closest('.card-body').querySelector('.card-title');
        if (nameElement) productName = nameElement.textContent;
    }

    fetch('/project1/shop/updateCart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `quantities[${productId}]=${quantity}`
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.text();
    })
    .then(html => {
        // Parse the returned HTML and update only the cart area
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newCart = doc.querySelector('.container.my-5');
        if (newCart) {
            document.querySelector('.container.my-5').innerHTML = newCart.innerHTML;
            
            // Show different notifications based on quantity change
            if (quantity > oldQuantity) {
                Toastify({
                    text: `<?php echo __('Đã tăng số lượng'); ?> "${productName}" <?php echo __('từ'); ?> ${oldQuantity} <?php echo __('lên'); ?> ${quantity}`,
                    duration: 2000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#28a745",
                    stopOnFocus: true
                }).showToast();
            } else if (quantity < oldQuantity) {
                Toastify({
                    text: `<?php echo __('Đã giảm số lượng'); ?> "${productName}" <?php echo __('từ'); ?> ${oldQuantity} <?php echo __('xuống'); ?> ${quantity}`,
                    duration: 2000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#17a2b8",
                    stopOnFocus: true
                }).showToast();
            } else {
                Toastify({
                    text: '<?php echo __('Cập nhật giỏ hàng thành công!'); ?>',
                    duration: 2000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#28a745",
                    stopOnFocus: true
                }).showToast();
            }
            
            attachCartEvents(); // Gắn lại sự kiện sau khi cập nhật DOM
        }
    })
    .catch(error => {
        Toastify({
            text: '<?php echo __('Có lỗi khi cập nhật giỏ hàng!'); ?>',
            duration: 2000,
            gravity: "top",
            position: "right",
            backgroundColor: "#dc3545",
            stopOnFocus: true
        }).showToast();
    });
}

function removeFromCartAjax(productId) {
    // Get product name before removing
    const productElement = document.querySelector(`.btn-danger[name="remove"][value="${productId}"]`);
    let productName = "sản phẩm";
    let productImage = "";
    let productPrice = "";
    
    if (productElement && productElement.closest('.card-body')) {
        const cardBody = productElement.closest('.card-body');
        const nameElement = cardBody.querySelector('.card-title');
        const priceElement = cardBody.querySelector('.text-muted');
        const imageElement = cardBody.querySelector('img');
        
        if (nameElement) productName = nameElement.textContent;
        if (priceElement) productPrice = priceElement.textContent;
        if (imageElement) productImage = imageElement.src;
    }

    // Kiểm tra số lượng sản phẩm trước khi xóa
    const productCount = document.querySelectorAll('.card').length;
    // Kiểm tra nếu có voucher đang được áp dụng
    const hasVoucher = document.querySelector('input[name="voucher_code"]')?.value;

    fetch('/project1/shop/updateCart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `remove=${productId}`
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.text();
    })
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newCart = doc.querySelector('.container.my-5');
        if (newCart) {
            document.querySelector('.container.my-5').innerHTML = newCart.innerHTML;
            Toastify({
                text: `<?php echo __('Đã xóa'); ?> "${productName}" <?php echo __('khỏi giỏ hàng!'); ?>`,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#dc3545",
                stopOnFocus: true
            }).showToast();
            attachCartEvents();
            
            // Kiểm tra nếu đây là sản phẩm cuối cùng trong giỏ hàng và có voucher
            if (productCount <= 1 && hasVoucher) {
                // Hiển thị thông báo voucher đã bị xóa
                setTimeout(() => {
                    Toastify({
                        text: '<?php echo __('Mã giảm giá đã bị hủy vì giỏ hàng trống'); ?>',
                        duration: 4000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#dc3545",
                        stopOnFocus: true
                    }).showToast();
                }, 1000);
            }
            
            // Kiểm tra nếu giỏ hàng trống
            if (document.querySelectorAll('.card').length === 0) {
                setTimeout(() => {
                    Toastify({
                        text: '<?php echo __('Giỏ hàng của bạn hiện đang trống!'); ?>',
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#17a2b8",
                        stopOnFocus: true
                    }).showToast();
                }, 500);
            }
        }
    })
    .catch(error => {
        console.error('<?php echo __("Error removing product:"); ?>', error);
        Toastify({
            text: '<?php echo __('Có lỗi khi xóa sản phẩm!'); ?>',
            duration: 2000,
            gravity: "top",
            position: "right",
            backgroundColor: "#dc3545",
            stopOnFocus: true
        }).showToast();
    });
}

// Kết nối WebSocket và lắng nghe sự kiện cập nhật sản phẩm
document.addEventListener('DOMContentLoaded', function() {
    if (typeof realtime !== 'undefined') {
        // Kết nối với vai trò user
        realtime.connect('user');
        
        // Xử lý sự kiện khi nhận được thông báo cập nhật sản phẩm
        realtime.on('update_product', function(data) {
            // Kiểm tra xem sản phẩm có trong giỏ hàng không
            const productId = data.product ? data.product.id : null;
            if (productId) {
                const productElement = document.querySelector(`.btn-danger[name="remove"][value="${productId}"]`);
                if (productElement) {
                    // Hiển thị thông báo
                    Toastify({
                        text: `<?php echo __('Sản phẩm'); ?> "${data.product.name}" <?php echo __('trong giỏ hàng của bạn vừa được cập nhật.'); ?>`,
                        duration: 5000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#0d6efd",
                        stopOnFocus: true,
                        onClick: function() {
                            // Khi click vào thông báo, đồng bộ giỏ hàng
                            syncCartWithLatestProducts(false);
                        },
                        close: true
                    }).showToast();
                    
                    // Hiển thị thông báo chi tiết nếu có thay đổi về số lượng
                    if (data.product.quantity !== undefined) {
                        // Tìm số lượng hiện tại trong giỏ hàng
                        const cardBody = productElement.closest('.card-body');
                        if (cardBody) {
                            const inputElement = cardBody.querySelector('.quantity-input');
                            if (inputElement) {
                                const currentMax = parseInt(inputElement.getAttribute('data-max') || '0');
                                if (currentMax !== data.product.quantity) {
                                    // Không hiển thị thông báo thứ nhất về thay đổi số lượng tồn kho
                                }
                            }
                        }
                    }
                    
                    // Tự động đồng bộ giỏ hàng
                    syncCartWithLatestProducts(false);
                }
            }
        });
        
        // Xử lý sự kiện khi nhận được thông báo xóa sản phẩm
        realtime.on('delete_product', function(data) {
            // Kiểm tra xem sản phẩm có trong giỏ hàng không
            const productId = data.product_id || (data.product ? data.product.id : null);
            if (productId) {
                const productElement = document.querySelector(`.btn-danger[name="remove"][value="${productId}"]`);
                if (productElement) {
                    // Lấy tên sản phẩm trước khi xóa
                    let productName = "sản phẩm";
                    const cardBody = productElement.closest('.card-body');
                    if (cardBody) {
                        const nameElement = cardBody.querySelector('.card-title');
                        if (nameElement) productName = nameElement.textContent;
                    }
                    
                    // Hiển thị thông báo chi tiết về sản phẩm bị xóa
                    Toastify({
                        text: `<?php echo __('Sản phẩm'); ?> "${productName}" <?php echo __('đã bị xóa khỏi hệ thống và sẽ bị xóa khỏi giỏ hàng của bạn'); ?>`,
                        duration: 5000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#dc3545",
                        stopOnFocus: true,
                        close: true
                    }).showToast();
                    
                    // Xóa sản phẩm khỏi giỏ hàng ngay lập tức
                    removeDeletedProductFromCart(productId, productName);
                }
            }
            
            // Đồng bộ giỏ hàng để xóa các sản phẩm không còn tồn tại
            syncCartWithLatestProducts(false);
        });
    }
    
    // Đồng bộ giỏ hàng khi trang được tải (không hiển thị thông báo)
    syncCartWithLatestProducts(true);
});

// Hàm đồng bộ giỏ hàng với thông tin sản phẩm mới nhất
function syncCartWithLatestProducts(isSilent = false) {
    fetch('/project1/shop/syncCartWithLatestProducts', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Kiểm tra nếu voucher đã bị xóa do giỏ hàng trống
            if (data.voucherRemoved && !isSilent) {
                Toastify({
                    text: `<?php echo __('Mã giảm giá đã bị hủy vì giỏ hàng trống'); ?>`,
                    duration: 5000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#dc3545",
                    stopOnFocus: true
                }).showToast();
            }
            
            if (data.updated) {
                // Nếu có sản phẩm bị xóa
                if (data.deletedProducts && data.deletedProducts.length > 0) {
                    data.deletedProducts.forEach(product => {
                        // Xóa sản phẩm khỏi DOM
                        const productElement = document.querySelector(`.btn-danger[name="remove"][value="${product.id}"]`);
                        if (productElement && productElement.closest('.card')) {
                            productElement.closest('.card').parentElement.remove();
                        }
                        
                        // Chỉ hiển thị thông báo nếu không phải chế độ im lặng
                        if (!isSilent) {
                            Toastify({
                                text: `<?php echo __('Sản phẩm'); ?> "${product.name}" <?php echo __('đã bị xóa khỏi giỏ hàng do không còn tồn tại'); ?>`,
                                duration: 5000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#dc3545",
                                stopOnFocus: true
                            }).showToast();
                        }
                    });
                }
                
                // Nếu có sản phẩm được cập nhật
                if (data.updatedProducts && data.updatedProducts.length > 0) {
                    data.updatedProducts.forEach(product => {
                        // Tìm phần tử sản phẩm trong DOM
                        const productElement = document.querySelector(`.btn-danger[name="remove"][value="${product.id}"]`);
                        if (productElement && productElement.closest('.card')) {
                            const cardBody = productElement.closest('.card-body');
                            
                            // Cập nhật tên sản phẩm
                            if (product.changes.includes('name')) {
                                const nameElement = cardBody.querySelector('.card-title');
                                if (nameElement) {
                                    nameElement.textContent = product.name;
                                    
                                    // Thêm hiệu ứng highlight cho phần tử đã thay đổi
                                    nameElement.style.backgroundColor = '#fff3cd';
                                    nameElement.style.padding = '2px 5px';
                                    nameElement.style.borderRadius = '3px';
                                    setTimeout(() => {
                                        nameElement.style.backgroundColor = '';
                                        nameElement.style.padding = '';
                                        nameElement.style.borderRadius = '';
                                        nameElement.style.transition = 'background-color 1s ease';
                                    }, 100);
                                }
                            }
                            
                            // Cập nhật giá sản phẩm
                            if (product.changes.includes('price')) {
                                const priceElement = cardBody.querySelector('.text-muted');
                                if (priceElement) {
                                    priceElement.innerHTML = `<?php echo __('Giá:'); ?> ${new Intl.NumberFormat('vi-VN').format(product.price)} <?php echo __('VND'); ?>`;
                                    
                                    // Thêm hiệu ứng highlight cho phần tử đã thay đổi
                                    priceElement.style.backgroundColor = '#fff3cd';
                                    priceElement.style.padding = '2px 5px';
                                    priceElement.style.borderRadius = '3px';
                                    setTimeout(() => {
                                        priceElement.style.backgroundColor = '';
                                        priceElement.style.padding = '';
                                        priceElement.style.borderRadius = '';
                                        priceElement.style.transition = 'background-color 1s ease';
                                    }, 100);
                                }
                            }
                            
                            // Cập nhật hình ảnh sản phẩm
                            if (product.changes.includes('image')) {
                                const imageElement = cardBody.querySelector('img');
                                if (imageElement) {
                                    // Cập nhật URL hình ảnh mới
                                    imageElement.src = `/project1/${product.image}`;
                                    
                                    // Thêm hiệu ứng cho hình ảnh
                                    imageElement.style.opacity = '0.5';
                                    setTimeout(() => {
                                        imageElement.style.opacity = '1';
                                        imageElement.style.transition = 'opacity 0.5s ease';
                                    }, 100);
                                }
                            }
                            
                            // Cập nhật số lượng tối đa
                            if (product.changes.includes('quantity')) {
                                const inputElement = cardBody.querySelector('.quantity-input');
                                if (inputElement) {
                                    inputElement.setAttribute('max', product.max_quantity);
                                    inputElement.setAttribute('data-max', product.max_quantity);
                                    
                                    // Nếu số lượng hiện tại vượt quá số lượng tối đa mới
                                    if (parseInt(inputElement.value) > product.max_quantity) {
                                        inputElement.value = product.max_quantity;
                                        
                                        // Hiển thị thông báo lỗi
                                        let errorElement = cardBody.querySelector('.text-danger');
                                        if (!errorElement) {
                                            errorElement = document.createElement('div');
                                            errorElement.className = 'text-danger small mt-1';
                                            cardBody.querySelector('.d-flex').insertAdjacentElement('afterend', errorElement);
                                        }
                                        errorElement.textContent = '<?php echo __('Số lượng đã được điều chỉnh do thay đổi tồn kho'); ?>';
                                        
                                        // Thêm hiệu ứng highlight cho input
                                        inputElement.style.backgroundColor = '#f8d7da';
                                        inputElement.style.borderColor = '#dc3545';
                                        setTimeout(() => {
                                            inputElement.style.backgroundColor = '';
                                            inputElement.style.borderColor = '';
                                            inputElement.style.transition = 'all 0.5s ease';
                                        }, 2000);
                                    }
                                }
                            }
                            
                            // Chỉ hiển thị thông báo nếu không phải chế độ im lặng
                            if (!isSilent) {
                                // Hiển thị thông báo chi tiết về các thay đổi
                                let changeText = '';
                                if (product.changes.includes('name')) changeText += '<?php echo __('tên'); ?>, ';
                                if (product.changes.includes('price')) changeText += '<?php echo __('giá'); ?>, ';
                                if (product.changes.includes('quantity')) changeText += '<?php echo __('số lượng'); ?>, ';
                                if (product.changes.includes('category')) changeText += '<?php echo __('danh mục'); ?>, ';
                                
                                changeText = changeText.slice(0, -2); // Xóa dấu phẩy cuối cùng
                                
                                Toastify({
                                    text: `<?php echo __('Sản phẩm'); ?> "${product.name}": ${changeText} <?php echo __('đã được cập nhật'); ?>`,
                                    duration: 4000,
                                    gravity: "top",
                                    position: "right",
                                    backgroundColor: "#0d6efd",
                                    stopOnFocus: true
                                }).showToast();
                                
                                // Hiển thị thông báo chi tiết cho từng loại thay đổi
                                if (product.changeDetails) {
                                    // Thông báo thay đổi tên
                                    if (product.changes.includes('name')) {
                                        setTimeout(() => {
                                            Toastify({
                                                text: `<?php echo __('Sản phẩm đã chỉnh sửa tên'); ?>:\n${product.changeDetails.name.old} → ${product.changeDetails.name.new}`,
                                                duration: 5000,
                                                gravity: "top",
                                                position: "right",
                                                backgroundColor: "#17a2b8",
                                                stopOnFocus: true
                                            }).showToast();
                                        }, 500);
                                    }
                                    
                                    // Thông báo thay đổi giá
                                    if (product.changes.includes('price')) {
                                        setTimeout(() => {
                                            Toastify({
                                                text: `<?php echo __('Sản phẩm'); ?> "${product.name}" <?php echo __('đã thay đổi giá'); ?>:\n${new Intl.NumberFormat('vi-VN').format(product.changeDetails.price.old)} <?php echo __('VND'); ?> → ${new Intl.NumberFormat('vi-VN').format(product.changeDetails.price.new)} <?php echo __('VND'); ?>`,
                                                duration: 5000,
                                                gravity: "top",
                                                position: "right",
                                                backgroundColor: "#28a745",
                                                stopOnFocus: true
                                            }).showToast();
                                        }, 1000);
                                    }
                                    
                                    // Thông báo thay đổi danh mục
                                    if (product.changes.includes('category') && product.changeDetails.category.new) {
                                        setTimeout(() => {
                                            Toastify({
                                                text: `<?php echo __('Sản phẩm'); ?> "${product.name}" <?php echo __('thay đổi danh mục'); ?>:\n${product.changeDetails.category.old || '<?php echo __('Không có'); ?>'} → ${product.changeDetails.category.new}`,
                                                duration: 5000,
                                                gravity: "top",
                                                position: "right",
                                                backgroundColor: "#6f42c1",
                                                stopOnFocus: true
                                            }).showToast();
                                        }, 1500);
                                    }
                                    
                                    // Thông báo thay đổi số lượng tồn kho
                                    if (product.changes.includes('quantity')) {
                                        setTimeout(() => {
                                            Toastify({
                                                text: `<?php echo __('Sản phẩm'); ?> "${product.name}" <?php echo __('đã điều chỉnh số lượng tồn kho'); ?>:\n${product.changeDetails.quantity.old} → ${product.changeDetails.quantity.new}`,
                                                duration: 5000,
                                                gravity: "top",
                                                position: "right",
                                                backgroundColor: "#fd7e14",
                                                stopOnFocus: true
                                            }).showToast();
                                        }, 2000);
                                    }
                                }
                            }
                        }
                    });
                    
                    // Cập nhật tổng tiền
                    updateCartSummary(data.cartSummary);
                }
            }
        }
    })
    .catch(error => {
        console.error('<?php echo __("Error syncing cart:"); ?>', error);
    });
}

// Cập nhật thông tin tổng tiền của giỏ hàng
function updateCartSummary(summary) {
    if (!summary) return;
    
    // Cập nhật tạm tính
    const subtotalElement = document.querySelector('.list-group-item:nth-child(1) span:last-child');
    if (subtotalElement) {
        subtotalElement.textContent = `${new Intl.NumberFormat('vi-VN').format(summary.subtotal)} <?php echo __('VND'); ?>`;
        
        // Hiệu ứng highlight
        subtotalElement.style.backgroundColor = '#fff3cd';
        setTimeout(() => {
            subtotalElement.style.backgroundColor = '';
            subtotalElement.style.transition = 'background-color 1s ease';
        }, 1000);
    }
    
    // Cập nhật giảm giá nếu có
    if (summary.discount > 0) {
        const discountElement = document.querySelector('.list-group-item.text-success span:last-child');
        if (discountElement) {
            discountElement.textContent = `-${new Intl.NumberFormat('vi-VN').format(summary.discount)} <?php echo __('VND'); ?>`;
        }
    }
    
    // Cập nhật tổng cộng
    const totalElement = document.querySelector('.list-group-item.fw-bold span:last-child');
    if (totalElement) {
        totalElement.textContent = `${new Intl.NumberFormat('vi-VN').format(summary.total)} <?php echo __('VND'); ?>`;
        
        // Hiệu ứng highlight
        totalElement.style.backgroundColor = '#fff3cd';
        setTimeout(() => {
            totalElement.style.backgroundColor = '';
            totalElement.style.transition = 'background-color 1s ease';
        }, 1000);
    }
}

// Gắn lại sự kiện sau khi cập nhật DOM
function attachCartEvents() {
    document.querySelectorAll('.quantity-input').forEach(function(input) {
        input.addEventListener('change', function() {
            var max = parseInt(this.getAttribute('data-max'));
            var val = parseInt(this.value);
            if (val > max) {
                alert('<?php echo __('Số lượng vượt quá tồn kho!'); ?>');
                this.value = max;
                return;
            }
            if (val < 1) {
                this.value = 1;
                return;
            }
            var id = this.getAttribute('data-id');
            updateCartAjax(id, this.value);
        });
    });
    document.querySelectorAll('.btn-increase').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var input = document.querySelector('input.quantity-input[data-id="'+id+'"]');
            var max = parseInt(input.getAttribute('data-max'));
            var val = parseInt(input.value);
            if (val < max) {
                input.value = val + 1;
                input.dispatchEvent(new Event('change'));
            }
        });
    });
    document.querySelectorAll('.btn-decrease').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var input = document.querySelector('input.quantity-input[data-id="'+id+'"]');
            var val = parseInt(input.value);
            if (val > 1) {
                input.value = val - 1;
                input.dispatchEvent(new Event('change'));
            }
        });
    });
    document.querySelectorAll('.btn-danger[name="remove"]').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var id = this.value;
            removeFromCartAjax(id);
        });
    });
    
    // Thêm gắn sự kiện voucher
    attachVoucherEvents();
}

// Lần đầu
attachCartEvents();
attachVoucherEvents();

// AJAX xử lý áp dụng voucher
function applyVoucherAjax(code) {
    // Hiển thị loading
    const applyBtn = document.getElementById('applyVoucherBtn');
    const originalText = applyBtn.textContent;
    applyBtn.disabled = true;
    applyBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + originalText;
    
    // Xóa thông báo cũ nếu có
    document.getElementById('voucherMessage').innerHTML = '';
    
    fetch('/project1/shop/applyVoucher', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `voucher_code=${encodeURIComponent(code)}`
    })
    .then(response => {
        return response.text();
    })
    .then(html => {
        // Parse HTML response để lấy thông tin voucher và giỏ hàng mới
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Kiểm tra nếu có lỗi voucher
        const errorElement = doc.querySelector('.text-danger.small');
        // Kiểm tra xem voucher đã được áp dụng hay không
        const voucherInput = doc.querySelector('input[name="voucher_code"]');
        const newVoucherCode = voucherInput ? voucherInput.value.trim() : '';
        const originalVoucherCode = code.trim();
        
        // Nếu mã voucher đã nhập không còn trong response, tức là không hợp lệ
        if ((errorElement && errorElement.textContent.trim()) || 
            (originalVoucherCode && !newVoucherCode)) {
            
            let errorMessage = errorElement ? errorElement.textContent : '<?php echo __('Mã giảm giá không hợp lệ'); ?>';
            
            // Hiển thị lỗi
            document.getElementById('voucherMessage').innerHTML = 
                `<div class="text-danger small mt-2">${errorMessage}</div>`;
            
            // Hiển thị thông báo lỗi
            showUniqueToast(errorMessage, 'error');
            
            // Reset form nếu là lỗi không tồn tại hoặc không hợp lệ
            if (errorMessage.includes('không tồn tại') || !newVoucherCode) {
                document.querySelector('input[name="voucher_code"]').value = '';
            }
            
            // Cập nhật DOM từ response để giỏ hàng được cập nhật
            updateCartFromHTML(doc);
        } else {
            // Tìm giá trị voucher trong response
            const voucherInput = doc.querySelector('input[name="voucher_code"]');
            const voucherCode = voucherInput ? voucherInput.value : '';
            
            // Cập nhật tổng hợp giỏ hàng từ response
            updateCartFromHTML(doc);
            
            // Hiển thị thông báo thành công
            if (voucherCode) {
                // Lưu voucher đã áp dụng để tránh hiển thị thông báo trùng lặp
                window.lastAppliedVoucher = voucherCode;
                
                // Đặt hẹn giờ để xóa biến sau 5 giây
                setTimeout(() => {
                    window.lastAppliedVoucher = null;
                }, 5000);
                
                // Hiển thị thông báo thành công
                showUniqueToast(`<?php echo __('Đã áp dụng mã giảm giá'); ?> "${voucherCode}" <?php echo __('thành công'); ?>`, 'success');
                
                // Thêm nút hủy voucher nếu chưa có
                if (!document.getElementById('removeVoucherBtn')) {
                    const voucherForm = document.getElementById('voucherForm');
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.id = 'removeVoucherBtn';
                    removeBtn.className = 'btn btn-outline-danger';
                    removeBtn.textContent = '<?php echo __('Hủy'); ?>';
                    removeBtn.addEventListener('click', removeVoucherAjax);
                    voucherForm.appendChild(removeBtn);
                }
            } 
            // Không hiển thị thông báo "Đã hủy mã giảm giá" khi áp dụng voucher mới
            // Chỉ hiển thị khi người dùng chủ động bấm nút hủy
        }
    })
    .catch(error => {
        console.error('Error applying voucher:', error);
        document.getElementById('voucherMessage').innerHTML = 
            `<div class="text-danger small mt-2"><?php echo __('Có lỗi xảy ra khi áp dụng mã giảm giá.'); ?></div>`;
        
        showUniqueToast('<?php echo __('Có lỗi xảy ra khi áp dụng mã giảm giá.'); ?>', 'error');
    })
    .finally(() => {
        // Reset button
        applyBtn.disabled = false;
        applyBtn.textContent = originalText;
    });
}

// AJAX xử lý hủy voucher
function removeVoucherAjax() {
    // Hiển thị loading
    const removeBtn = document.getElementById('removeVoucherBtn');
    if (!removeBtn) return;
    
    const originalText = removeBtn.textContent;
    removeBtn.disabled = true;
    removeBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + originalText;
    
    fetch('/project1/shop/removeVoucher', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => {
        return response.text();
    })
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Cập nhật DOM từ response để giỏ hàng được cập nhật
        updateCartFromHTML(doc);
        
        // Hiển thị thông báo thành công
        showUniqueToast('<?php echo __('Đã hủy mã giảm giá thành công'); ?>', 'info');
    })
    .catch(error => {
        console.error('Error removing voucher:', error);
        
        showUniqueToast('<?php echo __('Có lỗi xảy ra khi hủy mã giảm giá.'); ?>', 'error');
        
        // Reset the button
        if (removeBtn) {
            removeBtn.disabled = false;
            removeBtn.textContent = originalText;
        }
    });
}

// Cập nhật DOM từ HTML response
function updateCartFromHTML(doc) {
    // Cập nhật toàn bộ cart container
    const newCart = doc.querySelector('.container.my-5');
    if (newCart) {
        document.querySelector('.container.my-5').innerHTML = newCart.innerHTML;
        attachCartEvents();
        attachVoucherEvents(); // Gắn lại sự kiện voucher
    }
}

// Hàm hiển thị thông báo mà không bị trùng lặp
function showUniqueToast(text, type = 'success') {
    // Tạo ID duy nhất cho thông báo dựa trên nội dung
    const toastId = text.replace(/\s+/g, '_').substring(0, 30);
    
    // Nếu thông báo này đang hiển thị, không hiển thị lại
    if (window.currentToasts[toastId]) {
        return;
    }
    
    // Xác định màu nền dựa trên loại thông báo
    let backgroundColor;
    switch (type) {
        case 'success': backgroundColor = "#28a745"; break;
        case 'error': backgroundColor = "#dc3545"; break;
        case 'warning': backgroundColor = "#fd7e14"; break;
        case 'info': backgroundColor = "#17a2b8"; break;
        case 'primary': backgroundColor = "#0d6efd"; break;
        default: backgroundColor = "#28a745";
    }
    
    // Đánh dấu thông báo này đang hiển thị
    window.currentToasts[toastId] = true;
    
    // Hiển thị thông báo
    const toast = Toastify({
        text: text,
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: backgroundColor,
        stopOnFocus: true,
        callback: function() {
            // Xóa khỏi danh sách khi thông báo biến mất
            delete window.currentToasts[toastId];
        }
    }).showToast();
    
    return toast;
}

// Gắn sự kiện cho form voucher
function attachVoucherEvents() {
    // Form áp dụng voucher
    const voucherForm = document.getElementById('voucherForm');
    if (voucherForm) {
        voucherForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const voucherInput = this.querySelector('input[name="voucher_code"]');
            if (voucherInput && voucherInput.value.trim()) {
                applyVoucherAjax(voucherInput.value.trim());
            }
        });
    }
    
    // Nút hủy voucher
    const removeVoucherBtn = document.getElementById('removeVoucherBtn');
    if (removeVoucherBtn) {
        removeVoucherBtn.addEventListener('click', removeVoucherAjax);
    }
}

// Hàm xóa sản phẩm đã bị xóa khỏi giỏ hàng
function removeDeletedProductFromCart(productId, productName) {
    // Get product element
    const productElement = document.querySelector(`.btn-danger[name="remove"][value="${productId}"]`);
    if (!productElement) return;
    
    const card = productElement.closest('.card');
    
    // Thêm hiệu ứng khi xóa
    if (card) {
        card.style.transition = 'all 0.5s ease';
        card.style.transform = 'translateX(100%)';
        card.style.opacity = '0';
    }

    // Đợi hiệu ứng hoàn thành
    setTimeout(() => {
        fetch('/project1/shop/updateCart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `remove=${productId}`
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newCart = doc.querySelector('.container.my-5');
            if (newCart) {
                document.querySelector('.container.my-5').innerHTML = newCart.innerHTML;
                
                Toastify({
                    text: `Đã xóa "${productName}" khỏi giỏ hàng do sản phẩm không còn tồn tại!`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#dc3545",
                    stopOnFocus: true
                }).showToast();
                
                attachCartEvents();
                
                // Kiểm tra nếu giỏ hàng trống
                if (document.querySelectorAll('.card').length === 0) {
                    // Hiển thị thông báo giỏ hàng trống
                    setTimeout(() => {
                        Toastify({
                            text: 'Giỏ hàng của bạn hiện đang trống!',
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#17a2b8",
                            stopOnFocus: true
                        }).showToast();
                    }, 1000);
                    
                    // Kiểm tra nếu trước đó có voucher, hiển thị thông báo voucher đã bị hủy
                    const voucherInput = document.querySelector('input[name="voucher_code"]');
                    if (voucherInput && !voucherInput.value) {
                        setTimeout(() => {
                            Toastify({
                                text: 'Mã giảm giá đã bị hủy vì giỏ hàng trống',
                                duration: 4000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#dc3545",
                                stopOnFocus: true
                            }).showToast();
                        }, 2000);
                    }
                }
            }
        })
        .catch(error => {
            console.error('<?php echo __("Error removing product:"); ?>', error);
            
            // Fallback: Reload the page if AJAX fails
            window.location.reload();
        });
    }, 500);
}
</script>

<?php include 'app/views/shares/footer.php'; ?>