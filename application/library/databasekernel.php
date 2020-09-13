<?php

namespace Library;

use Illuminate\Database\Capsule\Manager as IlluminateCapsule;
use Illuminate\Database\Eloquent\Model as IlluminateEloquent;
use Yaf\Registry;

class DatabaseKernel extends IlluminateEloquent
{
    protected static $instance = null;

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

    protected $capsule = null;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $config = Registry::get('database');
        if (!$config->database) {
            throw new \Exception("Must configure database in .ini first");
        }
        $config = $config->database->toArray();
        $this->capsule = new IlluminateCapsule();
        $this->capsule->addConnection($config, "default");
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }

    protected function getModel()
    {
        return $this->capsule::table($this->table);
    }
}