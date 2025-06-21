<?php include 'app/views/shares/header.php'; ?>
<h1><?php echo __('Danh sách voucher'); ?></h1>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th><?php echo __('Mã'); ?></th>
            <th><?php echo __('Mô tả'); ?></th>
            <th><?php echo __('Giảm cố định'); ?></th>
            <th><?php echo __('Giảm (%)'); ?></th>
            <th><?php echo __('Đơn tối thiểu'); ?></th>
            <th><?php echo __('Bắt đầu'); ?></th>
            <th><?php echo __('Kết thúc'); ?></th>
            <th><?php echo __('Tối đa'); ?></th>
            <th><?php echo __('Đã dùng'); ?></th>
            <th><?php echo __('Trạng thái'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($vouchers as $voucher): ?>
        <tr>
            <td><?php echo $voucher->id; ?></td>
            <td><?php echo htmlspecialchars($voucher->code, ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($voucher->description, ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo $voucher->discount_amount !== null ? number_format($voucher->discount_amount, 0, ',', '.') : '0'; ?></td>
            <td><?php echo $voucher->discount_percent; ?></td>
            <td><?php echo $voucher->min_order_amount !== null ? number_format($voucher->min_order_amount, 0, ',', '.') : '0'; ?></td>
            <td><?php echo $voucher->start_date; ?></td>
            <td><?php echo $voucher->end_date; ?></td>
            <td><?php echo $voucher->max_uses; ?></td>
            <td><?php echo $voucher->current_uses; ?></td>
            <td>
                <?php echo $voucher->is_active ? '<span class="badge bg-success">'.__('Đang hoạt động').'</span>' : '<span class="badge bg-secondary">'.__('Ngừng').'</span>'; ?>
            </td>
        <!--
            <td>
                <a href="/project1/voucher/show/<?php echo $voucher->id; ?>" class="btn btn-primary btn-sm"><?php echo __('Xem'); ?></a>
                <a href="/project1/voucher/edit/<?php echo $voucher->id; ?>" class="btn btn-warning btn-sm"><?php echo __('Sửa'); ?></a>
                <a href="/project1/voucher/delete/<?php echo $voucher->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo __('Bạn có chắc chắn muốn xóa voucher này?'); ?>');"><?php echo __('Xóa'); ?></a>
            </td>
        -->
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include 'app/views/shares/footer.php'; ?>