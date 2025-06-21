<?php
require_once('app/config/database.php');
require_once('app/models/VoucherModel.php');
require_once('app/helpers/SessionHelper.php');

class VoucherController
{
    private $voucherModel;
    private $db;

    public function __construct()
    {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /project1/account/login');
            exit;
        }
        $this->db = (new Database())->getConnection();
        $this->voucherModel = new VoucherModel($this->db);
    }

    public function list()
    {
        // Cập nhật trạng thái voucher hết hạn
        $this->voucherModel->updateVoucherStatus();
        
        $vouchers = $this->voucherModel->getVouchers();
        include 'app/views/voucher/list.php';
    }

    public function show($id)
    {
        $voucher = $this->voucherModel->getVoucherById($id);
        if ($voucher) {
            include 'app/views/voucher/show.php';
        } else {
            echo "Không tìm thấy voucher.";
        }
    }

    public function add()
    {
        $errors = [];
        include 'app/views/voucher/add.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'code' => $_POST['code'] ?? '',
                'description' => $_POST['description'] ?? '',
                'discount_type' => $_POST['discount_type'] ?? '',
                'discount_amount' => $_POST['discount_amount'] === '' ? 0 : $_POST['discount_amount'],
                'discount_percent' => $_POST['discount_percent'] === '' ? 0 : $_POST['discount_percent'],
                'min_order_amount' => $_POST['min_order_amount'] === '' ? 0 : $_POST['min_order_amount'],
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'max_uses' => $_POST['max_uses'] === '' ? 1 : $_POST['max_uses'],
                'is_active' => true
            ];

            $result = $this->voucherModel->addVoucher($data);
            if ($result) {
                $_SESSION['toast_message'] = "Thêm voucher thành công!";
                $_SESSION['toast_type'] = "success";
                header('Location: /project1/voucher/list');
            } else {
                $_SESSION['toast_message'] = "Không thể thêm voucher!";
                $_SESSION['toast_type'] = "error";
                header('Location: /project1/voucher/add');
            }
        }
    }

    public function edit($id)
    {
        $voucher = $this->voucherModel->getVoucherById($id);
        $errors = [];
        if ($voucher) {
            include 'app/views/voucher/edit.php';
        } else {
            echo "Không tìm thấy voucher.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $data = [
                'code' => $_POST['code'] ?? '',
                'description' => $_POST['description'] ?? '',
                'discount_type' => $_POST['discount_type'] ?? '',
                'discount_amount' => $_POST['discount_amount'] === '' ? 0 : $_POST['discount_amount'],
                'discount_percent' => $_POST['discount_percent'] === '' ? 0 : $_POST['discount_percent'],
                'min_order_amount' => $_POST['min_order_amount'] === '' ? 0 : $_POST['min_order_amount'],
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'max_uses' => $_POST['max_uses'] === '' ? 1 : $_POST['max_uses'],
                'is_active' => true
            ];
            $errors = [];
            if (empty($data['code'])) $errors[] = "Mã voucher không được để trống.";
            if (empty($data['start_date'])) $errors[] = "Ngày bắt đầu không được để trống.";
            if (empty($data['end_date'])) $errors[] = "Ngày kết thúc không được để trống.";
            if (empty($errors)) {
                $result = $this->voucherModel->updateVoucher($id, $data);
                if ($result) {
                    $_SESSION['toast_message'] = "Cập nhật voucher thành công!";
                    $_SESSION['toast_type'] = "success";
                } else {
                    $_SESSION['toast_message'] = "Không thể cập nhật voucher!";
                    $_SESSION['toast_type'] = "error";
                }
                header('Location: /project1/voucher/list');
                exit;
            } else {
                $voucher = (object)array_merge(['id' => $id], $data);
                include 'app/views/voucher/edit.php';
            }
        }
    }

    public function delete($id)
    {
        // Lấy code voucher
        $voucher = $this->voucherModel->getVoucherById($id);
        if (!$voucher) {
            $_SESSION['toast_message'] = "Không tìm thấy voucher!";
            $_SESSION['toast_type'] = "error";
            header('Location: /project1/voucher/list');
            return;
        }
        // Kiểm tra có đơn hàng nào dùng voucher này chưa
        $query = "SELECT COUNT(*) FROM orders WHERE voucher_code = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$voucher->code]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            $_SESSION['toast_message'] = "Không thể xóa voucher đã được sử dụng cho đơn hàng!";
            $_SESSION['toast_type'] = "error";
            header('Location: /project1/voucher/list');
            return;
        }
        $result = $this->voucherModel->deleteVoucher($id);
        if ($result) {
            $_SESSION['toast_message'] = "Xóa voucher thành công!";
            $_SESSION['toast_type'] = "success";
        } else {
            $_SESSION['toast_message'] = "Không thể xóa voucher!";
            $_SESSION['toast_type'] = "error";
        }
        header('Location: /project1/voucher/list');
    }
}
?>