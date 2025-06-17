<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class AppLogger
{
    private static $logger = null;

    public static function getLogger(string $channel = 'app'): Logger
    {
        if (!self::$logger) {
            $logDir = __DIR__ . '/../../logs';
            if (!file_exists($logDir)) {
                mkdir($logDir, 0777, true);
            }

            self::$logger = new Logger($channel);
            self::$logger->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));

            self::$logger->pushHandler(new StreamHandler($logDir . '/Logsytem.txt', Logger::DEBUG));
        }

        return self::$logger;
    }
}
