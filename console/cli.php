<?php
date_default_timezone_set('Asia/Shanghai');
define("APP_PATH",  realpath(dirname(__FILE__) . '/../'));
require APP_PATH.'/vendor/autoload.php';
ini_set('display_errors', 1);

$app = new Yaf\Application(APP_PATH . '/conf/app.ini');
$app->bootstrap()->getDispatcher()->dispatch(new Yaf\Request\Simple());