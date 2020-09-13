<?php

namespace Common;
use Yaf\Application;

class Functions
{
    public static function test()
    {
        echo Application::app()->getConfig()->get('application')->directory;
        echo "##";
        echo "Common test function";
    }
}
