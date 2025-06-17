<?php

require_once __DIR__ . '/../config/Logger.php';

trait LoggerTrait
{
    public function log(string $channel = 'app')
    {
        return AppLogger::getLogger($channel);
    }
}
