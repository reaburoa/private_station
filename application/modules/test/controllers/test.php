<?php

use Library\HttpKernel;
use Foo\FooModel;
use Library\Session;

class TestController extends HttpKernel
{
    public function fooAction()
    {
        $id = $this->get('id');
        var_dump($id);
        $mod_test = new FooModel();
        $one_row = $mod_test->getOneRowById($id);
        $this->returnJson(['foo' => 'foo_1', 'user' => $one_row]);
    }

    public function fAction()
    {
        $session = Session::getInstance();
        $session_id = $session->initSessionId();
        $session->initSession();
        $session->setSession(['user_id' => 123, 'username' => "foo123"]);
        var_dump($session_id);

        $this->renderTpl('f', ['foo' => 'foo123']);
    }

    public function fdAction()
    {
        $session = Session::getInstance();
        $user_id = $session->getSession("user_id");
        var_dump($user_id);
    }
}