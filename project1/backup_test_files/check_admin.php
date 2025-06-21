<?php
// Kết nối đến cơ sở dữ liệu
require_once 'app/config/database.php';
$db = (new Database())->getConnection();

// Kiểm tra tài khoản admin
$query = "SELECT * FROM account WHERE username = 'admin'";
$stmt = $db->prepare($query);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h1>Thông tin tài khoản admin</h1>";
echo "<pre>";
print_r($admin);
echo "</pre>";

if (isset($admin['role']) && $admin['role'] === 'admin') {
    echo "<p style='color:green;font-weight:bold;'>✓ Tài khoản admin có role 'admin'</p>";
} else {
    echo "<p style='color:red;font-weight:bold;'>✗ Tài khoản admin KHÔNG có role 'admin'</p>";
    
    // Cập nhật role cho admin
    echo "<h2>Đang cập nhật role cho admin...</h2>";
    $updateQuery = "UPDATE account SET role = 'admin' WHERE username = 'admin'";
    $updateStmt = $db->prepare($updateQuery);
    if ($updateStmt->execute()) {
        echo "<p style='color:green;'>✓ Đã cập nhật role thành 'admin' cho tài khoản admin</p>";
    } else {
        echo "<p style='color:red;'>✗ Lỗi khi cập nhật role: " . implode(", ", $updateStmt->errorInfo()) . "</p>";
    }
    
    // Kiểm tra lại
    $checkQuery = "SELECT role FROM account WHERE username = 'admin'";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->execute();
    $newRole = $checkStmt->fetchColumn();
    echo "<p>Role mới: " . $newRole . "</p>";
}

// Kiểm tra cấu trúc bảng account
echo "<h2>Cấu trúc bảng account</h2>";
$columnsQuery = "SHOW COLUMNS FROM account";
$columnsStmt = $db->prepare($columnsQuery);
$columnsStmt->execute();
$columns = $columnsStmt->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($columns);
echo "</pre>";

echo "<p>Bây giờ hãy <a href='/project1/account/logout'>đăng xuất</a> và đăng nhập lại với tài khoản admin.</p>";
?> 