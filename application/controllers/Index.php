<?php

use Library\HttpKernel;
use COmmon\Functions;

class IndexController extends HttpKernel
{
    public function indexAction()
    {
        $id = $this->get('id', 2);
        $this->renderTpl('index', ['id' => $id]);
    }

    public function commonAction()
    {
        $this->returnJson(Functions::T("test"));
    }
}