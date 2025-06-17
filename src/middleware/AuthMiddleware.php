<?php

require_once __DIR__ . '/../utils/JWTUtils.php';  // chứa hàm decode token

class AuthMiddleware
{
    public static function handle()
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? null;

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            Flight::halt(401, json_encode(['error' => 'Authorization token missing']));
        }

        $token = trim(str_replace('Bearer', '', $authHeader));

        try {
            $payload = JWTUtils::decode($token);
            Flight::set('user', $payload); // lưu user để dùng trong controller
        } catch (Exception $e) {
            Flight::halt(401, json_encode(['error' => 'Invalid or expired token']));
        }
    }
}
