<?php

namespace Services;

use Redis;
use Exceptions\MRedisExeption;

class MRedis
{
    private Redis $redis;
    private static $instance;

    private function __construct()
    {
        $dbOptions = (require SRC_DIR . '/settings.php')['redis'];
        try {
            $this->redis = new Redis();
            $this->redis->connect($dbOptions['host'], $dbOptions['port']);
        } catch (MRedisExeption $e) {
            throw new MRedisExeption('Ошибка при подключении к Redis: ' . $e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getRedis(): Redis
    {
        return $this->redis;
    }
}

