<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\EmailRequest;
use App\Http\Requests\User\RegisterProfileRequest;
use App\Http\Requests\User\PhoneRequest;
use App\Providers\RouteServiceProvider;
use App\Service\CommonService;
use App\Service\CouponService;
use App\Service\UserService;
use App\Notifications\User\VerifyEmailCustom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;
use Symfony\Component\HttpFoundation\Request;
use Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('guest');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $m_user = User::where('email', $data['email'])
            ->whereNull('email_verified_at');

        if($m_user->exists()) {
            $user = $m_user->first();
        } else {
            $user = User::create([
                'email' => $data['email'],
                'email_verify_token' => base64_encode($data['email'])
            ]);
        }

        try {
            $user->notify(new VerifyEmailCustom());
        } catch (\Exception $exception) {
            Log::error("EMail Sending Failed");
        }
        return $user;
    }

    public function showRegEmailForm(Request $request)
    {
        return view('user.myaccount.reg_mail', ['page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function registerEmail(EmailRequest $request)
    {
        // todo verify email address //
        //////////////////////////////

        event(new Registered($user = $this->create($request->all())));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        $request->session()->put('email', $request['email']);

        return $request->wantsJson() ? new JsonResponse([], 201) : redirect(route('user.register.sent_mail'));

        //$request->session()->put('email', $request['email']);
        //return view('user.myaccount.reg_mail_success', ['page_id' => '', 'page_id_02' => '']);
    }

    public function showRegProfileForm(Request $request)
    {
        $email = $request->getSession()->get('email');

        if ( !is_string($email) || $email == '' ) {
            return redirect(route('user.register.email.form'));
        }

        return view('user.myaccount.reg_profile', ['title' => 'マイページ', 'page_id' => '', 'page_id_02' => '', 'email' => $email]);
    }

    public function registerProfile(RegisterProfileRequest $request)
    {
        $request->session()->put('name', $request['name']);
        $request->session()->put('user_area_id', $request['user_area_id']);
        $request->session()->put('user_firstname', $request['user_firstname']);
        $request->session()->put('user_lastname', $request['user_lastname']);
        $request->session()->put('user_sei', $request['user_sei']);
        $request->session()->put('user_mei', $request['user_mei']);
        $request->session()->put('user_birthday', $request['user_birthday']);
        $request->session()->put('user_sex', $request['user_sex']);

//        $password = Hash::make($request['password']);
        $request->session()->put('password', $request['password']);

        return redirect(route('user.register.phone.form'));
    }

    public function showRegPhoneForm(Request $request)
    {
        return view('user.myaccount.reg_phone', ['page_id' => '', 'page_id_02' => '']);
    }

    public function registerPhone(PhoneRequest $request)
    {
        $request->getSession()->put('user_phone', $request['phone']);
        return redirect(route('user.register.verify_phone.form'));
    }

    public function showVerifyPhoneForm(Request $request, $from = 0)
    {
        return view('user.myaccount.reg_verify_phone', [
            'from' => $from,            // register or set
            'page_id' => '', 'page_id_02' => '']);
    }

    public function verifyPhone(Request $request)
    {
        // todo sending SMS //
        /////////////////////

        $verify_result = 1;
        if ( $verify_result ) {
            return $this->registerFinish($request);
        } else {
            return view('user.myaccount.reg_verify_phone', ['page_id' => '', 'page_id_02' => '']);
        }
    }

    //sent mail
    public function sentMail(Request $request)
    {
        //$request->session()->put('email', $request['email']);
        //return view('user.myaccount.reg_mail_success', ['page_id' => '', 'page_id_02' => '']);
        return view("user.myaccount.reg_mail_success", ['page_id' => '', 'page_id_02' => '']);
    }

    public function showUserForm(Request $request)
    {
        $user = User::where('email_verify_token', $request->input('token'))
            ->first();
        $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
        if(!$user->save()){
            abort(404);
        }

        return view('user.myaccount.reg_profile', ['title' => 'マイページ', 'page_id' => '', 'page_id_02' => '', 'email' => $user->email]);
    }

    public function registerFinish(Request $request)
    {
        // todo match verifing code //
        //////////////////////////////
        if (!$request->getSession()->get('email')) {
            $request->session()->flash('danger', '処理の有効時間（2時間）を超えています。 メール入力からもう一度お試しください。');
            return redirect()->route('user.login.myaccount.before');
        }

        $m_user = User::where('email', $request->getSession()->get('email'));

        if($m_user->exists()) {
            $user = $m_user->first();
            $user->update([
                'user_no' => UserService::generateCustomerID(),
                'name' => $request->getSession()->get('name'),
                'user_firstname' => $request->getSession()->get('user_firstname'),
                'user_lastname' => $request->getSession()->get('user_lastname'),
                'user_sei' => $request->getSession()->get('user_sei'),
                'user_mei' => $request->getSession()->get('user_mei'),
                'user_birthday' => $request->getSession()->get('user_birthday'),
                'user_sex' => $request->getSession()->get('user_sex'),
                'user_area_id' => $request->getSession()->get('user_area_id'),
                'user_phone' => $request->getSession()->get('user_phone'),
                'password' => Hash::make($request->getSession()->get('password'))
            ]);
        } else {
            User::create([
                'user_no' => UserService::generateCustomerID(),
                'name' => $request->getSession()->get('name'),
                'email' => $request->getSession()->get('email'),
                'user_firstname' => $request->getSession()->get('user_firstname'),
                'user_lastname' => $request->getSession()->get('user_lastname'),
                'user_sei' => $request->getSession()->get('user_sei'),
                'user_mei' => $request->getSession()->get('user_mei'),
                'user_birthday' => $request->getSession()->get('user_birthday'),
                'user_sex' => $request->getSession()->get('user_sex'),
                'user_area_id' => $request->getSession()->get('user_area_id'),
                'user_phone' => $request->getSession()->get('user_phone'),
                'password' => Hash::make($request->getSession()->get('password'))
            ]);
        }

        $invite_code = Session::get('invite_code');
        if($invite_code != null)
        {
            $user = UserService::getUserByCode($invite_code);
            CouponService::getSysCoupon($user->id);
        }


        $login_result = $this->guard()->attempt([
            'email' => $request->getSession()->get('email'),
            'password'=> $request->getSession()->get('password')
        ]);

        if ( $login_result ) {
            $this->sendLoginResponse($request);
            return redirect(route('user.register.finish'));
        }
    }

    protected function sendLoginResponse(Request $request)
    {
        return $request->getSession()->regenerate();
    }

    protected function registerFinishForm(Request $request)
    {
        return view('user.myaccount.reg_finish', ['page_id' => '', 'page_id_02' => '']);
    }

    protected function verifyCodeValidator(array $data)
    {
        return Validator::make($data, [
            'code_1' => ['required', 'int', 'max:1'],
        ]);
    }
}
