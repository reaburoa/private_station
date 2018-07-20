<?php

namespace Core;

use Yaf\View_Interface;
use Yaf\Registry;

/**
 * 视图渲染基类
 */
class View implements View_Interface
{
    private static $ScriptPath = null;
    public static $ScriptParams = [];

    public function assign($name, $value = null)
    {
        self::$ScriptParams[$name] = $value;
    }

    public function display($tpl, $var_array = array())
    {

    }

    public function render($tpl, $var_array = array())
    {
        ob_start();
        extract(array_merge(self::$ScriptParams, $var_array));
        if (strpos('.php', $tpl) === false) {
            $tpl .= '.'.Registry::get('app')->application->view->ext;
        }
        require $this->getScriptPath().'/'.$tpl;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public function setScriptPath($tpl_dir)
    {
        self::$ScriptPath = $tpl_dir;
    }

    public function getScriptPath()
    {
        return self::$ScriptPath;
    }
}