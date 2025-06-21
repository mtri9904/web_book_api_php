<?php
session_start();
echo "<pre>";
echo "SESSION DATA:\n";
print_r($_SESSION);
echo "\n\nIS_LOGGED_IN: ";
echo isset($_SESSION['user_id']) ? "YES" : "NO";
echo "\n\nROLE: ";
echo isset($_SESSION['role']) ? $_SESSION['role'] : "NOT SET";
echo "</pre>";

echo "<h2>User Role Check</h2>";
echo "isset(\$_SESSION['role']): " . (isset($_SESSION['role']) ? 'true' : 'false') . "<br>";
echo "\$_SESSION['role']: " . ($_SESSION['role'] ?? 'not set') . "<br>";

echo "<h2>User Info</h2>";
echo "isset(\$_SESSION['username']): " . (isset($_SESSION['username']) ? 'true' : 'false') . "<br>";
echo "\$_SESSION['username']: " . ($_SESSION['username'] ?? 'not set') . "<br>";
echo "isset(\$_SESSION['user_id']): " . (isset($_SESSION['user_id']) ? 'true' : 'false') . "<br>";
echo "\$_SESSION['user_id']: " . ($_SESSION['user_id'] ?? 'not set') . "<br>";

echo "<h2>Admin Check</h2>";
require_once 'app/helpers/SessionHelper.php';
echo "SessionHelper::isAdmin(): " . (SessionHelper::isAdmin() ? 'true' : 'false') . "<br>";
?> 