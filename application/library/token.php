<?php

namespace Library;

/**
 * 基于Token机制实现用户登录
 */
class Token extends SessionKernel
{
    protected static $salt = null;

    public function getToken()
    {

    }

    public function setToken()
    {

    }

    public function setSalt()
    {
        self::$salt = 'token';
    }

    public function getSalt()
    {
        return self::$salt;
    }

}
