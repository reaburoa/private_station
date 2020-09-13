<?php

use Library\HttpKernel;
use Service\TestService;
use Service\Foo\FooService;
use Library\Session;

class FooController extends HttpKernel
{
    public function fooAction()
    {
        $session = Session::getInstance();
        $session_id = $session->initSessionId();
        $session->initSession();
        $session->setSession(['fo' => 'foo', 'foo' => 'fo']);
        return $this->returnJson(['session_id' => $session_id]);
    }

    public function foo1Action()
    {
        $session = Session::getInstance();
        return $this->returnJson([
            'fo' => $session->getSession('fo')
        ]);
    }

    public function logOutAction()
    {
        $se = Session::getInstance();
        $se->initSession();
        $se->destroySession();
    }

    public function foo2Action()
    {
        echo FooService::getInstance()->fs();
        echo '<br />';
        echo TestService::getInstance()->foo();
    }

}