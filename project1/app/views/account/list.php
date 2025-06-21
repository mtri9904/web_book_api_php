<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <h2>Danh sách tài khoản</h2>
    
    <div class="mb-3">
        <a href="/project1/account/add" class="btn btn-primary">Thêm tài khoản</a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($accounts) && is_array($accounts)): ?>
                    <?php foreach ($accounts as $account): ?>
                        <tr>
                            <td><?php echo $account->id; ?></td>
                            <td><?php echo htmlspecialchars($account->username); ?></td>
                            <td><?php echo htmlspecialchars($account->email); ?></td>
                            <td><?php echo htmlspecialchars($account->role); ?></td>
                            <td>
                                <a href="/project1/account/edit/<?php echo $account->id; ?>" class="btn btn-sm btn-warning">Sửa</a>
                                <a href="/project1/account/delete/<?php echo $account->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Không có tài khoản nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?> 