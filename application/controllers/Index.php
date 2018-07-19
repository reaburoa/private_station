<?php

use Core\BaseCon;

class IndexController extends BaseCon
{
    public function indexAction()
    {
        $this->getView()->render('index/index.phtml', ['foo' => 'foo123']);
    }
}