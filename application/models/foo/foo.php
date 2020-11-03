<?php

namespace Foo;

use Library\DatabaseKernel;

class FooModel extends DatabaseKernel
{
    protected $table = 't_test';

    public function create(array $data)
    {
        return $this->getModel()->insertGetId($data);
    }

    public function getOneRowById($id)
    {
        return $this->getModel()->where('id', $id)->get()->toArray();
    }
}