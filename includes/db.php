<?php
define('DB_HOST', 'mysql.railway.internal');
define('DB_USER', 'root');
define('DB_PASS', 'pXVPCEJAgSSOpvssNYCdHRafOownmhdi');
define('DB_NAME', 'railway');
define('DB_PORT', 3306);

function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    $conn->set_charset("utf8mb4");
    if ($conn->connect_error) {
        die("خطأ في الاتصال بقاعدة البيانات: " . $conn->connect_error);
    }
    return $conn;
}

$conn = getConnection();
?>
