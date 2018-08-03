<?php

use Library\HttpKernel;
use Yaf\Registry;

class IndexController extends HttpKernel
{
    public function indexAction()
    {
        $id = $this->get('id', 2);
        $this->renderTpl('index', ['id' => $id]);
    }
}