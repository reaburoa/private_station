<?php

use Yaf\Config\Ini;
use Yaf\Registry;
use Yaf\Bootstrap_Abstract;
use Yaf\Dispatcher;
use Yaf\Application;

class Bootstrap extends Bootstrap_Abstract
{
    public function _init(Dispatcher $dispatcher)
    {
        mb_internal_encoding('UTF-8');
        ini_set('default_socket_timeout', -1);
        $env = Application::app()->environ();

        $app = new Ini(APP_PATH.'/conf/app.ini', $env);
        Registry::set('app', $app);

        $session = new Ini(APP_PATH.'/conf/session.ini', $env);
        Registry::set('session', $session);

        $database = new Ini(APP_PATH.'/conf/database.ini', $env);
        Registry::set('database', $database);

        $redis = new Ini(APP_PATH.'/conf/redis.ini', $env);
        Registry::set('redis', $redis);
    }
}