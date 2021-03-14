<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Api\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ADMIN_HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * 認証カラムにis_enableを追加する
     *
     * @param Request $request
     * @return void
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password', 'is_enable');
    }

    /**
     * 認証カラム変更
     *
     * @return void
     */
    public function username()
    {
        $loginId = request()->input('login_id');
        $field  = 'login_id';
        request()->merge([$field => $loginId, 'is_enable' => 1]);
        return $field;
    }

    /**
     * Guardの認証方法を指定
     *
     * @return void
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    protected function authenticated(Request $request, $user)
    {
        $user->role;
        return $user;
    }

    protected function loggedOut(Request $request)
    {
        // セッションを再生成する
        $request->session()->regenerate();

        return response()->json();
    }
}
