<?php

use Library\HttpKernel;
use Library\Logger;

class IndexController extends HttpKernel
{
    public function indexAction()
    {
        //Logger::InitLogger();
        //Logger::getInstance('app')->warning('12322434');
        Logger::getInstance('sys')->warning('12322434', ['a'=>'n', 'foo'=>'foo123']);

        /*$mod = new TestModel();
        var_dump($mod->getOneRowById(2));
        $id = $this->get('id', 2);*/
        $this->renderTpl('index', ['id' => 12]);
    }

    public function dAction()
    {
        \Service\TestService::getInstance()->foo();
        $this->returnJson(["d" => 'ddd']);
    }
}