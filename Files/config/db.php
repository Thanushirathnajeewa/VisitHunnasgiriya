<?php

$host = '127.0.0.1';
$port = 3306;
$db   = 'visithunnasgiriya';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(503);
    exit(
        'Database connection failed. Check Backend/config/db.php (host, port, database name). '
        . 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
    );
}
