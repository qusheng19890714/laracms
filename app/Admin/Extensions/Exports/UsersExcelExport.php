<?php

namespace App\Admin\Extensions\Export;

use Maatwebsite\Excel\Excel;
use Encore\Admin\Grid\Exporters\AbstractExporter;

class UserExcelExport extends AbstractExporter
{

    public function export()
    {
        $gridData = $this->getData();

        $data = [];

        //导出表头
        $data[] = ['ID', trans('user::user.name.label'), trans('user::user.email.label'), trans('user::user.phone.label'), trans('user::user.created_at.label')];

        foreach ($gridData as $v)
        {
            $data[] = [$v['id'], $v['name'], $v['email'], $v['tel'], $v['created_at']];
        }

        // 导出excel
        \Excel::create('用户数据', function($excel) use ($data) {

            $excel->sheet('用户', function($sheet) use($data) {

                $sheet->rows($data);

            });

        })->download('xls');
    }

}

