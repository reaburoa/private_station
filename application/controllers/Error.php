<?php

use Core\BaseCon;

class ErrorController extends BaseCon
{
    public function errorAction($exception)
    {
        $this->returnJson("Exception Occur", $exception->getCode(), $exception->getMessage());
    }
}