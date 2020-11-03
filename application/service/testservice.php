<?php

namespace Service;

use Foo\FooModel;
use Illuminate\Support\Facades\DB;
use Library\ServiceKernel;

class TestService extends ServiceKernel
{
    /**
     *
     * @return string
     * @throws \Throwable
     */
    public function foo()
    {
        FooModel::getInstance()->getConnection()->beginTransaction();
        try {
            $ret = FooModel::getInstance()->create(['name' => 'ddd']);
            FooModel::getInstance()->getConnection()->commit();
        } catch (\Exception $e) {
            FooModel::getInstance()->getConnection()->rollBack();
        }
        var_dump($ret);
        $rt = FooModel::getInstance()->getOneRowById($ret);
        var_dump($rt);
        return 'foo';
    }
}