<?php

namespace Library;

/**
 * 基于Token机制实现用户登录
 */
class Token implements SessionInterface
{
    private static $salt = '';

    public function genToken()
    {
        return sha1(self::$salt.microtime(true).uniqid());
    }
}
