<?php

namespace Library;

/**
 * 基于Session进行用户登录验证
 */
class Session implements SessionInterface
{
    private static $salt = '';

    /**
     * Session初始化
     */
    public function init()
    {
        $session_id = $this->genSessionId();
        session_id($session_id);
        return $session_id;
    }

    /**
     * 开启一个会话
     */
    public function startSession()
    {
        session_start();
    }

    /**
     * 销毁当前会话的Session信息
     */
    public function destroySession()
    {
        session_destroy();
    }

    /**
     * 获取随机sessionId
     * @return string
     */
    public function genSessionId()
    {
        return sha1(self::$salt.microtime(true).uniqid());
    }

    /**
     * 设置Session信息
     *
     * @param mixed $key 如果$key是数组，则数组全部设置到Session中
     * @param string $value Session变量值，此时$key为字符串
     * @return boolean
     */
    public function setSession($key, $value = null)
    {
        if ($value && !is_string($key)) {
            return true;
        }

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $_SESSION[$k] = $v;
            }
        } else {
            $_SESSION[$key] = $value;
        }

        return true;
    }

    /**
     * 获取Session中信息
     *
     * @param string $key 如果传入$key则获取其值，否则获取全部session信息
     * @return mixed
     */
    public function getSession($key = null)
    {
        $this->startSession();
        return $key ? $_SESSION[$key] : $_SESSION;
    }
}
