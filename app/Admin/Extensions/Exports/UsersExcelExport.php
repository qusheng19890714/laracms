<?php

namespace App\Admin\Extensions\Export;

use Encore\Admin\Grid\Exporters\ExcelExporter;

class UserExcelExport extends ExcelExporter
{

    protected $fileName = '用户列表.xlsx';

    protected $columns = [
        'id'      => 'ID',
        'name'    => '用户名',
        'email'   => 'Email',
        'phone'   => '手机号码',
        'created_at' => '注册时间'
    ];


}

