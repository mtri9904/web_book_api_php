<?php
$activePage = 'user';
$pageTitle = __('Quản lý người dùng');
ob_start();
?>

<div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-header bg-gradient-primary-to-secondary">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="text-white mb-0">
                    <i class="bi bi-people me-2"></i><?= __('Danh sách người dùng') ?>
                </h3>
            </div>
            <div class="col-auto">
                <a href="/project1/admin/user/add" class="btn btn-light">
                    <i class="bi bi-plus-circle me-1"></i> <?= __('Thêm người dùng mới') ?>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if (isset($users) && count($users) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60">#</th>
                            <th><?= __('Tên') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Vai trò') ?></th>
                            <th><?= __('Trạng thái') ?></th>
                            <th><?= __('Ngày đăng ký') ?></th>
                            <th width="180" class="text-center"><?= __('Thao tác') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user->id ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-icon-small me-2">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div>
                                            <span class="fw-medium"><?= htmlspecialchars($user->fullname) ?></span>
                                            <?php if (isset($user->username)) : ?>
                                                <div class="small text-muted">@<?= htmlspecialchars($user->username) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($user->email) ?></td>
                                <td>
                                    <?php if ($user->role === 'admin'): ?>
                                        <span class="badge bg-danger"><?= __('Quản trị viên') ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-info"><?= __('Người dùng') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($user->is_active) && $user->is_active == 1) : ?>
                                        <span class="badge bg-success"><?= __('Đang hoạt động') ?></span>
                                    <?php else : ?>
                                        <span class="badge bg-danger"><?= __('Đã khóa') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y', strtotime($user->created_at)) ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="/project1/admin/user/show/<?= $user->id ?>" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="<?= __('Xem') ?>">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/project1/admin/user/edit/<?= $user->id ?>" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="<?= __('Sửa') ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if ($_SESSION['user_id'] != $user->id): ?>
                                            <a href="javascript:void(0)" onclick="confirmDelete(<?= $user->id ?>)" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="<?= __('Xóa') ?>">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-danger btn-sm" disabled title="<?= __('Không thể xóa tài khoản hiện tại') ?>" data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-people-fill display-4 text-muted"></i>
                <p class="mt-3"><?= __('Không tìm thấy người dùng nào.') ?></p>
                <a href="/project1/admin/user/add" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> <?= __('Thêm người dùng mới') ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('Xác nhận xóa') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= __('Bạn có chắc chắn muốn xóa người dùng này? Hành động này không thể hoàn tác.') ?></p>
                <p class="text-danger"><strong><?= __('Cảnh báo') ?>:</strong> <?= __('Tất cả dữ liệu liên quan đến người dùng này cũng sẽ bị xóa.') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('Hủy') ?></button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger"><?= __('Xóa') ?></a>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary-to-secondary {
    background: linear-gradient(135deg, #1a2a44 0%, #293e63 100%);
}

.user-icon-small {
    width: 32px;
    height: 32px;
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-state {
    padding: 2rem;
    text-align: center;
}
</style>

<script>
    function confirmDelete(id) {
        document.getElementById('confirmDeleteBtn').href = '/project1/admin/user/delete/' + id;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?>