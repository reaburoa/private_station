<?php

namespace Service\Foo;

use Library\ServiceKernel;

class FooService extends ServiceKernel
{
    public function fs()
    {
        return "This is foo service, fs method";
    }
}