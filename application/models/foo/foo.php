<?php

namespace Foo;

use Library\DatabaseKernel;

class FooModel extends DatabaseKernel
{
    protected $table = 't_test';

    public function getOneRowById($id)
    {
        return $this->getModel($this->table)->where('id', $id)->get()->toArray();
    }
}