<?php
namespace Core;

use Yaf\Controller_Abstract;
use Yaf\Dispatcher;

class BaseCon extends Controller_Abstract
{
    private static $ActionStartTime = null;

    public function init()
    {
        self::$ActionStartTime = microtime(true);
    }

    public function returnJson($data, $code = 0, $msg = '')
    {
        Dispatcher::getInstance()->disableView();
        header('Content-Type: application/json; charset=utf-8');
        header('Take-Time: '.sprintf("%.6f", microtime(true) - self::$ActionStartTime));
        $body = [
            'code' => (int)$code,
            'data' => empty($data) ? null : $data,
            'msg' => (string)$msg
        ];
        $this->getResponse()->setBody(json_encode($body, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE));
    }
}