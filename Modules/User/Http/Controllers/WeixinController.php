<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Base\EasyWechatController;

class WeixinController extends EasyWechatController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index(Request $request)
    {

        $response = $this->weixin->oauth->scopes(['snsapi_userinfo'])->redirect();

        return $response;


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