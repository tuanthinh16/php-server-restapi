<?php

require_once __DIR__ . '/../../vendor/autoload.php'; // nếu chưa autoload ở index.php

use Predis\Client;

class RedisHelper
{
    private static $client = null;

    public static function getInstance()
    {
        if (!self::$client) {
            self::$client = new Client([
                'scheme' => 'tcp',
                'host' => getenv('REDIS_HOST') ?: '127.0.0.1',
                'port' => getenv('REDIS_PORT') ?: 6379,
            ]);
        }

        return self::$client;
    }

    public static function set($key, $value, $ttl = 300)
    {
        return self::getInstance()->setex($key, $ttl, json_encode($value));
    }

    public static function get($key)
    {
        $value = self::getInstance()->get($key);
        return $value ? json_decode($value, true) : null;
    }

    public static function delete($key)
    {
        return self::getInstance()->del([$key]);
    }
}
