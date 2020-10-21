<?php

use \Library\DatabaseKernel;

class TestModel extends DatabaseKernel
{
    protected $table = 't_banner';

    public function getOneRowById($id)
    {
        $ret = $this->getModel()->where('id', $id)->get()->toJson();
        return $ret ? json_decode($ret, true) : $ret;
    }
}