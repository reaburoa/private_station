<?php

namespace Library;

use Yaf\Registry;

/**
 * 用户会话
 */
abstract class SessionKernel
{
    protected static $instance = null;
    protected static $salt = null;

    /**
     * @return static
     */
    public static function getInstance()
    {
        $class_name = get_called_class();
        if (!isset(static::$instance[$class_name])) {
            static::$instance[$class_name] = new static();
        }

        return static::$instance[$class_name];
    }

    protected function __construct()
    {
        static::setSalt();
    }

    /**
     * 获取随机sessionId
     * @return string
     */
    public function genSessionId()
    {
        return sha1(static::$salt.microtime(true).uniqid());
    }

    /**
     * 获取会话配置信息
     */
    public function getConf()
    {
        return Registry::get('session')->session;
    }

    /**
     * 设置会话加密盐
     */
    abstract public function setSalt();

    /**
     * 获取会话加密盐
     */
    abstract public function getSalt();
}
