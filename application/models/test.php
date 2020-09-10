<?php

use \Library\DatabaseKernel;

class TestModel extends DatabaseKernel
{
    protected $table = 't_test';

    public function getOneRowById($id)
    {
        return $this->getModel($this->table)->where('id', $id)->get()->toArray();
    }
}