<?php
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
