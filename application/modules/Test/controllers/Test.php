<?php

use Core\BaseCtrl;

class TestController extends BaseCtrl
{
    public function fooAction()
    {
        $id = $this->getRequest()->get('id');
        $mod_test = new TestModel();
        $one_row = $mod_test->getOneRowById($id);
        $this->returnJson(['foo' => 'foo_1', 'user' => $one_row]);
    }

    public function fAction()
    {
        $this->renderTpl('f', ['foo' => 'foo123']);
    }
}