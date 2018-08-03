<?php

use Library\HttpKernel;
use Service\TestService;
use Service\Foo\FooService;
use Library\Session;

class FooController extends HttpKernel
{
    public function fooAction()
    {
        $session = new Session();
        $session_id = $session->init();
        $session->startSession();
        $session->setSession('uid', 123);
        $session->setSession('name', 'test');
        $session->setSession(['fo' => 'foo', 'foo' => 'fo']);
        return $this->returnJson(['session_id' => $session_id]);
    }

    public function foo1Action()
    {
        $session = new Session();
        var_dump($session->getSession('fo'));
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