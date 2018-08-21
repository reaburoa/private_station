<?php

use Library\HttpKernel;
use Service\TestService;
use Service\Foo\FooService;
use Library\Session;
use Rean\MyRedis;

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
        var_dump($session->getSession('fo'));
    }

    public function logOutAction()
    {
        $se = Session::getInstance();
        $se->initSession();
        $se->destroySession();
    }

    public function foo2Action()
    {
        $srv_test = new TestService();
        $srv_f = new FooService();
        echo $srv_f->fs();
        echo '<br />';
        echo $srv_test->foo();
    }

    public function foo3Action()
    {
        $r = MyRedis::getInstance('cache');
        $r->set('foo', 123);
        var_dump($r->get('foo'));
    }
}