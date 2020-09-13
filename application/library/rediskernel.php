<?php

namespace Library;

use Rean\MyRedis;
use Yaf\Registry;

class RedisKernel extends MyRedis
{
    public function getRedisConf()
    {
        $conf = Registry::get('redis');
        $arr_conf = $conf->redis->toArray();
        $format_conf = [];
        foreach ($arr_conf as $key => $value) {
            if (is_array($value)) {
                $format_conf[$key]['host'] = $value['host'];
                $format_conf[$key]['port'] = $value['port'];
                $format_conf[$key]['auth'] = $value['auth'];
                $format_conf[$key]['timeout'] = $value['timeout'];
            } else {
                $format_conf[$key] = $value;
            }
        }

        return $format_conf;
    }

    /**
     * @param string $cluster cluster's name
     * @throws \Exception when cluster is false
     * @return \Redis
     */
    public static function getClient($channel)
    {
        return self::getInstance($channel);
    }
}