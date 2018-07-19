<?php

use Yaf\Config\Ini;
use Yaf\Registry;
use Yaf\Bootstrap_Abstract;
use Yaf\Dispatcher;

class Bootstrap extends Bootstrap_Abstract
{
    public function _init(Dispatcher $dispatcher)
    {
        mb_internal_encoding('UTF-8');
        ini_set('default_socket_timeout', -1);

        $conf = new Ini(APP_PATH . '/conf/app.ini', 'dev');
        Registry::set('app', $conf);
    }
}