<?php include 'app/views/shares/header.php'; ?>
<?php
$fullname = '';
$phone = '';
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../../models/AccountModel.php';
    $db = (new Database())->getConnection();
    $accountModel = new AccountModel($db);
    $acc = $accountModel->getAccountById($_SESSION['user_id']);
    if ($acc) {
        if (!empty($acc->fullname)) {
            $fullname = htmlspecialchars($acc->fullname);
        }
        if (!empty($acc->phone)) {
            $phone = htmlspecialchars($acc->phone);
        }
    }
}
?>
<div class="container my-5">
    <h1 class="text-center mb-4"><?php echo __('Thanh toán'); ?></h1>
    
    <?php
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo '<div class="text-center"><p class="text-white">' . __('Giỏ hàng của bạn đang trống.') . '</p>';
        echo '<a href="/project1/shop/listproduct" class="btn btn-primary mt-3">' . __('Tiếp tục mua sắm') . '</a></div>';
        include 'app/views/shares/footer.php';
        exit;
    }
    
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $voucher_code = !empty($_SESSION['voucher_code']) ? $_SESSION['voucher_code'] : '';
    $discount = !empty($_SESSION['voucher_discount']) ? $_SESSION['voucher_discount'] : 0;
    $total = $subtotal - $discount;
    if ($total < 0) $total = 0;
    ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3"><?php echo __('Thông tin giao hàng'); ?></h5>
                    <form method="post" action="/project1/shop/processCheckout" id="checkoutForm">
                        <div class="mb-3">
                            <label for="name" class="form-label"><?php echo __('Họ và tên'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required value="<?php echo $fullname; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label"><?php echo __('Số điện thoại'); ?> <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" required value="<?php echo $phone; ?>" pattern="[0-9]+" title="<?php echo __('Vui lòng chỉ nhập số'); ?>" inputmode="numeric" maxlength="15" onkeypress="return isNumberKey(event)">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label"><?php echo __('Địa chỉ'); ?> <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                </div>
            </div>
            
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3"><?php echo __('Đơn hàng của bạn'); ?></h5>
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo __('Sản phẩm'); ?></th>
                                    <th class="text-center"><?php echo __('Số lượng'); ?></th>
                                    <th class="text-end"><?php echo __('Giá'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="checkout-products">
                                <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                                <tr data-product-id="<?php echo $id; ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($item['image']): ?>
                                                <img src="/project1/<?php echo $item['image']; ?>" alt="Product Image" class="img-fluid rounded me-3" style="max-width: 60px;">
                                            <?php endif; ?>
                                            <span><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                                    <td class="text-end"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> <?php echo __('VND'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold"><?php echo __('Tạm tính:'); ?></td>
                                    <td class="text-end"><?php echo number_format($subtotal, 0, ',', '.'); ?> <?php echo __('VND'); ?></td>
                                </tr>
                                <?php if ($discount > 0): ?>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold text-success"><?php echo __('Giảm giá:'); ?></td>
                                    <td class="text-end text-success">-<?php echo number_format($discount, 0, ',', '.'); ?> <?php echo __('VND'); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold"><?php echo __('Tổng cộng:'); ?></td>
                                    <td class="text-end fw-bold"><?php echo number_format($total, 0, ',', '.'); ?> <?php echo __('VND'); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="/project1/shop/cart" class="btn btn-outline-secondary"><?php echo __('Quay lại giỏ hàng'); ?></a>
                        <button type="submit" class="btn btn-primary"><?php echo __('Đặt hàng'); ?></button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3"><?php echo __('Tóm tắt đơn hàng'); ?></h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between border-0 py-2">
                            <span><?php echo __('Số lượng sản phẩm:'); ?></span>
                            <span><?php echo count($_SESSION['cart']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between border-0 py-2">
                            <span><?php echo __('Tạm tính:'); ?></span>
                            <span><?php echo number_format($subtotal, 0, ',', '.'); ?> <?php echo __('VND'); ?></span>
                        </li>
                        <?php if ($discount > 0): ?>
                            <li class="list-group-item d-flex justify-content-between border-0 py-2 text-success">
                                <span><?php echo __('Giảm giá:'); ?></span>
                                <span>-<?php echo number_format($discount, 0, ',', '.'); ?> <?php echo __('VND'); ?></span>
                            </li>
                            <?php if (!empty($voucher_code)): ?>
                            <li class="list-group-item d-flex justify-content-between border-0 py-2">
                                <span><?php echo __('Mã giảm giá:'); ?></span>
                                <span><?php echo htmlspecialchars($voucher_code, ENT_QUOTES, 'UTF-8'); ?></span>
                            </li>
                            <?php endif; ?>
                        <?php endif; ?>
                        <li class="list-group-item d-flex justify-content-between border-0 py-2 fw-bold">
                            <span><?php echo __('Tổng cộng:'); ?></span>
                            <span><?php echo number_format($total, 0, ',', '.'); ?> <?php echo __('VND'); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: rgba(255, 255, 255, 0.95);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.form-control {
    border-radius: 8px;
    border: 1px solid #ced4da;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.btn {
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
    .d-flex.align-items-center {
        flex-direction: column;
        align-items: flex-start !important;
    }
    .d-flex.align-items-center img {
        margin-bottom: 10px;
    }
}
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="/project1/public/js/realtime.js"></script>
<script>
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

// Xử lý khi paste vào ô số điện thoại
document.getElementById('phone').addEventListener('paste', function(e) {
    // Ngăn chặn hành động paste mặc định
    e.preventDefault();
    
    // Lấy nội dung được paste
    var pastedText = (e.clipboardData || window.clipboardData).getData('text');
    
    // Chỉ giữ lại các ký tự số
    var numericText = pastedText.replace(/[^0-9]/g, '');
    
    // Chèn nội dung đã lọc vào input
    if (numericText) {
        // Nếu có cả vùng chọn hiện tại, thay thế nó
        if (document.selection) {
            // IE
            var textRange = document.selection.createRange();
            textRange.text = numericText;
        } else if (this.selectionStart !== undefined) {
            // Các trình duyệt hiện đại
            var startPos = this.selectionStart;
            var endPos = this.selectionEnd;
            this.value = this.value.substring(0, startPos) + numericText + this.value.substring(endPos, this.value.length);
            this.selectionStart = this.selectionEnd = startPos + numericText.length;
        } else {
            // Fallback
            this.value += numericText;
        }
    }
});

// Kết nối WebSocket và lắng nghe sự kiện cập nhật sản phẩm
document.addEventListener('DOMContentLoaded', function() {
    if (typeof realtime !== 'undefined') {
        // Kết nối với vai trò user
        realtime.connect('user');
        
        // Xử lý sự kiện khi nhận được thông báo cập nhật sản phẩm
        realtime.on('update_product', function(data) {
            // Kiểm tra xem sản phẩm có trong đơn hàng không
            const productId = data.product ? data.product.id : null;
            if (productId) {
                const productRow = document.querySelector(`tr[data-product-id="${productId}"]`);
                if (productRow) {
                    // Hiển thị thông báo
                    Toastify({
                        text: `<?php echo __('Sản phẩm'); ?> "${data.product.name}" <?php echo __('trong đơn hàng của bạn vừa được cập nhật.'); ?>`,
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
                        // Tìm số lượng hiện tại trong đơn hàng
                        const quantityElement = productRow.querySelector('td:nth-child(2)');
                        if (quantityElement) {
                            const currentQuantity = parseInt(quantityElement.textContent) || 0;
                            
                            // Hiển thị thông báo chi tiết về thay đổi số lượng
                            setTimeout(() => {
                                Toastify({
                                    text: `<?php echo __('Sản phẩm'); ?> "${data.product.name}" <?php echo __('đã thay đổi số lượng tồn kho'); ?>:
                                    <?php echo __('Số lượng tồn kho mới'); ?>: ${data.product.quantity}
                                    <?php echo __('Số lượng trong đơn hàng của bạn'); ?>: ${currentQuantity}${currentQuantity > data.product.quantity ? ' (' + (currentQuantity - data.product.quantity) + ' <?php echo __('vượt quá tồn kho'); ?>)' : ''}`,
                                    duration: 5000,
                                    gravity: "top",
                                    position: "right",
                                    backgroundColor: "#fd7e14",
                                    stopOnFocus: true
                                }).showToast();
                            }, 1000);
                        }
                    }
                    
                    // Tự động đồng bộ giỏ hàng
                    syncCartWithLatestProducts(false);
                }
            }
        });
        
        // Xử lý sự kiện khi nhận được thông báo xóa sản phẩm
        realtime.on('delete_product', function(data) {
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
        if (data.success && data.updated) {
            let needsUpdate = false;
            
            // Nếu có sản phẩm bị xóa
            if (data.deletedProducts && data.deletedProducts.length > 0) {
                data.deletedProducts.forEach(product => {
                    // Xóa sản phẩm khỏi DOM
                    const productRow = document.querySelector(`tr[data-product-id="${product.id}"]`);
                    if (productRow) {
                        productRow.remove();
                        needsUpdate = true;
                        
                        // Chỉ hiển thị thông báo nếu không phải chế độ im lặng
                        if (!isSilent) {
                            Toastify({
                                text: `<?php echo __('Sản phẩm'); ?> "${product.name}" <?php echo __('đã bị xóa khỏi đơn hàng do không còn tồn tại'); ?>`,
                                duration: 5000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#dc3545",
                                stopOnFocus: true
                            }).showToast();
                        }
                    }
                });
            }
            
            // Nếu có sản phẩm được cập nhật
            if (data.updatedProducts && data.updatedProducts.length > 0) {
                data.updatedProducts.forEach(product => {
                    // Tìm phần tử sản phẩm trong DOM
                    const productRow = document.querySelector(`tr[data-product-id="${product.id}"]`);
                    if (productRow) {
                        needsUpdate = true;
                        
                        // Cập nhật tên sản phẩm
                        if (product.changes.includes('name')) {
                            const nameElement = productRow.querySelector('.d-flex span');
                            if (nameElement) {
                                nameElement.textContent = product.name;
                                
                                // Thêm hiệu ứng highlight
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
                        
                        // Cập nhật hình ảnh sản phẩm
                        if (product.changes.includes('image')) {
                            const imageElement = productRow.querySelector('img');
                            if (imageElement) {
                                // Cập nhật URL hình ảnh mới
                                imageElement.src = `/project1/${product.image}`;
                                
                                // Thêm hiệu ứng
                                imageElement.style.opacity = '0.5';
                                setTimeout(() => {
                                    imageElement.style.opacity = '1';
                                    imageElement.style.transition = 'opacity 0.5s ease';
                                }, 100);
                            }
                        }
                        
                        // Cập nhật số lượng nếu cần
                        if (product.changes.includes('quantity')) {
                            const quantityElement = productRow.querySelector('td:nth-child(2)');
                            // Fallback cho max_quantity nếu undefined
                            const maxQuantity = product.max_quantity !== undefined ? product.max_quantity : 
                                               (product.changeDetails && product.changeDetails.quantity && product.changeDetails.quantity.max) ? 
                                               product.changeDetails.quantity.max : product.quantity;
                            if (quantityElement && maxQuantity !== undefined && parseInt(quantityElement.textContent) > maxQuantity) {
                                quantityElement.textContent = maxQuantity;
                                
                                // Thêm hiệu ứng highlight
                                quantityElement.style.backgroundColor = '#f8d7da';
                                quantityElement.style.color = '#dc3545';
                                setTimeout(() => {
                                    quantityElement.style.backgroundColor = '';
                                    quantityElement.style.color = '';
                                    quantityElement.style.transition = 'all 0.5s ease';
                                }, 2000);
                            }
                        }
                        
                        // Cập nhật giá sản phẩm
                        if (product.changes.includes('price')) {
                            const priceElement = productRow.querySelector('td:nth-child(3)');
                            if (priceElement) {
                                const totalPrice = product.price * product.quantity;
                                priceElement.textContent = `${new Intl.NumberFormat('vi-VN').format(totalPrice)} <?php echo __('VND'); ?>`;
                                
                                // Thêm hiệu ứng highlight
                                priceElement.style.backgroundColor = '#fff3cd';
                                setTimeout(() => {
                                    priceElement.style.backgroundColor = '';
                                    priceElement.style.transition = 'background-color 1s ease';
                                }, 1000);
                            }
                        }
                        
                        // Chỉ hiển thị thông báo nếu không phải chế độ im lặng
                        if (!isSilent) {
                            // Hiển thị thông báo chi tiết về các thay đổi
                            let changeText = '';
                            if (product.changes.includes('name')) changeText += 'tên, ';
                            if (product.changes.includes('price')) changeText += 'giá, ';
                            if (product.changes.includes('image')) changeText += 'hình ảnh, ';
                            if (product.changes.includes('quantity')) changeText += 'số lượng, ';
                            if (product.changes.includes('category')) changeText += 'danh mục, ';
                            
                            changeText = changeText.slice(0, -2); // Xóa dấu phẩy cuối cùng
                            
                            Toastify({
                                text: `<?php echo __('Sản phẩm'); ?> "${product.name}" <?php echo __('đã được cập nhật'); ?>:
${changeText}`,
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
                                            text: `<?php echo __('Sản phẩm đã chỉnh sửa tên'); ?>:
${product.changeDetails.name.old} → ${product.changeDetails.name.new}`,
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
                                            text: `<?php echo __('Sản phẩm'); ?> "${product.name}" <?php echo __('đã thay đổi giá'); ?>:
${new Intl.NumberFormat('vi-VN').format(product.changeDetails.price.old)} <?php echo __('VND'); ?> → ${new Intl.NumberFormat('vi-VN').format(product.changeDetails.price.new)} <?php echo __('VND'); ?>`,
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
                                            text: `<?php echo __('Sản phẩm'); ?> "${product.name}" <?php echo __('thay đổi danh mục'); ?>:
${product.changeDetails.category.old || '<?php echo __('Không có'); ?>'} → ${product.changeDetails.category.new}`,
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
                                    // Lấy số lượng tồn kho mới làm giá trị mặc định cho tồn kho tối đa
                                    const maxQuantity = (product.changeDetails.quantity && product.changeDetails.quantity.max) 
                                        ? product.changeDetails.quantity.max 
                                        : (product.changeDetails.quantity && product.changeDetails.quantity.new) 
                                            ? product.changeDetails.quantity.new 
                                            : product.quantity || "N/A";
                                    
                                    // Lấy giá trị old và new, với fallback nếu không có
                                    const oldQuantity = (product.changeDetails.quantity && product.changeDetails.quantity.old) 
                                        ? product.changeDetails.quantity.old 
                                        : "N/A";
                                    const newQuantity = (product.changeDetails.quantity && product.changeDetails.quantity.new) 
                                        ? product.changeDetails.quantity.new 
                                        : product.quantity || "N/A";
                                    
                                    setTimeout(() => {
                                        Toastify({
                                            text: `<?php echo __('Sản phẩm'); ?> "${product.name}" <?php echo __('đã điều chỉnh số lượng'); ?>:
${oldQuantity} → ${newQuantity}
<?php echo __('Số lượng tồn kho tối đa'); ?>: ${maxQuantity}`,
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
            }
            
            // Cập nhật tổng tiền nếu có thay đổi
            if (needsUpdate) {
                updateOrderSummary(data.cartSummary);
            }
        }
    })
    .catch(error => {
        console.error('<?php echo __("Error syncing cart:"); ?>', error);
    });
}

// Cập nhật thông tin tổng tiền của đơn hàng
function updateOrderSummary(summary) {
    if (!summary) return;
    
    // Cập nhật tạm tính ở bảng
    const subtotalRowElement = document.querySelector('tfoot tr:first-child td:last-child');
    if (subtotalRowElement) {
        subtotalRowElement.textContent = `${new Intl.NumberFormat('vi-VN').format(summary.subtotal)} <?php echo __('VND'); ?>`;
        
        // Hiệu ứng highlight
        subtotalRowElement.style.backgroundColor = '#fff3cd';
        setTimeout(() => {
            subtotalRowElement.style.backgroundColor = '';
            subtotalRowElement.style.transition = 'background-color 1s ease';
        }, 1000);
    }
    
    // Cập nhật giảm giá nếu có
    if (summary.discount > 0) {
        const discountRowElement = document.querySelector('tfoot tr:nth-child(2) td:last-child');
        if (discountRowElement) {
            discountRowElement.textContent = `-${new Intl.NumberFormat('vi-VN').format(summary.discount)} <?php echo __('VND'); ?>`;
        }
    }
    
    // Cập nhật tổng cộng ở bảng
    const totalRowElement = document.querySelector('tfoot tr:last-child td:last-child');
    if (totalRowElement) {
        totalRowElement.textContent = `${new Intl.NumberFormat('vi-VN').format(summary.total)} <?php echo __('VND'); ?>`;
        
        // Hiệu ứng highlight
        totalRowElement.style.backgroundColor = '#fff3cd';
        setTimeout(() => {
            totalRowElement.style.backgroundColor = '';
            totalRowElement.style.transition = 'background-color 1s ease';
        }, 1000);
    }
    
    // Cập nhật tóm tắt đơn hàng ở sidebar
    const sidebarSubtotalElement = document.querySelector('.card-body .list-group-item:nth-child(2) span:last-child');
    if (sidebarSubtotalElement) {
        sidebarSubtotalElement.textContent = `${new Intl.NumberFormat('vi-VN').format(summary.subtotal)} <?php echo __('VND'); ?>`;
    }
    
    // Cập nhật giảm giá ở sidebar nếu có
    if (summary.discount > 0) {
        const sidebarDiscountElement = document.querySelector('.card-body .list-group-item.text-success span:last-child');
        if (sidebarDiscountElement) {
            sidebarDiscountElement.textContent = `-${new Intl.NumberFormat('vi-VN').format(summary.discount)} <?php echo __('VND'); ?>`;
        }
    }
    
    // Cập nhật tổng cộng ở sidebar
    const sidebarTotalElement = document.querySelector('.card-body .list-group-item.fw-bold span:last-child');
    if (sidebarTotalElement) {
        sidebarTotalElement.textContent = `${new Intl.NumberFormat('vi-VN').format(summary.total)} <?php echo __('VND'); ?>`;
    }
}

// Handle real-time voucher updates in checkout page
document.addEventListener('DOMContentLoaded', function() {
    // Connect to WebSocket server when the page loads
    if (typeof realtime !== 'undefined') {
        // Listen for voucher update events
        realtime.on('update_voucher', function(data) {
            if (data && data.voucher) {
                <?php if (!empty($_SESSION['voucher_code'])): ?>
                // Compare with current voucher code in session
                const currentVoucherCode = "<?php echo $_SESSION['voucher_code']; ?>";
                
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
                                Toastify({
                                    text: "<?php echo __('Mã giảm giá đã hết hạn hoặc bị vô hiệu hóa và đã được gỡ bỏ'); ?>",
                                    duration: 3000,
                                    gravity: "top",
                                    position: "right",
                                    backgroundColor: "#fd7e14",
                                    stopOnFocus: true
                                }).showToast();
                                
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            }
                        });
                    } else {
                        // The voucher was updated but still valid
                        Toastify({
                            text: "<?php echo __('Mã giảm giá đã được cập nhật, trang sẽ tải lại để áp dụng thay đổi'); ?>",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#0d6efd",
                            stopOnFocus: true
                        }).showToast();
                        
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }
                }
                <?php endif; ?>
            }
        });
        
        // Listen for voucher deletion events
        realtime.on('delete_voucher', function(data) {
            if (data && data.voucher_code) {
                <?php if (!empty($_SESSION['voucher_code'])): ?>
                // Compare with current voucher code in session
                const currentVoucherCode = "<?php echo $_SESSION['voucher_code']; ?>";
                
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
                            Toastify({
                                text: "<?php echo __('Mã giảm giá đã bị xóa và đã được gỡ bỏ khỏi giỏ hàng của bạn'); ?>",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#dc3545",
                                stopOnFocus: true
                            }).showToast();
                            
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        }
                    });
                }
                <?php endif; ?>
            }
        });
    }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>