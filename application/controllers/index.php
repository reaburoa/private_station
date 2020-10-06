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
        var_dump($this->route("name"), $this->route("value"));
        $this->returnJson(["d" => 'ddd']);
    }
}