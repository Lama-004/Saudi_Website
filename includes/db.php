<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'saudi_website');

function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset("utf8mb4");
    
    if ($conn->connect_error) {
        die("خطأ في الاتصال بقاعدة البيانات: " . $conn->connect_error);
    }
    return $conn;
}

$conn = getConnection();

?>
