<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
        /*$this->middleware('guest');*/
    }

    public function showLinkRequestForm()
    {
        return view("user.auth.passwords.email", ['page_id' => '', 'page_id_02' => '']);
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return redirect()->route('password.sent_mail', ['email'=>$request->input('email', '')]);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        $request->session()->flash('error', 'メール送信が失敗しました。');
        return back()->with('status', trans($response));
    }

    public function sentMail(Request $request)
    {
        $params = $request->all();
        return view("user.auth.passwords.sent_mail", ['page_id' => '', 'page_id_02' => '', 'email'=>$params['email']]);
    }
}
