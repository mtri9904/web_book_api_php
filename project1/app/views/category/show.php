<?php include 'app/views/shares/header.php'; ?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <h3 class="text-center font-weight-light my-2 text-white">
            <i class="bi bi-folder me-2"></i><?php echo __('Chi tiết danh mục'); ?>
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="row g-0">
            <!-- Phần trái - Thông tin cơ bản -->
            <div class="col-md-4 border-end">
                <div class="p-4 h-100 d-flex flex-column">
                    <div class="text-center mb-4">
                        <div class="category-icon mb-3 mx-auto">
                            <i class="bi bi-folder2-open display-1"></i>
                        </div>
                        
                        <h4 class="fw-bold"><?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?></h4>
                        <span class="badge bg-primary fs-6 px-3 py-2 mb-2">
                            ID: <?php echo $category->id; ?>
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-card-text me-2"></i><?php echo __('Mô tả'); ?>
                        </h5>
                        <p><?php echo !empty($category->description) ? htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8') : '<em class="text-muted">'.__('Không có mô tả').'</em>'; ?></p>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between">
                            <a href="/project1/Category/edit/<?php echo $category->id; ?>" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> <?php echo __('Sửa'); ?>
                            </a>
                            <a href="/project1/Category/list" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> <?php echo __('Quay lại'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Phần phải - Thông tin chi tiết -->
            <div class="col-md-8">
                <div class="p-4">
                    <div class="row">
                        <!-- Thống kê sản phẩm -->
                        <div class="col-12 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i><?php echo __('Thống kê sản phẩm'); ?></h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    // Đếm số sản phẩm trong danh mục
                                    $productCount = 0;
                                    $totalProducts = 0;
                                    $percentage = 0;
                                    
                                    try {
                                        // Kiểm tra xem bảng product có tồn tại không
                                        $checkTable = $GLOBALS['db']->query("SHOW TABLES LIKE 'product'");
                                        if ($checkTable->rowCount() > 0) {
                                            $query = "SELECT COUNT(*) FROM product WHERE category_id = ?";
                                            $stmt = $GLOBALS['db']->prepare($query);
                                            $stmt->execute([$category->id]);
                                            $productCount = $stmt->fetchColumn();
                                            
                                            // Lấy tổng số sản phẩm
                                            $queryTotal = "SELECT COUNT(*) FROM product";
                                            $stmtTotal = $GLOBALS['db']->prepare($queryTotal);
                                            $stmtTotal->execute();
                                            $totalProducts = $stmtTotal->fetchColumn();
                                            
                                            $percentage = $totalProducts > 0 ? ($productCount / $totalProducts) * 100 : 0;
                                        }
                                    } catch (PDOException $e) {
                                        // Bỏ qua lỗi nếu bảng không tồn tại
                                        $productCount = 0;
                                        $totalProducts = 0;
                                        $percentage = 0;
                                    }
                                    ?>
                                    
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="stats-icon bg-primary rounded-circle p-3 me-3">
                                                    <i class="bi bi-box-seam text-white fs-4"></i>
                                                </div>
                                                <div>
                                                    <h3 class="mb-0"><?php echo $productCount; ?></h3>
                                                    <p class="text-muted mb-0"><?php echo __('Sản phẩm'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="progress mt-3 mt-md-0" style="height: 10px;">
                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                    style="width: <?php echo $percentage; ?>%" 
                                                    aria-valuenow="<?php echo $percentage; ?>" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">
                                                <?php echo number_format($percentage, 1); ?>% <?php echo __('của tổng số sản phẩm'); ?>
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <?php if ($productCount > 0): ?>
                                    <div class="mt-4">
                                        <a href="/project1/Product/list?category_id=<?php echo $category->id; ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> <?php echo __('Xem tất cả sản phẩm'); ?>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sản phẩm mới nhất -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-box me-2"></i><?php echo __('Sản phẩm mới nhất'); ?></h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    // Lấy 5 sản phẩm mới nhất trong danh mục
                                    $latestProducts = [];
                                    
                                    try {
                                        // Kiểm tra xem bảng product có tồn tại không
                                        $checkTable = $GLOBALS['db']->query("SHOW TABLES LIKE 'product'");
                                        if ($checkTable->rowCount() > 0) {
                                            $queryProducts = "SELECT id, name, price, image FROM product WHERE category_id = ? ORDER BY id DESC LIMIT 5";
                                            $stmtProducts = $GLOBALS['db']->prepare($queryProducts);
                                            $stmtProducts->execute([$category->id]);
                                            $latestProducts = $stmtProducts->fetchAll(PDO::FETCH_OBJ);
                                        }
                                    } catch (PDOException $e) {
                                        // Bỏ qua lỗi nếu bảng không tồn tại
                                        $latestProducts = [];
                                    }
                                    
                                    if (count($latestProducts) > 0):
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="60"><?php echo __('Ảnh'); ?></th>
                                                    <th><?php echo __('Tên sản phẩm'); ?></th>
                                                    <th><?php echo __('Giá'); ?></th>
                                                    <th width="100"><?php echo __('Hành động'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($latestProducts as $product): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($product->image)): ?>
                                                        <img src="/project1/<?php echo $product->image; ?>" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" width="50" height="50" class="img-thumbnail">
                                                        <?php else: ?>
                                                        <div class="bg-light text-center" style="width: 50px; height: 50px; line-height: 50px;">
                                                            <i class="bi bi-image text-muted"></i>
                                                        </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ</td>
                                                    <td>
                                                        <a href="/project1/Product/show/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-info">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <?php echo __('Chưa có sản phẩm nào trong danh mục này'); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary-to-secondary {
    background: linear-gradient(135deg, #1a2a44 0%, #293e63 100%);
}

.category-icon {
    color: #1a2a44;
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 255, 204, 0.1);
    border-radius: 50%;
}

.stats-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<?php include 'app/views/shares/footer.php'; ?>