<?php

require_once __DIR__ . '/../config/RedisHelper.php';

class RateLimiter
{
    public static function limit(string $key, int $maxAttempts = 5, int $ttl = 60): bool
    {
        $redis = RedisHelper::getInstance();

        $current = $redis->incr($key);

        if ($current == 1) {
            // Đặt thời gian sống nếu lần đầu
            $redis->expire($key, $ttl);
        }

        return $current <= $maxAttempts;
    }

    public static function remaining(string $key, int $maxAttempts = 5): int
    {
        $redis = RedisHelper::getInstance();
        $used = (int) $redis->get($key);
        return max(0, $maxAttempts - $used);
    }
}
