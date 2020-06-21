<?php

use Library\HttpKernel;

class IndexController extends HttpKernel
{
    public function indexAction()
    {
        \Common\Functions::test();
        $id = $this->get('id', 2);
        $this->renderTpl('index', ['id' => $id]);
    }
}