<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\FrontController;
use Illuminate\Support\Facades\Validator;
use Modules\User\Entities\Authorization;
use Modules\User\Entities\User;

class RegisterController extends FrontController
{
    use RegistersUsers;

    protected $redirectTo = '/order';

    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('register.index');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'identifier' => ['required', 'string', 'email', 'max:255', 'unique:authorizations'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'captcha' => ['required', 'captcha']
        ],[
            'captcha.required' => '验证码不能为空',
            'captcha.captcha'  => '请输入正确的验证码',
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([

            'name' => $data['name']

        ]);

        return Authorization ::create([
            'user_id' => $user->id,
            'type'    => 'email',
            'identifier' => $data['identifier'],
            'verified' => 1,
            'ip' => request()->ip(),
            'password' => bcrypt($data['password']),
        ]);
    }


}