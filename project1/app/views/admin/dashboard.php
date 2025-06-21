<?php
$activePage = 'dashboard';
$pageTitle = __('Tổng quan hệ thống');

// Chuẩn bị dữ liệu cho biểu đồ phân bố sản phẩm theo danh mục
$categoryProductCounts = [];
foreach ($categories as $category) {
    // Đếm số sản phẩm trong mỗi danh mục
    $categoryProductCounts[$category->name] = isset($categoryStats[$category->id]) ? $categoryStats[$category->id] : 0;
}

// Sắp xếp theo số lượng sản phẩm (giảm dần)
arsort($categoryProductCounts);

// Giới hạn số danh mục hiển thị để đảm bảo biểu đồ không quá rối
$maxCategories = 10;
if (count($categoryProductCounts) > $maxCategories) {
    $othersCount = 0;
    $tempCounts = array_slice($categoryProductCounts, 0, $maxCategories - 1, true);
    
    foreach ($categoryProductCounts as $category => $count) {
        if (!array_key_exists($category, $tempCounts)) {
            $othersCount += $count;
        }
    }
    
    $categoryProductCounts = $tempCounts;
    if ($othersCount > 0) {
        $categoryProductCounts[__('Khác')] = $othersCount;
    }
}

ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="text-white mb-0">
                    <i class="bi bi-speedometer2 me-2"></i><?= __('Tổng quan hệ thống') ?>
                </h3>
            </div>
            <div class="col-auto">
                <span class="text-white">
                    <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y') ?>
                </span>
            </div>
        </div>
    </div>
    <div class="card-body py-4">
        <!-- Thẻ tổng quan dạng grid -->
        <div class="row g-4">
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm dashboard-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon rounded-circle bg-primary">
                                <i class="bi bi-box-seam text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0"><?= __('Sản phẩm') ?></h5>
                                <small class="text-muted"><?= __('Tổng số sản phẩm') ?></small>
                            </div>
                        </div>
                        <h1 class="display-5 text-primary mb-2"><?= $productCount ?></h1>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/project1/admin/product/list" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-arrow-right me-1"></i><?= __('Xem chi tiết') ?>
                            </a>
                            <a href="/project1/admin/product/add" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i><?= __('Thêm mới') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm dashboard-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon rounded-circle bg-success">
                                <i class="bi bi-people text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0"><?= __('Người dùng') ?></h5>
                                <small class="text-muted"><?= __('Tổng số tài khoản') ?></small>
                            </div>
                        </div>
                        <h1 class="display-5 text-success mb-2"><?= $userCount ?></h1>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/project1/admin/user/list" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-arrow-right me-1"></i><?= __('Xem chi tiết') ?>
                            </a>
                            <a href="/project1/admin/user/add" class="btn btn-sm btn-success">
                                <i class="bi bi-plus-lg me-1"></i><?= __('Thêm mới') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm dashboard-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon rounded-circle bg-danger">
                                <i class="bi bi-receipt text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0"><?= __('Đơn hàng') ?></h5>
                                <small class="text-muted"><?= __('Tổng số đơn hàng') ?></small>
                            </div>
                        </div>
                        <h1 class="display-5 text-danger mb-2"><?= $orderCount ?></h1>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/project1/admin/order/list" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-arrow-right me-1"></i><?= __('Xem chi tiết') ?>
                            </a>
                            <span class="badge bg-danger rounded-pill">
                                <i class="bi bi-bar-chart-fill me-1"></i><?= __('Đơn hàng thành công') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm dashboard-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon rounded-circle bg-info">
                                <i class="bi bi-folder2 text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0"><?= __('Danh mục') ?></h5>
                                <small class="text-muted"><?= __('Tổng số danh mục') ?></small>
                            </div>
                        </div>
                        <h1 class="display-5 text-info mb-2"><?= $categoryCount ?></h1>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 100%"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/project1/admin/category/list" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-arrow-right me-1"></i><?= __('Xem chi tiết') ?>
                            </a>
                            <a href="/project1/admin/category/add" class="btn btn-sm btn-info">
                                <i class="bi bi-plus-lg me-1"></i><?= __('Thêm mới') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm dashboard-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon rounded-circle bg-warning">
                                <i class="bi bi-ticket-perforated text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0"><?= __('Mã giảm giá') ?></h5>
                                <small class="text-muted"><?= __('Mã đang hoạt động') ?></small>
                            </div>
                        </div>
                        <h1 class="display-5 text-warning mb-2"><?= count($vouchers) ?></h1>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 100%"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/project1/admin/voucher/list" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-arrow-right me-1"></i><?= __('Xem chi tiết') ?>
                            </a>
                            <a href="/project1/admin/voucher/add" class="btn btn-sm btn-warning">
                                <i class="bi bi-plus-lg me-1"></i><?= __('Thêm mới') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm dashboard-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon rounded-circle bg-secondary">
                                <i class="bi bi-star text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0"><?= __('Đánh giá') ?></h5>
                                <small class="text-muted"><?= __('Tổng số đánh giá') ?></small>
                            </div>
                        </div>
                        <h1 class="display-5 text-secondary mb-2"><?= isset($reviewCount) ? $reviewCount : '0' ?></h1>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-secondary" role="progressbar" style="width: 100%"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/project1/admin/review" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-right me-1"></i><?= __('Xem chi tiết') ?>
                            </a>
                            <span class="badge bg-secondary rounded-pill">
                                <i class="bi bi-star-fill me-1"></i><?= __('Đánh giá khách hàng') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê và biểu đồ -->
        <div class="row mt-4 g-4">
            <!-- Mã giảm giá gần đây -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-ticket-perforated me-2"></i><?= __('Mã giảm giá đang hoạt động') ?>
                        </h5>
                        <a href="/project1/admin/voucher/list" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i><?= __('Xem tất cả') ?>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (count($vouchers) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th><?= __('Mã') ?></th>
                                            <th><?= __('Mô tả') ?></th>
                                            <th><?= __('Giảm giá') ?></th>
                                            <th><?= __('Hết hạn') ?></th>
                                            <th><?= __('Trạng thái') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($vouchers, 0, 5) as $voucher): ?>
                                            <tr>
                                                <td><strong><?= $voucher->code ?></strong></td>
                                                <td><?= htmlspecialchars(mb_substr($voucher->description, 0, 30)) ?><?= mb_strlen($voucher->description) > 30 ? '...' : '' ?></td>
                                                <td>
                                                    <?php if ($voucher->discount_amount > 0): ?>
                                                        <span class="badge bg-primary"><?= number_format($voucher->discount_amount) ?> VND</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-info"><?= $voucher->discount_percent ?>%</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($voucher->end_date)) ?></td>
                                                <td>
                                                    <?php if ($voucher->is_active): ?>
                                                        <span class="badge bg-success rounded-pill"><?= __('Hoạt động') ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger rounded-pill"><?= __('Không hoạt động') ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state p-4 text-center">
                                <i class="bi bi-ticket-perforated-fill display-4 text-muted"></i>
                                <p class="mt-3"><?= __('Không có mã giảm giá nào đang hoạt động') ?></p>
                                <a href="/project1/admin/voucher/add" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i><?= __('Thêm mã giảm giá mới') ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Biểu đồ phân bố sản phẩm theo danh mục -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-pie-chart me-2"></i><?= __('Phân bố sản phẩm theo danh mục') ?>
                        </h5>
                        <div class="btn-group btn-group-sm chart-type-toggle">
                            <button type="button" class="btn btn-outline-primary active" data-chart-type="donut">
                                <i class="bi bi-pie-chart"></i>
                            </button>
                            <button type="button" class="btn btn-outline-primary" data-chart-type="bar">
                                <i class="bi bi-bar-chart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 280px;">
                            <canvas id="categoryDistributionChart"></canvas>
                                    </div>
                        <div class="chart-legend mt-3 d-flex flex-wrap justify-content-center">
                            <!-- Legend will be generated dynamically -->
                                </div>
                        <div id="noDataMessage" class="text-center py-5" style="display: none;">
                            <i class="bi bi-exclamation-circle text-muted" style="font-size: 2.5rem;"></i>
                            <p class="mt-3 text-muted"><?= __('Không có dữ liệu danh mục để hiển thị') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Thống kê nâng cao -->
        <div class="row mt-4 g-4">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-lightning-charge me-2"></i><?= __('Thao tác nhanh') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <a href="/project1/admin/product/add" class="btn btn-primary w-100 p-3 d-flex flex-column align-items-center">
                                    <i class="bi bi-box-seam fs-2 mb-2"></i>
                                    <span><?= __('Thêm sản phẩm mới') ?></span>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="/project1/admin/category/add" class="btn btn-info w-100 p-3 d-flex flex-column align-items-center">
                                    <i class="bi bi-folder-plus fs-2 mb-2"></i>
                                    <span><?= __('Thêm danh mục mới') ?></span>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="/project1/admin/voucher/add" class="btn btn-warning w-100 p-3 d-flex flex-column align-items-center">
                                    <i class="bi bi-ticket-perforated fs-2 mb-2"></i>
                                    <span><?= __('Thêm mã giảm giá') ?></span>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="/project1/admin/order/list" class="btn btn-danger w-100 p-3 d-flex flex-column align-items-center">
                                    <i class="bi bi-receipt fs-2 mb-2"></i>
                                    <span><?= __('Xem đơn hàng') ?></span>
                                </a>
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

.dashboard-card {
    transition: all 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.stats-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stats-icon i {
    font-size: 1.5rem;
}

.empty-state {
    padding: 2rem;
    text-align: center;
}

/* Chart styles */
.chart-legend-item {
    display: inline-flex;
    align-items: center;
    margin: 5px 10px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s;
    padding: 3px 8px;
    border-radius: 20px;
}

.chart-legend-item:hover {
    background-color: rgba(0,0,0,0.05);
    transform: translateY(-2px);
}

.chart-legend-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 5px;
    display: inline-block;
}

.chart-legend-text {
    display: inline-flex;
    align-items: center;
}

.chart-legend-count {
    margin-left: 5px;
    background-color: #f8f9fa;
    padding: 1px 6px;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: bold;
}

.chart-type-toggle .btn.active {
    background-color: #1a2a44;
    color: white;
    border-color: #1a2a44;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lấy dữ liệu danh mục từ PHP
    const categoryData = <?= json_encode($categoryProductCounts ?? []); ?>;
    
    // Khởi tạo biểu đồ phân bố sản phẩm
    initCategoryChart(categoryData);
    
    // Xử lý chuyển đổi giữa các loại biểu đồ
    document.querySelectorAll('.chart-type-toggle button').forEach(button => {
        button.addEventListener('click', function() {
            const chartType = this.getAttribute('data-chart-type');
            document.querySelectorAll('.chart-type-toggle button').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            updateChartType(chartType);
        });
    });
});

// Biến lưu đối tượng biểu đồ
let categoryChart = null;
let currentChartType = 'donut';

// Hàm khởi tạo biểu đồ
function initCategoryChart(data) {
    const ctx = document.getElementById('categoryDistributionChart').getContext('2d');
    
    // Nếu không có dữ liệu, hiển thị thông báo
    if (!data || Object.keys(data).length === 0) {
        document.getElementById('noDataMessage').style.display = 'block';
        document.querySelector('.chart-container').style.display = 'none';
        document.querySelector('.chart-legend').style.display = 'none';
        return;
    }
    
    // Chuẩn bị dữ liệu cho biểu đồ
    const labels = Object.keys(data);
    const values = Object.values(data);
    
    // Tạo danh sách màu ngẫu nhiên nhưng đẹp mắt
    const backgroundColors = generateNiceColors(labels.length);
    
    // Tạo biểu đồ
    categoryChart = new Chart(ctx, {
        type: currentChartType === 'donut' ? 'doughnut' : 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: backgroundColors,
                borderColor: backgroundColors.map(color => adjustBrightness(color, -20)),
                borderWidth: 1,
                hoverOffset: 15,
                borderRadius: currentChartType === 'bar' ? 5 : 0
            }]
        },
        options: getChartOptions()
    });
    
    // Tạo phần chú thích (legend) tùy chỉnh
    createCustomLegend(labels, values, backgroundColors);
}

// Hàm cập nhật kiểu biểu đồ
function updateChartType(chartType) {
    if (!categoryChart) return;
    
    currentChartType = chartType;
    
    // Lưu dữ liệu hiện tại
    const data = categoryChart.data;
    
    // Hủy biểu đồ hiện tại
    categoryChart.destroy();
    
    // Tạo biểu đồ mới với kiểu đã chọn
    const ctx = document.getElementById('categoryDistributionChart').getContext('2d');
    categoryChart = new Chart(ctx, {
        type: chartType === 'donut' ? 'doughnut' : 'bar',
        data: data,
        options: getChartOptions()
    });
    
    // Cập nhật dataset cho phù hợp với kiểu biểu đồ
    if (chartType === 'bar') {
        categoryChart.data.datasets[0].borderRadius = 5;
    } else {
        categoryChart.data.datasets[0].borderRadius = 0;
    }
    
    categoryChart.update();
}

// Hàm trả về các tùy chọn cho biểu đồ
function getChartOptions() {
    const baseOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false // Ẩn legend mặc định, chúng ta sẽ tự tạo
            },
            tooltip: {
                enabled: true,
                backgroundColor: 'rgba(26, 42, 68, 0.95)',
                titleFont: {
                    size: 14,
                    weight: 'bold',
                    family: "'Segoe UI', Roboto, 'Helvetica Neue', sans-serif"
                },
                bodyFont: {
                    size: 13,
                    family: "'Segoe UI', Roboto, 'Helvetica Neue', sans-serif"
                },
                padding: 12,
                cornerRadius: 8,
                displayColors: true,
                borderColor: 'rgba(255,255,255,0.2)',
                borderWidth: 1,
                titleColor: 'rgba(255, 255, 255, 0.95)',
                bodyColor: 'rgba(255, 255, 255, 0.9)',
                boxShadow: '0 4px 8px rgba(0,0,0,0.2)',
                animations: {
                    opacity: {
                        duration: 200
                    }
                },
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${label}: ${value} <?= __('sản phẩm') ?> (${percentage}%)`;
                    },
                    title: function(tooltipItems) {
                        return tooltipItems[0].label;
                    },
                    labelTextColor: function() {
                        return 'rgba(255, 255, 255, 0.9)';
                    }
                }
            },
            datalabels: {
                display: false
            }
        },
        animation: {
            duration: 1800,
            easing: 'easeOutElastic',
            animateScale: true,
            animateRotate: true,
            delay: function(context) {
                // Thêm hiệu ứng lần lượt xuất hiện cho từng segment
                return context.dataIndex * 100;
            }
        },
        // Thêm hiệu ứng hover
        hover: {
            mode: 'index',
            intersect: false,
            animationDuration: 300
        },
        // Thêm interaction
        onClick: function(event, elements) {
            if (elements.length > 0) {
                const index = elements[0].index;
                toggleDatasetVisibility(index);
            }
        }
    };
    
    // Tùy chọn riêng cho biểu đồ cột
    if (currentChartType === 'bar') {
        return {
            ...baseOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                                            title: {
                            display: true,
                            text: '<?= __('Số lượng sản phẩm') ?>',
                            font: {
                                size: 12,
                                weight: 'normal'
                            }
                        }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        },
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        };
    }
    
    // Tùy chọn riêng cho biểu đồ tròn
    return {
        ...baseOptions,
        cutout: '60%', // Độ dày của donut
        radius: '90%'
    };
}

// Tạo custom legend
function createCustomLegend(labels, values, colors) {
    const legendContainer = document.querySelector('.chart-legend');
    legendContainer.innerHTML = '';
    
    // Tổng số sản phẩm để tính phần trăm
    const total = values.reduce((a, b) => a + b, 0);
    
    // Lọc ra những danh mục có số lượng sản phẩm > 0
    labels.forEach((label, index) => {
        if (values[index] <= 0) return;
        
                                const percentage = ((values[index] / total) * 100).toFixed(1);
        const legendItem = document.createElement('div');
        legendItem.className = 'chart-legend-item';
        legendItem.dataset.index = index;
        
        // Tạo nội dung với hiệu ứng hover
        legendItem.innerHTML = `
            <span class="chart-legend-color" style="background-color: ${colors[index]}"></span>
            <span class="chart-legend-text">
                ${label} 
                <span class="chart-legend-count" title="${percentage}% <?= __('của tổng số') ?>">${values[index]}</span>
            </span>
        `;
        
        // Thêm sự kiện click để highlight segment tương ứng
        legendItem.addEventListener('click', function() {
            toggleDatasetVisibility(parseInt(this.dataset.index));
        });
        
        // Thêm sự kiện hover để highlight segment tương ứng
        legendItem.addEventListener('mouseenter', function() {
            highlightDataset(parseInt(this.dataset.index), true);
        });
        
        legendItem.addEventListener('mouseleave', function() {
            highlightDataset(parseInt(this.dataset.index), false);
        });
        
        legendContainer.appendChild(legendItem);
    });
    
    // Thêm nút Reset nếu có từ 2 danh mục trở lên
    if (labels.length > 1) {
        const resetButton = document.createElement('button');
        resetButton.className = 'btn btn-sm btn-outline-secondary mt-2 ms-2';
        resetButton.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i><?= __('Hiển thị tất cả') ?>';
        resetButton.addEventListener('click', resetVisibility);
        
        // Thêm vào DOM sau phần legend
        legendContainer.parentNode.insertBefore(resetButton, legendContainer.nextSibling);
    }
}

// Hàm bật/tắt hiển thị của segment trong biểu đồ
function toggleDatasetVisibility(index) {
    if (!categoryChart) return;
    
    const meta = categoryChart.getDatasetMeta(0);
    const isHidden = meta.data[index].hidden;
    
    meta.data[index].hidden = !isHidden;
    
    // Highlight hoặc unhighlight item trong legend
    const legendItems = document.querySelectorAll(`.chart-legend-item[data-index="${index}"]`);
    if (legendItems.length > 0) {
        const legendItem = legendItems[0];
        if (isHidden) {
            legendItem.style.opacity = '1';
            legendItem.style.backgroundColor = '';
        } else {
            legendItem.style.opacity = '0.5';
            legendItem.style.backgroundColor = '#f8f9fa';
        }
    }
    
    categoryChart.update();
    
    // Kiểm tra xem có cần hiển thị nút reset không
    checkResetButtonVisibility();
}

// Hàm highlight dataset khi hover
function highlightDataset(index, isHighlighted) {
    if (!categoryChart) return;
    
    const meta = categoryChart.getDatasetMeta(0);
    
    // Không áp dụng highlight cho các phần tử đã bị ẩn
    if (meta.data[index].hidden) return;
    
    if (isHighlighted) {
        // Làm mờ tất cả các segment khác
        meta.data.forEach((dataPoint, i) => {
            if (i !== index && !dataPoint.hidden) {
                dataPoint.options.backgroundColor = makeColorTransparent(dataPoint.options.backgroundColor, 0.3);
            }
        });
        
        // Làm nổi bật segment được chọn
        meta.data[index].options.borderWidth = 2;
        meta.data[index].options.borderColor = '#ffffff';
        meta.data[index].options.hoverOffset = 10;
        
        if (currentChartType === 'donut') {
            // Hiệu ứng pull-out cho biểu đồ tròn
            meta.data[index].options.offset = 10;
        } else {
            // Hiệu ứng độ sáng cho biểu đồ cột
            meta.data[index].options.backgroundColor = makeColorBrighter(meta.data[index].options.backgroundColor, 20);
        }
    } else {
        // Khôi phục lại tất cả các segment
        const originalColors = categoryChart.data.datasets[0].backgroundColor;
        meta.data.forEach((dataPoint, i) => {
            dataPoint.options.backgroundColor = originalColors[i];
            dataPoint.options.borderWidth = 1;
            dataPoint.options.hoverOffset = 15;
            dataPoint.options.offset = 0;
        });
    }
    
    categoryChart.update();
}

// Hàm reset lại tất cả dataset về trạng thái hiển thị
function resetVisibility() {
    if (!categoryChart) return;
    
    const meta = categoryChart.getDatasetMeta(0);
    
    // Hiện tất cả segment
    meta.data.forEach(dataPoint => {
        dataPoint.hidden = false;
    });
    
    // Reset tất cả legend item
    document.querySelectorAll('.chart-legend-item').forEach(item => {
        item.style.opacity = '1';
        item.style.backgroundColor = '';
    });
    
    categoryChart.update();
    
    // Ẩn nút reset
    checkResetButtonVisibility(true);
}

// Kiểm tra và cập nhật trạng thái nút reset
function checkResetButtonVisibility(forceHide = false) {
    const resetButton = document.querySelector('.chart-legend').nextElementSibling;
    if (!resetButton || !resetButton.classList.contains('btn-outline-secondary')) return;
    
    if (forceHide) {
        resetButton.style.display = 'none';
        return;
    }
    
    // Kiểm tra xem có segment nào đang bị ẩn không
    if (categoryChart) {
        const meta = categoryChart.getDatasetMeta(0);
        const hasHiddenSegment = meta.data.some(dataPoint => dataPoint.hidden);
        resetButton.style.display = hasHiddenSegment ? 'inline-block' : 'none';
    }
}

// Làm mờ màu sắc
function makeColorTransparent(color, opacity) {
    if (color.startsWith('#')) {
        const r = parseInt(color.slice(1, 3), 16);
        const g = parseInt(color.slice(3, 5), 16);
        const b = parseInt(color.slice(5, 7), 16);
        return `rgba(${r}, ${g}, ${b}, ${opacity})`;
    } else if (color.startsWith('rgb')) {
        return color.replace('rgb', 'rgba').replace(')', `, ${opacity})`);
    } else if (color.startsWith('rgba')) {
        return color.replace(/rgba\((.+?),\s*[\d.]+\)/, `rgba($1, ${opacity})`);
    }
    return color;
}

// Làm sáng màu sắc
function makeColorBrighter(color, amount) {
    if (color.startsWith('#')) {
        return adjustBrightness(color, amount);
    } else if (color.startsWith('rgb')) {
        // Chuyển rgb thành mảng [r, g, b]
        const rgb = color.match(/\d+/g).map(Number);
        // Tăng độ sáng cho mỗi thành phần
        const newRgb = rgb.map(v => Math.min(255, v + amount));
        return `rgb(${newRgb.join(', ')})`;
    }
    return color;
}

// Tạo màu ngẫu nhiên nhưng đẹp mắt
function generateNiceColors(count) {
    // Palette màu được cải thiện - màu sắc hài hòa và dễ nhìn hơn
    const baseColors = [
        '#4361ee', '#3a0ca3', '#7209b7', '#f72585', '#4cc9f0',
        '#4895ef', '#560bad', '#f3722c', '#f8961e', '#90be6d',
        '#43aa8b', '#577590', '#277da1', '#fb8500', '#023e8a',
        '#0077b6', '#0096c7', '#00b4d8', '#48cae4', '#14213d',
        '#006d77', '#83c5be', '#ee6c4d', '#293241', '#5e60ce',
        '#ff9e00', '#38b000', '#9d4edd', '#ff5400', '#3a86ff'
    ];
    
    // Nếu số lượng danh mục ít hơn số màu có sẵn
    if (count <= baseColors.length) {
        // Lấy màu với khoảng cách đều để tránh màu gần giống nhau
        const step = Math.floor(baseColors.length / count);
        const result = [];
        for (let i = 0; i < count; i++) {
            result.push(baseColors[(i * step) % baseColors.length]);
        }
        return result;
    }
    
    // Nếu cần nhiều màu hơn, tạo thêm màu ngẫu nhiên với hue đều nhau
    const colors = [...baseColors];
    const additionalCount = count - baseColors.length;
    
    for (let i = 0; i < additionalCount; i++) {
        const h = Math.floor((i / additionalCount) * 360);
        const s = 70 + Math.floor(Math.random() * 20); // 70-90%
        const l = 45 + Math.floor(Math.random() * 10); // 45-55%
        colors.push(`hsl(${h}, ${s}%, ${l}%)`);
    }
    
    // Xáo trộn mảng màu để tăng tính đa dạng
    return shuffleArray(colors.slice(0, count));
}

// Hàm xáo trộn mảng
function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

// Hàm điều chỉnh độ sáng của màu (cho border)
function adjustBrightness(color, amount) {
    // Nếu là màu hex
    if (color.startsWith('#')) {
        // Chuyển sang RGB
        let r = parseInt(color.slice(1, 3), 16);
        let g = parseInt(color.slice(3, 5), 16);
        let b = parseInt(color.slice(5, 7), 16);
        
        // Điều chỉnh giá trị
        r = Math.max(0, Math.min(255, r + amount));
        g = Math.max(0, Math.min(255, g + amount));
        b = Math.max(0, Math.min(255, b + amount));
        
        // Chuyển lại thành hex
        return `#${r.toString(16).padStart(2, '0')}${g.toString(16).padStart(2, '0')}${b.toString(16).padStart(2, '0')}`;
    }
    // Nếu là màu hsl
    else if (color.startsWith('hsl')) {
        const match = color.match(/hsl\((\d+),\s*(\d+)%,\s*(\d+)%\)/);
        if (!match) return color;
        
        const h = parseInt(match[1]);
        const s = parseInt(match[2]);
        let l = parseInt(match[3]);
        
        // Chỉ điều chỉnh lightness
        l = Math.max(0, Math.min(100, l + Math.sign(amount) * 10));
        
        return `hsl(${h}, ${s}%, ${l}%)`;
    }
    
    return color;
}
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?> 