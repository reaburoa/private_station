<?php

use Core\BaseCtrl;

class IndexController extends BaseCtrl
{
    public function indexAction()
    {
        $id = $this->get('id', 2);
        $this->renderTpl('index', ['id' => $id]);
    }
}