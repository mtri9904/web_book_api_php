<?php
class VoucherModel
{
    private $conn;
    private $table_name = "voucher";

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function getVouchers()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getVoucherById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
public function getVoucherByCode($code)
{
    $query = "SELECT * FROM voucher WHERE code = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$code]);
    return $stmt->fetch(PDO::FETCH_OBJ);
}
public function addVoucher($data) {
    $query = "INSERT INTO voucher 
        (code, description, discount_type, discount_amount, discount_percent, min_order_amount, start_date, end_date, max_uses, is_active)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([
        $data['code'],
        $data['description'],
        $data['discount_type'], // Loại giảm giá
        $data['discount_amount'],
        $data['discount_percent'],
        $data['min_order_amount'],
        $data['start_date'],
        $data['end_date'],
        $data['max_uses'],
        $data['is_active']
    ]);
}

    public function updateVoucher($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " SET 
            code = ?, description = ?, discount_amount = ?, discount_percent = ?, min_order_amount = ?, 
            start_date = ?, end_date = ?, max_uses = ?, is_active = ?
            WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['code'],
            $data['description'],
            $data['discount_amount'],
            $data['discount_percent'],
            $data['min_order_amount'],
            $data['start_date'],
            $data['end_date'],
            $data['max_uses'],
            $data['is_active'],
            $id
        ]);
    }
public function updateVoucherStatus() {
    // Cập nhật trạng thái các voucher đã hết hạn
    $query = "UPDATE voucher 
              SET is_active = FALSE 
              WHERE end_date < NOW() AND is_active = TRUE";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    
    // Cập nhật trạng thái các voucher đã hết lượt sử dụng
    $query = "UPDATE voucher 
              SET is_active = FALSE 
              WHERE max_uses > 0 AND current_uses >= max_uses AND is_active = TRUE";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    
    return true;
}

/**
 * Tăng số lần sử dụng voucher một cách an toàn
 * 
 * @param string $code Mã voucher cần cập nhật
 * @return boolean Kết quả cập nhật
 */
public function incrementVoucherUsage($code) {
    // Lấy thông tin voucher hiện tại
    $queryGet = "SELECT current_uses FROM voucher WHERE code = ?";
    $stmtGet = $this->conn->prepare($queryGet);
    $stmtGet->execute([$code]);
    $voucher = $stmtGet->fetch(PDO::FETCH_OBJ);
    
    // Tính toán giá trị current_uses mới
    $currentUses = $voucher && isset($voucher->current_uses) ? $voucher->current_uses : 0;
    $newUses = $currentUses + 1;
    
    // Cập nhật số lần sử dụng
    $queryUpdate = "UPDATE voucher SET current_uses = ? WHERE code = ?";
    $stmtUpdate = $this->conn->prepare($queryUpdate);
    $result = $stmtUpdate->execute([$newUses, $code]);
    
    // Cập nhật trạng thái voucher sau khi sử dụng
    $this->updateVoucherStatus();
    
    return $result;
}

    public function deleteVoucher($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
    public function getAllVouchers() {
    $query = "SELECT * FROM voucher ORDER BY created_at DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

}

?>