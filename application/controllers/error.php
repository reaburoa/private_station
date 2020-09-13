<?php

use Library\HttpKernel;

class ErrorController extends HttpKernel
{
    public function errorAction($exception)
    {
        $this->returnJson("Exception Occur", $exception->getCode(), $exception->getMessage());
    }
}