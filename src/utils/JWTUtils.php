<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTUtils
{
    public static function decode($token)
    {
        $key = getenv('JWT_SECRET');
        return JWT::decode($token, new Key($key, 'HS256'));
    }

    public static function encode($payload)
    {
        $key = getenv('JWT_SECRET');
        return JWT::encode($payload, $key, 'HS256');
    }
}
