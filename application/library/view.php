<?php

namespace Library;

use Yaf\View_Interface;
use Yaf\Registry;

/**
 * 视图渲染基类
 */
class View implements View_Interface
{
    /**
     * 视图文件夹目录
     */
    private static $ScriptPath = null;

    /**
     * 视图页面参数
     */
    public static $ScriptParams = [];

    /**
     * 视图文件根目录
     */
    public static $ViewRoot = APP_PATH.'/application/views';

    /**
     * 传递变量到模板
     *
     * @param string $name 变量名
     * @param mixed $value 变量值
     * @return boolean
     */
    public function assign($name, $value = null)
    {
        self::$ScriptParams[$name] = $value;
        return true;
    }

    public function display($tpl, $var_array = array())
    {

    }

    public function render($tpl, $var_array = array())
    {
        ob_start();
        extract(array_merge(self::$ScriptParams, $var_array));
        $tpl_ext = $this->getTplExt();
        if (strpos('.'.$tpl_ext, $tpl) === false) {
            $tpl .= '.'.$tpl_ext;
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

    public function getScriptPath($request = null)
    {
        return self::$ScriptPath;
    }

    /**
     * 获取模板文件扩展
     */
    public function getTplExt()
    {
        return Registry::get('app')->application->view->ext;
    }
}