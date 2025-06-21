<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/utils/JWTHandler.php');

class AccountController {
private $accountModel;
private $db;
private $jwtHandler;

public function __construct($db = null) {
if ($db) {
$this->db = $db;
} else {
$this->db = (new Database())->getConnection();
}
$this->accountModel = new AccountModel($this->db);
$this->jwtHandler = new JWTHandler();
}
function register(){
    $securityQuestions = $this->accountModel->getSecurityQuestions();
    include_once 'app/views/account/register.php';
}
public function login() {
    $error = '';
    if (isset($_SESSION['login_error'])) {
        $error = $_SESSION['login_error'];
        unset($_SESSION['login_error']);
    }
    include_once 'app/views/account/login.php';
}
function save(){
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$username = $_POST['username'] ?? '';
$fullName = $_POST['fullname'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmpassword'] ?? '';
$email = $_POST['email'] ?? '';
$age = $_POST['age'] ?? 0;
$phone = $_POST['phone'] ?? '';
$securityQuestion = $_POST['security_question'] ?? '';
$securityAnswer = $_POST['security_answer'] ?? '';
$errors =[];
if(empty($username)){
$errors['username'] = "Vui lòng nhập tên đăng nhập!";
}
if(empty($fullName)){
$errors['fullname'] = "Vui lòng nhập họ tên!";
}
if(empty($password)){
$errors['password'] = "Vui lòng nhập mật khẩu!";
}
if($password != $confirmPassword){
$errors['confirmPass'] = "Mật khẩu và xác nhận không khớp";
}
if(empty($email)){
$errors['email'] = "Vui lòng nhập email!";
}
if(empty($phone)){
$errors['phone'] = "Vui lòng nhập số điện thoại!";
}
if(empty($securityQuestion)){
$errors['security_question'] = "Vui lòng chọn câu hỏi bảo mật!";
}
if(empty($securityAnswer)){
$errors['security_answer'] = "Vui lòng nhập câu trả lời bảo mật!";
}
//kiểm tra username đã được đăng ký chưa?
$account = $this->accountModel->getAccountByUsername($username);
if($account){
$errors['account'] = "Tài khoản này đã có người đăng ký!";
}
if(count($errors) > 0){
    $securityQuestions = $this->accountModel->getSecurityQuestions();
    include_once 'app/views/account/register.php';
}else{
$password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
$result = $this->accountModel->save($username, $fullName, $password, $email, $age, $phone, $securityQuestion, $securityAnswer);
if($result){
    // Hiển thị thông báo thành công và chuyển hướng đến trang đăng nhập
    header('Location: /project1/account/login');
    exit;
}
}
}
}
function logout(){
    // Xóa tất cả các biến session liên quan đến người dùng
    unset($_SESSION['username']);
    unset($_SESSION['user_id']);
    unset($_SESSION['role']);
    
    // Chuyển hướng về trang chủ index.php
    header('Location: /project1');
    exit;
}
public function checkLogin() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $account = $this->accountModel->getAccountByUsername($username);

        if ($account) {
            // Kiểm tra nếu tài khoản bị khóa
            if (isset($account->is_active) && $account->is_active == 0) {
                $_SESSION['login_error'] = 'Tài khoản của bạn tạm thời bị khóa';
                header('Location: /project1/account/login');
                exit;
            }
            
            $pwd_hashed = $account->password;
            if (password_verify($password, $pwd_hashed)) {
                $_SESSION['user_id'] = $account->id; // Lưu user_id vào session
                $_SESSION['username'] = $account->username;
                $_SESSION['role'] = $account->role; // Lưu role vào session
                
                // Chuyển hướng đến trang chủ index.php thay vì trang sản phẩm
                header('Location: /project1');
                exit;
            } else {
                // Lưu thông báo lỗi vào session
                $_SESSION['login_error'] = 'Mật khẩu không chính xác';
                header('Location: /project1/account/login');
                exit;
            }
        } else {
            // Lưu thông báo lỗi vào session
            $_SESSION['login_error'] = 'Tài khoản không tồn tại';
            header('Location: /project1/account/login');
            exit;
        }
    }
}
public function info() {
    if (!SessionHelper::isLoggedIn()) {
        header('Location: /project1/account/login');
        exit;
    }
    
    if (!isset($_SESSION['user_id'])) {
        // Nếu không có user_id trong session, đăng xuất và chuyển hướng đến trang đăng nhập
        $this->logout();
        exit;
    }
    
    $userId = $_SESSION['user_id'];
    $account = $this->accountModel->getAccountById($userId);
    
    // Nếu không tìm thấy tài khoản trong database, đăng xuất và chuyển hướng đến trang đăng nhập
    if (!$account) {
        $this->logout();
        exit;
    }
    
    $success = null;
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullname = $_POST['fullname'] ?? '';
        $email = $_POST['email'] ?? '';
        $age = $_POST['age'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';
        
        if (empty($fullname)) {
            $errors[] = 'Họ tên không được để trống';
        }
        if (empty($email)) {
            $errors[] = 'Email không được để trống';
        }
        if (empty($phone)) {
            $errors[] = 'Số điện thoại không được để trống';
        }
        
        $newPassword = null;
        if (!empty($password) || !empty($confirm)) {
            if ($password !== $confirm) {
                $errors[] = 'Mật khẩu xác nhận không khớp';
            } elseif (strlen($password) < 4) {
                $errors[] = 'Mật khẩu phải từ 4 ký tự trở lên';
            } else {
                $newPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            }
        }
        
        if (empty($errors)) {
            $this->accountModel->updateAccountById(
                $userId, 
                $fullname, 
                $newPassword, 
                $email, 
                $age, 
                $phone
            );
            $success = 'Cập nhật thông tin thành công!';
            $account = $this->accountModel->getAccountById($userId);
        }
    }
    include 'app/views/account/info.php';
}

// Quên mật khẩu - Hiển thị lựa chọn phương thức
public function forgotPasswordMethod() {
    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (SessionHelper::isLoggedIn()) {
        header('Location: /project1');
        exit;
    }
    
    include 'app/views/account/forgot_password_method.php';
}

// Quên mật khẩu bằng câu hỏi bảo mật
public function forgotPasswordBySecurityQuestion() {
    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (SessionHelper::isLoggedIn()) {
        header('Location: /project1');
        exit;
    }
    
    $error = '';
    $securityQuestions = $this->accountModel->getSecurityQuestions();
    $showSecurityQuestion = false;
    $username = '';
    $question = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['username_or_email']) && !isset($_POST['security_answer'])) {
            // Bước 1: Nhập username/email và hiển thị câu hỏi bảo mật
            $username_or_email = $_POST['username_or_email'] ?? '';
            
            // Kiểm tra xem đầu vào là username hay email
            $account = null;
            if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
                // Nếu là email
                $account = $this->accountModel->getAccountByEmail($username_or_email);
            } else {
                // Nếu là username
                $account = $this->accountModel->getAccountByUsername($username_or_email);
            }
            
            if ($account) {
                if (!empty($account->security_question)) {
                    $showSecurityQuestion = true;
                    $question = $account->security_question;
                    $_SESSION['reset_username'] = $account->username;
                } else {
                    $error = 'Tài khoản này chưa thiết lập câu hỏi bảo mật!';
                }
            } else {
                $error = 'Tên đăng nhập hoặc email không đúng!';
            }
        } elseif (isset($_POST['security_answer'])) {
            // Bước 2: Kiểm tra câu trả lời bảo mật
            $username = $_SESSION['reset_username'] ?? '';
            $securityAnswer = $_POST['security_answer'] ?? '';
            
            $account = $this->accountModel->getAccountByUsername($username);
            if ($account && strtolower($account->security_answer) === strtolower($securityAnswer)) {
                $_SESSION['reset_user_id'] = $account->id;
                $_SESSION['reset_verified'] = true;
                header('Location: /project1/account/resetpassword');
                exit;
            } else {
                $error = 'Câu trả lời bảo mật không đúng!';
                $showSecurityQuestion = true;
                $question = $account->security_question;
            }
        }
    }
    
    include 'app/views/account/forgot_password_security.php';
}

// Quên mật khẩu - Bước 1: Nhập username
public function forgotPassword() {
    $error = '';
    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (SessionHelper::isLoggedIn()) {
        header('Location: /project1');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username_or_email = $_POST['username_or_email'] ?? '';
        
        // Kiểm tra xem đầu vào là username hay email
        $account = null;
        if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
            // Nếu là email
            $account = $this->accountModel->getAccountByEmail($username_or_email);
        } else {
            // Nếu là username
            $account = $this->accountModel->getAccountByUsername($username_or_email);
        }
        
        if ($account) {
            $_SESSION['reset_user_id'] = $account->id;
            header('Location: /project1/account/verifyproducts');
            exit;
        } else {
            $error = 'Tên đăng nhập hoặc email không đúng!';
        }
    }
    include 'app/views/account/forgot_password.php';
}

// Quên mật khẩu - Bước 2: Chọn 3 sản phẩm đã mua
public function verifyProducts() {
    $error = '';
    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (SessionHelper::isLoggedIn()) {
        header('Location: /project1');
        exit;
    }
    
    if (!isset($_SESSION['reset_user_id'])) {
        header('Location: /project1/account/login');
        exit;
    }
    $userId = $_SESSION['reset_user_id'];
    require_once('app/models/OrderModel.php');
    $orderModel = new OrderModel($this->db);
    $productsBought = $orderModel->getPurchasedProductsByUserId($userId);
    // Nếu chưa mua sản phẩm nào, cho qua luôn bước xác thực
    if (empty($productsBought)) {
        $_SESSION['reset_verified'] = true;
        header('Location: /project1/account/resetpassword');
        exit;
    }
    
    // Nếu chưa có sản phẩm xác thực được chọn, random một sản phẩm và lưu vào session
    if (!isset($_SESSION['reset_product_id'])) {
        $productBought = $productsBought[array_rand($productsBought)];
        $_SESSION['reset_product_id'] = $productBought->id;
        $_SESSION['reset_product_name'] = $productBought->name;
    } else {
        // Lấy lại thông tin sản phẩm đã chọn trước đó từ session
        foreach ($productsBought as $p) {
            if ($p->id == $_SESSION['reset_product_id']) {
                $productBought = $p;
                break;
            }
        }
    }
    
    // Lấy 2 sản phẩm khác không phải của user (random, loại trừ tất cả sản phẩm đã mua)
    $purchasedIds = array_map(function($p) { return $p->id; }, $productsBought);
    if (count($purchasedIds) > 0) {
        $placeholders = implode(',', array_fill(0, count($purchasedIds), '?'));
        $query = "SELECT id, name, image FROM product WHERE id NOT IN ($placeholders) ORDER BY RAND() LIMIT 2";
        $stmt = $this->db->prepare($query);
        foreach ($purchasedIds as $k => $id) {
            $stmt->bindValue($k + 1, $id, PDO::PARAM_INT);
        }
        $stmt->execute();
        $otherProducts = $stmt->fetchAll(PDO::FETCH_OBJ);
    } else {
        // Nếu user chưa mua sản phẩm nào, lấy 2 sản phẩm bất kỳ
        $query = "SELECT id, name, image FROM product ORDER BY RAND() LIMIT 2";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $otherProducts = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    // Gộp lại và trộn thứ tự
    $products = [];
    if (isset($productBought)) $products[] = $productBought;
    foreach ($otherProducts as $p) $products[] = $p;
    shuffle($products);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selected = $_POST['product'] ?? null;
        // Debug log
        file_put_contents(__DIR__.'/../../debug_forgot.txt', "productBought: ".$_SESSION['reset_product_id']."\nselected: ".$selected."\n", FILE_APPEND);
        
        // So sánh với ID đã lưu trong session, không phải ID random mới
        if ($selected == $_SESSION['reset_product_id']) {
            $_SESSION['reset_verified'] = true;
            // Xóa session product_id vì không cần nữa
            unset($_SESSION['reset_product_id']);
            unset($_SESSION['reset_product_name']);
            header('Location: /project1/account/resetpassword');
            exit;
        } else {
            unset($_SESSION['reset_user_id']);
            unset($_SESSION['reset_product_id']);
            unset($_SESSION['reset_product_name']);
            $error = 'Bạn chọn chưa đúng sản phẩm đã mua!';
            include 'app/views/account/verify_products.php';
            echo "<script>alert('Bạn chọn chưa đúng sản phẩm đã mua!');window.location='/project1/account/login';</script>";
            exit;
        }
    }
    include 'app/views/account/verify_products.php';
}

// Quên mật khẩu - Bước 3: Đặt lại mật khẩu mới
public function resetPassword() {
    $error = '';
    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (SessionHelper::isLoggedIn()) {
        header('Location: /project1');
        exit;
    }
    
    if (!isset($_SESSION['reset_user_id']) || !isset($_SESSION['reset_verified'])) {
        header('Location: /project1/account/login');
        exit;
    }
    $userId = $_SESSION['reset_user_id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';
        if (empty($password) || strlen($password) < 4) {
            $error = 'Mật khẩu phải từ 4 ký tự trở lên';
        } elseif ($password !== $confirm) {
            $error = 'Mật khẩu xác nhận không khớp';
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $this->accountModel->updateAccountById($userId, null, $hashed);
            unset($_SESSION['reset_user_id']);
            unset($_SESSION['reset_verified']);
            header('Location: /project1/account/login');
            exit;
        }
    }
    include 'app/views/account/reset_password.php';
}

public function googleLogin() {
    require_once 'app/config/google_config.php';
    
    $params = [
        'client_id' => GOOGLE_CLIENT_ID,
        'redirect_uri' => GOOGLE_REDIRECT_URI,
        'response_type' => 'code',
        'scope' => 'email profile',
        'access_type' => 'online',
        'prompt' => 'select_account'
    ];
    
    $authUrl = GOOGLE_AUTH_URL . '?' . http_build_query($params);
    header('Location: ' . $authUrl);
    exit;
}

public function googleCallback() {
    require_once 'app/config/google_config.php';
    
    if (isset($_GET['code'])) {
        // Exchange authorization code for access token
        $tokenParams = [
            'code' => $_GET['code'],
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'grant_type' => 'authorization_code'
        ];
        
        $ch = curl_init(GOOGLE_TOKEN_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenParams));
        curl_setopt($ch, CURLOPT_POST, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $tokenData = json_decode($response, true);
        
        if (isset($tokenData['access_token'])) {
            // Get user info using access token
            $ch = curl_init(GOOGLE_USERINFO_URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $tokenData['access_token']
            ]);
            
            $userInfo = json_decode(curl_exec($ch), true);
            curl_close($ch);
            
            if (isset($userInfo['sub'])) {
                // Save or update user account
                $account = $this->accountModel->saveGoogleAccount(
                    $userInfo['sub'],
                    $userInfo['email'],
                    $userInfo['name']
                );
                
                if ($account) {
                    // Set session variables
                    $_SESSION['user_id'] = $account->id;
                    $_SESSION['username'] = $account->username;
                    $_SESSION['role'] = $account->role;
                    
                    header('Location: /project1');
                    exit;
                }
            }
        }
    }
    
    // If something went wrong, redirect to login page
    header('Location: /project1/account/login');
    exit;
}

// API Login - Trả về JWT token
public function apiLogin() {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    
    $account = $this->accountModel->getAccountByUsername($username);
    
    if ($account && password_verify($password, $account->password)) {
        $token = $this->jwtHandler->encode([
            'id' => $account->id, 
            'username' => $account->username,
            'role' => $account->role
        ]);
        
        echo json_encode(['token' => $token]);
    } else {
        http_response_code(401);
        echo json_encode(['message' => __('Invalid credentials')]);
    }
}
}