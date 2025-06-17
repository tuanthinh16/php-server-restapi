<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if (!isset($_ENV['DB_HOST'])) {
    echo "Lỗi: Không đọc được file .env\n";
    echo "Thư mục hiện tại: " . __DIR__ . "\n";
    echo "Nội dung .env:\n" . file_get_contents(__DIR__ . '/../.env');
}
require_once __DIR__ . '/../src/routes/api.php';
Flight::start();