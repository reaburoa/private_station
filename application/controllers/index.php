<?php

use Library\HttpKernel;

class IndexController extends HttpKernel
{
    public function indexAction()
    {
        $mod = new TestModel();
        var_dump($mod->getOneRowById(2));
        $id = $this->get('id', 2);
        $this->renderTpl('index', ['id' => $id]);
    }

    public function dAction()
    {
        echo "d Action\n";
    }
}