<?php

namespace Modules\Hark\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\FrontController;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Hark\Export\GoodsSellExport;

class ExportController extends FrontController
{
    /**
     * 导出某地区销量前1000的菜品
     *
     * @return Response
     */
    public function exportCanberraGoodsSell(Request $request)
    {
        $region_id = $request->region_id;

        if (!$region_id) {

            return $this->error('请输入地区id');
        }

        return Excel::download(new GoodsSellExport($region_id), '堪培拉菜品销量Top1000.xlsx');
    }

    /**
     * 新建
     *
     * @return Response
     */
    public function create()
    {
        return $this->view();
    }

    /**
     * 保存
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * 显示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit($id)
    {
        return $this->view();
    }

    /**
     * 更新
     *
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}