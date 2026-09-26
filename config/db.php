<?php
/**
 * VisitHunnasgiriya database connection.
 * Uses the existing database imported through phpMyAdmin.
 */

if (!defined('BASE_URL')) {
    define('BASE_URL', '/VisitHunnasgiriya/');
}

$host = 'localhost';
$db   = 'visithunnasgiriya';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed. Please make sure the "visithunnasgiriya" database is imported in phpMyAdmin and MySQL is running.');
}
?>
