<?php

namespace Library;

use Yaf\Application;

class Language
{
    const ZH_CN = 'zh_cn';
    const EN_US = 'en_us';

    private static $package = null;

    public static function T($code, $lang = self::ZH_CN)
    {
        $application = Application::app()->getConfig()->get("application")->library;
        if (!isset(self::$package[$lang]) || empty(self::$package)) {
            self::$package[$lang] = require_once($application . "/language/{$lang}.php");
        }
        return !isset(self::$package[$lang][$code]) || empty(self::$package[$lang][$code]) ? $code : self::$package[$lang][$code];
    }
}