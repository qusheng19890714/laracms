<?php

namespace Modules\User\Export;

use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExcelExport extends ExcelExporter implements WithMapping
{

    protected $fileName = '用户列表.xlsx';


    protected $columns = [
        'id'      => 'ID',
        'name'    => '用户名',
        'authorization.identifier' => '手机号/邮箱',
        'created_at' => '注册时间'
    ];



    public function map($row) : array
    {
        return [
            $row->id,
            $row->name,
            data_get($row, 'authorization.identifier'),
            $row->created_at

        ];
    }
}

