<?php

namespace Library;

/**
 * 基于Session进行用户登录验证
 */
class Session extends SessionKernel
{
    protected static $salt = null;

    /**
     * Session初始化
     */
    public function initSessionId()
    {
        $session_id = $this->genSessionId();
        session_id($session_id);
        return $session_id;
    }

    /**
     * 开启一个会话
     */
    public function initSession()
    {
        $session_conf = $this->getConf();
        $handler = new RedisSessionHandler();
        $handler->setSessionLifeTime($session_conf['cookie_lifetime']);
        session_set_save_handler($handler, true);
        session_start([
            'name' => $session_conf['name'],
            'cookie_path' => $session_conf['cookie_path'],
            'cookie_domain' => $session_conf['cookie_domain'],
            'cookie_secure' => isset($_SERVER['HTTPS']) ? true : false,
            'cookie_httponly' => $session_conf['cookie_http_only'],
            'cookie_lifetime' => $session_conf['cookie_lifetime'],
        ]);
    }

    /**
     * 销毁当前会话的Session信息
     */
    public function destroySession()
    {
        session_destroy();
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
        $this->initSession();
        return $key ? $_SESSION[$key] : $_SESSION;
    }

    public function setSalt()
    {
        self::$salt = 'session';
    }

    public function getSalt()
    {
        return self::$salt;
    }
}
