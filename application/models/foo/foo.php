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
        $this->getModel()
            ->join("tt", "id", '=', 't_id', 'left', false)->toSql();
        // t_test 和表 tt 进行左连接，按照first 、 second参数进行，操作是 =， 比如 t_test.id=tt.t_id
        // where == true 表示按照 where进行连接，否则是按照on 进行连接，如 left join table on **.id = **.t_id
        // 后边在带where子句进行查询
        return $this->getModel()->where('id', $id)->get()->toArray();
    }
}