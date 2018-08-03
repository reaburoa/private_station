<?php

namespace Library;

/**
 * 基于Token机制实现用户登录
 */
class Token extends SessionKernel
{
    protected static $salt = '';

    public function genUniqueId()
    {
        return sha1(self::$salt.microtime(true).uniqid());
    }

    public function getToken()
    {

    }

    public function setToken()
    {

    }

    public function setSalt()
    {

    }

    public function getSalt()
    {

    }

}
