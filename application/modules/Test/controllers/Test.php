<?php

use Core\BaseCon;

class TestController extends BaseCon
{
    public function fooAction()
    {
       // $name = $this->getRequest()->get('n');
        $id = $this->getRequest()->get('id');
        $mod_test = new TestModel();
        $one_row = $mod_test->getOneRowById($id);
        $this->returnJson(['foo' => 'foo_1', 'user' => $one_row]);
    }
}