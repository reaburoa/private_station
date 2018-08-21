<?php

namespace Library;

use Rean\MyRedis;

class RedisSessionHandler extends \SessionHandler
{
    private static $SessionPrefix = 'sess_';

    private static $RedisSessionChanel = 'session';

    private static $Lifetime = 0;

    /**
     * var @$Handler \Redis
     */
    private static $Handler = null;

    public function __construct()
    {
        self::$Handler = MyRedis::getInstance(self::$RedisSessionChanel);
    }

    public function close()
    {
        return true;
    }

    public function destroy($session_id)
    {
        self::$Handler->del($this->getKey($session_id));
        return true;
    }

    public function gc($maxlifetime)
    {
        return true;
    }

    public function open($save_path, $name)
    {
        return true;
    }

    public function read($session_id)
    {
        $key = $this->getKey($session_id);
        $ret = MyRedis::getInstance(self::$RedisSessionChanel)->get($key);
        return $ret === false ? '' : $ret;
    }

    public function write($session_id, $session_data)
    {
        $key = $this->getKey($session_id);
        return self::$Handler->setex($key, self::$Lifetime, $session_data) === true ? true : false;
    }

    private function getKey($id)
    {
        return self::$SessionPrefix.$id;
    }

    public function setSessionLifeTime($lifetime)
    {
        self::$Lifetime = $lifetime;
    }

    public function __destruct()
    {
        session_write_close();
    }
}
