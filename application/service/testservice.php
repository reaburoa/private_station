<?php

namespace Service;

use Foo\FooModel;
use Library\ServiceKernel;

class TestService extends ServiceKernel
{
    public function foo()
    {
        $rt = FooModel::getInstance()->getOneRowById(2);
        var_dump($rt);
        return 'foo';
    }
}