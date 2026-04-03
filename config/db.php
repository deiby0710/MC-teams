<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// $hostDB = "localhost";
$hostDB = "db";
$nameDB = "app_db";
$userDB = "user";
$pwDB   = "password";

try {
    $pdo = new PDO(
        "mysql:host=$hostDB;dbname=$nameDB;charset=utf8mb4",
        $userDB, $pwDB,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode(['error' => '❌ Error DB: ' . $e->getMessage()]));
}