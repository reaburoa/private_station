<?php

use Rean\MyPDO;

class TestModel extends MyPDO
{
    protected static $database = 'test';
    protected static $table = 't_test';
    protected static $cluster = 'test';

    public function getOneRowById($id)
    {
        return $this->getOneRow(['id' => $id]);
    }
}