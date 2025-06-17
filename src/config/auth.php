<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{
    public static function generateToken($userId)
    {
        $secretKey = getenv('JWT_SECRET');
        $issuedAt = time();
        $expire = $issuedAt + getenv('JWT_EXPIRE');

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'sub' => $userId
        ];

        return JWT::encode($payload, $secretKey, 'HS256');
    }

    public static function validateToken($token)
    {
        try {
            $secretKey = getenv('JWT_SECRET');
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
            return $decoded->sub;
        } catch (Exception $e) {
            return false;
        }
    }
}