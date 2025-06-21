<?php
// Thông tin API
$apiUrl = 'http://localhost:8080/project1/api/product/7';

// Lấy token từ tham số URL hoặc sử dụng token mặc định
$token = isset($_GET['token']) ? $_GET['token'] : '';

// Thiết lập header
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
];

// Khởi tạo cURL
$ch = curl_init();

// Thiết lập các tùy chọn cURL
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Thực hiện yêu cầu
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Đóng cURL
curl_close($ch);

// Hiển thị kết quả
header('Content-Type: application/json');
echo json_encode([
    'api_url' => $apiUrl,
    'http_code' => $httpCode,
    'response' => json_decode($response),
    'token' => $token ? substr($token, 0, 10) . '...' : 'No token provided'
]);
?> 