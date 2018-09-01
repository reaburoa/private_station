<?php

define("APP_PATH",  realpath(dirname(__FILE__) . '/../'));

require APP_PATH.'/vendor/autoload.php';

$app = new Yaf\Application(APP_PATH . '/conf/app.ini');
$app->bootstrap()->getDispatcher()->dispatch(new Yaf\Request\Simple());
