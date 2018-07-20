<?php
namespace Core;

use Yaf\Controller_Abstract;
use Yaf\Dispatcher;

/**
 * 所有controller继承基类
 */
abstract class BaseCtrl extends Controller_Abstract
{
    private static $ActionStartTime = null;

    /**
     * 控制器初始化，可以将相关初始化功能记录于此
     */
    public function init()
    {
        // 关闭自动渲染页面
        Dispatcher::getInstance()->disableView();
        self::$ActionStartTime = microtime(true);
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
        return $this->getRequest()->get($key, $default);
    }

    /**
     * 获取Post数据
     *
     * @param mixed $key 请求字段
     * @param string $default 默认值
     * @return string
     */
    public function getPost($key, $default = '')
    {
        return $this->getRequest()->getPost($key, $default);
    }

    public function renderTpl($tpl, array $parameters = [])
    {
        $content = $this->getViewObj()->render($tpl, $parameters);
        $this->getResponse()->setBody($content);
    }

    public function getViewObj()
    {
        $modules = $this->getModuleName();
        $view_path = APP_PATH.'/application/views/'.strtolower($modules);
        $obj_view = new View();
        $obj_view->setScriptPath($view_path);

        return $obj_view;
    }
}