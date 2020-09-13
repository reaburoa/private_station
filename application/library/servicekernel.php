<?php

namespace Library;

class ServiceKernel
{
    protected static $instance = null;

    private function __construct()
    {
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(self::$instance[$class]) || empty(self::$instance[$class])) {
            self::$instance[$class] = new static();
        }

        return self::$instance[$class];
    }
}
