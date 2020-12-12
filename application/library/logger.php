<?php

namespace Library;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\RotatingFileHandler;
use Yaf\Registry;
use Monolog\Logger as MonoLogger;

class Logger
{
    private static $chan = '';
    protected static $instance = null;

    /**
     * @param string $chan logger channel name
     * @return MonoLogger
     */
    public static function getInstance($chan)
    {
        $class = get_called_class();
        if (!isset(self::$instance[$class]) || empty(self::$instance[$class])) {
            self::$instance[$class] = new static($chan);
        }

        return self::$instance[$class];
    }

    private function __construct($chan)
    {
        static::$chan = $chan;
    }

    private static $LogLevel = [
        'DEBUG' => MonoLogger::DEBUG,
        'INFO' => MonoLogger::INFO,
        'NOTICE' => MonoLogger::NOTICE,
        'WARNING' => MonoLogger::WARNING,
        'ERROR' => MonoLogger::ERROR,
        'CRITICAL' => MonoLogger::CRITICAL,
        'ALERT' => MonoLogger::ALERT,
        'EMERGENCY' => MonoLogger::EMERGENCY,
    ];

    private static $LoggerObj = [];

    public static function InitLogger()
    {
        $logger = Registry::get('logger')->toArray();
        foreach ($logger as $key => $cfg) {
            $log_file = $cfg['log_path'].'/'.$cfg['log_name'].'.log';
            $level = self::$LogLevel[strtoupper($cfg['log_level'])];
            $handler = new RotatingFileHandler($log_file, 0, $level);
            $handler->setFormatter(new JsonFormatter());
            $log = new MonoLogger($cfg['log_name']);
            $log->pushHandler($handler);
            $log->pushHandler(new FirePHPHandler());

            self::$LoggerObj[$cfg['log_name']] = $log;
        }
    }

    public function __call($name, $arguments)
    {
        call_user_func_array([self::$LoggerObj[self::$chan], $name], $arguments);
    }
}
