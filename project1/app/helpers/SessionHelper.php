<?php
class SessionHelper {
    public static function isLoggedIn() {
        return isset($_SESSION['username']) && !empty($_SESSION['username']);
    }
public static function isAdmin() {
return isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
}