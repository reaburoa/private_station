<?php

use Library\HttpKernel;
use Service\TestService;
use Service\Foo\FooService;

class FooController extends HttpKernel
{
    public function fooAction()
    {
        $session = new \Core\Session();
        session_name("ses_id");
        $session_id = $session->init();
        $session->setSessionExpire(30);
        $session->startSession();
        $session->setSession('uid', 123);
        $session->setSession('name', 'test');
        $session->setSession(['fo' => 'foo', 'foo' => 'fo']);
        return $this->returnJson(['session_id' => $session_id]);
    }

    public function foo1Action()
    {
        $session = new \Core\Session();
        var_dump(session_name());

        $session->setSessionExpire(30);
        var_dump($session->getSession('fo'));
/*
        var_dump(session_get_cookie_params());
        var_dump(session_id(), $_COOKIE['PHPSESSID'], session_cache_expire());*/
    }

    public function foo2Action()
    {
        $srv_test = new TestService();
        $srv_f = new FooService();
        echo $srv_f->fs();
        echo '<br />';
        echo $srv_test->foo();
    }
}