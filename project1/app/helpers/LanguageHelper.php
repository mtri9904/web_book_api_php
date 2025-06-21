<?php
// Xử lý chuyển đổi ngôn ngữ
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] === 'en' ? 'en' : 'vi';
    $url = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: $url");
    exit;
}
if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'vi';

// Định nghĩa hàm dịch toàn cục
if (!function_exists('__')) {
    function __($text) {
        $lang = $_SESSION['lang'] ?? 'vi';
        $dict = [];
        if ($lang === 'en') {
            $dict = include __DIR__ . '/../lang/lang_en.php';
        } else {
            $dict = include __DIR__ . '/../lang/lang_vi.php';
        }
        return $dict[$text] ?? $text;
    }
} 