<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Modules\Core\Base\FrontController;
use Auth;

class LoginController extends FrontController
{
    use AuthenticatesUsers;

    protected $redirectTo = '/order';


    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest')->except('logout');
    }


    /**
     * 登录页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return $this->view('login.index');
    }

    /**
     * 登录名字段
     * @return string
     */
    public function username()
    {
        return 'identifier';
    }

    public function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('user::user.login.error.tip')],
        ]);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/order');
    }

    protected function guard()
    {
        return Auth::guard('web');
    }
}