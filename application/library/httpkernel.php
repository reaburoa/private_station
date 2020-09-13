<?php
namespace Library;

use Yaf\Controller_Abstract;
use Yaf\Dispatcher;

/**
 * 所有controller继承基类
 */
abstract class HttpKernel extends Controller_Abstract
{
    /**
     * 控制器开始时间
     */
    private static $ActionStartTime = null;

    /**
     * 视图实例
     * @return object View
     */
    protected static $ViewObject = null;

    /**
     * 控制器初始化，可以将相关初始化功能记录于此
     */
    public function init()
    {
        self::$ActionStartTime = microtime(true);

        // 关闭自动渲染页面
        Dispatcher::getInstance()->disableView();

        $module = $this->getModuleName();
        $view_path = View::$ViewRoot.'/'.strtolower($module);
        self::$ViewObject = new View();
        self::$ViewObject->setScriptPath($view_path);
    }

    /**
     * Api请求时返回数据调用
     *
     * @param mixed $data 接口返回数据
     * @param int $code 错误码，默认0表示正常
     * @param string $msg 错误信息
     */
    public function returnJson($data, $code = 0, $msg = '')
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Take-Time: '.sprintf("%.6f", microtime(true) - self::$ActionStartTime));
        $body = [
            'code' => (int)$code,
            'data' => empty($data) ? null : $data,
            'msg' => (string)$msg
        ];
        $this->getResponse()->setBody(json_encode($body, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * 获取请求参数
     *
     * @param mixed $key 请求字段
     * @param string $default 默认值
     * @return string
     */
    public function get($key, $default = '')
    {
        return $this->getRequest()->getQuery($key, $default);
    }

    /**
     * 获取Post数据
     *
     * @param mixed $key 请求字段
     * @param string $default 默认值
     * @return string
     */
    public function post($key, $default = '')
    {
        return $this->getRequest()->getPost($key, $default);
    }

    /**
     * 获取请求参数，不区分 GET|POST 类型
     *
     * @param mixed $key 请求字段
     * @param string $default 默认值
     * @return string
     */
    public function request($key, $default = '')
    {
        return $this->getRequest()->get($key, $default);
    }

    /**
     * 获取URL路径数据
     *
     * @param mixed $key 请求字段
     * @param string $default 默认值
     * @return string
     */
    public function route($key, $default = '')
    {
        return $this->getRequest()->getParam($key, $default);
    }

    /**
     * 渲染View页面，自动渲染module目录下的视图
     *
     * @param string $tpl 视图文件，如foo, foo.php，foo/foo，foo/foo.php
     * @param array $parameters 页面参数
     */
    public function renderTpl($tpl, array $parameters = [])
    {
        header('Take-Time: '.sprintf("%.6f", microtime(true) - self::$ActionStartTime));
        $content = self::$ViewObject->render($tpl, $parameters);
        $this->getResponse()->setBody($content);
    }

    /**
     * 单独设置页面变量数据
     * @param string $key 变量key
     * @param mixed $value 变量值，可以是字符串、数组等格式
     */
    public function assign($key, $value = null)
    {
        self::$ViewObject->assign($key, $value);
    }
}