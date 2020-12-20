<?php

namespace Library;

use Illuminate\Database\Capsule\Manager as IlluminateCapsule;
use Illuminate\Database\Eloquent\Model as IlluminateEloquent;
use Yaf\Registry;

class DatabaseKernel extends IlluminateEloquent
{
    protected static $capsule = null;

    protected static $instance = null;

    protected static $connections = [];

    protected $connection = 'default';

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

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $config = Registry::get('database');
        if (!$config->database) {
            throw new \Exception("Must configure database in .ini first");
        }
        $config = $config->database->toArray();
        if (self::$capsule === null) {
            self::$capsule = new IlluminateCapsule();
//            self::$capsule->addConnection($config, "d1");
//            self::$capsule->addConnection($config, "d2");
            self::$capsule->addConnection($config, $this->connection);
            self::$connections = [
                $this->connection
            ];
            self::$capsule->setAsGlobal();
            self::$capsule->bootEloquent();
        }
    }

    protected function getModel()
    {
        return $this->getConnection()->table($this->table);
    }

    public function getConnection($name = null)
    {
        if ($name == null) {
            $name = $this->connection;
        }
        return self::$capsule->getConnection($name);
    }

    // 开启事务
    public function transaction()
    {
        foreach (self::$connections as $v) {
            $this->getConnection($v)->beginTransaction();
        }
    }

    // 回滚事务
    public function rollBack($toLevel = null)
    {
        foreach (self::$connections as $v) {
            $this->getConnection($v)->rollBack($toLevel);
        }
    }

    // 事务提交
    public function commit()
    {
        foreach (self::$connections as $v) {
            $this->getConnection($v)->commit();
        }
    }
}