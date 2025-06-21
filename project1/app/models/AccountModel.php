<?php
class AccountModel
{
private $conn;
private $table_name = "account";
public function __construct($db)
{
$this->conn = $db;
}
public function getAccountByUsername($username)
{
    $query = "SELECT * FROM account WHERE username = :username";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    
    return $result;
}

public function getAccountByEmail($email)
{
$query = "SELECT * FROM account WHERE email = :email";
$stmt = $this->conn->prepare($query);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_OBJ);
return $result;
}

function save($username, $name, $password, $email, $age, $phone, $security_question, $security_answer, $role="user"){
$query = "INSERT INTO " . $this->table_name . "(username, fullname, password, email, age, phone, security_question, security_answer, role, is_active)
VALUES (:username, :fullname, :password, :email, :age, :phone, :security_question, :security_answer, :role, 1)";
$stmt = $this->conn->prepare($query);
// Làm sạch dữ liệu
$name = htmlspecialchars(strip_tags($name));
$username = htmlspecialchars(strip_tags($username));
$email = htmlspecialchars(strip_tags($email));
$phone = htmlspecialchars(strip_tags($phone));
$security_question = htmlspecialchars(strip_tags($security_question));
$security_answer = htmlspecialchars(strip_tags($security_answer));
// Gán dữ liệu vào câu lệnh
$stmt->bindParam(':username', $username);
$stmt->bindParam(':fullname', $name);
$stmt->bindParam(':password', $password);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':age', $age, PDO::PARAM_INT);
$stmt->bindParam(':phone', $phone);
$stmt->bindParam(':security_question', $security_question);
$stmt->bindParam(':security_answer', $security_answer);
$stmt->bindParam(':role', $role);
// Thực thi câu lệnh
if ($stmt->execute()) {
return true;
}
return false;
}
public function getAccountById($id)
{
    $query = "SELECT * FROM account WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
}
public function updateAccountById($id, $fullname = null, $password = null, $email = null, $age = null, $phone = null, $security_question = null, $security_answer = null, $is_active = null)
{
    $updateFields = [];
    $params = [];
    
    if ($fullname !== null) {
        $updateFields[] = "fullname = :fullname";
        $params[':fullname'] = $fullname;
    }
    
    if ($password !== null) {
        $updateFields[] = "password = :password";
        $params[':password'] = $password;
    }
    
    if ($email !== null) {
        $updateFields[] = "email = :email";
        $params[':email'] = $email;
    }
    
    if ($age !== null) {
        $updateFields[] = "age = :age";
        $params[':age'] = $age;
    }
    
    if ($phone !== null) {
        $updateFields[] = "phone = :phone";
        $params[':phone'] = $phone;
    }
    
    if ($security_question !== null) {
        $updateFields[] = "security_question = :security_question";
        $params[':security_question'] = $security_question;
    }
    
    if ($security_answer !== null) {
        $updateFields[] = "security_answer = :security_answer";
        $params[':security_answer'] = $security_answer;
    }
    
    if ($is_active !== null) {
        $updateFields[] = "is_active = :is_active";
        $params[':is_active'] = $is_active;
    }
    
    if (empty($updateFields)) {
        return false;
    }
    
    $query = "UPDATE account SET " . implode(", ", $updateFields) . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    return $stmt->execute();
}

public function getSecurityQuestions() {
    return [
        '1' => 'Tên trường tiểu học đầu tiên của bạn là gì?',
        '2' => 'Tên con vật cưng đầu tiên của bạn là gì?',
        '3' => 'Bạn sinh ra ở thành phố nào?',
        '4' => 'Họ tên đệm của mẹ bạn là gì?',
        '5' => 'Món ăn yêu thích của bạn là gì?',
        '6' => 'Bạn gặp người yêu/vợ/chồng đầu tiên ở đâu?',
        '7' => 'Người hùng thời thơ ấu của bạn là ai?',
        '8' => 'Chiếc xe đầu tiên của bạn là gì?'
    ];
}

public function verifySecurityAnswer($username, $security_answer) {
    $query = "SELECT * FROM account WHERE username = :username AND security_answer = :security_answer";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':security_answer', $security_answer, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
}

public function getAccountCount() {
    $query = "SELECT COUNT(*) FROM " . $this->table_name;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Admin user management methods
public function getAllUsers() {
    $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

public function getUserById($id) {
    return $this->getAccountById($id);
}

public function emailExists($email) {
    $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE email = :email";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

public function register($name, $email, $password, $role = 'user') {
    $query = "INSERT INTO " . $this->table_name . " 
              (username, fullname, email, password, role, is_active, created_at) 
              VALUES 
              (:username, :fullname, :email, :password, :role, 1, NOW())";
    
    $stmt = $this->conn->prepare($query);
    
    // Generate a username from email
    $username = explode('@', $email)[0] . rand(100, 999);
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Clean data
    $name = htmlspecialchars(strip_tags($name));
    $email = htmlspecialchars(strip_tags($email));
    
    // Bind parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':fullname', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);
    
    return $stmt->execute();
}

public function updateUser($id, $name, $email, $password = null, $role = 'user', $is_active = 1) {
    $query = "UPDATE " . $this->table_name . " SET fullname = :fullname, email = :email";
    
    // Nếu có mật khẩu mới, thêm vào cập nhật
    if (!empty($password)) {
        $query .= ", password = :password";
    }
    
    // Thêm cập nhật role
    $query .= ", role = :role";
    
    // Thêm cập nhật trạng thái tài khoản
    $query .= ", is_active = :is_active";
    
    $query .= " WHERE id = :id";
    
    $stmt = $this->conn->prepare($query);
    
    // Clean data
    $name = htmlspecialchars(strip_tags($name));
    $email = htmlspecialchars(strip_tags($email));
    
    // Bind parameters
    $stmt->bindParam(':fullname', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':is_active', $is_active, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    // Nếu có mật khẩu mới, hash và bind
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashed_password);
    }
    
    return $stmt->execute();
}

public function deleteUser($id) {
    $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}

public function getAccountByGoogleId($googleId) {
    $query = "SELECT * FROM account WHERE google_id = :google_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':google_id', $googleId, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
}

public function saveGoogleAccount($googleId, $email, $name) {
    // Check if account already exists with this email
    $existingAccount = $this->getAccountByEmail($email);
    if ($existingAccount) {
        // Update existing account with Google ID
        $query = "UPDATE account SET google_id = :google_id WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':google_id', $googleId);
        $stmt->bindParam(':email', $email);
        return $stmt->execute() ? $existingAccount : false;
    }

    // Create new account
    $username = explode('@', $email)[0] . rand(100, 999);
    // Tạo mật khẩu ngẫu nhiên cho tài khoản Google
    $randomPassword = bin2hex(random_bytes(8)); // Tạo chuỗi ngẫu nhiên 16 ký tự
    $hashedPassword = password_hash($randomPassword, PASSWORD_DEFAULT);
    
    $query = "INSERT INTO account (username, fullname, email, google_id, password, role, is_active) 
              VALUES (:username, :fullname, :email, :google_id, :password, 'user', 1)";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':fullname', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':google_id', $googleId);
    $stmt->bindParam(':password', $hashedPassword);
    
    if ($stmt->execute()) {
        return $this->getAccountByGoogleId($googleId);
    }
    return false;
}
}