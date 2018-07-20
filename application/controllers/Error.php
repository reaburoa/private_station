<?php

use Core\BaseCtrl;

class ErrorController extends BaseCtrl
{
    public function errorAction($exception)
    {
        $this->returnJson("Exception Occur", $exception->getCode(), $exception->getMessage());
    }
}