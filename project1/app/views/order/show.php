<?php include 'app/views/shares/header.php'; ?>

<!-- CSS cho modal xem ảnh tùy chỉnh -->
<style>
    .product-thumbnail {
        transition: transform 0.3s ease;
        cursor: zoom-in;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .product-thumbnail:hover {
        transform: scale(1.05);
    }
    
    /* Modal xem ảnh */
    .custom-image-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background-color: rgba(0,0,0,0.3);
    }
    
    .modal-content {
        position: relative;
        background-color: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.4);
        max-width: 80%;
        max-height: 90vh;
        margin: 0 auto;
        overflow: hidden;
        width: 800px;
    }
    
    .modal-image {
        max-width: 100%;
        max-height: 70vh;
        object-fit: contain;
        display: block;
        margin: 0 auto;
    }
    
    .modal-close {
        position: absolute;
        top: -15px;
        right: -15px;
        width: 36px;
        height: 36px;
        background-color: white;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(0,0,0,0.5);
        z-index: 10;
        border: none;
        padding: 0;
        line-height: 36px;
        text-align: center;
    }
    
    .modal-close::before {
        content: "×";
        display: block;
    }
    
    .modal-close:hover {
        background-color: #f0f0f0;
    }
    
    .modal-title {
        text-align: center;
        margin-top: 8px;
        font-size: 14px;
        color: #333;
    }
</style>
<h1><?php echo __('Chi tiết đơn hàng'); ?></h1>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <h4 class="mb-3 text-primary"><?php echo __('Thông tin khách hàng'); ?></h4>
        <p><strong><?php echo __('Họ tên:'); ?></strong> <?php echo htmlspecialchars($order->name, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong><?php echo __('Số điện thoại:'); ?></strong> <?php echo htmlspecialchars($order->phone, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong><?php echo __('Địa chỉ:'); ?></strong> <?php echo htmlspecialchars($order->address, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php if (!empty($order->voucher_code)): ?>
            <p><strong><?php echo __('Mã giảm giá đã áp dụng:'); ?></strong> <span class="badge bg-success"><?php echo htmlspecialchars($order->voucher_code); ?></span></p>
        <?php endif; ?>
        <p><strong><?php echo __('Tổng tiền:'); ?></strong> <?php echo number_format($order->total, 0, ',', '.'); ?> VND</p>
        <?php if (!empty($order->voucher_discount) && $order->voucher_discount > 0): ?>
            <p><strong><?php echo __('Số tiền được giảm:'); ?></strong> <span class="text-success">- <?php echo number_format($order->voucher_discount, 0, ',', '.'); ?> VND</span></p>
        <?php endif; ?>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <h4 class="mb-3 text-primary" id="review-section"><?php echo __('Sản phẩm đã đặt'); ?></h4>
        <ul class="list-group mb-3">
            <?php foreach ($orderDetails as $item): ?>
            <li class="list-group-item">
                <div class="d-flex align-items-center gap-3">
                    <?php if (!empty($item->product_image)): ?>
                        <a href="/project1/<?php echo htmlspecialchars($item->product_image); ?>" 
                           data-title="<?php echo htmlspecialchars($item->product_name); ?>"
                           data-alt="<?php echo htmlspecialchars($item->product_name); ?>">
                            <img src="/project1/<?php echo htmlspecialchars($item->product_image); ?>" 
                                 alt="<?php echo htmlspecialchars($item->product_name); ?>" 
                                 class="product-thumbnail"
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px; background: #f6f6f6; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        </a>
                    <?php else: ?>
                        <img src="https://via.placeholder.com/80x80?text=No+Image" 
                             alt="No image" 
                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px; background: #f6f6f6;">
                    <?php endif; ?>
                    <div class="flex-grow-1">
                        <strong><?php echo htmlspecialchars($item->product_name); ?></strong>

                        
                        <?php if (!empty($item->category_name)): ?>
                            <div class="small">
                                <span class="badge bg-info text-dark"><?php echo htmlspecialchars($item->category_name); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (strpos($item->product_name, '[Sản phẩm đã bị xóa]') !== false): ?>
                            <div class="small text-danger mt-1"><?php echo __('Sản phẩm này đã bị xóa khỏi hệ thống'); ?></div>
                        <?php endif; ?>
                        
                        <div class="small text-muted mt-2"><?php echo __('Giá:'); ?> <?php echo number_format($item->price, 0, ',', '.'); ?> VND</div>
                        <div class="small text-muted"><?php echo __('Số lượng:'); ?> <?php echo $item->quantity; ?></div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="fw-bold"><?php echo number_format($item->price * $item->quantity, 0, ',', '.'); ?> VND</span>
                        <?php
                        // Kiểm tra sản phẩm có còn tồn tại trong hệ thống hay không
                        require_once 'app/models/ProductModel.php';
                        $productModel = new ProductModel($this->db);
                        $productExists = $productModel->getProductById($item->product_id);
                        
                        if ($productExists && strpos($item->product_name, '[Sản phẩm đã bị xóa]') === false):
                            // Kiểm tra nếu người dùng đã đánh giá sản phẩm này chưa (bất kỳ đơn hàng nào)
                            require_once 'app/models/ReviewModel.php';
                            $reviewModel = new ReviewModel($this->db);
                            $hasReviewedInOrder = $reviewModel->checkUserReviewedProduct($_SESSION['user_id'], $item->product_id, $order->id);
                            $hasReviewedAnywhere = $reviewModel->hasUserReviewedProduct($_SESSION['user_id'], $item->product_id);
                        ?>
                            <?php if (!$hasReviewedInOrder && !$hasReviewedAnywhere): ?>
                                <a href="/project1/review/add?product_id=<?php echo $item->product_id; ?>&order_id=<?php echo $order->id; ?>" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="bi bi-star me-1"></i><?php echo __('Đánh giá'); ?>
                                </a>
                            <?php elseif ($hasReviewedInOrder): ?>
                                <span class="badge bg-success mt-2"><i class="bi bi-check-circle me-1"></i><?php echo __('Đã đánh giá trong đơn hàng này'); ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary mt-2"><i class="bi bi-check-circle me-1"></i><?php echo __('Đã đánh giá sản phẩm này'); ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge bg-secondary mt-2"><?php echo __('Sản phẩm không còn ở shop'); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<a href="/project1/order/list" class="btn btn-secondary"><?php echo __('Quay lại danh sách đơn hàng'); ?></a>
<!-- JavaScript cho modal xem ảnh tùy chỉnh -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tạo modal container
        const modal = document.createElement('div');
        modal.className = 'custom-image-modal';
        modal.innerHTML = `
            <div class="modal-content">
                <button class="modal-close"></button>
                <img class="modal-image" src="" alt="Ảnh sản phẩm">
                <div class="modal-title"></div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Tìm tất cả các thumbnail và thêm sự kiện click
        document.querySelectorAll('.product-thumbnail').forEach(img => {
            const parentLink = img.parentElement;
            if (parentLink && parentLink.tagName === 'A') {
                const fullImg = parentLink.getAttribute('href');
                const title = parentLink.getAttribute('data-title') || '';
                
                // Thêm thuộc tính cho modal
                img.setAttribute('data-full-img', fullImg);
                img.setAttribute('title', title);
                
                // Ngăn chặn hành vi mặc định của link
                parentLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Hiển thị ảnh trong modal
                    document.querySelector('.modal-image').src = fullImg;
                    document.querySelector('.modal-title').textContent = title;
                    modal.style.display = 'flex';
                });
            }
        });
        
        // Đóng modal khi click vào nút đóng
        document.querySelector('.modal-close').addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        // Đóng modal khi click ra ngoài ảnh
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
        
        // Đóng modal khi nhấn ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                modal.style.display = 'none';
            }
        });
    });
</script>

<?php include 'app/views/shares/footer.php'; ?>