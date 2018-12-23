<?php

namespace Common;
use Yaf\Application;

class Functions
{
    private static $package = null;

    public static function T($code, $lang = 'zh_cn')
    {
        $library = Application::app()->getConfig()->get("application")->library;
        if (empty(self::$package)) {
            self::$package = require_once($library . "/language/{$lang}.php");
        }
        return empty(self::$package[$code]) ? $code : self::$package[$code];
    }
}
