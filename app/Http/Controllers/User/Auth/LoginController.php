<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\User\VerifyEmailCustom;
use App\Providers\RouteServiceProvider;
use App\Service\UserService;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\EmailRequest;
use App\Http\Requests\User\EmailExistRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Log;
use Session;
use Str;
use Illuminate\Contracts\Auth\Guard;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    protected  $loginMode = 0; // 0:email, 1:phone

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('guest:web')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        return view("user.myaccount.login", ['title' => 'ログイン', 'page_id' => '', 'page_id_02' => '']);
    }

    public function beforeLogin(Request $request)
    {
        // redirect to origin page that call on this page
        if ( !$request->session()->has('url.intended') ) {
            redirect()->setIntendedUrl($request->session()->previousUrl());
        }
        /////

        return view('user.myaccount.index_unlogged', ['title' => 'マイページ', 'page_id' => '', 'page_id_02' => '']);
    }

    public function showlostPwdForm(Request $request)
    {
        return view('user.myaccount.lost_pwd', ['page_id' => '', 'page_id_02' => '']);
    }

    public function lostPwd(EmailExistRequest $request)
    {
        // todo emailing : send a password

        /*try {
            $user->notify(new VerifyEmailCustom());
        } catch (\Exception $exception) {
            Log::error("EMail Sending Failed");
        }*/

        // if success!
        return $this->lostPwdSuccess($request);
    }

    public function lostPwdSuccess(Request $request)
    {
        return view('user.myaccount.lost_pwd_success', ['page_id' => '', 'page_id_02' => '']);
    }

    public function login(LoginRequest $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ( $request->input('tab_item') == config('const.login_mode.email') ) {
            $this->loginMode = 0;
        } elseif ( $request->input('tab_item') == config('const.login_mode.phone') ) {
            $this->loginMode = 1;
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt($this->credentials($request), 0);
    }

    protected function credentials(Request $request)
    {
        $login_params = $request->only($this->username(), 'password');

        return array_merge($login_params);
    }

    protected function username()
    {
        if ( $this->loginMode == config('const.login_mode.email') ) {
            return 'email';
        } elseif ( $this->loginMode == config('const.login_mode.phone') ) {
            return 'user_phone';
        }
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);


        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
//        $request->session()->flush();

        return $this->loggedOut($request) ?: redirect(route('user.login.myaccount.before'));
    }


    protected function guard()
    {
        return Auth::guard('web');
    }
}
