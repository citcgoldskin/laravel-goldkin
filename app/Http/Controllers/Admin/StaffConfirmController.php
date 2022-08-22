<?php

namespace App\Http\Controllers\Admin;

use App\Mail\Admin\SendAlertEmail;
use App\Models\PersonConfirm;
use App\Models\User;
use App\Service\PersonConfirmService;
use App\Service\UserService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Storage;
use Session;
use DB;

class StaffConfirmController extends AdminController
{
    protected $per_page = 20;
    public function index(Request $request)
    {
        $params = $request->all();
        $condition = Session::get('admin.fraud.staff_confirm', []);
        if (Session::get('admin.fraud.staff_confirm_detail')) {
            Session::forget('admin.fraud.staff_confirm_detail');
        }

        if (isset($params['search_params'])) {
            foreach ($params['search_params'] as $key=>$val) {
                $condition[$key] = $val;
            }
        }

        if (isset($params['order']) && $params['order']) {
            $condition['order'] = $params['order'];
        } else {
            $condition['order'] = config('const.user_sort_code.register_new');
        }

        Session::put('admin.fraud.staff_confirm', $condition);

        $obj_users = PersonConfirmService::doSearch($condition)->paginate($this->per_page);

        return view('admin.staff_confirm.index', [
            'obj_users' => $obj_users,
            'search_params' => $condition
        ]);
    }

    public function detail(Request $request, PersonConfirm $person_confirm)
    {
        $condition = Session::get('admin.fraud.staff_confirm_detail', []);
        return view('admin.staff_confirm.detail', [
            'obj_user' => $person_confirm->user,
            'person_confirm' => $person_confirm,
            'condition' => $condition
        ]);
    }

    public function doCreateAlert(Request $request)
    {
        $params = $request->all();
        if (isset($params['_token'])) {
            unset($params['_token']);
        }
        if ($params['agree_type'] == config('const.person_confirm_agree_category.agree')) {
            if (isset($params['chk-disagree'])) {
                unset($params['chk-disagree']);
            }
        } else if($params['agree_type'] == config('const.person_confirm_agree_category.disagree')) {
            if (isset($params['chk-agree'])) {
                unset($params['chk-agree']);
            }
        }
        Session::put('admin.fraud.staff_confirm_detail', $params);
        return redirect()->route('admin.staff_confirm.alert_create');
    }

    public function createAlert(Request $request)
    {
        $condition = Session::get('admin.fraud.staff_confirm_detail', []);
        $obj_user = User::find($condition['user_id']);
        return view('admin.staff_confirm.alert_create', [
            'obj_user' => $obj_user,
            'condition' => $condition
        ]);
    }

    public function doConfirmAlert(Request $request)
    {
        $params = $request->all();
        $staff_confirm_detail = Session::get('admin.fraud.staff_confirm_detail', []);
        $staff_confirm_detail['alert_title'] = $params['alert_title'];
        $staff_confirm_detail['alert_text'] = $params['alert_text'];
        Session::put('admin.fraud.staff_confirm_detail', $staff_confirm_detail);
        return redirect()->route('admin.staff_confirm.alert_confirm');
    }

    public function confirmAlert(Request $request)
    {
        $condition = Session::get('admin.fraud.staff_confirm_detail', []);
        return view('admin.staff_confirm.alert_confirm', [
            'condition' => $condition
        ]);
    }

    public function sendAlert(Request $request)
    {
        $condition = Session::get('admin.fraud.staff_confirm_detail', []);
        $obj_user = User::find($condition['user_id']);
        $contents = "送信しました。";

        $contents = "送信が失敗しました。";
        if (PersonConfirmService::doPersonConfirm($condition)) {
            try {
                $mail_obj = Mail::to($obj_user->email);
                /*$cc_mail_arr = [config('const.yamadazaidan_email')];
                $mail_obj->cc($cc_mail_arr);*/
                $mail_obj->send(new SendAlertEmail($condition));
                $contents = "送信しました。";
            } catch (\Exception $exception) {
                Log::error("EMail Sending Failed");
            }
        }

        $modal_confrim_url = route('admin.staff_confirm.index');

        Session::forget('admin.fraud.staff_confirm_detail');

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

}
