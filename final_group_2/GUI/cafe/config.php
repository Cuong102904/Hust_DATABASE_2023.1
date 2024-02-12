<?php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'khanh');
define('DB_USER', 'postgres');
define('DB_PASSWORD', 'khanh');

try {
    $pdo = new PDO("pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully to the PostgreSQL database.";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
 