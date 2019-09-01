<?php


namespace App\Services\Redis;

class Redis
{
    /** @var Redis */
    private static $redis;

    public static function Cache()
    {
        if (!self::$redis)
        {
            self::$redis = new \Redis();
            self::$redis->pconnect(
                explode(',', $_ENV['REDIS_SERVER'])[0],
                explode(',', $_ENV['REDIS_SERVER'])[1]
            );
        }

        return self::$redis;
    }
}