<?php

namespace Library;

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

    public function genSessionId()
    {
        return sha1(static::$salt.microtime(true).uniqid());
    }

    abstract public function setSalt();
    abstract public function getSalt();
}
