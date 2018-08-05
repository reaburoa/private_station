<?php

use Library\HttpKernel;

class IndexController extends HttpKernel
{
    public function indexAction()
    {
        $id = $this->get('id', 2);
        $this->renderTpl('index', ['id' => $id]);
    }
}